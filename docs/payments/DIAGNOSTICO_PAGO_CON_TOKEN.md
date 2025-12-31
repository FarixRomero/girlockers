# Diagnóstico: Problema con Pago con Tarjeta Guardada

## Problema Reportado

Al intentar pagar con tarjeta guardada (token), el sistema muestra "Pago no completado" o redirige a un formulario completo en lugar de solo pedir CVV.

## Hallazgos Clave de la Documentación

Según `/docs/payments/PAGOS_CON_TARJETAS_GUARDADAS.md` (líneas 1300-1303):

**¿Debo validar CVV al pagar con token?**
❌ **NO**. El token ya incluye la información de la tarjeta validada. IziPay no requiere CVV para pagos con token.

**PERO:**
- En **PRODUCCIÓN**: Los pagos con token deben funcionar SIN CVV
- En **TEST**: Los pagos con token PUEDEN requerir CVV o NO funcionar si PSP_610 no está habilitado

## Causa Más Probable: PSP_610 No Habilitado

El error PSP_610 significa: **"Merchant Acceptance Agreement not enabled"** - La funcionalidad OneClick/Token no está habilitada en tu cuenta Izipay.

### ¿Qué es PSP_610?

Es la configuración en Izipay que permite:
- Procesar pagos con tokens guardados (OneClick)
- Pagar sin reingresar datos de tarjeta
- Pagos en 1 clic

### Verificar si PSP_610 está habilitado

1. **Revisar logs** después de intentar pagar con tarjeta guardada
2. Buscar en los logs:
   ```
   detailedErrorCode: PSP_610
   ```
   O mensajes que contengan:
   ```
   merchant acceptance agreement
   ```

## Cambios Realizados

### 1. Corregido: Agregado `formAction: SILENT` para Pagos con Token

**Archivo:** `app/Services/IzipayService.php` (línea 117)

**Problema anterior:**
- No se enviaba el parámetro `formAction` al llamar a Izipay con token
- Izipay usaba el valor por defecto `PAYMENT`, que devuelve un formToken
- Esto causaba que se pidiera CVV o mostrara formulario completo

**Solución implementada:**
Según la documentación oficial de Izipay, existen dos formas de pagar con token:

| Modo | formAction | Respuesta | Uso |
|------|-----------|-----------|-----|
| **Pago en 0 clics** | `SILENT` | Objeto Payment directo | Sin interacción del usuario |
| **Pago en 1 clic** | `PAYMENT` | formToken | Con interacción (CVV) |

Ahora enviamos:
```php
'formAction' => 'SILENT', // Pago sin interacción del usuario (0-click)
```

Esto indica a Izipay que:
- ✅ Queremos procesar el pago DIRECTAMENTE con el token
- ✅ SIN pedir CVV al usuario
- ✅ La respuesta debe ser un objeto Payment (no formToken)

**Importante:** Si PSP_610 no está habilitado, Izipay rechazará la solicitud con error PSP_610 incluso con `formAction: SILENT`.

### 2. Detección de Error PSP_610

**Archivo:** `app/Livewire/Student/PurchaseMembership.php` (líneas 143-164)

Ahora detecta específicamente el error PSP_610 y muestra un mensaje claro:
```
"Tu cuenta Izipay aún no tiene habilitado el pago con tarjeta guardada (token / OneClick).
Usa "nueva tarjeta" o solicita a Izipay habilitar PSP_610 para tu comercio."
```

### 2. Logging Mejorado en IzipayService

**Archivo:** `app/Services/IzipayService.php` (líneas 117-139)

Ahora los logs muestran:
- Datos del request (orden, monto, moneda, referencia del cliente)
- Vista previa del token (primeros 8 caracteres + ***)
- orderStatus de la respuesta
- Si hay formToken en la respuesta
- errorMessage y detailedErrorCode
- detailedErrorMessage
- Data completa de la respuesta

### 3. Logging Mejorado en PaymentFormController

**Archivo:** `app/Http/Controllers/PaymentFormController.php` (líneas 30-86)

Ahora los logs muestran:
- Si el formToken viene de sesión o del registro de pago
- Vista previa del formToken
- Todas las claves de sesión disponibles
- Tipo de formToken (nuevo vs. existente)
- Timestamp de creación del formToken

