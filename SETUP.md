# Hot Rocks - Proyecto Iniciado ✅

## Lo que se ha completado

### Estructura Base Laravel
- ✅ Proyecto Laravel 11 con estructura completa
- ✅ Docker y docker-compose configurado
- ✅ Configuración de variables de entorno (.env)
- ✅ Composer.json con dependencias

### Base de Datos - Modelos y Migraciones
**Modelos Eloquent creados:**
1. **User** - Usuarios con roles (admin, editor, band)
2. **Band** - Perfiles de bandas con datos básicos
3. **Review** - Reseñas de shows
4. **ReviewPhoto** - Galería de fotos por reseña
5. **Show** - Agenda de shows con aprobación
6. **Tag** - Etiquetas para géneros y ciudades
7. **InstagramSettings** - Config para Graph API

**Tablas de migraciones creadas:**
- users (con roles)
- bands
- shows (con estado: pending/approved/rejected)
- show_band (relación muchos-a-muchos)
- reviews
- review_photos
- tags
- review_tag (relación muchos-a-muchos)
- instagram_settings

### Controladores Implementados
1. **HomeController** - Página principal
2. **ReviewController** - Listado y visualización de reseñas
3. **BandController** - Listado y fichas de bandas
4. **AgendaController** - Búsqueda de shows
5. **Admin/ReviewAdminController** - CRUD de reseñas (admin/editor)
6. **Admin/ShowAdminController** - Aprobación/rechazo de shows

### Rutas Configuradas
```
/ → Home
/reviews → Listado de reseñas
/reviews/{id} → Detalle de reseña
/bands → Listado de bandas
/bands/{id} → Ficha de banda
/agenda → Agenda de shows
/admin/reviews → Panel de reseñas (protegido)
/admin/shows/pending → Cola de aprobación (solo admin)
```

### Vistas Base
- ✅ Layout principal con paleta de colores (negro + rojo #D6001C + blanco)
- ✅ Home con últimas reseñas y próximos shows
- Vistas faltantes: reviews, bands, agenda, admin (a completar en próximo paso)

### Seeders
- Admin de prueba (admin@hotrocks.local / admin123)
- Editor de prueba (editor@hotrocks.local / editor123)
- 5 bandas de ejemplo
- Tags de géneros y ciudades

## Próximos pasos

### Fase 1 - MVP (A hacer inmediatamente)
1. **Vistas frontend completas:**
   - reviews/index.blade.php (listado con search)
   - reviews/show.blade.php (detalle)
   - bands/index.blade.php
   - bands/show.blade.php
   - agenda/index.blade.php (con buscador)
   - admin/reviews/* (CRUD)
   - admin/shows/pending.blade.php

2. **Autenticación:**
   - Login para usuarios (admin/editor/band)
   - Registro para bandas
   - Middleware de autorización

3. **Subida de archivos:**
   - Fotos (con compresión automática)
   - Flyers de shows
   - Compresión de imágenes

4. **Instagram Graph API:**
   - Servicio para traer posts automáticamente
   - Mostrar en home

5. **Búsqueda:**
   - Por banda
   - Por lugar
   - Por fecha
   - Por género/tag

### Fase 2 - Mejoras
- Fichas de venues
- Newsletter
- Comentarios moderados
- Ranking de shows
- Timeline visual
- SEO optimizado

## Configuración para desarrollo local

```bash
# 1. Copiar .env
cp .env.example .env

# 2. Levantar Docker
docker-compose up -d

# 3. La app automáticamente corre migraciones y seeders

# 4. Acceder
http://localhost:8000

# Credenciales de prueba:
# Admin: admin@hotrocks.local / admin123
# Editor: editor@hotrocks.local / editor123
```

## Arquitectura de carpetas importante

```
/app/Models/           → Entidades de BD (Band, Review, User, etc.)
/app/Http/Controllers/ → Lógica de requests
/database/migrations/  → Schema de BD (ya creadas)
/database/seeders/     → Datos iniciales
/resources/views/      → Templates Blade (próximas a completar)
/routes/web.php        → Rutas HTTP
/storage/uploads/      → Almacenamiento de fotos/videos
/config/              → Configuración
/docker-compose.yml   → Orquestación de servicios
```

## Notas importantes

⚠️ **Marca registrada**: El logo actual usa la lengua/labios de Rolling Stones. Antes de lanzar públicamente, consultar riesgo legal.

✅ **Base de datos lista**: Todas las tablas e relaciones están creadas. Solo falta implementar las vistas frontend.

✅ **Docker listo**: El proyecto puede levantarse directamente con `docker-compose up -d`

✅ **Seguridad**: Middleware de auth y roles ya está en controladores admin

## Archivo de referencia rápida

- README.md → Instrucciones completas de setup y deployment
- .env.example → Variables requeridas
- docker-compose.yml → Servicios (MySQL + Laravel)
- routes/web.php → Todas las rutas definidas
