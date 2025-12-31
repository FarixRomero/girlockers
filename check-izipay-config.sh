#!/bin/bash

echo "🔍 Verificando configuración de Izipay en producción"
echo "====================================================="
echo ""

ssh -i ssh-mb-api.pem ubuntu@34.197.80.87 << 'ENDSSH'
cd /home/ubuntu/proyectos/girlockers

echo "📋 Configuración actual de IZIPAY en .env:"
echo "==========================================="
grep "^IZIPAY_" .env || echo "⚠️  No se encontraron variables IZIPAY_* en .env"

echo ""
echo ""
echo "🔍 Diagnóstico:"
echo "==============="

IZIPAY_MODE=$(grep "^IZIPAY_MODE=" .env | cut -d '=' -f2)
echo "Modo actual: $IZIPAY_MODE"

if [ "$IZIPAY_MODE" = "test" ]; then
    echo "❌ PROBLEMA: Estás en modo TEST en producción"
    echo ""
    echo "📝 Solución:"
    echo "1. Obtén tus credenciales de PRODUCCIÓN de Izipay"
    echo "2. Edita el archivo .env y configura:"
    echo "   IZIPAY_MODE=production"
    echo "   IZIPAY_PROD_USERNAME=tu_usuario_prod"
    echo "   IZIPAY_PROD_PASSWORD=tu_password_prod"
    echo "   IZIPAY_PROD_PUBLIC_KEY=tu_public_key_prod"
    echo "   IZIPAY_PROD_HMAC_KEY=tu_hmac_key_prod"
    echo ""
elif [ "$IZIPAY_MODE" = "production" ]; then
    echo "✅ Modo: production (correcto)"
    echo ""
    echo "Verificando credenciales de producción..."

    PROD_USERNAME=$(grep "^IZIPAY_PROD_USERNAME=" .env | cut -d '=' -f2)
    PROD_PASSWORD=$(grep "^IZIPAY_PROD_PASSWORD=" .env | cut -d '=' -f2)
    PROD_PUBLIC_KEY=$(grep "^IZIPAY_PROD_PUBLIC_KEY=" .env | cut -d '=' -f2)
    PROD_HMAC_KEY=$(grep "^IZIPAY_PROD_HMAC_KEY=" .env | cut -d '=' -f2)

    if [ -z "$PROD_USERNAME" ] || [ -z "$PROD_PASSWORD" ] || [ -z "$PROD_PUBLIC_KEY" ] || [ -z "$PROD_HMAC_KEY" ]; then
        echo "❌ PROBLEMA: Credenciales de producción incompletas"
        echo ""
        echo "📝 Solución:"
        echo "Configura todas las variables IZIPAY_PROD_* en el .env"
    else
        echo "✅ Credenciales de producción configuradas"
    fi
else
    echo "⚠️  Modo desconocido o no configurado: '$IZIPAY_MODE'"
fi

ENDSSH

echo ""
echo "✓ Verificación completada"
echo ""
echo "📌 Nota: Si necesitas editar el .env en producción, usa:"
echo "   ssh -i ssh-mb-api.pem ubuntu@34.197.80.87"
echo "   cd /home/ubuntu/proyectos/girlockers"
echo "   nano .env"
