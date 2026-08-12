#!/bin/bash

# Hot Rocks - Quick Start Script
# ==============================

echo "🎸 Hot Rocks - Inicializando proyecto..."
echo ""

# Verificar que estamos en el directorio correcto
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Error: Este script debe ejecutarse desde la raíz del proyecto (donde está docker-compose.yml)"
    exit 1
fi

# Copiar .env si no existe
if [ ! -f ".env" ]; then
    echo "📋 Copiando .env.example a .env..."
    cp .env.example .env
    echo "✅ .env creado"
else
    echo "✅ .env ya existe"
fi

echo ""
echo "🐳 Levantando servicios Docker..."
docker-compose up -d

echo ""
echo "⏳ Esperando a que MySQL esté listo..."
sleep 10

echo ""
echo "🗄️  Ejecutando migraciones..."
docker-compose exec -T laravel php artisan migrate --force

echo ""
echo "🌱 Cargando datos de prueba..."
docker-compose exec -T laravel php artisan db:seed

echo ""
echo "✅ ¡Proyecto listo!"
echo ""
echo "📍 Acceder a: http://localhost:8000"
echo ""
echo "🔐 Credenciales de prueba:"
echo "   Admin:  admin@hotrocks.local / admin123"
echo "   Editor: editor@hotrocks.local / editor123"
echo ""
echo "📖 Para ver logs:"
echo "   docker-compose logs -f laravel"
echo ""
echo "🛑 Para detener:"
echo "   docker-compose down"
echo ""
