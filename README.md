# API de Opiniones Turísticas

Este proyecto es un backend construido con Laravel que proporciona una API RESTful para gestionar reseñas de destinos turísticos en México, simulando un sistema de recolección de datos para un corpus de análisis de sentimientos.

El proyecto está completamente contenedorizado con Docker para un fácil despliegue y portabilidad.

## Características Principales

-   Backend moderno y modular con **Laravel 12**.
-   API RESTful con endpoints **CRUD** (Crear, Leer, Actualizar, Eliminar) para gestionar opiniones.
-   Base de datos NoSQL en la nube con **MongoDB Atlas**.
-   Entorno de desarrollo local consistente y aislado con **Docker**.
-   Exportación de datos a formato **CSV**.
-   Una interfaz visual simple para demostración y pruebas.

---

## Stack Tecnológico

-   **Backend:** PHP 8.2+, Laravel 12
-   **Base de Datos:** MongoDB Atlas
-   **Contenerización:** Docker / Docker Compose
-   **Servidor Web:** Nginx

---

## Cómo Levantar el Entorno Local

Sigue estos pasos para ejecutar el proyecto en tu máquina local.

### Prerrequisitos

-   Tener [Docker](https://www.docker.com/products/docker-desktop/) instalado y en ejecución.
-   En Windows, se recomienda usar [WSL2](https://learn.microsoft.com/es-es/windows/wsl/install).

### Pasos de Instalación

1.  **Clonar el repositorio:**
    ```bash
    git clone [https://github.com/UzielLujan/OpinionesTuristicas-Api.git](https://github.com/UzielLujan/OpinionesTuristicas-Api.git)
    cd OpinionesTuristicas-Api
    ```

2.  **Crear el archivo de entorno:**
    Copia el archivo de ejemplo `.env.example` para crear tu propio archivo de configuración.
    ```bash
    cp .env.example .env
    ```

3.  **Configurar la base de datos:**
    Abre el archivo `.env` y añade tu cadena de conexión de MongoDB Atlas en la variable `DB_URI`.
    ```
    DB_CONNECTION=mongodb
    DB_URI="TU_CADENA_DE_CONEXION_A_MONGODB_ATLAS"
    ```

4.  **Construir y levantar los contenedores:**
    Este comando construirá las imágenes de Docker y pondrá en marcha la aplicación en segundo plano.
    ```bash
    docker compose up -d --build
    ```

5.  **Generar la clave de la aplicación:**
    ```bash
    docker compose exec app php artisan key:generate
    ```

6.  **Ejecutar las migraciones:**
    Esto creará la colección `opinions` en tu base de datos.
    ```bash
    docker compose exec app php artisan migrate
    ```

¡Y listo! La aplicación estará disponible en `http://localhost:8080`.

---

## Endpoints de la API

La URL base de la API es `http://localhost:8080/api`.

| Método | Ruta                     | Descripción                               |
| :----- | :----------------------- | :---------------------------------------- |
| `GET`  | `/opinions`              | Obtiene una lista de todas las opiniones. |
| `POST` | `/opinions`              | Crea una nueva opinión.                   |
| `GET`  | `/opinions/{id}`         | Muestra una opinión específica.           |
| `PUT`  | `/opinions/{id}`         | Actualiza una opinión específica.         |
| `DELETE`| `/opinions/{id}`         | Elimina una opinión específica.           |
| `GET`  | `/opinions/export`       | Descarga todas las opiniones como un archivo CSV. |

Para facilitar las pruebas, puedes importar la colección de Postman (`Opiniones_Turisticas_API.postman_collection.json`) incluida en este repositorio.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
