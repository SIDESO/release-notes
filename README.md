# Sideso Release Notes

Paquete Laravel para mostrar releases de un repositorio privado o público de GitHub, filtrando solo instaladores y assets permitidos, excluyendo siempre el código fuente.

## Instalación

1. Instala el paquete vía Composer:

```
composer require sideso/release-notes
```

2. Publica el archivo de configuración:

```
php artisan vendor:publish --provider="Sideso\ReleaseNotes\ReleaseNotesServiceProvider" --tag=config
```

3. Agrega tu token de GitHub en el archivo `.env`:

```
GITHUB_TOKEN=tu_token_aqui
```

## Uso

Incluye el componente Blade donde desees mostrar los releases:

```
<x-release-notes :repository="$repository" :allowedAssets="[]"/>
```

Puedes personalizar los assets permitidos enviando el parámetro `allowedAssets[]` como regex.

### Seguridad en descargas

- **Las descargas de assets solo están disponibles para usuarios autenticados.**
- **Las URLs de descarga son firmadas y no pueden ser alteradas.**
- Si un usuario no está autenticado o la URL es manipulada, la descarga será rechazada.

### Ejemplo de flujo seguro

- El componente genera enlaces de descarga usando URLs firmadas:
  ```blade
  <a href="{{ URL::signedRoute('release-notes.download', [...]) }}">Descargar</a>
  ```
- La ruta de descarga está protegida con los middlewares `auth` y `signed`.


