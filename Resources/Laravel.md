# Laravel

## Project Structure

![image-20250712192128703](/Users/adrianruiz/Library/Application Support/typora-user-images/image-20250712192128703.png)

# Requerimientos

1. `php` version >= 8.2

   ```bash
   php --version
   ```

   

2. `composer`

   ```bash
   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
   php composer-setup.php
   php -r "unlink('composer-setup.php');"
   
   ```

   Global install.

   ```bash
   sudo mv composer.phar /usr/local/bin/composer
   ```

3. `laravel` 

   > Solo para versiones mas recientes: `Laravel 12.x`

   ```bash
   composer global require laravel/installer
   ```

   - Agregar `laravel` al PATH.
     ```bash
     sudo nano ~/.bashrc
     ```

   - Agregar la siguiente instrucción al final del archivo:
     ```bash
     export PATH="$PATH:$HOME/.composer/vendor/bin"
     ```

   - Actualizar el script de `bashrc`:
     ```bash
     source ~/.bashrc
     ```



## Creacion de proyectos

### Composer

````bash
 composer create-project laravel/laravel:^11.6.1 [nombre-del-proyecto]
````

### *Laravel*

> Para instalar la última versión de Laravel (actualmente 12.x)

```bash
laravel new [nombre-del-proyecto]
```

# Crear proyectos en versiones Anteriores

**Instalacion especifica en una version de Laravel**

Para instalar una versión específica de Laravel, puede usar los siguientes comandos:

- **Laravel 11.x** 

  > Latest version: `11.6.1`

  ```php
  composer create-project laravel/laravel:^11 [nombre-del-proyecto]
  ```

- **Laravel 10.x**

  ```php
  composer create-project laravel/laravel:^10 [nombre-del-proyecto]
  ```

- **Laravel 9.x**

  ```php
  composer create-project laravel/laravel:^9 [nombre-del-proyecto]
  ```

- **Laravel 8.x**

  ```php
  composer create-project laravel/laravel:^8 [nombre-del-proyecto]
  ```

- **Laravel 7.x**

  ```php
  composer create-project --prefer-dist laravel/laravel:^7.0 [nombre-del-proyecto]
  ```

- To install **Laravel 6.x** and below, use:

  ```php
  composer create-project --prefer-dist laravel/laravel [nombre-del-proyecto] "6.*"
  ```



## Verificación de la instalación

Navegue hasta el directorio de su proyecto:

```bash
cd [nombre-del-proyecto]
```

Inicie el servidor de desarrollo de Laravel:

```bash
php artisan serve --host=0.0.0.0 --port=8000 
```

Abra su navegador y visite http://localhost:8000. Si ve la página de bienvenida predeterminada de Laravel, la instalación se realizó correctamente. Se verá así.

