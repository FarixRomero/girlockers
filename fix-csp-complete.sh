#!/bin/bash

echo "🔧 Actualizando CSP con TODOS los dominios de MiCuentaWeb"
echo "=========================================================="
echo ""

ssh -i ssh-mb-api.pem ubuntu@34.197.80.87 << 'ENDSSH'
cd /home/ubuntu/proyectos/girlockers

echo "📋 CSP actual:"
grep "Content-Security-Policy" public/.htaccess | head -c 200
echo "..."
echo ""

echo "📝 Creando backup..."
cp public/.htaccess public/.htaccess.backup.$(date +%Y%m%d_%H%M%S)
echo "✓ Backup creado"
echo ""

echo "🔧 Aplicando corrección completa..."

# Reemplazar la línea completa de CSP con la versión actualizada
sudo sed -i 's|Header set Content-Security-Policy ".*"|Header set Content-Security-Policy "default-src '\''self'\''; script-src '\''self'\'' '\''unsafe-inline'\'' '\''unsafe-eval'\'' https://www.youtube.com https://iframe.mediadelivery.net https://static.micuentaweb.pe https://secure.micuentaweb.pe; style-src '\''self'\'' '\''unsafe-inline'\'' https://fonts.googleapis.com https://static.micuentaweb.pe; img-src '\''self'\'' data: https:; font-src '\''self'\'' data: https://fonts.gstatic.com; frame-src '\''self'\'' https://www.youtube.com https://iframe.mediadelivery.net https://static.micuentaweb.pe https://secure.micuentaweb.pe; connect-src '\''self'\'' https://video.bunnycdn.com https://storage.bunnycdn.com https://*.b-cdn.net https://static.micuentaweb.pe https://secure.micuentaweb.pe; media-src '\''self'\'' https://video.bunnycdn.com https://vz-e2a43e7b-5d6.b-cdn.net https://*.b-cdn.net;"|g' public/.htaccess

echo "✓ CSP actualizada"
echo ""

echo "📋 Nueva CSP:"
grep "Content-Security-Policy" public/.htaccess
echo ""

echo "🔄 Reiniciando Apache..."
sudo systemctl restart apache2

echo ""
echo "✅ Corrección completa aplicada exitosamente"
echo ""
echo "🎯 Dominios agregados:"
echo "   ✓ script-src: static.micuentaweb.pe + secure.micuentaweb.pe"
echo "   ✓ style-src: static.micuentaweb.pe"
echo "   ✓ frame-src: static.micuentaweb.pe + secure.micuentaweb.pe"
echo "   ✓ connect-src: static.micuentaweb.pe + secure.micuentaweb.pe (source maps)"
echo ""
echo "🧹 Limpia la caché del navegador (Ctrl+Shift+R) y prueba de nuevo"

ENDSSH

echo ""
echo "✓ Actualización completada"
