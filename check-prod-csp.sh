#!/bin/bash

# Script para diagnosticar y corregir CSP en producción
echo "🔍 Diagnóstico de CSP en Producción"
echo "===================================="
echo ""

ssh -i ssh-mb-api.pem ubuntu@34.197.80.87 << 'ENDSSH'
cd /home/ubuntu/proyectos/girlockers

echo "📁 1. Verificando ubicación del proyecto:"
pwd
echo ""

echo "📄 2. Verificando si existe public/.htaccess:"
ls -lah public/.htaccess
echo ""

echo "📋 3. Contenido actual de la CSP en .htaccess:"
grep -A 1 "Content-Security-Policy" public/.htaccess || echo "❌ No se encontró CSP"
echo ""

echo "🔧 4. Verificando módulos de Apache necesarios:"
apache2ctl -M | grep -E "headers|rewrite" || echo "❌ Módulos no encontrados"
echo ""

echo "📦 5. Verificando configuración del VirtualHost:"
sudo apache2ctl -S 2>&1 | grep -A 5 "girlockers\|ubuntu"
echo ""

echo "✅ 6. Verificando si Apache permite .htaccess (AllowOverride):"
sudo grep -r "AllowOverride" /etc/apache2/sites-enabled/ 2>/dev/null || echo "⚠️  No se encontró configuración"
echo ""

echo "🔍 7. Verificando permisos del .htaccess:"
stat public/.htaccess
echo ""

echo "================================"
echo "🛠️  ¿Aplicar corrección automática? (y/n)"
read -p "> " respuesta

if [ "$respuesta" = "y" ] || [ "$respuesta" = "Y" ]; then
    echo ""
    echo "📝 Aplicando corrección..."

    # Backup
    cp public/.htaccess public/.htaccess.backup.$(date +%Y%m%d_%H%M%S)
    echo "✓ Backup creado"

    # Actualizar CSP
    sed -i 's|script-src '\''self'\'' '\''unsafe-inline'\'' '\''unsafe-eval'\'' https://www.youtube.com https://iframe.mediadelivery.net;|script-src '\''self'\'' '\''unsafe-inline'\'' '\''unsafe-eval'\'' https://www.youtube.com https://iframe.mediadelivery.net https://static.micuentaweb.pe;|g' public/.htaccess

    sed -i 's|style-src '\''self'\'' '\''unsafe-inline'\'' https://fonts.googleapis.com;|style-src '\''self'\'' '\''unsafe-inline'\'' https://fonts.googleapis.com https://static.micuentaweb.pe;|g' public/.htaccess

    echo "✓ CSP actualizada"

    echo ""
    echo "📋 Nueva CSP:"
    grep "Content-Security-Policy" public/.htaccess

    echo ""
    echo "🔄 Reiniciando Apache..."
    sudo systemctl restart apache2

    echo ""
    echo "✅ Corrección aplicada exitosamente"
    echo "🧹 Limpia la caché del navegador (Ctrl+Shift+R) y prueba de nuevo"
else
    echo "❌ Corrección cancelada"
fi

ENDSSH

echo ""
echo "✓ Diagnóstico completado"