![](https://cdn.hashnode.com/res/hashnode/image/upload/v1726071514110/b6f2642c-a0e9-4e39-9914-b02643c25252.png?auto=compress,format&format=webp)

# Estructura de directorios

Una vez creado el proyecto, Laravel proporciona una estructura limpia y organizada:

![](https://cdn.hashnode.com/res/hashnode/image/upload/v1726071712716/41ab3277-e947-421c-9247-f9ecf839c7e1.png?auto=compress,format&format=webp)

## Vamos a desglosar la estructura

La estructura de directorios de Laravel está diseñada para ser limpia y organizada, lo que facilita a los desarrolladores en el trabajo y en sus aplicaciones. 

> Aquí hay un desglose de los directorios clave y sus funciones

1. **App**

Este directorio contiene el núcleo de su aplicación. Alberga sus controladores, modelos y servicios. Dentro, encontrará subdirectorios para una mejor organización:

2. **bootsrap**

El directorio `bootstrap` incluye el archivo `app.php`, que inicia el framework. También tiene una carpeta de caché que almacena los archivos generados por el framework, como la caché de rutas y servicios, para ayudar a mejorar el rendimiento.

3. **config**

Como su nombre indica, el directorio de `config` contiene todos los archivos de configuración de su aplicación. Es una buena idea revisar estos archivos y familiarizarse con las diversas configuraciones y opciones disponibles para usted.

4. **database**

El directorio de bases de datos contiene sus migraciones de base de datos, fábricas de modelos y semillas. Si lo desea, también puede utilizar este directorio para almacenar una base de datos SQLite.

5. **public**

Dentro del directorio público es donde encontrará el archivo `index.php`, que sirve como punto de partida para todas las solicitudes entrantes a su aplicación y configura la carga automática. Esta carpeta también contiene sus recursos como imágenes, JavaScript y CSS.

6. **resources**

Las vistas y los activos no compilados como CSS o JavaScript se almacenan en el directorio de`resources`.

7. **routes**

El directorio donde se almacenan todas las definiciones de ruta para su aplicación se llama directorio de`routes`. Laravel incluye dos archivos de ruta por defecto: web.php y console.php.

8. **storage**

Los registros, las plantillas de Blade compiladas, las sesiones basadas en archivos, las cachés de archivos y otros archivos generados por el framework se almacenan en el directorio de almacenamiento. Este directorio se divide en directorios de app, framework y los logs. El directorio de la app está disponible para almacenar cualquier archivo creado por su aplicación. El directorio del framework se utiliza para almacenar archivos y cachés generados por el framework. Al final, el directorio de registros alberga los archivos de registro para logs.

9. **test/**

Las pruebas automatizadas se encuentran en el directorio de pruebas. Por ejemplo, `Pest` o `PHPUnit` viene con pruebas unitarias preinstaladas y pruebas de características. Cada categoría de prueba debe terminar con el término Prueba.

10. **vendor/**

El directorio del `vendor` contiene sus dependencias de Composer.

11. **artisan**

Este archivo es la interfaz de línea de comandos de Laravel. Lo usas para ejecutar comandos de Artisan como migraciones, generar modelos, ejecutar pruebas y más.

# Configuración del proyecto Laravel

## Configuración del archivo de entorno (.env)

Laravel utiliza un archivo `.env` para administrar configuraciones específicas del entorno, como credenciales de base de datos, configuraciones de correo y la URL de la aplicación. Copia `.env.example` a `.env`  ejecutando:

```bash
cp .env.example .env
```

> Edite `.env` para configurar los ajustes del entorno, como las conexiones de la base de datos y la URL de la aplicación.

## Configuración de conexiones a bases de **datos**

Abra `.env` y configure los detalles de conexión de su base de datos:

```bash
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
// In laravel 11 , la database por defecto es sqlite
```

## Configuración de la clave de la aplicación

Generar una nueva clave de aplicación:

```bash
php artisan key:generate
```

> Esta clave se utiliza para el cifrado y las sesiones seguras.

## Configuración de rutas web y API

Las rutas se definen `routes/web.php` para rutas web y `routes/api.php` para rutas **API**. 

> En Laravel 11, los archivos de `ruta api.php` y `channels.php` ya no se incluyen por defecto porque muchas aplicaciones no los necesitan. Si necesitamos el archivo `api.php` o `channels.php` en la carpeta de rutas, podemos crearlos usando un comando Artisan.
>
> Los eventos del lado del servidor al cliente se definen en `channels.php` , se usa para mostrar notificaciones en tiempo real, implementar un chat, mostrar datos en vivo y emitir eventos para otras apps o clientes usando WebSockets o servicios externos.
>
> ```php
> ...
>   
> BROADCAST_DRIVER=log
> //Para pruebas usa log, en producción usarías pusher, redis, etc.
> ...
> ```
>
> 

```bash
php artisan install:api
php artisan install:broadcasting
```

## Configuración de la URL de la aplicación

Actualice el valor de `APP_URL` en `.env`:

```bash
....
APP_URL=http://localhost
...
```

## Configuración de los ajustes de correo

Si planea usar el correo electrónico, configure los ajustes de correo en `.env`:

```php
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
//Reemplace los valores con los detalles de su servidor de correo.
```

## Database Setup

### **Creación de una nueva base de datos**

Cree una nueva base de datos en su servidor local (por ejemplo, a través de phpMyAdmin o MySQL o SQLite) y actualice el archivo `.env` con los detalles.

 **Configuración de los ajustes de Base de Datos**

Configure los ajustes de correo en `.env`:

```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crud
DB_USERNAME=root
DB_PASSWORD=admin
```

**Migraciones en ejecución**

Las migraciones son la forma de Laravel de gestionar el esquema de la base de datos. Para crear tablas, ejecute:

```bash
php artisan migrate
```

# Ejecutando el servidor de desarrollo de Laravel

**Iniciando el servidor integrado**

Para iniciar el servidor integrado, ejecute:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Acceder a la aplicación en un navegador web, después de ejecutar el comando, visite http://localhost:8000 para acceder a su aplicación o http://localhost:8000/api/.

---

# Controladores

```bash
php artisan make:controller [nombre-del-controlador]
```



## Creacion del controlador para Auth

En lugar de definir la lógica en las rutas, cree un AuthController para manejar los `endpoints` relacionados con la autenticación:

```bash
php artisan make:controller AuthController
```

Agregue un método de `login` que devuelve una respuesta JSON:

```php
public function login()
{
    return response()->json(['message' => 'Hello login'], 200);
}
```

Define un metodo `POST`   para iniciar sesion en el archivo `api.php`:

```php
Route::post('/login', [AuthController::class, 'login']);
```

Pruebe el endpoint `/api/login` para verificar la respuesta.

![image-20250713144312694](/Users/adrianruiz/Library/Application Support/typora-user-images/image-20250713144312694.png)

## Simplificando las respuestas JSON con un Trait (Rasgo)

Un **trait** es un conjunto de métodos que puedes incluir en una o más clases usando la palabra clave `use`. Sirve para **compartir funcionalidades comunes** sin duplicar código.

Para evitar que JSON repita el código de respuesta, cree un rasgo `ApiResponse` en `app/Traits/ApiResponses.php`.

```php
<?php
namespace App\Traits;

trait ApiResponses
{
    protected function success($message, $status = 200)
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
        ], $status);
    }

    protected function ok($message)
    {
        return $this->success($message, 200);
    }
}
```

Usa este `trait` en tu controlador:

```php
<?php
  
use App\Traits\ApiResponses;

class AuthController extends Controller
{
    use ApiResponses;

    public function login()
    {
        return $this->ok('Hello login');
    }
}
```

Esto hace que la respuesta JSON sea estandarizada donde es más fácil y limpia.

# ¿Qué es Artisan en Laravel 11?

**Artisan** es la **interfaz de línea de comandos (CLI)** de Laravel. Permite **automatizar tareas comunes** como la creación de controladores, modelos, migraciones, seeders, componentes Blade, y más.

Artisan está basado en **Symfony Console** y viene preinstalado con Laravel. Para usarlo, simplemente ejecuta:

```less
php artisan
```

Esto mostrará una lista de todos los **comandos disponibles**.

![](https://i.ibb.co/1BFHpcY/image.png)

------

## ¿Para qué sirve Artisan?

Artisan se usa para **ahorrar tiempo y evitar errores manuales** al ejecutar tareas repetitivas en Laravel, tales como:

- 🔄 **Generar código automáticamente** (controladores, modelos, migraciones, componentes Blade).
- 📂 **Administrar la base de datos** (migraciones, seeders, factories).
- 🛠 **Limpiar caché** (configuración, vistas, rutas).
- 🚀 **Optimizar el rendimiento** en producción.

### Comandos más usados de Artisan

**Información general**

```bash
php artisan list  # Ver todos los comandos disponibles
php artisan help migrate  # Obtener ayuda sobre un comando específico
```

------

**Generar archivos**

```bash
php artisan make:model Producto  # Crear un modelo
php artisan make:controller ProductoController  # Crear un controlador
php artisan make:migration create_productos_table  # Crear una migración
php artisan make:seeder ProductoSeeder  # Crear un seeder
php artisan make:component Alert  # Crear un componente Blade
```

------

**Migraciones y Base de Datos**

```bash
php artisan migrate  # Ejecutar todas las migraciones
php artisan migrate:rollback  # Deshacer la última migración
php artisan db:seed  # Ejecutar los seeders
php artisan tinker  # Iniciar una consola interactiva para probar código
```

------

**Caché y Configuración**

```bash
php artisan config:cache  # Cachear la configuración
php artisan cache:clear  # Limpiar la caché
php artisan route:cache  # Cachear las rutas
php artisan view:clear  # Limpiar la caché de vistas
```

------

**Servidor de Desarrollo**

```bash
php artisan serve  # Iniciar el servidor local (http://127.0.0.1:8000)
```

# Controladores en Laravel 11

**¿Cómo funcionan los Controladores en Laravel 11?**

1. **Reciben una solicitud HTTP** desde una ruta (`GET`, `POST`, `PUT`, `DELETE`).
2. **Procesan la lógica de negocio** (por ejemplo, consultar la base de datos).
3. **Retornan una respuesta** (puede ser una vista, JSON, o una redirección).

## Creación de controladores con `php artisan make:controller`

Puedes generar un controlador usando **Artisan**:

```less
php artisan make:controller PostController
```

Esto crea un archivo en:

```less
app/Http/Controllers/PostController.php
```

![](https://i.ibb.co/qYn6yd97/Captura-de-pantalla-2025-02-25-a-la-s-3-16-26-p-m.png)

![](https://i.ibb.co/jk7kvBt8/Captura-de-pantalla-2025-02-25-a-la-s-3-29-59-p-m.png)

**Nota:** No se genera un error cuando la ruta es inválida o no es invocable.

![](https://i.ibb.co/0jtg6MmV/image.png)

![](https://i.ibb.co/MkKmR47b/image.png)

### ¿Qué hace la función `__invoke()` en Laravel 11?

En **Laravel 11**, la función **`__invoke()`** permite que una clase actúe como un **controlador de una sola acción** (Single Action Controller). Esto significa que en lugar de definir varios métodos en un controlador, puedes usar esta función especial para manejar una única ruta.

------

**¿Cómo funciona `__invoke()`?**

En PHP, `__invoke()` es un **método mágico** que permite que una **clase se llame como una función**.

En Laravel, cuando usas un controlador con `__invoke()`, no necesitas definir un método con nombre (`index`, `store`, etc.), ya que toda la lógica se ejecuta dentro de `__invoke()`.

------

**Ejemplo de `__invoke()` en un controlador**

**Crear un controlador invocable con Artisan**

Ejecuta:

```less
php artisan make:controller PostController --invokable
```

Esto generará un controlador en:

```less
app/Http/Controllers/PostController.php
```

![](https://i.ibb.co/WpNxBVCC/Captura-de-pantalla-2025-02-25-a-la-s-3-53-10-p-m.png)

![](https://i.ibb.co/PvJ12sT3/image.png)

#### 11.3. Envío de datos desde el controlador hacia la vista.

![](https://i.ibb.co/fdXqZRv1/Captura-de-pantalla-2025-02-25-a-la-s-4-05-51-p-m.png)

**PostController.php**

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $datos = [
            [
                "parrafo" => "Campuslands forma a jóvenes con competencias únicas para su primer empleo de calidad en desarrollo de software.",
            ],
            [
                "parrafo" => "Conecta con el talento que tu empresa necesita. En Campuslands formamos desarrolladores preparados para los retos de la industria tech."
            ]
        ];
        return view('about', ["informacion" => $datos]);
    }
}

```

### Ejecución de un Método Específico en un Controlador

**api.php**

```php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get("/about", [ PostController::class, "showData" ] );
```

---

## Crea un controlador con los métodos API predefinidos.

```less
php artisan make:controller BlogController --api
```

📌 **Crea un controlador optimizado para APIs en `app/Http/Controllers/BlogController.php`**.

A diferencia de un controlador de recursos estándar (`-r` o `--resource`), **no incluye los métodos `create()` y `edit()`**, ya que estos métodos se usan solo en vistas web.

------

**Métodos generados en `BlogController.php`**

El controlador generado contendrá **5 métodos** para manejar operaciones CRUD en una API:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

```

📌 **Este controlador devuelve respuestas en formato JSON**, lo que es ideal para APIs REST.

------

**Definir las rutas en `routes/api.php`**

Para exponer este controlador como API, agrega la siguiente línea en `routes/api.php`:

```php
use App\Http\Controllers\BlogController;

Route::apiResource('blog', BlogController::class);
```

Esto generará automáticamente las siguientes rutas:

![](https://i.ibb.co/Y4yRpPmD/Captura-de-pantalla-2025-02-26-a-la-s-3-02-38-p-m.png)

**La diferencia con `Route::resource()` es que `apiResource()` excluye `create()` y `edit()`**, ya que en una API no se necesitan formularios HTML.

---

## Cómo conectar con una base de datos en Laravel 11

Al configurar Laravel con el comando `laravel new example-app`, se seleccionó MySQL como base de datos. Esta configuración se almacena en las variables de entorno dentro del archivo `.env`. Todas las configuraciones realizadas en la línea de comandos quedan registradas en dicho archivo. En este caso, los parámetros de la base de datos están en las variables de entorno que comienzan con `DB_`. Si alguna de estas variables no está definida en el `.env`, Laravel utilizará la configuración predeterminada establecida en el archivo `database.php`, donde se encuentran valores por defecto en caso de que no se proporcionen en el entorno.

### Configuración de conexión en `.env`

![](https://i.ibb.co/RGtGGhfD/Captura-de-pantalla-2025-02-26-a-la-s-3-18-30-p-m.png)

### Uso de MySQL, PostgreSQL y SQLite

![](https://i.ibb.co/dsjstKDM/Captura-de-pantalla-2025-02-26-a-la-s-3-23-23-p-m.png)

Todas las bases de datos sql que se pueden usar en laravel 11

![](https://i.ibb.co/B28PyJ4P/Captura-de-pantalla-2025-02-26-a-la-s-3-32-31-p-m.png)

Si deseas utilizar una base de datos NoSQL, como Redis, Laravel ya incluye soporte nativo. Solo necesitas configurar las variables de entorno adecuadas para su uso. Para más información sobre la configuración, consulta la documentación oficial: [Laravel Redis](https://laravel.com/docs/11.x/redis).

En el caso de MongoDB, Laravel no incluye la configuración por defecto en el archivo `database.php`, por lo que será necesario instalar el paquete correspondiente a través de Composer. Sigue las indicaciones de la documentación oficial: [Laravel MongoDB](https://laravel.com/docs/11.x/mongodb).

![](https://i.ibb.co/m5sp1TzD/Captura-de-pantalla-2025-02-26-a-la-s-3-35-47-p-m.png)

### Prueba de Conexión a la Base de Datos MySQL

Existen dos formas de ejecutar consultas en MySQL utilizando Eloquent: mediante SQL puro o empleando los métodos propios de Eloquent. En este ejemplo, si usamos una consulta en SQL puro, se devolverá un `array`, mientras que si utilizamos los métodos de Eloquent, el resultado será una instancia de `stdClass`

Array

```php
use Illuminate\Support\Facades\DB;
$datos = DB::select("SELECT * FROM sessions");
```

stdClass

```php
use Illuminate\Support\Facades\DB;
$datos = DB::table("sessions")->get();
```

![](https://i.ibb.co/Gr83rWt/Captura-de-pantalla-2025-02-26-a-la-s-4-20-41-p-m.png)

### Comprobar si Laravel reconoce la base de datos

```less
php artisan migrate:status
```

Si la conexión es correcta, verás una lista de migraciones.
Si hay un error, Laravel mostrará **"Base table or view not found"**, lo que indica que **no se ha conectado correctamente**.

![](https://i.ibb.co/2xcjLny/Captura-de-pantalla-2025-02-27-a-la-s-2-20-43-p-m.png)

#### Probar conexión con `php artisan tinker`

Laravel incluye **Tinker**, una consola interactiva para probar la conexión.

```less
php artisan tinker
```

Luego, dentro de Tinker, escribe:

```less
DB::connection()->getPdo();
```

**Si la conexión es exitosa**, verás algo como:

![](https://i.ibb.co/7ddJRtCm/Captura-de-pantalla-2025-02-27-a-la-s-2-15-14-p-m.png)

Aquí también se pueden ejecutar las consultas para verificar si están correctamente formuladas en Laravel 11.
![](https://i.ibb.co/FkWsQSnJ/Captura-de-pantalla-2025-02-27-a-la-s-2-16-52-p-m.png)

**Si hay un error, revisa el mensaje y corrige `.env`.**

Para salir de Tinker, escribe:

```php
exit
```

---

# Qué son y cómo funcionan las migraciones en Laravel 11

Las **migraciones en Laravel 11** son una forma de **gestionar la estructura de la base de datos** mediante código en PHP en lugar de modificarla manualmente con SQL.

**📌 Beneficios de las migraciones:** ✅ Versionado de la base de datos.
✅ Permite modificar la estructura sin perder datos.
✅ Se puede compartir con otros desarrolladores en el equipo.
✅ Funciona con múltiples bases de datos (MySQL, PostgreSQL, SQLite, etc.).

## Creación y ejecución de migraciones

```less
php artisan make:migration create_posts_table
```

📌 Esto generará un archivo en:

```less
database/migrations/2024_02_25_123456_create_posts_table.php
```

![](https://i.ibb.co/Q336hx38/Captura-de-pantalla-2025-02-27-a-la-s-2-27-45-p-m.png)

### Definir esquemas con `Schema::create()`

#### `up()`: Aplica cambios a la base de datos



```less
php artisan migrate
```

**Se usa para crear, modificar o agregar elementos a la base de datos.**

**Ejemplo de `up()`: Crear una tabla**

```php
public function up(): void
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->text('contenido');
        $table->timestamps();
    });
}
```

**Este código creará una tabla `posts` con las columnas `id`, `titulo`, `contenido` y marcas de tiempo (`created_at` y `updated_at`).**

![](https://i.ibb.co/21qF3NMV/Captura-de-pantalla-2025-02-27-a-la-s-2-40-17-p-m.png)

#### Tipos de Columna Disponibles

El esquema de la construcción de planos ofrece una variedad de métodos que corresponden a los diferentes tipos de columnas que puedes añadir a tus tablas de base de datos. Cada uno de los métodos disponibles se enumera en la tabla a continuación:

Aquí vemos las migraciones que ya se ejecutaron y tenemos la tabla posts que se creó automáticamente.

![](https://i.ibb.co/JR8vYPHG/Captura-de-pantalla-2025-02-27-a-la-s-2-43-35-p-m.png)

##### ¿Por qué Laravel crea la tabla `migrations` en la base de datos?**

Cuando ejecutas `php artisan migrate` por primera vez en **Laravel 11**, se crea automáticamente una **tabla llamada `migrations`** en la base de datos.

**La función principal de esta tabla es registrar qué migraciones han sido ejecutadas.**

---

**¿Qué contiene la tabla `migrations`?**

Esta tabla almacena el historial de las migraciones aplicadas. Su estructura suele ser:

![](https://i.ibb.co/RkvvfyTY/image.png)

**Explicación de cada columna:**

- **`id`** → Identificador único de la migración.
- **`migration`** → Nombre del archivo de la migración aplicada.
- **`batch`** → Número de lote en el que se ejecutó la migración.

------

**¿Para qué sirve la tabla `migrations`?**

1. **Evita ejecutar la misma migración más de una vez.**
2. **Permite revertir cambios con `migrate:rollback`.**
3. **Ayuda a Laravel a rastrear qué migraciones se han ejecutado.**

Cuando ejecutas `php artisan migrate`, Laravel **solo ejecuta las migraciones que NO están en la tabla `migrations`**.

------

**Cómo ver las migraciones registradas**

```less
php artisan migrate:status
```

![](https://i.ibb.co/Q306zZWX/image.png)

**Si una migración no tiene batch, significa que aún no ha sido ejecutada.**

---

#### `down()`: Revierte los cambios en la base de datos

El método **`down()`** se ejecuta cuando se usa el comando:

```less
php artisan migrate:rollback
```

**Se usa para eliminar o revertir los cambios hechos en `up()`. de todas las migraciónes**

**Ejemplo de `down()`: Eliminar una tabla**

```php
public function down(): void
{
    Schema::dropIfExists('posts');
}
```

**Este código eliminará la tabla `posts` si existe.**

##### Modificación de tablas y rollback

Eliminamos todas las tablas de la base de datos `example_app`, o utilizamos el comando `php artisan migrate:rollback` de forma repetida hasta que se eliminen por completo todas las tablas en MySQL, para poder continuar.

```less
php artisan migrate:rollback
```

![](https://i.ibb.co/QFKSB8kf/Captura-de-pantalla-2025-02-27-a-la-s-3-18-38-p-m.png)

![](https://i.ibb.co/rGqwnFdL/Captura-de-pantalla-2025-02-27-a-la-s-3-21-40-p-m.png)

![](https://i.ibb.co/dsPQbgSY/Captura-de-pantalla-2025-02-27-a-la-s-3-25-05-p-m.png)

De esta manera, se mantiene un control sobre las migraciones ejecutadas. Si ejecutamos el comando `php artisan migrate:rollback`, se ejecutará únicamente el método `down()` de las migraciones pertenecientes al último lote, que en este caso es el número 2.

![](https://i.ibb.co/qMdYwTy1/Captura-de-pantalla-2025-02-27-a-la-s-3-37-29-p-m.png)

Ahora, ¿qué sucede si necesitamos modificar la estructura de una tabla? Supongamos que en la tabla `Users` queremos agregar un campo de texto para almacenar la URL de una imagen de perfil llamada `avatar`.

![](https://i.ibb.co/LXd5Kq7K/Captura-de-pantalla-2025-02-27-a-la-s-3-55-33-p-m.png)

No habrá ningún cambio en la estructura de la tabla `users`, ya que esta migración se ejecutó en el primer lote y no se volverá a ejecutar, a menos que realicemos un rollback. Sin embargo, si estamos en un entorno de desarrollo y los datos de la tabla no son relevantes, podemos utilizar el comando `php artisan migrate:fresh`, que eliminará todas las tablas y volverá a ejecutar las migraciones desde cero.

```less
php artisan migrate:fresh
```

![](https://i.ibb.co/7JZWt9VN/Captura-de-pantalla-2025-02-27-a-la-s-4-05-48-p-m.png)

**Nota:** ¿Qué sucede si ya tenemos datos y estamos en producción? En ese caso, se eliminara todos los datos que ya están registrados en todas las tablas

##### Modificar la tabla son afectar sus datos en la migración

**Datos de la tabla posts**

```sql
INSERT INTO posts (titulo, created_at, updated_at) VALUES 
('Introducción a MySQL', NOW(), NOW()),
('Guía completa de Laravel', NOW(), NOW()),
('Eloquent ORM en profundidad', NOW(), NOW()),
('Buenas prácticas en bases de datos', NOW(), NOW()),
('Cómo optimizar consultas SQL', NOW(), NOW()),
('Relaciones en Eloquent', NOW(), NOW()),
('Uso de migraciones en Laravel', NOW(), NOW()),
('Manejo de seeds en Laravel', NOW(), NOW()),
('Consultas avanzadas en MySQL', NOW(), NOW()),
('Cómo implementar Soft Deletes en Laravel', NOW(), NOW());

```

## **Factories**

En Laravel, los **Factories (Fábricas de modelos)** son una herramienta que permite **generar datos falsos o de prueba** para tus modelos de Eloquent.

En lugar de tener que escribir manualmente cada valor de las columnas cuando quieras llenar tu base de datos, los *Factories* permiten definir **atributos por defecto** que se asignarán automáticamente al crear instancias de un modelo.

Se utilizan principalmente en dos escenarios:

1. **Testing (Pruebas):**
   Al probar tu aplicación, puedes necesitar crear registros falsos rápidamente para simular usuarios, publicaciones, productos, etc. Los *Factories* generan esos datos de forma automática y consistente.
2. **Seeding (Poblado de la base de datos):**
   Cuando quieres inicializar tu base de datos con información de prueba o datos iniciales, puedes usar los *Factories*junto con los *Seeders* para insertar múltiples registros de forma sencilla.

```bash
php artisan make:factory PostFactory
```

**`PostFactory.php`**

```php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(10),
            'content' => fake()->paragraph(),
            'status' => fake()->boolean(),
        ];
    }
}
```



## **Seeders**

Un **Seeder** en Laravel es una **clase especial que permite poblar la base de datos con datos iniciales o de prueba**.

Su propósito es que no tengas que insertar manualmente los registros uno por uno, sino que puedas automatizar este proceso.

```bash
php artisan make:seeder PostSeeder
```

### Usos principales:

1. **Datos iniciales (Bootstrapping):**
   Cuando una aplicación necesita tener información mínima desde el inicio (por ejemplo, roles de usuario, categorías, configuraciones iniciales).
2. **Datos de prueba (Testing / Demo):**
   Puedes poblar tu base de datos con registros falsos utilizando *Factories* o datos definidos manualmente para simular escenarios reales.
3. **Control del orden de inserción:**
   Desde la clase `DatabaseSeeder`, puedes llamar a varios *seeders* y definir en qué orden se ejecutan. Esto es muy útil si una tabla depende de otra.

```bash
php artisan db:seed --class=PostSeeder
```

**`PostSeeder.php`**

```php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Crear inserts o datos Mocks con Factory
        Post::factory()->count(50)->create();
        //Crear un solo datos con random haciendo uso de Insert
        DB::table('posts')->insert([
            'title' => Str::random(10),
            'content' => Str::random(10).'@example.com',
            'status' => true,
        ]);
    }
}
```

En el anterior  ejemplo se muestra dos formas de poblar la tabla `posts`:

1. **Usando Factories:**

   ```php
   Post::factory()->count(50)->create();
   ```

   > Esto crea **50 posts falsos** generados automáticamente a partir de `PostFactory`.

2. **Insertando manualmente con DB Facade:**

   ```php
   DB::table('posts')->insert([
       'title' => Str::random(10),
       'content' => Str::random(10).'@example.com',
       'status' => true,
   ]);
   ```

   > Esto inserta un único registro en la tabla `posts`, con valores generados aleatoriamente mediante `Str::random()`.

------

Archivo posts de la migration

```less
php artisan make:migration create_posts_table
```

**2025_02_27_192713_create_posts_table.php**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

```

![](https://i.ibb.co/nMFBxXc4/image.png)

Si necesitas modificar la estructura de una tabla cuya migración ya fue ejecutada y no quieres perder datos, debes crear una nueva migración. Para ello, ejecuta el comando `php artisan make:migration`. En este caso, hay que ser específico y nombrarla `add_body_to_posts_table`, pero puedes elegir cualquier nombre. Laravel 11 detectará automáticamente las palabras `_to_` o `_from_` en el nombre para generar la estructura adecuada que modifique la tabla correspondiente.

```less
php artisan make:migration add_body_to_posts_table
```

![](https://i.ibb.co/FPzmkL3/Captura-de-pantalla-2025-02-27-a-la-s-4-34-21-p-m.png)

![](https://i.ibb.co/fGp4Zfh1/Captura-de-pantalla-2025-02-27-a-la-s-4-37-33-p-m.png)

**add_body_to_posts_table.php**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->longText('body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('body');
        });
    }
};

```

![](https://i.ibb.co/20085Lhj/Captura-de-pantalla-2025-02-28-a-la-s-2-26-03-p-m.png)

Ahora, en la tabla de `posts`, hemos logrado conservar la columna `body` sin perder información. Sin embargo, en lugar de que aparezca al final, queremos posicionarla justo después del título.

![](https://i.ibb.co/ZpjK4ych/Captura-de-pantalla-2025-02-28-a-la-s-2-31-42-p-m.png)

![](https://i.ibb.co/tTQK5WHC/Captura-de-pantalla-2025-02-28-a-la-s-2-33-39-p-m.png)

---

# Introducción a Eloquent, el ORM de Laravel 11

## ¿Qué es Eloquent y cómo funciona?

**Eloquent** es el **ORM (Object-Relational Mapping)** de Laravel que permite interactuar con bases de datos de forma sencilla y elegante mediante modelos en PHP, en lugar de escribir consultas SQL manualmente.

**Características principales de Eloquent:**

- Usa **modelos** para interactuar con tablas de la base de datos.
- Soporta relaciones como **uno a uno, uno a muchos, muchos a muchos** y relaciones polimórficas.
- Permite utilizar consultas con **fluidez (query builder)** y **métodos directos en modelos**.
- Genera **consultas SQL automáticamente**, facilitando el acceso y manipulación de datos.

###  Modelos y conexión con la base de datos

```less
php artisan make:model Post
```

**Crea un modelo llamado `Post` dentro de `app/Models/`** en Laravel 11. Este modelo representa la tabla `posts` en la base de datos y se usa para interactuar con los registros de la tabla sin escribir consultas SQL manualmente.

![](https://i.ibb.co/zVC9NT68/image.png)

**Nota:** Eloquent asume que el nombre de la tabla es `posts` en plural, por lo que el modelo debe nombrarse en singular y seguir la convención `PascalCase`. Esto suele funcionar correctamente en la mayoría de los casos. Sin embargo, si necesitamos asignar un nombre de tabla diferente, podemos sobrescribir la propiedad `table`.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = "posts"; // Este es el nombre de la tabla a la que intentará conectarse.
}
```

---

Laravel genera el archivo:

```less
app/Models/Post.php
```

![](https://i.ibb.co/9L4Lhvh/Captura-de-pantalla-2025-02-28-a-la-s-3-01-31-p-m.png)

**Código base de `app/Models/Post.php`:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
}

```

**Este modelo ahora puede interactuar con la tabla `posts` en la base de datos.**

Agregamos las siguientes lineas de código:
![](https://i.ibb.co/DPtkRv75/Captura-de-pantalla-2025-02-28-a-la-s-3-04-04-p-m.png)

![](https://i.ibb.co/RkX3zTkd/Captura-de-pantalla-2025-02-28-a-la-s-3-13-07-p-m.png)

### **Crear modelo con migración (`-m` o `--migration`)**

Genera el modelo **y su migración** para crear la tabla en la BD:

```less
php artisan make:model Post -m
```

```less
app/Models/Post.php
database/migrations/xxxx_xx_xx_xxxxxx_create_posts_table.php
```

![](https://i.ibb.co/WWS28s4J/Captura-de-pantalla-2025-02-28-a-la-s-3-27-28-p-m.png)

---

## **¿Qué es `Tinker` en Laravel 11?**

**`Tinker`** es una consola interactiva que permite ejecutar **código PHP y consultas Eloquent en tiempo real** dentro de una aplicación Laravel. Se basa en **PsySH**, un shell de PHP interactivo que facilita la depuración y prueba de código sin necesidad de usar archivos o controladores.

------

**¿Para qué se usa `Tinker`?**

- **Probar consultas en Eloquent**.
- **Crear, actualizar y eliminar registros en la base de datos**.
- **Ejecutar funciones y métodos de Laravel en tiempo real**.
- **Depurar código sin necesidad de usar controladores o vistas**.

- 

![](https://i.ibb.co/jPrwv0jY/Captura-de-pantalla-2025-02-28-a-la-s-4-00-53-p-m.png)

Cuando entras a **Tinker**, puedes importar el modelo usando `use`:

```less
use App\Models\Post;
```

![](https://i.ibb.co/v61rQxS9/Captura-de-pantalla-2025-02-28-a-la-s-4-03-09-p-m.png)

Todos los métodos de Eloquent que retornan múltiples resultados de modelos devuelven instancias de la clase `Illuminate\Database\Eloquent\Collection`. Esto incluye tanto los resultados obtenidos mediante consultas como aquellos accedidos a través de relaciones. Para conocer más sobre los métodos disponibles en Eloquent, puedes consultar la documentación oficial de Laravel en el siguiente enlace: [Métodos disponibles en Eloquent](https://laravel.com/docs/11.x/eloquent-collections#available-methods).

---

### Uso de `find()` y `findOrFail()

En **Laravel 11**, `find()` y `findOrFail()` son métodos de **Eloquent ORM** usados para buscar registros en la base de datos. Ambos permiten recuperar un modelo por su **ID**, pero tienen comportamientos diferentes cuando el registro no existe.

Ejemplo de `find()`

```php
use App\Models\Post; 

/**
 * Display the specified resource.
*/
public function show(string $id)
{
    return Post::find($id);
}
```

**Salida si el ID `1` existe:**

![](https://i.ibb.co/JW9tSkzw/image.png)

📌 **Salida si el ID `1` no existe:**

![](https://i.ibb.co/YTYyr23k/image.png)

✅ **No lanza una excepción, solo devuelve `null`**.

------

**Uso de `findOrFail()`**

**Tomado de [Adaptado](https://laravel.com/docs/11.x/eloquent-collections#method-find-or-fail)**

El método **`findOrFail($id)`** hace lo mismo que `find()`, pero **si no encuentra el registro, lanza una excepción `ModelNotFoundException`**.

📌 **Ejemplo: Buscar un `Post` con ID = 1**

```php
use App\Models\Post; 

/**
 * Display the specified resource.
*/
public function show(string $id)
{
    return Post::findOrFail($id);
}
```

✅ **Si el post con `id = 1` existe**, muestra su título.
❌ **Si no existe, Laravel lanza un error 404 automáticamente**:

![](https://i.ibb.co/5gy2ddpp/image.png)

![](https://i.ibb.co/qLWSFmW4/image.png)

📌 **Esto es útil para evitar errores silenciosos y manejar mejor los registros inexistentes.**

**Diferencias clave entre `find()` y `findOrFail()`**

| **Método**        | **Retorno si el registro existe** | **Retorno si el registro NO existe**         | **Uso recomendado**                   |
| ----------------- | --------------------------------- | -------------------------------------------- | ------------------------------------- |
| `find($id)`       | ✅ Devuelve el modelo encontrado   | ⚠️ Devuelve `null` (sin error)                | Cuando el registro puede ser opcional |
| `findOrFail($id)` | ✅ Devuelve el modelo encontrado   | ❌ Lanza `ModelNotFoundException` (Error 404) | Cuando el registro es obligatorio     |

#### Organizar las vistas

**web.php**

```php
use App\Http\Controllers\PostController;

Route::resource('blog', PostController::class)->names([
    'index' => 'BLOG',
]);

```

**PostController.php**

```php
/**
 * Display a listing of the resource.
 */
public function index()
{
    return response()->json(["informacion" => Post::get()]);
}
/**
 * Display the specified resource.
 */
public function show(string $id)
{

    return response()->json(["post" => Post::findOrFail($id)]);
}
```

### Insertar registros en la base de datos con Eloquent

#### Uso de `create()` y `save()

Tanto **`create()` como `save()`** se utilizan para **guardar datos en la base de datos** con **Eloquent ORM**, pero cada uno tiene un uso específico.

Si quieres seguir usando `save()`, pero de una forma más **elegante y concisa**, puedes utilizar `fill()` o `forceFill()` para asignar los valores de una sola vez antes de guardar.

#### Usar `fill()` + `save()`

```php
/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    $post = new Post();
    $post->fill($request->only(['titulo', 'body']))->save();
    return "post insertado";
}
```

- **Código más corto y limpio.**
- **Evita escribir múltiples asignaciones manuales.**
- **Requiere `$fillable` en el modelo.**

**Asegúrate de definir `$fillable` en `app/Models/Post.php`:**

```php
class Post extends Model
{
  ...
    protected $fillable = ['titulo', 'body'];
}
```

#### Usar `forceFill()` + `save()` (Si no quieres `$fillable`)

Si por alguna razón **no puedes o no quieres definir `$fillable`**, usa `forceFill()`.

```php
$post = new Post();
$post->forceFill([
    'titulo' => $request->titulo,
    'body' => $request->body
])->save();
return "post insertado";
```

- **No requiere `$fillable` en el modelo.**
- **Permite asignar valores masivamente sin restricciones.**
- **Más seguro que `Post::create()` cuando trabajas con datos sensibles.**

![](https://i.ibb.co/PGK1g0kq/Captura-de-pantalla-2025-03-04-a-la-s-4-32-43-p-m.png)

#### Usar `create()` (Requiere `$fillable`)

```php
$post = Post::create($request->only(['titulo', 'body']));
```

- **Código más limpio y corto.**
- **Laravel maneja automáticamente la asignación de valores.**
- **Requiere definir `$fillable` en el modelo.**

```php
protected $fillable = ['titulo', 'body'];
```

#### Usar `fill()` + `save()`

```php
$post = new Post();
$post->fill($request->only(['titulo', 'body']))->save();
```

- **Mantiene el uso de `save()`.**
- **Más corto y limpio que la asignación manual.**
- **Requiere `$fillable` en el modelo.**

![](https://i.ibb.co/nMtDGwkr/Captura-de-pantalla-2025-03-04-a-la-s-4-37-28-p-m.png)

#### Usar `updateOrCreate()` (Si necesitas crear o actualizar)

Si quieres **crear un post si no existe o actualizarlo si ya existe**, usa `updateOrCreate()`.

```php
$post = Post::updateOrCreate(
    ['titulo' => $request->titulo], // Condición de búsqueda
    ['body' => $request->body] // Datos a actualizar o crear
);
```

- **Si el `titulo` ya existe, solo actualiza `body`.**
- **Si no existe, lo crea automáticamente.**
- **Reduce la necesidad de hacer verificaciones manuales.**

------

**Comparación de Métodos**

| **Método**             | **Código**                                                   | **Ventajas**                               |
| ---------------------- | ------------------------------------------------------------ | ------------------------------------------ |
| **`create()`**         | `Post::create($request->only(['titulo', 'body']));`          | Más corto y directo. Requiere `$fillable`. |
| **`fill() + save()`**  | `$post = new Post(); $post->fill($request->only(['titulo', 'body']))->save();` | Usa `save()` pero más limpio.              |
| **`updateOrCreate()`** | `Post::updateOrCreate(['titulo' => $request->titulo], ['body' => $request->body]);` | Si existe, actualiza; si no, crea.         |

### Validación de datos antes de insertar

Si quieres devolver una respuesta JSON con un código HTTP, usa `response()->json()` pasando el código como segundo parámetro.

**Ejemplo: Responder con `201 Created` si se guarda correctamente**

```php
/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    $post = new Post();
    $post->forceFill([
        'titulo' => $request->titulo,
        'body' => $request->body
    ]);
    // Intentar guardar el post y verificar si se guardó correctamente
    if ($post->save()) {  // Retorna un true si se realiza la insercion
        return response()->json([
            'mensaje' => 'Post guardado con éxito',
            'id' => $post->id,   // Retorna el ID del post creado
            'created' => $post->created_at,
        ], 201);  // Código 201 (Created)
    } else {
        return response()->json([
            'error' => 'Error al guardar el post'
        ], 500);  // Código 500 (Internal Server Error)
    }
}
```

**Ejemplo de respuestas esperadas en JSON:**

- **Si se guarda correctamente:**

  ```json
  {
    "mensaje": "Post guardado con éxito",
    "id": 23,
    "created": "2025-03-05T19:16:08.000000Z"
  }
  ```

  **Código HTTP:** `201 Created`

  ![](https://i.ibb.co/rGsgcyMx/image.png)

- **Si falla al guardar:**

  ```php
  {
      "error": "Error al guardar el post"
  }
  ```

  **Código HTTP:** `500 Internal Server Error`

---

### Validación Personalizada

Para crear una validación personalizada, puedes utilizar una fachada `Validator` en lugar de `validate`. La instancia del validador contiene dos argumentos: los datos que se van a validar y una matriz de reglas de validación. Estos dos argumentos se pasan al método `::make` de la fachada del validador, generando una nueva instancia del validador.

**Post.php** mejorado:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Soft delete
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'content', 'status',
        'published_at', 'cover_image', 'tags', 'meta'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags'         => 'array',
        'meta'         => 'array',
    ];

    public function categories()
    {
        // Tabla pivote post_category
        return $this->belongsToMany(Category::class)->using(CategoryPost::class)->withTimestamps();
    }
}
```

#### ¿Qué es **Soft Delete**?

- En lugar de `DELETE`, Laravel coloca fecha en `deleted_at`.
- El modelo **oculta** por defecto registros eliminados lógicamente.
- Puedes **restaurarlos** (`$post->restore()`) o **forzar** borrado (`forceDelete()`).
- Consultas que incluyan eliminados: `withTrashed()` / `onlyTrashed()`.

**`Category.php`**

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    // Relaciones
    public function posts()
    {
        // Tabla pivote category_post
        return $this->belongsToMany(Post::class)->using(CategoryPost::class)->withTimestamps();
    }
}

```

**`CategoryPost.php`**

```php
    php artisan make:model CategoryPost.php --pivot
```

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryPost extends Pivot
{
    protected $table = 'category_post';

    protected $fillable = [
        'post_id',
        'category_id',
    ];
}

```



**Crea una nueva migración **

```bash
php artisan make:migration add_fields_to_posts_table --table=posts
```

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'status'
            ]);

            $table->string('slug', 220)->unique();

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            $table->timestamp('published_at')->nullable();

            $table->string('cover_image')->nullable();

            $table->json('tags')->nullable(); // ["laravel","php"]
            $table->json('meta')->nullable(); // {"seo_title":"...","seo_desc":"..."}

            $table->softDeletes();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
  			Schema::table('posts', function (Blueprint $table) {
              // Eliminar columnas nuevas
              $table->dropColumn([
                  'slug',
                  'published_at',
                  'cover_image',
                  'tags',
                  'meta',
                  'deleted_at'
              ]);

              // Quitar el índice compuesto
              $table->dropIndex(['status', 'published_at']);
          		// NOTA:
							//dropIndex: para eliminar el índice compuesto (status, published_at). Aquí debes usar el nombre correcto del índice, que Laravel genera como:
							//$table->dropIndex('posts_status_published_at_index');
          
              // Revertir cambios de columnas existentes
              $table->boolean('status')->change();
              $table->text('content')->change();
          });
    }
};
```

**Nueva tabla `categories`**

```php
return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->string('slug', 140)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

**Tabla pivote `category_post` (N:M)**

```php
return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['post_id', 'category_id']); // evita duplicados
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_post');
    }
};

