# OhSanSi-Backend

Aplicación web de inscripción a las olimpiadas Oh! SanSi

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

═══════════════════════════════════════════════
🛠️ Guía para levantar el proyecto desde cero
═══════════════════════════════════════════════

📁 1. Clona el repositorio

git clone https://github.com/andyortz/OhSanSi-Backend.git
cd OhSanSi-Backend

🔄 Asegúrate de estar en la rama principal: develop

📦 2. Instala dependencias PHP con Composer

composer install

📝 3. Copia el archivo de entorno

cp .env.example .env

🔐 4. Genera la APP KEY de Laravel

php artisan key:generate

🛢️ 5. Configura la base de datos en el archivo .env

Previo a la configuración debes crear una nueva base de datos que este VACIA
y conectarla

DB_CONNECTION=pgsql  
 DB_HOST=127.0.0.1  
 DB_PORT=5432  
 DB_DATABASE=nombre_de_base  
 DB_USERNAME=usuario  
 DB_PASSWORD=contraseña

📂 6. Crea el enlace simbólico para archivos públicos

php artisan storage:link

📚 7. Ejecuta las migraciones

php artisan migrate

🌱 8. Ejecuta los seeders de datos

php artisan db:seed

🚀 9. Levanta el servidor local

php artisan serve

🌐 Luego abre en tu navegador: http://localhost:8000

🧪 10. Datos mínimos requeridos para pruebas con Postman

Para que las pruebas con Postman funcionen correctamente, asegúrate de tener al menos **un registro válido** en las siguientes tablas:

-   olimpiadas
-   olimpistas
-   tutores
-   areas_competencia
-   niveles_categoria
-   pagos
