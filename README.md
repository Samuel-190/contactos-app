# APLICACÍON DE GESTION DE CONTACTOS

Este proyecto consiste en una aplicacion web para adminitristar contactos, el usuario puede registrarse y iniciar sesíon para acceder a su panel de administracion, en el mismo podra gestionar sus contactos.

## 📥 Cómo instalar

1. Clona el repositorio
2. Abrelo localmente
3. Instala y ejecuta la base de datos `contactos.sql` que viene incluida en el repositorio.
4. Inicia un servidor local dentro del repositorio `contactos-app`, puedes hacerlo desde la terminal asi `php -S localhost:5000`.
5. Abre tu navegador y ingresa el puerto de tu servidor local, en la URL escribe algo asi `http://localhost:5000`.
6. ¡Y listo!, ya puedes empezar a usar la app.

## 🚀 Como usar

Al abrir tu servidor local en el navegador, veras el `index.php` ya en accion:

- Aqui el usuario podra ingresar sus credenciales y iniciar sesíon.

Pero...¿ Ya esta registrado en la base de datos ?, si no lo esta, no podra iniciar sesíon, por que no existe.

Para eso esta el `agregar.php`:

- Aqui el usuario podra registrarse y asi poder existir en la base de datos y por fin poder iniciar sesíon.

Podras acceder a el de 3 maneras, la primera es dar clic en el `Aquí` del inicio de sesíon, la segunda es navegando en la URL de esta manera: `localhost:5000/agregar.php`. Y la tercera deberas descubrirla por ti mismo.

- Para terminar, no olvides revisar las credenciales en el `database.php`, puede que tu `$usuario` y `$pass` sean diferentes, de lo contrario php podria tener problemas para conectarse con tu base de datos. 


Y ya esta, de lo demas la aplicacion se encargara, solo sigue y lee las intrucciones que aparezcan en pantalla, a medida que usas la app.

## 🔩​ Estructura del repositorio

/conactos-app
|---/includes
|   |--- database.php
|   |___ funciones.php
|
|---- index.php
|---- agregar.php
|---- contactos.sql
|____ README.md

## ​📄​ Requisitos 

- Interprete de php instalado, por lo menos `PHP 7.4` o superior.
- Motor de base de datos instalado, preferencialmente `MySQL 5.7` o superior.
- Motor para servidor local, puedes usar `Laragon, XAMPP o PHP`, escoge el de tu preferencia.

Y... ¡diviertete mucho!


Autor: Samuel lopez :)