```

### ¿Qué son las **relaciones**?

- **belongsTo**: clave foránea en esta tabla hacia otra (aquí, `posts.user_id → users.id`).
- **belongsToMany**: relación de muchos a muchos vía tabla pivote (`category_post`).

### Validación con **Form Requests**

**Crear las clases**

```bash
php artisan make:request StorePostRequest
php artisan make:request UpdatePostRequest
php artisan make:request StoreCategoryRequest
```

**Reglas de `StorePostRequest`**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ejemplo: permitir a usuarios autenticados
        return auth()->check();
        // O usar políticas: return $this->user()->can('create', Post::class);
    }

    // Saneamos antes de validar (generar slug si no viene)
    protected function prepareForValidation(): void
    {
        $title = $this->input('title');
        if ($title && !$this->filled('slug')) {
            $this->merge(['slug' => Str::slug($title)]);
        }
    }

    public function rules(): array
    {
        return [
            'title'   => ['required', 'string', 'min:4', 'max:200'],
            'slug'    => ['required', 'string', 'max:220', 'unique:posts,slug'],
            'content' => ['required', 'string', 'min:20'],

            // status restringido
            'status'       => ['required', Rule::in(['draft','published','archived'])],

            // published_at requerido solo si status=published y debe ser fecha pasada o presente
            'published_at' => ['nullable','date','required_if:status,published','before_or_equal:now'],

            // Cover image (archivo subido)
            'cover_image'  => ['nullable','file','mimetypes:image/jpeg,image/png,image/webp','max:2048'],

            // tags: arreglo de strings únicos
            'tags'         => ['nullable','array','max:20'],
            'tags.*'       => ['string','min:2','max:30','distinct'],

            // meta: objeto JSON (array asociativo)
            'meta'              => ['nullable','array'],
            'meta.seo_title'    => ['nullable','string','max:60'],
            'meta.seo_desc'     => ['nullable','string','max:160'],

            // relación con usuario autenticado o id explícito
            'user_id'      => ['nullable','exists:users,id'],

            // categorías por id
            'category_ids' => ['nullable','array','max:10'],
            'category_ids.*' => ['integer','exists:categories,id'],
        ];
    }

    // Validación condicional extra o reglas post-make
    public function withValidator($validator)
    {
        $validator->sometimes('published_at', 'after_or_equal:today', function () {
            // ejemplo alternativo de lógica condicional
            return $this->status === 'published' && now()->isWeekend();
        });

        // Añade errores custom tras reglas
        $validator->after(function ($v) {
            if ($this->status === 'archived' && !$this->filled('meta.seo_desc')) {
                $v->errors()->add('meta.seo_desc', 'Los posts archivados requieren una descripción SEO.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'El título es obligatorio.',
            'slug.unique'      => 'Ya existe un post con ese slug.',
            'content.min'      => 'El contenido debe tener al menos :min caracteres.',
            'status.in'        => 'Estado inválido. Usa draft, published o archived.',
            'published_at.required_if' => 'Debes indicar fecha de publicación cuando el estado es published.',
            'cover_image.mimetypes' => 'La imagen debe ser JPG, PNG o WEBP.',
            'tags.*.distinct'  => 'Los tags no deben repetirse.',
            'category_ids.*.exists' => 'Alguna categoría no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'content' => 'contenido',
            'published_at' => 'fecha de publicación',
            'cover_image' => 'imagen de portada',
            'category_ids' => 'categorías',
        ];
    }
}

```

