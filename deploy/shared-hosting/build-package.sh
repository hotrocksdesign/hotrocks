#!/bin/bash
# Arma un .zip con el proyecto listo para subir a hosting compartido:
# copia el código, instala dependencias de PRODUCCIÓN (--no-dev) en una
# carpeta temporal aparte (no toca el vendor/ que usás en local), y
# comprime todo. No incluye .env (eso se sube/transfiere aparte, tiene
# secretos).
set -e

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
BUILD_DIR="$(mktemp -d)/hotrocks-app"
OUTPUT="$PROJECT_DIR/hotrocks-deploy.zip"

echo "Proyecto: $PROJECT_DIR"
echo "Armando build en: $BUILD_DIR"

mkdir -p "$BUILD_DIR"

rsync -a "$PROJECT_DIR"/ "$BUILD_DIR"/ \
  --exclude '.git' \
  --exclude 'node_modules' \
  --exclude 'docker' \
  --exclude 'deploy' \
  --exclude '.env' \
  --exclude '.env.*' \
  --exclude 'hotrocks-deploy.zip' \
  --exclude 'vendor' \
  --exclude '.claude' \
  --exclude 'storage/logs/*' \
  --exclude 'storage/framework/cache/data/*' \
  --exclude 'storage/framework/sessions/*' \
  --exclude 'storage/framework/views/*' \
  --exclude 'bootstrap/cache/*.php'

echo "Instalando dependencias de producción (--no-dev)..."
docker run --rm -v "$BUILD_DIR":/app -w /app php:8.4-cli bash -c "
  apt-get update -qq && apt-get install -y -qq unzip git libzip-dev >/dev/null && docker-php-ext-install zip >/dev/null
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
  composer install --no-dev --optimize-autoloader --no-interaction
"

echo "Comprimiendo..."
rm -f "$OUTPUT"
(cd "$(dirname "$BUILD_DIR")" && zip -rq "$OUTPUT" "$(basename "$BUILD_DIR")")

rm -rf "$(dirname "$BUILD_DIR")"

echo ""
echo "Listo: $OUTPUT"
echo "Subilo por FTP/SFTP a tu hosting y descomprimilo en una carpeta"
echo "fuera de public_html (ver deploy/shared-hosting/README.md)."
