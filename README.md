# Pasos para utilizar el Template

1. Cambiar el nombre y la descripción del proyecto en `composer.json`
2. `composer run-script init-app`
3. Verficiar en el `.env` que se haya configurado bien
   APP_NAME, DB_CONNECTION y DB_DATABASE, es necesario que APP_NAME y DB_CONNECTION se llamen iguales
4. En el archivo config/database.php, buscar dentro de `connections` aquella que se llame
   `template-laravel` y renombrarlo igual que el APP_NAME de `.env`
5. Verificar que según el ambiente en el que se encuentre, si local, replica o producción se hayan generado bien las variables del `.env` `BASE_ADMIN_URL`, `APP_URL`, `DB_HOST`.
6. En caso de ya poseer el token de la app (en replica o en prod) añadirlo en `.env` en la variable `APP_TOKEN`
7. En la variable de `DISCORD_URL` añadir la siguiente, si se debe informar sobre los errores, `https://discord.com/api/webhooks/1280220958128476343/uA-e7TiSCzsg6iKdibN5PDnaU1oxKuaQJgpsOv6gicAXRaYrzAQq8aF-okK7my6wJb9M/slack`
8. Happy hacking!

# Contenido del template

## Installar Laravel Spatie PDF:

`composer require spatie/laravel-pdf`
`composer require spatie/browsershot`
`composer run-script install-puppeteer`

descomnetar ruta en api.php `Route::get('prueba_pdf',[PDFController::class,'pdf']);`

# **T A B L E S:** Abstracción

### Sintaxis de creación por **C O M A N D O**
```bash
php artisan make:sub-table --model=NuevoModelo --name=nueva_tabla --labels="Opción1,Opción2,Opción3,Opción4" 
```
**Reglas:**
- Prohibido usar a `TABLES` para hacer relaciones. Sustituir los Models que se extendienden de `TABLES` (es decir, que son creados a partir del comando anterior).
- **ATENCIÓN:** **[php ... --model=X --name=Y ...]** la combinacion X e Y no debe existir en la BD.