#### Puntos claves

- `Rule::in` para enums string.
- `required_if` y reglas **condicionales** con `withValidator()->sometimes()`.
- Arrays y `tags.*`, `category_ids.*`.
- Archivos con `file`, `mimetypes`, `max` en KB.
- `prepareForValidation()` para **normalizar** datos (slug).
- `messages()` y `attributes()` para UX.

**`UpdatePostRequest` (ignorar unique del propio recurso)**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('title') && !$this->filled('slug')) {
            $this->merge(['slug' => Str::slug($this->input('title'))]);
        }
    }

    public function rules(): array
    {
        $postId = $this->route('post'); // Si usas Route Model Binding: $this->route('post')->id

        return [
            'title'   => ['sometimes','string','min:4','max:200'],
            'slug'    => [
                'sometimes','string','max:220',
                Rule::unique('posts','slug')
                    ->ignore($postId)                 // ignora este post
                    ->whereNull('deleted_at')         // respeta soft delete
            ],
            'content' => ['sometimes','string','min:20'],
            'status'  => ['sometimes', Rule::in(['draft','published','archived'])],
            'published_at' => ['nullable','date','required_if:status,published','before_or_equal:now'],
            'cover_image'  => ['nullable','file','mimetypes:image/jpeg,image/png,image/webp','max:2048'],
            'tags'         => ['nullable','array','max:20'],
            'tags.*'       => ['string','min:2','max:30','distinct'],
            'meta'         => ['nullable','array'],
            'meta.seo_title' => ['nullable','string','max:60'],
            'meta.seo_desc'  => ['nullable','string','max:160'],
            'category_ids' => ['nullable','array','max:10'],
            'category_ids.*' => ['integer','exists:categories,id'],
        ];
    }
}

