#!/bin/bash

# Deploy script - SSH auto connection and git pull
# Usage: ./deploy.sh

echo "🚀 Iniciando deployment..."

# Construir en local
npm run build

# Commit y push de los archivos de build
git add .
git commit -m "Agrega archivos de build al repositorio" || true
git push origin main

# Conectarse al servidor y ejecutar comandos remotos
wsl sshpass -p "Trilce@123" ssh -p 65002 -o StrictHostKeyChecking=no u912353527@89.116.115.104 << 'EOF'
  echo "📁 Navegando al directorio del proyecto..."
  cd domains/consultoria-im.com/public/girlockers


  echo "📦 Ejecutando git pull..."
  git pull origin main
  php artisan migrate:refresh --seed

  echo "✅ Deployment completado!"
EOF

echo "🎉 Script de deployment finalizado!"
