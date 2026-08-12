# Hot Rocks - Rock Show Coverage Website

## Descripción

Sitio web para cubrir y difundir shows/recitales de bandas de rock con reseñas propias (texto, fotos y videos), perfiles de bandas, buscador de shows, integración con Instagram y panel de administración.

## Stack Técnico

- **Backend**: Laravel 11
- **Base de datos**: MySQL 8.0
- **Containerización**: Docker & Docker Compose
- **Hosting**: Oracle Cloud Free Tier (o compatible con cualquier servidor con Docker)

## Estructura del Proyecto

```
hotrocks/
├── app/
│   ├── Models/              # Modelos Eloquent (User, Band, Review, Show, etc.)
│   └── Http/Controllers/    # Controladores (Home, Review, Band, Admin, etc.)
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/             # Seeders (datos de prueba)
├── routes/
│   └── web.php              # Rutas de la aplicación
├── resources/views/         # Vistas Blade (a implementar)
├── storage/
│   └── uploads/             # Fotos y archivos subidos
├── config/                  # Configuración de la aplicación
├── docker-compose.yml       # Orquestación de contenedores
├── Dockerfile               # Imagen Docker para Laravel
└── README.md                # Este archivo
```

## Modelos de Base de Datos

### Users
- id, name, email, password, role (admin/editor/band), band_id

### Bands
- id, name, slug, biography, photo_url, instagram_url, spotify_url, youtube_url, genre

### Shows
- id, band_id, user_id, date, venue, city, description, flyer_url, ticket_url, status (pending/approved/rejected)

### Reviews
- id, title, content, band_id, show_id, venue, show_date, featured_image, setlist_image, video_url, user_id, published_at

### ReviewPhotos
- id, review_id, photo_url, caption, order

### Tags
- id, name, slug, type (genre/city/other)

### ReviewTags
- id, review_id, tag_id (relación muchos-a-muchos)

### InstagramSettings
- id, graph_api_token, business_account_id, account_username, last_sync

### ShowBand
- id, show_id, band_id (relación muchos-a-muchos)

## Setup Local

### Requisitos
- Docker y Docker Compose instalados
- macOS/Linux/Windows (cualquier SO con Docker)

### Instalación

1. **Clonar o navegar al directorio del proyecto**
```bash
cd /Users/nicolas/Desktop/Hotrocks
```

2. **Copiar .env.example a .env**
```bash
cp .env.example .env
```

3. **Generar APP_KEY**
```bash
docker run --rm -v "$(pwd)":/app php:8.2-cli php -r "echo base64_encode(bin2hex(random_bytes(32))) . PHP_EOL;" > /tmp/key && sed -i '' "s/^APP_KEY=.*/APP_KEY=base64:$(cat /tmp/key)/" .env
```

4. **Construir y levantar contenedores**
```bash
docker-compose up -d
```

5. **Instalar dependencias y ejecutar migraciones** (automático en docker-compose.yml)
```bash
docker-compose exec laravel php artisan migrate
docker-compose exec laravel php artisan db:seed
```

6. **Acceder a la aplicación**
```
http://localhost:8000
```

### Parar los contenedores
```bash
docker-compose down
```

## Deployment en Oracle Cloud Free Tier

Esta guía usa el `docker-compose.prod.yml` incluido en el repo, que agrega
nginx como proxy reverso con HTTPS delante de la app (el `docker-compose.yml`
base sigue siendo el mismo que usás en local, sin cambios).

### 1. Crear la VM en Oracle Cloud

- Consola de Oracle Cloud → **Compute → Instances → Create Instance**
- Shape: **Ampere A1 (ARM)**, sigue siendo gratis sin límite de tiempo dentro del Always Free tier
- Imagen: **Ubuntu 22.04 LTS**
- Guardá la clave SSH que te genera (o subí la tuya) — la vas a necesitar para conectarte
- Anotá la **IP pública** de la instancia

**Abrir los puertos 80 y 443** en la VCN (esto se hace en dos lugares, es el paso que más gente se olvida):

1. En la consola: **Networking → Virtual Cloud Networks → tu VCN → Security Lists → Default Security List → Add Ingress Rules**. Agregá dos reglas: `0.0.0.0/0` puerto `80` y `0.0.0.0/0` puerto `443`.
2. Dentro de la VM (Ubuntu trae su propio firewall activo además del de Oracle):
```bash
sudo iptables -I INPUT 6 -m state --state NEW -p tcp --dport 80 -j ACCEPT
sudo iptables -I INPUT 6 -m state --state NEW -p tcp --dport 443 -j ACCEPT
sudo netfilter-persistent save
```

### 2. Apuntar tu dominio

En tu proveedor de DNS, creá un registro **A** apuntando a la IP pública de la VM. Esperá a que propague (podés chequear con `dig tudominio.com`) antes de pedir el certificado SSL.

### 3. Instalar Docker y traer el código