```

**StoreCategoryRequest**

```php
class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    protected function prepareForValidation(): void
    {
        if ($this->filled('name') && !$this->filled('slug')) {
            $this->merge(['slug' => str()->slug($this->name)]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','min:2','max:120','unique:categories,name'],
            'slug' => ['required','string','max:140','unique:categories,slug'],
        ];
    }
}
```

**`PostController.php`**

```php
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController
{
    public function index(): JsonResponse
    {
        $posts = Post::with('categories')->get();

        $data = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'status' => $post->status,
                'published_at' => $post->published_at,
                'cover_image' => $post->cover_image,
                'tags' => $post->tags,
                'meta' => $post->meta,
                'categories' => $post->categories->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                    ];
                }),
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ];
    });
      
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        // Manejo de archivo
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        $post = Post::create($data);

        if (!empty($data['category_ids'])) {
            $post->categories()->sync($data['category_ids']);
        }

        return response()->json($post, 201);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            // opcional: borrar anterior
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        $post->update($data);

        if (array_key_exists('category_ids', $data)) {
            $post->categories()->sync($data['category_ids'] ?? []);
        }

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $post->delete(); // Soft delete
        return response()->noContent();
    }

    public function restore(int $id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        return response()->json($post);
    }
}

```

**Rutas `api.php`**

```php
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class);
// Restaurar soft-deleted:
Route::post('posts/{id}/restore', [PostController::class, 'restore']);

