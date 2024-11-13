# Prueba Técnica Joiner Escorcia

 El sistema proporciona endpoints para la gestión de productos y órdenes.

## Requisitos del Sistema

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Docker (opcional)

## Configuración del Proyecto

### Instalación Local

1. Clonar el repositorio:
```bash
git clone https://github.com/Joineredh19/ecommerce-prueba-linktic
cd ecommerce-prueba-linktic
```

2. Instalar dependencias:
```bash
composer install
```

3. Configurar el archivo de entorno:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configurar la base de datos en el archivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prueba-tecnica-joiner
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

5. Ejecutar las migraciones:
```bash
php artisan migrate
```

### Instalación con Docker

1. Construir y levantar los contenedores:
```bash
docker-compose up -d
```

2. Acceder al contenedor de la aplicación:
```bash
docker-compose exec app bash
```

3. Instalar dependencias y configurar el proyecto:
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Estructura del Proyecto

```
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   └── Api
│   │   │       ├── ProductoController.php
│   │   │       └── OrdenController.php
│   │   └── Requests
│   │       ├── ProductoRequest.php
│   │       └── OrdenRequest.php
│   ├── Models
│   │   ├── Producto.php
│   │   ├── Orden.php
│   │   └── OrdenItem.php
│   └── Traits
│       └── ApiRespuesta.php
├── database
│   └── migrations
└── routes
    └── api.php
```

## API Endpoints

### Productos

- `GET /api/productos` - Listar todos los productos
- `POST /api/productos` - Crear un nuevo producto
- `GET /api/productos/{id}` - Obtener un producto específico
- `PUT /api/productos/{id}` - Actualizar un producto
- `DELETE /api/productos/{id}` - Eliminar un producto

### Órdenes

- `GET /api/ordenes` - Listar todas las órdenes
- `POST /api/ordenes` - Crear una nueva orden
- `GET /api/ordenes/{id}` - Obtener una orden específica
- `PUT /api/ordenes/{id}` - Actualizar el estado de una orden

## Documentación API

La documentación de la API está disponible a través de Swagger UI en:
```
http://localhost:8000/api/documentation
```

Para regenerar la documentación:
```bash
php artisan l5-swagger:generate
```

## Manejo de Errores

El sistema implementa un manejo de errores consistente con los siguientes códigos HTTP:

- 200: Éxito
- 201: Recurso creado
- 400: Error de solicitud
- 404: Recurso no encontrado
- 422: Error de validación
- 500: Error interno del servidor


## Características Adicionales

- Paginación automática en listados
- Validación robusta de datos
- Manejo de transacciones en operaciones críticas
- Soft deletes para productos y órdenes
- Documentación API con Swagger
