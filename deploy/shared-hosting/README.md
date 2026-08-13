# Deploy en hosting compartido (sin Docker, sin VPS)

Esta es la alternativa a la guía de Oracle Cloud del `README.md` principal.
Sirve para cualquier hosting tradicional con PHP 8.2+ y MySQL (cPanel,
Plesk, o paneles propios de proveedores argentinos como DonWeb, etc.).

## Qué necesitás confirmar del hosting antes de arrancar

1. **Versión de PHP ≥ 8.2** (activable desde el panel, normalmente en
   "Select PHP Version" o similar).
2. **Una base de datos MySQL** + usuario con permisos (se crea desde el
   panel, en "MySQL Databases").
3. **¿Tiene SSH o Terminal?** Esto define qué camino seguís abajo. La
   mayoría de los paneles cPanel modernos incluyen una app "Terminal".

Si tenés SSH/Terminal, todo esto se hace mucho más simple (podés correr
`git`, `composer` y `artisan` directo en el servidor). Si no lo tenés, se
puede igual, pero subiendo todo pre-armado por FTP.

---

## Escenario A: podés cambiar el document root

Algunos hostings te dejan apuntar el dominio directo a una subcarpeta
(ej: apuntar `hotrocks.com.ar` a `/home/usuario/hotrocks/public`). Si es
tu caso, es el camino más simple y **no hace falta nada de lo que sigue
en este archivo** — subís el proyecto tal cual está (misma estructura de
carpetas que en local) y apuntás el document root a la carpeta `public/`.

## Escenario B: el document root está fijo en `public_html`

Es el caso más común en hosting compartido barato. La solución estándar
para Laravel:

1. Subís **todo el proyecto** (menos `public_html`) a una carpeta hermana,
   ej: `/home/usuario/hotrocks-app/`
2. Copiás el **contenido** de `public/` (no la carpeta en sí) adentro de
   `public_html/`
3. Reemplazás `public_html/index.php` por el de
   [`deploy/shared-hosting/index.php`](index.php) de este repo (ya viene
   ajustado para buscar el proyecto en `../hotrocks-app`)

Quedaría así en el servidor:

```
/home/usuario/
├── hotrocks-app/          ← proyecto completo (app/, vendor/, storage/, etc.)
└── public_html/
    ├── index.php           ← el de deploy/shared-hosting/index.php
    ├── .htaccess            ← el de public/.htaccess (sin cambios)
    ├── favicon.ico
    ├── robots.txt
    └── storage -> ../hotrocks-app/storage/app/public   (symlink, ver más abajo)
```

---

## Pasos (con SSH/Terminal)

```bash
# 1. Conectarte
ssh usuario@tu-servidor

# 2. Clonar el proyecto (fuera de public_html)
git clone git@github.com:hotrocksdesign/hotrocks.git hotrocks-app
cd hotrocks-app

# 3. Instalar dependencias de producción
composer install --no-dev --optimize-autoloader

# 4. Configurar .env (desde tu Mac, no desde el servidor — tiene secretos,
# nunca va por git):
#   scp /Users/nicolas/Desktop/Hotrocks/.env.production.example usuario@servidor:~/hotrocks-app/.env
# Ya tiene APP_KEY y contraseñas generadas. Solo falta que ajustes DB_HOST
# (normalmente "localhost" en hosting compartido) y DB_DATABASE/DB_USERNAME
# con los datos reales que te dé el panel al crear la base — muchos
# hostings prefijan el nombre con tu usuario (ej: "usuario_hotrocks").

# 5. Migrar
php artisan migrate --force

# 6. Storage: si podés symlink (normalmente sí con SSH)
php artisan storage:link

# 7. Si estás en el Escenario B, copiá el contenido de public/ a public_html/
#    y reemplazá el index.php:
cp -r public/* ../public_html/
cp deploy/shared-hosting/index.php ../public_html/index.php
```

## Pasos (sin SSH — todo por FTP)

1. En tu Mac, generá el paquete listo para subir corriendo
   `bash deploy/shared-hosting/build-package.sh` (arma un `.zip` con el
   `vendor/` ya instalado, para no depender de Composer en el servidor).
2. Subís ese `.zip` por FTP/SFTP a `hotrocks-app/` (fuera de `public_html`)
   y lo descomprimís ahí (muchos paneles tienen "Extract" en el File
   Manager, así no hace falta subir archivo por archivo).
3. Copiás el contenido de `hotrocks-app/public/` a `public_html/`.
4. Reemplazás `public_html/index.php` por
   [`deploy/shared-hosting/index.php`](index.php).
5. Subís `.env.production.example` (está en la raíz del proyecto en tu
   Mac, tiene `APP_KEY` y contraseñas ya generadas) por FTP a
   `hotrocks-app/.env`, y ajustás `DB_HOST`/`DB_DATABASE`/etc. con File
   Manager o un cliente FTP que edite texto.
6. Las migraciones sin SSH son el único paso realmente incómodo — la
   opción más simple es conectar MySQL Workbench (o similar) a la base
   remota si el panel permite conexiones externas, y correr el `.sql`
   exportado desde tu base local. Si no permite conexión externa,
   la mayoría de los paneles tienen phpMyAdmin, donde podés importar
   un dump SQL directo.

## Permisos

`storage/` y `bootstrap/cache/` necesitan ser escribibles por el proceso
de PHP. Si algo tira error 500 después de subir todo, primero probá:

```bash
chmod -R 775 storage bootstrap/cache
```

## Notas

- `SESSION_DRIVER=cookie` y `CACHE_DRIVER=file` (ya configurados) andan
  bien en hosting compartido sin nada extra que instalar.
- Los límites de subida de PHP (`upload_max_filesize`, `post_max_size`)
  los controla el hosting, no nosotros — si al subir varias fotos te tira
  413 o el mismo error de "Content-Length exceeds", pedile al soporte del
  hosting que los suba (o cambialo vos mismo si el panel te deja, en
  "MultiPHP INI Editor" o similar).