```

#### **Reglas útiles**

- `bail`: detiene validación en primer error del campo.

  ```
  'title' => ['bail','required','min:4']
  ```

- `sometimes`, `filled`, `nullable`, `present`:

  ```
  'slug' => ['sometimes','string'], // valida solo si viene
  'nick' => ['filled','string'],    // si viene, no puede estar vacío
  'bio'  => ['nullable','string'],  // se permite null
  'csrf' => ['present']             // debe existir la clave (vacía o no)
  ```

- `prohibited_if`, `required_without`:

  ```
  'archive_reason' => ['prohibited_unless:status,archived'],
  'email'          => ['required_without:phone']
  ```

- `exists`/`unique` con clausura:

  ```
  Rule::unique('posts','slug')->where(fn($q) => $q->whereNull('deleted_at'))
  ```

- **Arrays anidados**:

  ```
  'images' => ['array','max:5'],
  'images.*.file' => ['file','mimetypes:image/jpeg,image/png','max:2048'],
  'images.*.alt'  => ['nullable','string','max:120'],
  ```

#### Regla personalizada

**Custom Rule **

```bash
php artisan make:rule SafeHtml
```

**`SafeHtml.php`**

```php
namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class SafeHtml implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (preg_match('/<\s*script\b/i', (string)$value)) {
            $fail('El campo :attribute contiene etiquetas no permitidas.');
        }
    }
}