```bash
ssh ubuntu@TU_IP_PUBLICA

# Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
newgrp docker

# Clonar el repo (privado en GitHub — necesitás una key con acceso o un token)
git clone git@github.com:tu-usuario/hotrocks.git
cd hotrocks
```

### 4. Configurar `.env` de producción

```bash
cp .env.example .env
```

Editá `.env` y ajustá como mínimo:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_PASSWORD=<una-contraseña-fuerte>
DB_ROOT_PASSWORD=<otra-contraseña-fuerte>
```

Generá una `APP_KEY` real:
```bash
docker run --rm -v "$(pwd)":/app php:8.4-cli php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
# pegá el resultado en APP_KEY= dentro de .env
```

### 5. Preparar la config de nginx con tu dominio

```bash
sed -i "s/tudominio.com/tu-dominio-real.com/g" docker/nginx/http-only.conf docker/nginx/https.conf
cp docker/nginx/http-only.conf docker/nginx/active.conf
```

### 6. Levantar todo (fase HTTP, para poder emitir el certificado)

```bash
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
```

Esto construye la imagen, corre las migraciones automáticamente y deja nginx sirviendo el sitio por HTTP en el puerto 80 (redirigiendo internamente a Laravel). Probá `http://tudominio.com` antes de seguir.

### 7. Obtener el certificado SSL con Certbot

```bash
sudo apt update && sudo apt install certbot -y

sudo certbot certonly --webroot \
  -w "$(pwd)/docker/certbot/webroot" \
  -d tu-dominio-real.com
```

### 8. Activar HTTPS

```bash
cp docker/nginx/https.conf docker/nginx/active.conf
docker-compose -f docker-compose.yml -f docker-compose.prod.yml restart nginx
```

Entrá a `https://tudominio.com` — debería verse el candado.

**Renovación automática** (Certbot ya instala un timer systemd que corre 2 veces por día, solo hace falta que reinicie nginx después de renovar):
```bash
echo '#!/bin/bash
docker restart hotrocks-nginx' | sudo tee /etc/letsencrypt/renewal-hooks/deploy/restart-nginx.sh
sudo chmod +x /etc/letsencrypt/renewal-hooks/deploy/restart-nginx.sh
```

### 9. Datos iniciales y seguridad

```bash
docker-compose exec laravel php artisan db:seed
```

Esto crea el admin de prueba (`admin@hotrocks.local` / `admin123`) — **entrá y cambiá esa contraseña de inmediato**, o mejor, creá tu propio usuario admin real vía tinker y borrá el de prueba.

### Actualizar el sitio después del primer deploy

```bash
cd hotrocks
git pull
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
docker-compose exec laravel php artisan migrate --force
```

## Estructura de Roles

### Admin
- Crear/editar/eliminar reseñas
- Crear/editar/eliminar bandas
- Aprobar o rechazar shows
- Gestionar usuarios y roles
- Acceso a todo el panel

### Editor/Redactor
- Crear/editar/eliminar sus propias reseñas
- Subir fotos/videos
- Ver estadísticas básicas

### Band
- Registrarse como banda
- Cargar shows futuros (quedan en estado "pending")
- Ver estado de aprobación

## Funcionalidades MVP (Fase 1)

- [x] Estructura de base de datos
- [x] Modelos Eloquent
- [ ] Vistas frontend (Home, Reseñas, Bandas, Agenda)
- [ ] Panel de admin
- [ ] Autenticación
- [ ] Subida de fotos con compresión
- [ ] Integración Instagram Graph API
- [ ] Buscador básico

## Funcionalidades Fase 2

- [ ] Fichas de venues
- [ ] Sistema de tags/géneros
- [ ] Newsletter
- [ ] Comentarios (moderados)
- [ ] Ranking de shows
- [ ] Timeline visual
- [ ] SEO mejorado

## Consideraciones de Seguridad

- Usar HTTPS en producción
- Validar todas las entradas de usuario
- Usar tokens CSRF en formularios
- Limitar acceso a rutas admin con middleware `auth` y verificación de roles
- Mantener secretos (.env) fuera del repo

## Variables de Entorno Importantes

```
APP_NAME=Hot Rocks
APP_ENV=production
APP_KEY=base64:xxxxx
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=hotrocks
DB_USERNAME=hotrocks
DB_PASSWORD=xxxxx

INSTAGRAM_GRAPH_API_TOKEN=xxxxx
INSTAGRAM_BUSINESS_ACCOUNT_ID=xxxxx

SHOP_URL=https://tu-shop-url.com
```

## Notas Importantes

⚠️ **Disclaimer sobre el Logo**: El logo actual utiliza la lengua/labios registrada de los Rolling Stones y el nombre "Hot Rocks" remite a un álbum de ellos. Antes de lanzar públicamente, consulta sobre el riesgo de uso de marca registrada de terceros.

## Contribuir

Instrucciones para colaboradores (a definir según política del proyecto)

## Licencia

[A definir]

## Contacto

[Información de contacto]
