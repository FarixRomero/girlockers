#!/bin/bash

# Colores para output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Establecer el mensaje de commit predeterminado si no se proporciona ningún argumento
if [ -z "$1" ]; then
    commit_message="Avance"
else
    commit_message="$1"
fi

echo -e "${BLUE}📦 Iniciando despliegue...${NC}"
echo ""

# Añadir todos los cambios
echo -e "${YELLOW}→ Añadiendo cambios al repositorio...${NC}"
git add .

# Hacer commit con el mensaje proporcionado como argumento
echo -e "${YELLOW}→ Creando commit: ${commit_message}${NC}"
git commit -m "$commit_message"

# Empujar los cambios al repositorio remoto
echo -e "${YELLOW}→ Subiendo cambios a GitHub...${NC}"
git push origin main

# Conectar por SSH y ejecutar los comandos en la instancia EC2
echo ""
echo -e "${BLUE}🚀 Actualizando servidor de producción...${NC}"
ssh -i ssh-mb-api.pem ubuntu@34.197.80.87 << 'ENDSSH'
cd /home/ubuntu/proyectos/girlockers

echo "→ Descargando últimos cambios..."
git pull origin master

echo "→ Instalando dependencias de Composer..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "→ Limpiando TODOS los caches (SIN regenerar)..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
php artisan migrate

echo "✓ Servidor actualizado exitosamente (modo seguro - sin cache)"
echo "⚠️  Laravel cargará configuración en cada request (más lento pero más seguro)"
ENDSSH