```

### Ejemplo de llamada válida en Postman

- **URL:** `http://localhost:8000/api/posts`
- **Método:** POST
- **Headers:**
  - Authorization: Bearer TU_TOKEN
  - Accept: application/json

**Method**: `POST` 

**URL**: `/api/posts`

**`payload`** ✅

```json
{
    "title": "Mi primer post",
    "content": "Este es el contenido de mi primer post, que tiene más de veinte caracteres.",
    "status": "published",
    "published_at": "2025-08-27 10:00:00",
    "tags": [
        "laravel",
        "ejemplo"
    ],
    "meta": {
        "seo_title": "SEO para mi post",
        "seo_desc": "Descripción SEO para mi post publicado."
    },
    "user_id": 1,
    "category_ids": [
        1,
        2
    ]
}
```

**`payload`**❌

```json
{
    "title": "abc", // Muy corto (min:4)
    "content": "Muy corto", // Muy corto (min:20)
    "status": "activo", // Valor no permitido (solo draft, published, archived)
    "published_at": "2030-01-01", // Fecha futura (no permitido si status=published)
    "tags": [
        "a",
        "a"
    ], // Tag muy corto y repetido
    "meta": {
        "seo_title": "Título muy largo que supera los sesenta caracteres permitidos para este campo SEO",
        "seo_desc": null
    },
    "category_ids": [
        999
    ] // Categoría que probablemente no existe
}
```

