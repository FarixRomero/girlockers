# Configuración del Landing Page

## 📝 Descripción

El landing page de Girls Lockers ahora es totalmente configurable desde el panel de administración. Puedes cambiar textos, precios, videos, testimonios y enlaces de redes sociales sin tocar código.

---

## 🎯 Acceso

**URL:** `http://127.0.0.1:8001/admin/landing-config`

**Requisitos:**
- Debes estar autenticado como admin
- Ruta: `/admin/landing-config`
- Named route: `admin.landing-config.index`

---

## 📂 Secciones Configurables

### 1. Hero Section
**Qué puedes cambiar:**
- ✅ 3 líneas del título principal
- ✅ Subtítulo/descripción
- ✅ ID del video de YouTube de fondo
- ✅ Textos de los 2 botones (primario y secundario)

**Ejemplo:**
```
Título 1: TU ESPACIO.
Título 2: TU RITMO.
Título 3: TU PODER.
Video: HefC_rMCs-Q (solo el ID)
```

---

### 2. Precios
**Qué puedes cambiar:**
- ✅ Precio mensual (ej: 30)
- ✅ Precio trimestral (ej: 50)
- ✅ Precio original trimestral tachado (ej: 60)
- ✅ Features del plan mensual (lista editable)
- ✅ Features del plan trimestral (lista editable)

**Nota:** El badge de ahorro se calcula automáticamente: `Precio Original - Precio Actual`

---

### 3. Videos de la Bóveda
**Qué puedes cambiar:**
- ✅ ID de YouTube de 2 videos
- ✅ Títulos de los videos
- ✅ Etiquetas (tags) de los videos

**Ejemplo:**
```
Video 1:
- ID: HefC_rMCs-Q
- Título: Los Orígenes del Locking
- Tag: Historia

Video 2:
- ID: 8b18KD5O3y8
- Título: Momentos Legendarios
- Tag: Batalla
```

---

### 4. Testimonios
**Qué puedes cambiar (3 testimonios):**
- ✅ Iniciales (2 letras, ej: MG)
- ✅ Usuario (ej: @LockerGirl_Lima)
- ✅ Ubicación (ej: Lima, Perú)
- ✅ Texto del testimonio

**Nota:** El testimonio 2 (del medio) tiene diseño destacado (fondo morado).

---

### 5. Estadísticas
**Qué puedes cambiar:**
- ✅ Cantidad de Lockers (ej: 500+)
- ✅ Cantidad de Lecciones (ej: 50+)
- ✅ Acceso (ej: 24/7)

---

### 6. Redes Sociales
**Qué puedes cambiar:**
- ✅ URL de Instagram
- ✅ URL de TikTok
- ✅ URL de YouTube

**Formato:** URLs completas (ej: https://instagram.com/girlslockers)

---

## 🎨 Cómo Usar

### Paso 1: Acceder al Panel
1. Inicia sesión como admin
2. Ve a `http://127.0.0.1:8001/admin/landing-config`

### Paso 2: Seleccionar Sección
- Usa las pestañas en la parte superior para navegar entre secciones:
  - Hero Section
  - Precios
  - Videos de la Bóveda
  - Testimonios
  - Estadísticas
  - Redes Sociales

### Paso 3: Editar Campos
- Cada campo tiene una **descripción** que explica qué hace
- Los campos de tipo **lista** (features) permiten:
  - Editar items existentes
  - Agregar nuevos items (botón "+ Agregar Feature")
  - Eliminar items (botón "Eliminar")

### Paso 4: Guardar
- Click en **"Guardar Cambios"** (botón morado en la esquina superior derecha)
- Verás un mensaje de confirmación en verde

### Paso 5: Ver Cambios
- Abre el landing en una pestaña nueva: `http://127.0.0.1:8001/`
- Los cambios se verán inmediatamente (con cache de 1 hora)

---

## 🔄 Cache

**Duración:** 1 hora por defecto

**Cómo limpiar el cache manualmente:**
```bash
php artisan cache:clear
```

**Cuándo se limpia automáticamente:**
- Al guardar cambios en el panel admin
- Al ejecutar `php artisan cache:clear`

---

## 🗄️ Base de Datos

**Tabla:** `landing_configs`

**Campos:**
- `key`: Identificador único (ej: hero_title_1)
- `value`: Valor del campo
- `type`: text, textarea, number, url, json
- `group`: hero, pricing, videos, testimonials, stats, social
- `label`: Nombre visible en el admin
- `description`: Ayuda para el campo

---

## 📊 Modelo

**Clase:** `App\Models\LandingConfig`

**Métodos útiles:**
```php
// Obtener valor por key
LandingConfig::getValue('hero_title_1', 'Default');

// Establecer valor
LandingConfig::setValue('hero_title_1', 'NUEVO TÍTULO');

// Obtener todos por grupo
LandingConfig::getByGroup('hero');

// Limpiar cache
LandingConfig::clearCache();
```

---

## ⚙️ Seeder

**Para resetear a valores por defecto:**
```bash
php artisan db:seed --class=LandingConfigSeeder
```

**Esto restaura:**
- Todos los textos originales
- Precios: S/30 (mensual), S/50 (trimestral)
- Videos por defecto
- Testimonios originales
- Links de redes a "#"

---

## 🎯 Ejemplos de Uso

### Cambiar el precio mensual a S/35
1. Ve a la pestaña **"Precios"**
2. Busca **"Precio Mensual"**
3. Cambia `30` a `35`
4. Click en **"Guardar Cambios"**

### Agregar un nuevo feature al plan trimestral
1. Ve a la pestaña **"Precios"**
2. Busca **"Features Plan Trimestral"**
3. Click en **"+ Agregar Feature"**
4. Escribe el nuevo feature
5. Click en **"Guardar Cambios"**

### Cambiar video de YouTube
1. Ve a la pestaña **"Videos de la Bóveda"**
2. Busca **"Video 1 - ID YouTube"**
3. Cambia solo el ID (ej: de `HefC_rMCs-Q` a `abc123xyz`)
4. Click en **"Guardar Cambios"**

### Actualizar testimonio
1. Ve a la pestaña **"Testimonios"**
2. Expande **"Testimonio 1"**
3. Edita cualquier campo (iniciales, usuario, ubicación, texto)
4. Click en **"Guardar Cambios"**

---

## 🚨 Notas Importantes

1. **No borres los valores por defecto** - Siempre deja un valor, aunque sea vacío
2. **IDs de YouTube** - Solo pon el ID del video, no la URL completa
   - ❌ Incorrecto: `https://youtube.com/watch?v=HefC_rMCs-Q`
   - ✅ Correcto: `HefC_rMCs-Q`
3. **Precios sin símbolo** - Solo el número, sin "S/" ni decimales
   - ❌ Incorrecto: `S/30.00`
   - ✅ Correcto: `30`
4. **Features** - Mantén al menos 1 feature por plan
5. **Testimonios** - El diseño del segundo testimonio (medio) es diferente automáticamente

---

## 📱 Responsive

Todos los cambios son **responsive** automáticamente. El diseño se adapta a:
- 📱 Mobile
- 💻 Desktop
- 📱 Tablet

No necesitas configurar versiones separadas.

---

## 🔐 Seguridad

- ✅ Solo accesible por admins
- ✅ Middleware `auth` y `admin`
- ✅ Validación en backend
- ✅ Escapado de HTML para prevenir XSS

---

**Última actualización:** 2025-11-08
**Creado por:** Claude (Anthropic)
