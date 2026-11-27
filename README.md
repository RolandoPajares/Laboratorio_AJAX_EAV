# EAV STORE

Catálogo dinámico de productos con MVC, Eloquent ORM, MariaDB, Fetch API y AJAX.

## Instalación

1. Iniciar Apache en el puerto 18080 y MySQL/MariaDB en el puerto 15327.
2. Importar database/01_esquema.sql.
3. Importar database/02_datos_prueba.sql.
4. Si la base ya contenía textos con signos ??, ejecutar database/04_corregir_utf8.sql.
5. Configurar app/Config/config.php.
6. Ejecutar composer install si no existe vendor/.
7. Abrir http://localhost:18080/dwa2/Laboratorio_AJAX_EAV/.

## ORM

El proyecto usa Eloquent ORM mediante illuminate/database. Los modelos principales son Producto, Categoria, Atributo y ValorProducto. Las relaciones son belongsTo y hasMany; los filtros usan whereHas y la carga de datos usa with.

## Rutas

GET /api/categorias
GET /api/filtros?categoria_id=1
GET /api/productos?categoria_id=2&q=phone

La entidad principal es productos. Las categorías clasifican productos y los atributos variables se almacenan en valores_productos.

No publicar app/Config/config.php porque contiene credenciales locales.