**Response** `422 Unprocessable Content`

```json
{
    "message": "The título field must be at least 4 characters. (and 9 more errors)",
    "errors": {
        "title": [
            "The título field must be at least 4 characters."
        ],
        "content": [
            "El contenido debe tener al menos 20 caracteres."
        ],
        "status": [
            "Estado inválido. Usa draft, published o archived."
        ],
        "published_at": [
            "The fecha de publicación field must be a date before or equal to now."
        ],
        "meta.seo_title": [
            "The meta.seo title field must not be greater than 60 characters."
        ],
        "tags.0": [
            "The tags.0 field must be at least 2 characters.",
            "Los tags no deben repetirse."
        ],
        "tags.1": [
            "The tags.1 field must be at least 2 characters.",
            "Los tags no deben repetirse."
        ],
        "category_ids.0": [
            "Alguna categoría no existe."
        ]
    }
}
```

**Method**: `PUT` 

**URL**: `/api/posts/1`

**`payload`** ✅

```json
{
    "title": "Mi primer post actualizado",
    "slug": "mi-primer-post-actualizado",
    "content": "Este es el contenido actualizado de mi primer post, que sigue teniendo más de veinte caracteres.",
    "status": "published",
    "published_at": "2025-08-27 12:00:00",
    "tags": [
        "laravel",
        "actualizado"
    ],
    "meta": {
        "seo_title": "SEO actualizado para mi post",
        "seo_desc": "Nueva descripción SEO para mi post publicado."
    },
    "category_ids": [
        2,
        3
    ]
}
```