## Cómo Diagnosticar el Problema

### Paso 1: Limpiar Logs Actuales

```bash
# En el servidor de desarrollo
echo "" > storage/logs/laravel.log
```

### Paso 2: Intentar Pago con Tarjeta Guardada

1. Ve a `http://localhost:8000/purchase-membership`
2. Selecciona una membresía
3. Haz clic en "Pagar con tarjeta guardada"
4. Observa qué sucede

### Paso 3: Revisar Logs

```bash
tail -f storage/logs/laravel.log
```

Busca estas secciones clave:

#### A) Inicio del Pago con Token
```
=== INICIO PAGO CON TARJETA GUARDADA ===
```

#### B) Request a Izipay
```
Izipay CreatePaymentWithToken Request
```
Verifica:
- ✅ `has_payment_token: true`
- ✅ `currency: PEN` (o USD según corresponda)
- ✅ `customer_reference` coincide con el user_id

#### C) Respuesta de Izipay
```
Izipay CreatePaymentWithToken Response
```
Busca uno de estos escenarios:

**Escenario A: PSP_610 No Habilitado**
```json
{
  "orderStatus": null,
  "hasFormToken": false,
  "errorMessage": "...",
  "detailedErrorCode": "PSP_610"
}
```
**Acción:** Solicitar a Izipay que habilite PSP_610 en tu cuenta TEST

**Escenario B: Requiere CVV (Test Mode)**
```json
{
  "orderStatus": "UNPAID",
  "hasFormToken": true,
  "errorMessage": null,
  "detailedErrorCode": null
}
```
**Acción:** Esto es normal en TEST mode, el formToken debe usarse para mostrar SOLO el campo CVV

**Escenario C: Pago Exitoso**
```json
{
  "orderStatus": "PAID",
  "hasFormToken": false,
  "errorMessage": null,
  "detailedErrorCode": null
}
```
**Acción:** ✅ Todo funciona correctamente

**Escenario D: Otro Error**
```json
{
  "orderStatus": "REFUSED",
  "errorMessage": "INSUFFICIENT_FUND",
  "detailedErrorCode": "..."
}
```
**Acción:** El error específico indicará el problema (fondos, tarjeta bloqueada, etc.)

## Solución según el Escenario

### Si es PSP_610:

**Opción 1: Habilitar PSP_610 (RECOMENDADO)**
1. Contactar soporte de Izipay
2. Solicitar: "Habilitar PSP_610 (OneClick) para mi cuenta TEST"
3. Esperar confirmación (usualmente 1-2 días hábiles)

**Opción 2: Usar Nueva Tarjeta (TEMPORAL)**
- Por ahora, los usuarios deben usar "Nueva tarjeta" en lugar de "Tarjeta guardada"
- Una vez habilitado PSP_610, funcionará correctamente

### Si requiere CVV en Test Mode:

Esto es comportamiento esperado. El formToken devuelto debe mostrar SOLO el campo CVV.

**Verificar:**
1. ¿El formToken se está pasando correctamente a la vista?
2. ¿La vista está usando ese formToken?

Revisar logs:
```
PaymentFormController - formToken sources
from_session: yes
source: session
```

### Si todo funciona en producción pero no en test:

Es normal. Izipay TEST mode tiene limitaciones. Verifica en PRODUCCIÓN después de:
1. Habilitar PSP_610 en cuenta de producción
2. Desplegar el código actualizado
3. Probar con tarjeta real

## Próximos Pasos

1. **Ejecuta el diagnóstico completo** siguiendo los pasos arriba
2. **Copia los logs relevantes** de las 3 secciones (inicio, request, response)
3. **Identifica el escenario** que se está presentando
4. **Aplica la solución correspondiente**

## Información de Contacto Izipay

**Soporte Técnico:**
- Email: soporte@izipay.pe
- Teléfono: (01) 708-5900
- Portal: https://secure.micuentaweb.pe/

**Al contactar, mencionar:**
- Tu ID de comercio (merchant ID)
- Que necesitas habilitar PSP_610 para pagos OneClick
- Tanto en TEST como en PRODUCCIÓN

---

**Fecha:** 2025-12-31
**Última actualización:** Después de análisis de documentación y código
