#!/bin/bash

# Script para arreglar permisos de Laravel en producción
# Uso: ./fix-permissions.sh

echo "======================================"
echo "Arreglando permisos de Laravel..."
echo "======================================"

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Directorio del proyecto
PROJECT_DIR="/home/ubuntu/proyectos/girlockers"

echo -e "${YELLOW}Navegando al directorio del proyecto...${NC}"
cd $PROJECT_DIR

echo -e "${YELLOW}1. Estableciendo propietario www-data:www-data para storage y bootstrap/cache...${NC}"
sudo chown -R www-data:www-data storage bootstrap/cache

echo -e "${YELLOW}2. Estableciendo permisos 775 para storage y bootstrap/cache...${NC}"
sudo chmod -R 775 storage bootstrap/cache

echo -e "${YELLOW}3. Agregando usuario ubuntu al grupo www-data...${NC}"
sudo usermod -a -G www-data ubuntu

echo -e "${YELLOW}4. Limpiando cachés de Laravel...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo -e "${YELLOW}5. Optimizando cachés para producción...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo -e "${GREEN}======================================"
echo -e "✓ Permisos arreglados correctamente!"
echo -e "======================================${NC}"

echo ""
echo "Verificando permisos actuales:"
ls -la storage/ | grep -E '^d'
echo ""
ls -la bootstrap/cache/ | grep -E '^d'
