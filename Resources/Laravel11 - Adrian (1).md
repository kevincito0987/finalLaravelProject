# Laravel 11

---

## Fundamentos y configuración inicial en Laravel 11

### 1.1 Fundamentos de Laravel e instalación

- **1.1.1 ¿Qué es Laravel?**  
  *(pendiente  )*

  - **1.1.1.1 Historia y características principales**  
    *(pendiente  )*

  - **1.1.1.2 Backend vs Fullstack en Laravel**  
    *(pendiente  )*

- **1.1.2 Instalación**

  - **1.1.2.1 Instalación de PHP y Composer**  
    Requisitos mínimos indicados: **PHP ≥ 8.2** y **Composer**.  
    ```bash
    php --version
    
    # Instalación Composer (método manual listado)
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    
    sudo mv composer.phar /usr/local/bin/composer
    ```

    > **Nota:** En algunos entornos la ruta global de Composer puede diferir (p. ej. `~/.config/composer/vendor/bin`).

  - **1.1.2.2 Instalación de Laravel (Installer y Composer)**  
    Instalación del **Laravel Installer 12.x** :  
    
    ```bash
    composer global require laravel/installer
    ```
    Agregar **Laravel** al `PATH` (ejemplo para Bash):
    ```bash
    sudo nano ~/.bashrc
    # Al final del archivo:
    export PATH="$PATH:$HOME/.composer/vendor/bin"
    source ~/.bashrc
    ```
    
  - **1.1.2.3 Variables de entorno y PATH**  
    Se sugiere exportar el `PATH` anterior para hacer disponible el binario `laravel` en la terminal.
  
  - **1.1.2.4 Creación de un proyecto básico**
    ```bash
    # Usando Composer (Laravel 11.x)
    composer create-project laravel/laravel:^11 [nombre-del-proyecto]
    
    # Usando una versión específica (ejemplo tomado del original)
    composer create-project laravel/laravel:^11.6.1 [nombre-del-proyecto]
    
    # Usando Laravel Installer (crea la última versión que soporte el Installer)
    laravel new [nombre-del-proyecto]
    ```
  
  - **1.1.2.5 Verificación de instalación (`artisan serve`)**
    ```bash
    cd [nombre-del-proyecto]
    php artisan serve --host=0.0.0.0 --port=8000
    # Visita: http://localhost:8000
    ```

---

### 1.2 Configuración inicial del entorno

- **1.2.1 Archivo `.env`**
  - **1.2.1.1 APP_KEY y seguridad básica**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
  - **1.2.1.2 Configuración de APP_URL**
    ```env
    APP_URL=http://localhost
    ```

- **1.2.2 Conexión a base de datos**
  - **1.2.2.1 Configuración en `.env`**
    ```env
    # Ejemplo con SQLite por defecto Laravel 11 usa sqlite
    DB_CONNECTION=sqlite
    ```
  - **1.2.2.2 Conexión MySQL, PostgreSQL** 
    
    ```env
    # Ejemplo con MySQL
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=crud
    DB_USERNAME=root
    DB_PASSWORD=admin
    ```
    
  - **1.2.2.3 Prueba con `php artisan tinker`**
    ```bash
    php artisan tinker
    DB::connection()->getPdo();  # Si conecta, devuelve el objeto PDO
    exit
    ```
    Verificación adicional:
    ```bash
    php artisan migrate:status
    ```
  
- **1.2.3 Configuración de correo y broadcasting**
  ```env
  MAIL_MAILER=log
  MAIL_HOST=127.0.0.1
  MAIL_PORT=2525
  MAIL_USERNAME=null
  MAIL_PASSWORD=null
  MAIL_ENCRYPTION=null
  MAIL_FROM_ADDRESS="hello@example.com"
  MAIL_FROM_NAME="${APP_NAME}"
  ```
  Broadcasting y API en Laravel 11:
  ```bash
  php artisan install:api
  php artisan install:broadcasting
  ```

---

## 2. Artisan y estructura de proyecto

### 2.1 Artisan CLI

- **2.1.1 Comandos principales (`make`, `migrate`, `db:seed`, `serve`)**
  ```bash
  php artisan list
  php artisan help migrate
  
  # Generación
  php artisan make:model Producto
  php artisan make:controller ProductoController
  php artisan make:migration create_productos_table
  php artisan make:seeder ProductoSeeder
  php artisan make:component Alert
  
  # Base de datos
  php artisan migrate
  php artisan migrate:rollback
  php artisan db:seed
  php artisan tinker
  
  # Caché / configuración
  php artisan config:cache
  php artisan cache:clear
  php artisan route:cache
  php artisan view:clear
  
  # Servidor local
  php artisan serve
  ```

- **2.1.2 Limpieza y optimización (`cache:clear`, `config:cache`, `route:cache`)**  
  
  ```bash
  # Caché / configuración
  php artisan config:cache
  php artisan cache:clear
  php artisan route:cache
  php artisan view:clear
  ```

### 2.2 Estructura de directorios

Una vez creado el proyecto, Laravel proporciona una estructura limpia y organizada:

![](https://cdn.hashnode.com/res/hashnode/image/upload/v1726071712716/41ab3277-e947-421c-9247-f9ecf839c7e1.png?auto=compress,format&format=webp)

- **2.2.1 App, Config, Database, Public, Resources**  
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

- **2.2.2 Storage, Tests, Vendor**  
  1. **storage**
     Los registros, las plantillas de Blade compiladas, las sesiones basadas en archivos, las cachés de archivos y otros archivos generados por el framework se almacenan en el directorio de almacenamiento. Este directorio se divide en directorios de app, framework y los logs. El directorio de la app está disponible para almacenar cualquier archivo creado por su aplicación. El directorio del framework se utiliza para almacenar archivos y cachés generados por el framework. Al final, el directorio de registros alberga los archivos de registro para logs.
  2. **test/**
     Las pruebas automatizadas se encuentran en el directorio de pruebas. Por ejemplo, `Pest` o `PHPUnit` viene con pruebas unitarias preinstaladas y pruebas de características. Cada categoría de prueba debe terminar con el término Prueba.
  3. **vendor/**
     El directorio del `vendor` contiene sus dependencias de Composer.

- **2.2.3 Archivo `artisan`**  
  1. Este archivo es la interfaz de línea de comandos de Laravel. Lo usas para ejecutar comandos de Artisan como migraciones, generar modelos, ejecutar pruebas y más.

---

## 3. Controladores y rutas

### 3.1 Uso de controladores

- **3.1.1 Sintaxis básica de controladores**
  ```bash
  php artisan make:controller PostController
  ```
  Ejemplo de método simple:
  ```php
  public function login()
  {
      return response()->json(['message' => 'Hello login'], 200);
  }
  ```

- **3.1.2 Controladores invocables (`__invoke`)**
  ```bash
  php artisan make:controller PostController --invokable
  ```
  El método `__invoke()` actúa como controlador de una sola acción.

- **3.1.3 Controladores RESTful (`--resource`, `--api`)**
  ```bash
  php artisan make:controller BlogController --api
  
  // routes/api.php
  use App\Http\Controllers\BlogController;
  Route::apiResource('blog', BlogController::class);
  ```
  > **Nota**: `apiResource()` no incluye `create()` ni `edit()`.

- **3.1.4 Inyección de dependencias en constructores y métodos**  
  *(pendiente  )*

### 3.2 Rutas en Laravel

- **3.2.1 `routes/web.php` y `routes/api.php`**  
  El documento recuerda que en Laravel 11 `api.php` y `channels.php` pueden no venir por defecto y se agregan con `php artisan install:api` y `php artisan install:broadcasting`.

- **3.2.2 Agrupación de rutas (prefix, name, middleware)**  
  *(pendiente  )*

- **3.2.3 Versionado de rutas para APIs**  
  *(pendiente  )*

---

## 4. Bases de datos y migraciones

### 4.1 Migraciones

- **4.1.1 Creación de migraciones**
  ```bash
  php artisan make:migration create_posts_table
  ```
  Ejemplo `up()`:
  ```php
  Schema::create('posts', function (Blueprint $table) {
      $table->id();
      $table->string('titulo');
      $table->timestamps();
  });
  ```
  Ejemplo `down()`:
  ```php
  Schema::dropIfExists('posts');
  ```

- **4.1.2 Tipos de columnas y constraints**  
  Se muestran ejemplos de `string`, `text`, `longText`, `enum`, `json`, índices (`index`, `unique`) y llaves foráneas en tablas pivote.

- **4.1.3 Rollback y refresh (`migrate:rollback`, `migrate:fresh`)**
  ```bash
  php artisan migrate:rollback
  php artisan migrate:fresh
  ```
  Además, se explica la tabla **`migrations`** y `migrate:status` para ver el estado.

### 4.2 Seeders y Factories

- **4.2.1 Creación de seeders**
  ```bash
  php artisan make:seeder PostSeeder
  php artisan db:seed --class=PostSeeder
  ```

- **4.2.2 Uso de Factories para datos falsos**
  ```bash
  php artisan make:factory PostFactory
  ```
  Ejemplo `PostFactory`:
  ```php
  public function definition(): array
  {
      return [
          'title'   => fake()->sentence(10),
          'content' => fake()->paragraph(),
          'status'  => fake()->boolean(),
      ];
  }
  ```

- **4.2.3 DatabaseSeeder y orden de ejecución**  
  En el `PostSeeder` se muestran dos vías: `Post::factory()->count(50)->create();` y un `DB::table('posts')->insert([...]);`

---

## 5. Eloquent ORM y CRUD básico

### 5.1 Modelos y consultas

- **5.1.1 Creación de modelos**
  ```bash
  php artisan make:model Post
  # o con migración
  php artisan make:model Post -m
  ```

- **5.1.2 Operaciones CRUD (`create`, `save`, `updateOrCreate`)**
  - **`create()`** (requiere `$fillable`):
    ```php
    $post = Post::create($request->only(['titulo', 'body']));
    ```
  - **`fill()` + `save()`**:
    ```php
    $post = new Post();
    $post->fill($request->only(['titulo', 'body']))->save();
    ```
  - **`forceFill()` + `save()`** (sin `$fillable`):
    ```php
    $post = new Post();
    $post->forceFill([
        'titulo' => $request->titulo,
        'body'   => $request->body,
    ])->save();
    ```
  - **`updateOrCreate()`**:
    ```php
    Post::updateOrCreate(
      ['titulo' => $request->titulo],
      ['body'   => $request->body]
    );
    ```

- **5.1.3 Métodos `find`, `findOrFail`, `first`, `where`**
  ```php
  Post::find($id);       // null si no existe
  Post::findOrFail($id); // lanza ModelNotFoundException (404)
  Post::where('titulo','like','%laravel%')->first();
  ```

### 5.2 Relaciones

- **5.2.1 Relaciones 1:1, 1:N, N:M**  
  El documento incluye ejemplos centrados en **N:M** y uso de **Soft Deletes**; las 1:1 y 1:N no se desarrollan explícitamente. *(parcial  )*

- **5.2.2 Tablas pivote (`belongsToMany`)**
  ```php
  // Post.php
  public function categories()
  {
      return $this->belongsToMany(Category::class)->using(CategoryPost::class)->withTimestamps();
  }
  ```
  Migración pivote:
  ```php
  Schema::create('category_post', function (Blueprint $table) {
      $table->id();
      $table->foreignId('post_id')->constrained()->cascadeOnDelete();
      $table->foreignId('category_id')->constrained()->cascadeOnDelete();
      $table->timestamps();
      $table->unique(['post_id', 'category_id']);
  });
  ```

---

## 6. Validaciones y respuestas

### 6.1 Validaciones

- **6.1.1 Validación en controladores (`$request->validate`)**  
  *(pendiente  ; se emplea `Validator` y Form Requests)*

- **6.1.2 Form Requests (`make:request`)**
  ```bash
  php artisan make:request StorePostRequest
  php artisan make:request UpdatePostRequest
  php artisan make:request StoreCategoryRequest
  ```
  Reglas destacadas (extracto):
  ```php
  'title'   => ['required','string','min:4','max:200'],
  'slug'    => ['required','string','max:220','unique:posts,slug'],
  'status'  => [Rule::in(['draft','published','archived'])],
  'tags'    => ['nullable','array','max:20'],
  'tags.*'  => ['string','min:2','max:30','distinct'],
  // ...
  ```

  Reglas de `StorePostRequest`:
  
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
  
  Reglas de `UpdatePostRequest`:
  
  ```php
  <?php
  
  namespace App\Http\Requests;
  
  use Illuminate\Foundation\Http\FormRequest;
  use Illuminate\Support\Str;
  use Illuminate\Validation\Rule;
  use App\Models\Post; //Importante NO Olvidar
  
  class UpdatePostRequest extends FormRequest
  {
      /**
       * Determine if the user is authorized to make this request.
       */
      public function authorize(): bool
      {
          return true;
      }
  
      /**
       * Get the validation rules that apply to the request.
       *
       * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
       */
      public function rules(): array
      {
          $routePost = $this->route('post');
          $postId = $routePost instanceof Post ? $routePost->getKey() : $routePost;
          return [
              'title'   => ['sometimes', 'string', 'min:4', 'max:200'],
              'slug'    => [
                  'sometimes',
                  'string',
                  'max:220',
                  Rule::unique('posts', 'slug')
                      ->ignore($postId)                 // ignora este post
                      ->whereNull('deleted_at')         // respeta soft delete
              ],
              'content' => ['sometimes', 'string', 'min:20'],
              'status'  => ['sometimes', Rule::in(['draft', 'published', 'archived'])],
              'published_at' => ['nullable', 'date', 'required_if:status,published', 'before_or_equal:now'],
              'cover_image'  => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:2048'],
              'tags'         => ['nullable', 'array', 'max:20'],
              'tags.*'       => ['string', 'min:2', 'max:30', 'distinct'],
              'meta'         => ['nullable', 'array'],
              'meta.seo_title' => ['nullable', 'string', 'max:60'],
              'meta.seo_desc'  => ['nullable', 'string', 'max:160'],
              'category_ids' => ['nullable', 'array', 'max:10'],
              'category_ids.*' => ['integer', 'exists:categories,id'],
          ];
      }
  }
  ```
  
  Reglas de `StoreCategoryRequest`:
  
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
  
  Implementación de las definidas anteriormente en `PostController`:
  
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
  
  Implementación de la ruta de `restore` en el  `api`:
  
  ```php
  use Illuminate\Support\Facades\Route;
  
  Route::apiResource('posts', PostController::class);
  // Restaurar soft-deleted:
  Route::post('posts/{id}/restore', [PostController::class, 'restore']);
  ```
  
  
  
- **6.1.3 Reglas personalizadas (`make:rule`)**
  
  ```bash
  php artisan make:rule SafeHtml
  ```
  Ejemplo sencillo que bloquea `<script>`:
  ```php
  public function validate(string $attribute, mixed $value, \Closure $fail): void
  {
      if (preg_match('/<\s*script\b/i', (string)$value)) {
          $fail('El campo :attribute contiene etiquetas no permitidas.');
      }
  }
  ```
  
  Implementacion de regla `SafeHtml`:
  
  ```php
  public function rules(): array
  {
          return [
              'title' => ['required', 'string', 'min:4', 'max:200'],
              'slug' => ['required', 'string', 'max:220', 'unique:posts,slug'],
              'content' => ['required', 'string', 'min:20', new SafeHtml], //Llamado a SafeHtml
  
              'status' => ['required', Rule::in(['draft', 'published', 'archived', 'default'])],
              //....
  			];
   }
  ```
  
  
  
  Ejemplo de error con payload invalido en el parametro `content` :
  
  ```json
  {
    "title": "Post inseguro",
    "slug": "post-inseguro",
    "content": "<script>alert('Hacked!');</script>", 
    "status": "draft"
  }
  ```
  
  Ejemplo de payload válido para la creación de `Post`:
  URL: `/api/posts`
  
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
      "category_ids": [
          1,
          2
      ]
  }
  ```
  
  Ejemplo de payload inválido para la creación de `Post`:
  
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
  
  

### 6.2 Respuestas JSON

- **6.2.1 Traits para estandarizar respuestas**
  
  ```bash
  php artisan make:trait ApiResponse
  ```
  
  
  
  ```php
  <?php
  
  namespace App\Traits;
  
  use Illuminate\Http\JsonResponse;
  
  trait ApiResponse
  {
      protected function successResponse($data, string $message = 'OK', int $code = 200): JsonResponse
      {
          return response()->json([
              'status'  => 'success',
              'message' => $message,
              'data'    => $data
          ], $code);
      }
  
      protected function errorResponse(string $message, int $code = 400, $errors = []): JsonResponse
      {
          return response()->json([
              'status'  => 'error',
              'message' => $message,
              'errors'  => $errors
          ], $code);
      }
  }
  ```
  
- **6.2.2 API Resources (`make:resource`)**  
  
  ```bash
  php artisan make:resource PostResource
  ```
  
  ```php
  <?php
  
  namespace App\Http\Resources;
  
  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;
  
  class PostResource extends JsonResource
  {
      public function toArray(Request $request): array
      {
          return [
              'id'           => $this->id,
              'title'        => $this->title,
              'slug'         => $this->slug,
              'content'      => $this->content,
              'status'       => $this->status,
              'published_at' => $this->published_at?->toDateTimeString(),
              'cover_image'  => $this->cover_image,
              'tags'         => $this->tags,
              'meta'         => $this->meta,
              'categories'   => $this->categories->map(fn ($cat) => [
                  'id'   => $cat->id,
                  'name' => $cat->name,
                  'slug' => $cat->slug,
              ]),
              'created_at'   => $this->created_at?->toDateTimeString(),
              'updated_at'   => $this->updated_at?->toDateTimeString(),
          ];
      }
  }
  
  ```
  
  Ejemplo de implementación de `PostResource` en `PostController`:
  
  ```php
  use App\Traits\ApiResponse;
  use App\Http\Resources\PostResource;
  use Illuminate\Http\JsonResponse;
  
  class PostController
  {
      use ApiResponse;
  	
      public function index(): JsonResponse
      {		
  				//Antes
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
  
  				//Ahora
          $posts = Post::with('categories')->get();
          return $this->successResponse(PostResource::collection($posts));
      }
                              
  		public function store(StorePostRequest $request): JsonResponse
      {
         $data = $request->validated();
  
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }
  
        $post = Post::create($data);
        $post->categories()->sync($data['category_ids'] ?? []);
        //Implementación de PostResource
        return $this->successResponse(new PostResource($post), 'Post creado correctamente', 201);
      }
  
      public function update(UpdatePostRequest $request, Post $post): JsonResponse
      {
          $data = $request->validated();
  
          if ($request->hasFile('cover_image')) {
              if ($post->cover_image) {
                  Storage::disk('public')->delete($post->cover_image);
              }
              $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
          }
  
          $post->update($data);
          $post->categories()->sync($data['category_ids'] ?? []);
  				//Implementación de PostResource
          return $this->successResponse(new PostResource($post), 'Post actualizado correctamente');
      }
  
      public function destroy(Post $post): JsonResponse
      {
          $post->delete();
          return $this->successResponse(null, 'Post eliminado', 204);
      }
  }
  ```
  
  
  
- **6.2.3 Códigos HTTP y manejo de errores**  
  
  ```php
  use App\Traits\ApiResponse;
  use App\Http\Resources\PostResource;
  use Illuminate\Http\JsonResponse;
  
  class PostController
  {
      use ApiResponse;
  	
      public function index(): JsonResponse
      {		
          $posts = Post::with('categories')->get();
          return $this->successResponse(PostResource::collection($posts));
      }
                              
  		public function store(StorePostRequest $request): JsonResponse
      {
          try {
              $data = $request->validated();
  
              if ($request->hasFile('cover_image')) {
                  $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
              }
  
              $post = Post::create($data);
              $post->categories()->sync($data['category_ids'] ?? []);
              return $this->successResponse(new PostResource($post), 'Post creado correctamente', 201);
  
          } catch (\Exception $e) {
            	//Implementación de Errores
              return $this->errorResponse('Error al crear el post', 500, ['exception' => $e->getMessage()]);
          }
      }
  
      public function update(UpdatePostRequest $request, Post $post): JsonResponse
      {
          $data = $request->validated();
  
          if ($request->hasFile('cover_image')) {
              if ($post->cover_image) {
                  Storage::disk('public')->delete($post->cover_image);
              }
              $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
          }
  
          $post->update($data);
          $post->categories()->sync($data['category_ids'] ?? []);
          return $this->successResponse(new PostResource($post), 'Post actualizado correctamente');
      }
  
      public function destroy(Post $post): JsonResponse
      {
          $post->delete();
          return $this->successResponse(null, 'Post eliminado', 204);
      }
  }
  ```
  
  

### 6.3 Manejo de excepciones

- **6.3.1** **Configurar el manejo de excepciones `withExceptions` en `bootstrap/app.php`**:

  - Validaciones específicos
    - `ValidationException` (**422**): cuando falla la validación (SafeHtml, campos obligatorios, etc.).
    - `AuthenticationException` (**401**): cuando no hay usuario autenticado.
    - `AuthorizationException` (**403**): cuando el usuario autenticado no tiene permisos.
    - `ModelNotFoundException` (**404**): cuando se busca un modelo inexistente.
    - `NotFoundHttpException` (**404**): cuando la ruta no existe.
    - `MethodNotAllowedHttpException` (**405**): cuando el método HTTP no está permitido.

  - Validación genérica
    - Si el error no cae en ninguno de los casos anteriores, se devuelve un `500 Error interno del servidor` o respuesta genérica.

  Ejemplo de Implementación de `Exceptions`:

  ```php
  <?php
  
  use App\Http\Middleware\ForceJsonResponse;
  use Illuminate\Foundation\Application;
  use Illuminate\Foundation\Configuration\Exceptions;
  use Illuminate\Foundation\Configuration\Middleware;
  use Illuminate\Validation\ValidationException;
  use Illuminate\Auth\AuthenticationException;
  use Illuminate\Auth\Access\AuthorizationException;
  use Illuminate\Database\Eloquent\ModelNotFoundException;
  use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
  use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
  use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
  
  return Application::configure(basePath: dirname(__DIR__))
      ->withRouting(
          web: __DIR__ . '/../routes/web.php',
          api: __DIR__ . '/../routes/api.php',
          commands: __DIR__ . '/../routes/console.php',
          health: '/up',
      )
      ->withMiddleware(function (Middleware $middleware) {
          //
      })
      ->withExceptions(function (Exceptions $exceptions) {
  
          // Forzar JSON en rutas API
          $exceptions->shouldRenderJsonWhen(
              fn($request) => $request->is('api/*') || $request->expectsJson()
          );
  
          // Validación (422)
          $exceptions->render(function (ValidationException $e, $request) {
              return response()->json([
                  'status'  => 'error',
                  'message' => 'Los datos proporcionados no son válidos.',
                  'errors'  => $e->errors(),
              ], 422);
          });
  
          // No autenticado (401)
          $exceptions->render(function (AuthenticationException $e, $request) {
              return response()->json([
                  'status'  => 'error',
                  'message' => 'No autenticado.',
                  'errors'  => [],
              ], 401);
          });
  
          // No autorizado (403)
          $exceptions->render(function (AuthorizationException $e, $request) {
              return response()->json([
                  'status'  => 'error',
                  'message' => 'No autorizado.',
                  'errors'  => [],
              ], 403);
          });
  
          // Modelo no encontrado (404)
          $exceptions->render(function (ModelNotFoundException $e, $request) {
              $model = class_basename($e->getModel());
              return response()->json([
                  'status'  => 'error',
                  'message' => "$model no encontrado.",
                  'errors'  => [],
              ], 404);
          });
  
          // Ruta no encontrada (404)
          $exceptions->render(function (NotFoundHttpException $e, $request) {
              return response()->json([
                  'status'  => 'error',
                  'message' => 'Ruta no encontrada.',
                  'errors'  => [],
              ], 404);
          });
  
          // Método no permitido (405)
          $exceptions->render(function (MethodNotAllowedHttpException $e, $request) {
              return response()->json([
                  'status'  => 'error',
                  'message' => 'Método no permitido.',
                  'errors'  => [],
              ], 405);
          });
  
          // Fallback genérico
          $exceptions->render(function (\Throwable $e, $request) {
              $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
  
              return response()->json([
                  'status'  => 'error',
                  'message' => $status === 500 ? 'Error interno del servidor.' : $e->getMessage(),
                  'errors'  => [],
              ], $status);
          });
      })->create();
  
  ```

---

## 7. Seguridad y autenticación

### 7.1 Middleware

- **7.1.1 ** **¿Qué es un Middleware?**  
  
  Un middleware en Laravel es una clase que actúa como un “filtro” o “capa intermedia” entre la petición HTTP y la aplicación.
  
  - Puede modificar la petición antes de que llegue al controlador.
  
  - Puede modificar la respuesta antes de que regrese al cliente.
  
    > **Ejemplos típicos**: autenticación (auth), verificación CSRF, logging, rate limiting, etc. 
  
  
  
  **Comando de Artisan** para crear un middleware
  
  ```bash
  php artisan make:middleware ForceJsonResponse
  ```
  
  ```php
  namespace App\Http\Middleware;
  
  use Closure;
  use Illuminate\Http\Request;
  use Symfony\Component\HttpFoundation\Response;
  
  class ForceJsonResponse
  {
      public function handle(Request $request, Closure $next): Response
      {
          $request->headers->set('Accept', 'application/json');
          return $next($request);
      }
  }
  ```
  
  
  
- **7.1.2 Middleware integrado (auth, throttle, verified)** 
  
  - **7.1.2.1**  **¿Qué es un ThrottleRequests?**
  
    Es un middleware que **limita la frecuencia** (rate limiting) de las solicitudes. Internamente usa **limiters** nombrados registrados con `RateLimiter::for(...)` y aplica ventanas de tiempo (token bucket/ventana fija) según configuración y protege tu API de abuso (bots, DDoS de baja escala, brute force). Cuando se supera el límite:
  
    - devuelve **HTTP 429** *Too Many Requests*,
    - adjunta cabeceras como `Retry-After`/`X-RateLimit-*` según configuración,
    - puedes personalizar el **payload JSON** de error.
  
    ```bash
    php artisan make:provider RouteServiceProvider
    ```
  
    Ejemplo de implementación de `RateLimiter` con un `Provider`:
  
    ```php
    <?php
    
    namespace App\Providers;
    
    use Illuminate\Support\ServiceProvider;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\RateLimiter;
    use Illuminate\Cache\RateLimiting\Limit;
    
    class RouteServiceProvider extends ServiceProvider
    {
        /**
         * Register services.
         */
        public function register(): void
        {
            //
        }
    
        /**
         * Bootstrap services.
         */
        public function boot(): void
        {
            RateLimiter::for('api', function (Request $request) {
                if ($userId = optional($request->user())->getAuthIdentifier()) {
                    return Limit::perMinute(3)->by('uid:' . $userId);// 100 Valor por Default
                }
                return Limit::perMinute(3)->by('ip:' . $request->ip());// // 30 Valor por Default
            });
        }
    }
    ```
  
    > **Nota**: Los *Service Providers* son el **punto central donde Laravel arranca todo lo que tu app necesita**: rutas, eventos, middlewares, bindings, etc.
  
    Ejemplo de **Limpiar cachés para limpiar los registro de rate limits**:
  
    ```bash
    php artisan cache:clear
    ```
  
    
  
  - **7.1.2.2** **¿Qué es un Route Model Binding?**
  
    Es un middleware del router que **resuelve vínculos de ruta a valores concretos** antes de entrar al controlador. Implementa el **Route Model Binding** y el **Implicit Binding**.
  
    **Para qué sirve:** cuando defines una ruta con `{post}` y tu acción recibe `Post $post`, este middleware:
  
    - extrae el parámetro de la URL (`/posts/1`),
    - ejecuta la resolución (por defecto `Post::findOrFail(1)` o la clave definida en `getRouteKeyName()`),
    - **inyecta** la instancia ya cargada en tu método.
      Sin él, `$post` te llega vacío o con `id=null` y tendrías que buscar el modelo manualmente.
  
  - **7.1.2.3**
    (pendiente)
  
  - **7.1.2.4**
    (pendiente)
  
  
  
- **7.1.3 Creación de middleware personalizado**  

  Este middleware se encarga de interceptar la petición antes de que llegue al controlador.
  Lo que hace es:

  - Revisar si la URL pertenece al grupo `api/*`.
  - Si es así, agrega la cabecera `Accept: application/json` aunque el cliente no la haya enviado.

  Con esto, te aseguras de que **todas las respuestas de la API** se devuelvan en JSON, sin depender de que Postman, un navegador o una app cliente lo especifique.

  ```php
  <?php
  
  namespace App\Http\Middleware;
  
  use Closure;
  use Illuminate\Http\Request;
  
  class ForceJsonResponse
  {
      public function handle(Request $request, Closure $next)
      {
          // Forzar que siempre se espere JSON en API
          // Por si Adrian se le OLVIDA
          if ($request->is('api/*')) {
              $request->headers->set('Accept', 'application/json');
          }
  
          return $next($request);
      }
  }
  
  ```

  Registrar el Middleware en `bootstrap/app.php`

  ```php
  ->withMiddleware(function (Middleware $middleware) {
      $middleware->appendToGroup('api', [
        // O Usar use App\Http\Middleware\ForceJsonResponse
          \App\Http\Middleware\ForceJsonResponse::class, 
      ]);
  })
  ```

  

### 7.2 Autenticación

- **7.2.1 Laravel Passport (login/register básico)**  
  
  **Laravel Passport** es un paquete oficial de Laravel que implementa un sistema completo de **autenticación mediante OAuth2**. Permite a las aplicaciones generar y gestionar **tokens de acceso** seguros para autenticar usuarios en APIs.
  
  Con Passport puedes:
  
  - Emitir **tokens de acceso personales** y de clientes.
  - Controlar el acceso a tus endpoints mediante **roles y permisos**.
  - Ofrecer un sistema de autenticación estándar y escalable para APIs RESTful.
  - Integrar flujos de autenticación seguros en aplicaciones móviles, web o servicios externos.
  
  En pocas palabras, Passport convierte a Laravel en un **servidor OAuth2 listo para producción**, simplificando la implementación de autenticación basada en **tokens** y el control de acceso a rutas protegidas.
  
  **7.2.1.1 Instalar y preparar Passport**
  
  ```bash
  composer require laravel/passport
  php artisan passport:install
  php artisan migrate
  ```
  
  > Esto generará las claves y los “clients” necesarios (*personal*/*password grants*).
  
  el provider principal se llama **`users`**, y está configurado para usar el modelo `App\Models\User`. Ejemplo de `config/auth.php`:
  
  ```php
  'providers' => [
      'users' => [
          'driver' => 'eloquent',
          'model'  => App\Models\User::class,
      ],
  ],
  ```
  
  En **`config/auth.php`** configura el guard `api` con Passport:
  
  ```php
  'guards' => [
      'web' => ['driver' => 'session', 'provider' => 'users'],
      'api' => ['driver' => 'passport', 'provider' => 'users'],
  ],
  ```
  
  **7.2.1.2 Relación con roles**
  
  En **`app/Models/User.php`** añade el trait de Passport y relación con roles:
  
  ```php
  use Laravel\Passport\HasApiTokens;
  use Illuminate\Foundation\Auth\User as Authenticatable;
  
  class User extends Authenticatable
  {
      use HasApiTokens; // <- Passport
      // ...
  }
  ```
  
  **¿Qué son los *Scopes* y *Abilities* en Laravel Passport?**
  
  - **Scopes (alcances):**
    Los *scopes* son **permisos de alto nivel** que se asignan a un token de acceso.
    Sirven para limitar lo que un cliente o usuario puede hacer con la API.
    Por ejemplo, un token con el scope `admin` puede acceder a rutas de administración, mientras que un token con el scope `user` solo accede a rutas básicas.
  
     **Ejemplo:**
  
    - Scope `posts.write` → permite crear publicaciones.
    - Scope `posts.delete` → permite eliminar publicaciones.
    - Un mismo usuario puede tener uno o varios scopes según sus privilegios.
  
  ------
  
  - **Abilities (habilidades):**
    Las *abilities* son **permisos más específicos o detallados** asociados a un token.
    Funcionan de manera similar a los *scopes*, pero se usan cuando se requiere un **control más granular** sobre lo que el token puede hacer dentro de la API.
  
     **Ejemplo práctico:**
  
    - Ability `view-profile` → leer información del perfil.
    - Ability `update-profile` → actualizar el perfil.
    - Ability `ban-user` → bloquear a un usuario específico.
  
  
  
  En **`app/Providers/AuthServiceProvider.php`** (o crea uno si no está) registra scopes/abilities:
  
  ```bash
  php artisan make:provider AuthServiceProvider
  ```
  
  Ejemplo de implementación de auth provider:
  
  ```php
  <?php
  
  namespace App\Providers;
  
  use Laravel\Passport\Passport;
  use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
  
  class AuthServiceProvider extends ServiceProvider
  {
      /**
       * El mapeo de políticas para la aplicación.
       *
       * @var array<class-string, class-string>
       */
      protected $policies = [
          // 'App\Models\Post' => 'App\Policies\PostPolicy',
      ];
  
      public function boot(): void
      {
          $this->registerPolicies();
  
          // Definir tiempos de expiración de tokens
          Passport::tokensExpireIn(now()->addHours(2));
          Passport::refreshTokensExpireIn(now()->addDays(30));
          Passport::personalAccessTokensExpireIn(now()->addMonths(6));
  
          //recurso.acción
          Passport::tokensCan([
              'posts.read'  => 'Leer posts',
              'posts.write' => 'Crear/editar posts',
              'posts.delete' => 'Puede actualizar posts',
              'posts.admin' => 'Acceso total a la API',
          ]);
  
          Passport::defaultScopes([
              'posts.read',
          ]);
      }
  }
  
  ```
  
  
  
  ```bash
  php artisan make:controller AuthController
  ```
  
  Ejemplo de implementación de controlador:
  
  ```php
  <?php
  
  namespace App\Http\Controllers;
  
  use App\Models\User;
  use App\Traits\ApiResponse;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Hash;
  
  class AuthController extends Controller
  {
      use ApiResponse;
  
  		public function register(Request $request)
      {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
  
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $defaultRole = \App\Models\Role::where('name', 'viewer')->first();
        if ($defaultRole) {
            $user->roles()->syncWithoutDetaching([$defaultRole->id]);
        }
  
        return $this->success($user->load('roles'), 'Usuario creado conéxito',201);
      }
  
      public function login(Request $request)
      {
          $request->validate([
              'email'    => ['required', 'email'],
              'password' => ['required', 'string', 'min:6'],
          ]);
  
          if (!Auth::attempt($request->only('email', 'password'))) {
              return $this->error('Credenciales inválidas', 401);
          }
  
          $user = $request->user();
  
          // Token de acceso Passport (Personal Access Token)
          $tokenResult = $user->createToken('api-token'); // puedes pasar scopes: createToken('api-token', ['posts.read'])
          $token = $tokenResult->accessToken;
  
          return $this->success([
              'token_type' => 'Bearer',
              'access_token' => $token,
              'user' => [
                  'id'    => $user->id,
                  'email' => $user->email,
                  'roles' => $user->roles()->pluck('name'),
              ],
          ]);
      }
  
      public function me(Request $request)
      {
          return $this->success([
              'id'    => $request->user()->id,
              'email' => $request->user()->email,
              'roles' => $request->user()->roles()->pluck('name'),
          ]);
      }
  
      public function logout(Request $request)
      {
          // Revoca el token actual
          $request->user()->token()?->revoke();
          return $this->success(null, "Sesión cerrada");
  	}
  }
  ```
  
  
  
  **Rutas** en `routes/api.php`:
  
  ```php
  Route::get('/health', fn() => ['ok' => true])->withoutMiddleware(['auth:api','role']); // o 'role' si se agrego directo en  $middleware->appendToGroup
  //->withoutMiddleware(['auth:api', RoleMiddleware::class]); Si no se creo el alias
  
  Route::prefix('posts')->group(function () {
      Route::middleware(['throttle:api', 'auth:api', 'role:viewer,editor,admin'])->group(function () {
          Route::get('/', [PostController::class, 'index']);
          Route::get('{post}', [PostController::class, 'show']);
      });
  
      // Escritura (editor|admin)
      Route::middleware(['throttle:api', 'auth:api', 'role:admin,editor'])->group(function () {
          Route::post('/', [PostController::class, 'store']);
          Route::put('{post}', [PostController::class, 'update']);
          Route::delete('{post}', [PostController::class, 'destroy']);
          Route::post('{post}/restore', [PostController::class, 'restore']) // soft-deletes
              // Si usas scopes de Passport:
              ->middleware('scopes:posts.write');
      });
  });
  
  Route::prefix('auth')->group(function () {
      Route::post('login',  [AuthController::class, 'login']);
      Route::post('signup', [AuthController::class, 'register']);
  
      Route::middleware('auth:api')->group(function () {
          Route::get('me',     [AuthController::class, 'me']);
          Route::post('logout', [AuthController::class, 'logout']);
      });
  });
  ```
  
  **7.2.1.3 Roles mínimos (migraciones + seeder)**
  
  Creación del modelo de roles y su migración:
  
  ```bash
  php artisan make:model Role -m //Ya incluye migración
  ```
  
  Ejemplo de implmentación de migración:
  
  ```php
  <?php
  
  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;
  
  return new class extends Migration
  {
      public function up(): void
      {
          Schema::create('roles', function (Blueprint $table) {
              $table->id();
              $table->string('name')->unique(); // admin, editor, viewer
              $table->string('label')->nullable();
              $table->timestamps();
          });
          Schema::create('role_user', function (Blueprint $table) {
              $table->id();
              $table->foreignId('role_id')->constrained()->cascadeOnDelete();
              $table->foreignId('user_id')->constrained()->cascadeOnDelete();
              $table->unique(['role_id', 'user_id']);
  	          $table->timestamps();
          });
      }
  
      public function down(): void
      {
          Schema::dropIfExists('role_user');
          Schema::dropIfExists('roles');
      }
  };
  ```
  
  Ejemplo de implementación del modelo:
  
  ```php
  <?php
  
  namespace App\Models;
  
  use Illuminate\Database\Eloquent\Model;
  
  class Role extends Model
  {
      protected $fillable = ['name', 'label'];
      public function users()
      {
          return $this->belongsToMany(User::class, 'role_user');
      }
  }
  
  ```
  
  Ejemplo de relaciones para las clases:
  ```php
  //User.php
  public function roles()
  {
      return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
  }
  
  public function hasRole(string|array $roles): bool
  {
      $names = is_array($roles) ? $roles : [$roles];
      return $this->roles()->whereIn('name', $names)->exists();
  }
  ```
  
  
  
  Creación del Seeder para los roles:
  
  ```bash
  php artisan make:seeder RoleSeeder
  ```
  
  Ejemplo de implementación del Seeder:
  
  ```php
  <?php
  
  namespace Database\Seeders;
  
  use Illuminate\Database\Console\Seeds\WithoutModelEvents;
  use Illuminate\Database\Seeder;
  
  class RoleSeeder extends Seeder
  {
      public function run(): void
      {
          Role::firstOrCreate(['name' => 'viewer'], ['label' => 'Lector']);
          Role::firstOrCreate(['name' => 'editor'], ['label' => 'Editor']);
          Role::firstOrCreate(['name' => 'admin'],  ['label' => 'Administrador']);
      }
  }
  
  ```
  
  > **Nota:** Validar el estado de las migraciones 
  >
  > ```bash
  >  php artisan migrate
  > ```
  
  
  
  Ejemplo de ejecución del Seeder:
  
  ```bash
  php artisan db:seed --class=RoleSeeder
  ```
  
  **7.2.1.4 Middleware `role` para proteger rutas por rol**
  
  ```bash
  php artisan make:middleware RoleMiddleware
  ```
  
  Ejemplo de implementación de middleware:
  ```php
  <?php
  
  namespace App\Http\Middleware;
  
  use Closure;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Log;
  use Symfony\Component\HttpFoundation\Response;
  
  class RoleMiddleware
  {
      /**
       * Handle an incoming request.
       *
       * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
       */
      public function handle(Request $request, Closure $next, ...$roles): Response
      {
          try {
              $user = $request->user();
  
              // Verificar si el usuario está autenticado
              if (!$user) {
                  Log::warning('RoleMiddleware: Usuario no autenticado intentando acceder a ruta protegida', [
                      'route' => $request->path(),
                      'method' => $request->method()
                  ]);
  
                  return response()->json([
                      'status' => 'error',
                      'message' => 'Usuario no autenticado'
                  ], 401);
              }
  
              // Verificar si se proporcionaron roles
              if (empty($roles)) {
                  Log::error('RoleMiddleware: No se especificaron roles requeridos', [
                      'route' => $request->path(),
                      'user_id' => $user->id
                  ]);
  
                  return response()->json([
                      'status' => 'error',
                      'message' => 'Error de configuración: roles no especificados'
                  ], 500);
              }
  
              // Verificar si el usuario tiene el rol requerido
              if (!$user->hasRole($roles)) {
                  Log::warning('RoleMiddleware: Usuario sin permisos suficientes', [
                      'user_id' => $user->id,
                      'user_email' => $user->email,
                      'required_roles' => $roles,
                      'user_roles' => $user->roles->pluck('name')->toArray(),
                      'route' => $request->path()
                  ]);
  
                  return response()->json([
                      'status' => 'error',
                      'message' => 'Usuario No Autorizado o Sin Rol'
                  ], 403);
              }
  
              Log::info('RoleMiddleware: Acceso autorizado', [
                  'user_id' => $user->id,
                  'required_roles' => $roles,
                  'route' => $request->path()
              ]);
  
              return $next($request);
          } catch (\Throwable $e) {
              Log::error('RoleMiddleware: Error inesperado', [
                  'error' => $e->getMessage(),
                  'file' => $e->getFile(),
                  'line' => $e->getLine(),
                  'route' => $request->path(),
                  'user_id' => $request->user()?->id
              ]);
  
              return response()->json([
                  'status' => 'error',
                  'message' => 'Error interno del servidor en verificación de roles'
              ], 500);
          }
      }
  }
  
  ```
  
  Regístralo en **`bootstrap/app.php`** (grupo `api`):
  
  ```php
  ->withMiddleware(function (Middleware $middleware) {
         $middleware->alias([
              'role' => RoleMiddleware::class,
              'scopes' => \Laravel\Passport\Http\Middleware\CheckToken::class,  // TODOS
              'scope'  => \Laravel\Passport\Http\Middleware\CheckTokenForAnyScope::class, // ALGUNO
          ]);
          //Obliga a toda la api a usar los siguientes:
          $middleware->appendToGroup('api', [
              ForceJsonResponse::class,
              //RoleMiddleware::class,
          ]);
      })
  ```
  
  Ejemplo de uso de middlewares en rutas:
  
  ```php
  <?php
  
  use App\Http\Controllers\AuthController;
  use App\Http\Controllers\BlogController;
  use App\Http\Controllers\PostController;
  use App\Http\Middleware\RoleMiddleware;
  use Illuminate\Support\Facades\Route;
  
  Route::get('/health', fn() => ['ok' => true])->withoutMiddleware(['auth:api', 'role']); // o 'role'
  
  Route::prefix('posts')->group(function () {
      Route::middleware(['throttle:api', 'auth:api', 'role:viewer,editor,admin'])->group(function () {
          Route::get('/', [PostController::class, 'index']);
          Route::get('{post}', [PostController::class, 'show']);
      });
  
      // Escritura (editor|admin)
      Route::middleware(['throttle:api', 'auth:api', 'role:admin,editor'])->group(function () {
          Route::post('/', [PostController::class, 'store']);
          Route::put('{post}', [PostController::class, 'update']);
          Route::delete('{post}', [PostController::class, 'destroy']);
          Route::post('{post}/restore', [PostController::class, 'restore']) // soft-deletes
              // Si usas scopes de Passport:
              ->middleware('scopes:posts.write');
      });
  });
  
  Route::prefix('auth')->group(function () {
      Route::post('login',  [AuthController::class, 'login']);
      Route::post('signup', [AuthController::class, 'register']);
  
      Route::middleware('auth:api')->group(function () {
          Route::get('me',     [AuthController::class, 'me']);
          Route::post('logout', [AuthController::class, 'logout']);
      });
  });
  
  ```
  
  Ejemplo de payload para postman:
  ```json
  {
      "name": "{{$randomFirstName}}",
      "password": "123AbcD#.",
      "password_confirmation": "123AbcD#.",
      "email": "{{$randomEmail}}"
  }
  ```
  
  Generar llaves de cifrado para *passport*:
  
  ```bash
  php artisan passport:keys --force
  ```
  
  Crear el Personal Client con provider `users`:
  
  ```bash
  php artisan passport:client --personal --provider=users
  ```
  
  
  
- **7.2.2 Policies y Gates (control de acceso)**  
  **7.2.2.1** ¿Qué son los *Gates*?
  
  Los *gates* son **funciones simples de autorización** que determinan si un usuario puede realizar una acción específica.
  Son ideales para reglas de autorización **rápidas y directas**, sin necesidad de crear clases adicionales.
  
  **Ejemplo de Gate:**
  
  ```php
  Gate::define('delete-post', function ($user, $post) {
      return $user->id === $post->user_id;
  });
  
  ```
  
  > **Nota**: Aquí se define que solo el dueño de un post puede eliminarlo.Al usar el gate, Laravel devuelve **true** o **false** según la condición.
  
  Ejemplo de implementación de Gate:
  
  ```php
  <?php
  
  namespace App\Providers;
  
  use App\Models\User;
  use Illuminate\Support\Facades\Gate;
  use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
  use Laravel\Passport\Passport;
  
  class AuthServiceProvider extends ServiceProvider
  {
      //Policia de las politicas
      protected $policies = [];
  
      /**
       * Bootstrap services.
       */
      public function boot(): void
      {
          $this->registerPolicies();
  
  
          Gate::define('view-health', function (User $user) {
              return $user->hasRole(['admin', 'editor']);
          });
  
          // Gate combinada con SCOPES de Passport (requiere al menos un scope)
          Gate::define('view-health-scope', function (User $user) {
              return $user->hasRole('admin') || $user->tokenCan('posts.admin');
          });
  
          // "before" global: super admin todo lo puede
        	// NO Recomendado
          Gate::before(function (User $user, string $ability) {
              return $user->hasRole('admin') ? true : null; // true = concede cualquier ability
          });
  
          //Definir Tokens
  				//....
      }
  }
  
  ```
  
  Ejemplo de implementación en rutas del api:
  
  ```php
  Route::get('/health', fn() => ['ok' => true]);
  Route::get('/health-any-auth', fn() => ['ok' => true])->middleware(['auth:api', 'can:view-health']);
  Route::get('/health-admin', fn() => ['ok' => true])->middleware(['auth:api', 'can:view-health-scope']);
  ```
  
  > **Nota**: **Orden de middlewares**: primero `auth:api`, luego `can:*` / `scopes`. Sin token, no hay autorización.
  
  **7.2.2.2** ¿Qué son las *Policies*?
  
  Las *policies* son **clases dedicadas a manejar la autorización** de un modelo o recurso específico.
  Son útiles cuando las reglas de autorización crecen y es necesario organizarlas de forma estructurada.
  
  ```bash
  php artisan make:policy PostPolicy --model=Post
  ```
  
  Ejemplo de implementación de politicas:
  
  ```php
  public function update(User $user, Post $post)
  {
      return $user->id === $post->user_id;
  }
  ```
  
  > **Nota**: Aquí se define que solo el autor del post puede actualizarlo.
  
  Ejemplo de implementación en el controlador:
  
  ```php
  $this->authorize('update', $post);
  ```
  
  Registro de la Policy en el provedor de `AuthServiceProvider`:
  ```php
  <?php
  
  namespace App\Providers;
  
  use App\Models\User;
  use App\Policies\PostPolicy;
  use Illuminate\Support\Facades\Gate;
  use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
  use Laravel\Passport\Passport;
  
  class AuthServiceProvider extends ServiceProvider
  {
      //Policia de las politicas
      protected $policies = [
          PostPolicy::class,
      ];
  		//....
  }
  ```
  
  Ejemplo de implementación de la policy en el api:
  ```php
  Route::put('{post}', [PostController::class, 'update'])->middleware(['scopes:posts.write', 'can:update,post']); //Se utiliza can:action,model
  ```
  
  Agregar la migración de la clave foránea de `post`:
  
  ```bash
  php artisan make:migration add_user_id_to_posts_table --table=posts
  ```
  
  Ejemplo de implementación de migración:
  
  ```php
  <?php
  
  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;
  
  return new class extends Migration
  {
      public function up(): void
      {
          Schema::table('posts', function (Blueprint $table) {
              $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
          });
      }
  
      public function down(): void
      {
          Schema::table('posts', function (Blueprint $table) {
              $table->dropForeign(['user_id']);
              $table->dropColumn('user_id');
          });
      }
  };
  
  ```
  
  Ejemplo de implementación del modelo `Post`:
  
  ```php
  <?php
  
  namespace App\Models;
  
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use App\Models\Category;
  use Illuminate\Database\Eloquent\SoftDeletes;
  
  class Post extends Model
  {
      use HasFactory, SoftDeletes;
      protected $table = "posts";
  
      protected $fillable = [
          'title',
          'content',
          'slug',
          'status',
          'published_at',
          'cover_image',
          'tags',
          'meta',
          'user_id'
      ];
      //....
      // Un post pertenece a un usuario
      public function user()
      {
          return $this->belongsTo(User::class);
      }
  }
  ```
  
  Ejemplo de implementación del modelo `User`:
  
  ```php
  <?php
  
  namespace App\Models;
  
  // use Illuminate\Contracts\Auth\MustVerifyEmail;
  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Foundation\Auth\User as Authenticatable;
  use Illuminate\Notifications\Notifiable;
  use Laravel\Passport\HasApiTokens;
  
  class User extends Authenticatable
  {
     	//....
  
      public function posts()
      {
          return $this->hasMany(Post::class);
      }
  }
  
  ```
  
  Ejemplo de implementación de controlador:
  ```php
  <?php
  
  namespace App\Http\Controllers;
  
  use App\Http\Requests\StorePostRequest;
  use App\Http\Requests\UpdatePostRequest;
  use App\Http\Resources\PostResource;
  use App\Models\Post;
  use App\Traits\ApiResponse;
  use Exception;
  use Illuminate\Database\Eloquent\ModelNotFoundException;
  use Illuminate\Database\RecordsNotFoundException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\Log;
  use Illuminate\Support\Facades\Storage;
  
  class PostController extends Controller
  {
      use ApiResponse;
      /**
       * Display a listing of the resource.
       */
      public function index(): JsonResponse
      {
          $posts = Post::with('user', 'categories')->get();
          return $this->success(PostResource::collection($posts));
      }
  
      /**
       * Store a newly created resource in storage.
       */
      public function store(StorePostRequest $request): JsonResponse
      {
          $data = $request->validated();
          // Nunca se acepta user_id desde el Request: Siempre se toma del token
          $data['user_id'] = $request->user()->id;
  
          if ($request->hasFile('cover_image')) {
              $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
          }
  
          $newPost = Post::create($data);
  
          if (!empty($data['category_ids'])) {
              $newPost->categories()->syncWithoutDetaching($data['category_ids']);
          }
          // Cargamos relaciones para la respuesta
          $newPost->load(['user', 'categories']);
          return $this->success(new PostResource($newPost), 'Post creado correctamente', 201);
      }
  
      /**
       * Display the specified resource.
       */
      public function show(Post $post): JsonResponse
      {
          // Cargamos relaciones para la respuesta
          $post->load(['user', 'categories']);
          return $this->success(new PostResource($post));
      }
  
      /**
       * Update the specified resource in storage.
       */
      public function update(UpdatePostRequest $request, Post $post)
      {
          $data = $request->validated();
  
          if ($request->hasFile('cover_image')) {
              if ($post->cover_image) {
                  Storage::disk('public')->delete($post->cover_image);
              }
              $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
          }
          Log::debug('Post data en update', ['data' => $data]);
          Log::debug('Post binding en update', ['post_id' => $post->id]);
  
          $post->update($data);
          $post->refresh();
          // Cargamos relaciones para la respuesta
          if (array_key_exists('category_ids', $data)) {
              $post->categories()->sync($data['category_ids'] ?? []);
          }
  
          $post->load(['user', 'categories']);
  
          return $this->success(new PostResource($post));
      }
  
      /**
       * Remove the specified resource from storage.
       */
      public function destroy(Post $post): JsonResponse
      {
          $post->delete(); //Soft delete
          return $this->success(null, 'Post eliminado', 204);
      }
  
      public function restore(int $id): JsonResponse
      {
          $post = Post::onlyTrashed()->find($id);
  
          if (!$post) {
              return $this->error("Post no encontrado", 404, ['id' => 'No se encontró el recurso con el id']);
          }
  
          $post->restore();
          // Cargamos relaciones para la respuesta
          $post->load(['user', 'categories']);
          return $this->success($post, 'Post restaurado correctamente');
      }
  }
  
  ```
  
  Ejemplo de implementación de post resource:
  ```php
  <?php
  
  namespace App\Http\Resources;
  
  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;
  
  class PostResource extends JsonResource
  {
      public function toArray(Request $request): array
      {
          return [
              'id' => $this->id,
              'title' => $this->title,
              'content' => $this->content,
              'slug' => $this->slug,
              'status' => $this->status,
              'cover_image' => $this->cover_image,
              // Relación con usuario
              'user' => $this->whenLoaded('user', function () {
                  return [
                      'id'    => $this->user->id,
                      'name'  => $this->user->name,
                      'email' => $this->user->email,
                  ];
              }),
              'categories' => $this->categories->map(function ($category) {
                  return [
                      'id' => $category->id,
                      'name' => $category->name,
                  ];
              }),
              'tags' => $this->tags,
              'meta' => $this->meta,
              'published_at' => $this->published_at,
          ];
      }
  }
  
  ```
  
  

---

## 8. Notificaciones en Laravel

### 8.3 Notificaciones y correos

- **8.3.1 Mailables (`make:mail`)** 
  
  Un **Mailable** es una **clase de Laravel que representa un correo electrónico**.
  Permite **definir, construir y enviar emails** de manera organizada y reutilizable, usando plantillas Blade o datos dinámicos.
  
  Características:
  
  - Dentro de la clase puedes definir el **asunto, remitente, destinatario y vista** del correo.
  
  - Los Mailables aceptan datos para personalizar el contenido.
  
  **8.3.1.1** ¿Qué son las *Queues* en Laravel?
  
  Las **Queues (colas)** permiten **ejecutar tareas en segundo plano**, mejorando la **velocidad y rendimiento** de la aplicación.
  En lugar de procesar tareas pesadas en la misma petición (ej. enviar un correo, generar un PDF, procesar una imagen), se encolan y ejecutan después, de forma asíncrona.
  
  Se usan para **mejorar la experiencia del usuario** y **desacoplar procesos pesados** como:
  
  - Envío masivo de correos.
  - Procesamiento de imágenes o videos.
  - Generación de reportes.
  - Integración con APIs externas.
  
  **8.3.1.2** Configuración de variables de entorno
  
  Configuracion de variables de entorno `.env` :
  
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=mailpit
  MAIL_PORT=1025
  MAIL_USERNAME=
  MAIL_PASSWORD=
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=no-reply@tudominio.test
  MAIL_FROM_NAME="${APP_NAME}"
  ```
  
  >  Tip local: también puedes usar **Mailpit** (Docker) y apuntar `MAIL_HOST=mailpit` y `MAIL_PORT=1025`.
  
  **8.3.1.3** Crear un Mailable con Markdown
  
  ```php
  php artisan make:mail UserRegisteredMail --markdown=mail.user.registered
  ```
  
  Esto creara dos archivos:
  
  - `app/Mail/UserRegisteredMail.php`
  - `resources/views/mail/user/registered.blade.php`
  
  Ejemplo de implementación de Mailable:
  ```php
  <?php
  
  namespace App\Mail;
  
  use App\Models\User;
  use Illuminate\Bus\Queueable;
  use Illuminate\Contracts\Queue\ShouldQueue;
  use Illuminate\Mail\Mailable;
  use Illuminate\Mail\Mailables\Content;
  use Illuminate\Mail\Mailables\Envelope;
  use Illuminate\Queue\SerializesModels;
  
  class UserRegisteredMail extends Mailable implements ShouldQueue
  {
      use Queueable, SerializesModels;
  
      /**
       * Create a new message instance.
       */
      public function __construct(public User $user)
      {
          //
      }
  
      /**
       * Get the message envelope.
       */
      public function envelope(): Envelope
      {
          return new Envelope(
              subject: 'Bienvenido a la aplicación 🚀',
          );
      }
  
      /**
       * Get the message content definition.
       */
      public function content(): Content
      {
          return new Content(
              markdown: 'mail.user.registered',
          );
      }
  
      /**
       * Get the attachments for the message.
       *
       * @return array<int, \Illuminate\Mail\Mailables\Attachment>
       */
      public function attachments(): array
      {
          return [];
      }
  }
  
  ```
  
  Ejemplo de implementación del template con Blade:
  
  ```php
  @component('mail::message')
  # ¡Hola, {{ $user->name }}!
  
  Tu registro fue exitoso. Ya puedes autenticarte y usar la API.
  
  @component('mail::button', ['url' => config('app.url')])
  Ir a la App
  @endcomponent
  
  Gracias,<br>
  {{ config('app.name') }}
  @endcomponent
  
  ```
  
  Ejemplo de implementación en el controlador:
  ```php
  <?php
  
  namespace App\Http\Controllers;
  
  use App\Mail\UserRegisteredMail;
  use App\Models\Role;
  use App\Models\User;
  use App\Traits\ApiResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Support\Facades\Mail;
  
  class AuthController extends Controller
  {
      use ApiResponse;
  
      //...
  
      function signup(Request $request)
      {
          $data = $request->validate([
              'name' => 'required|string|max:255',
              'email' => 'required|string|email|max:255|unique:users',
              'password' => 'required|string|min:8|confirmed',
          ]);
  
          $user = User::create([
              'name' => $data['name'],
              'email' => $data['email'],
              'password' => Hash::make($data['password']),
          ]);
  
          $defaultRole = Role::where('name', 'viewer')->first();
          if ($defaultRole) {
              $user->roles()->syncWithoutDetaching([$defaultRole->id]);
          }
          // Enviar correo (en cola si tu Mailable implementa ShouldQueue)
          Mail::to($user->email)->queue(new UserRegisteredMail($user));
          return $this->success($user->load('roles'), 'Usuario creado correctamente', 201);
      }
  
      //...
  }
  
  ```
  
  **8.3.1.4** Activar colas para correos
  
  ```bash
  php artisan queue:work
  ```
  
  **8.3.1.5** Visualizar los correos con `Mailpit`
  
  **Mailpit** es una herramienta ligera de **captura y prueba de correos electrónicos en entornos de desarrollo**.
  En lugar de enviar los correos reales a los destinatarios, los **intercepta y los muestra en una interfaz web local**.
  
  Ingrese a la siguiente URL según las configuraciónes con Docker:
  
  ```text
  http://localhost:8025
  ```
  
  **8.3.1.6** Ejemplo de Notificaciones cuando se crea un nuevo post
  
  ```bash
  php artisan make:mail PostCreatedMail --markdown=mail.post.created
  ```
  
  Ejemplo de implementación de Mailable:
  
  ```php
  <?php
  
  namespace App\Mail;
  
  use App\Models\Post;
  use Illuminate\Bus\Queueable;
  use Illuminate\Contracts\Queue\ShouldQueue;
  use Illuminate\Mail\Mailable;
  use Illuminate\Mail\Mailables\Content;
  use Illuminate\Mail\Mailables\Envelope;
  use Illuminate\Queue\SerializesModels;
  use Illuminate\Mail\Mailables\Attachment;
  use Illuminate\Support\Facades\Storage;
  
  class PostCreatedMail extends Mailable implements ShouldQueue
  {
      use Queueable, SerializesModels;
  
      public function __construct(public Post $post) {}
  
      public function envelope(): Envelope
      {
          return new Envelope(
              subject: 'Nueva publicación creada: ' . $this->post->title,
          );
      }
  
      /**
       * Get the message content definition.
       */
      public function content(): Content
      {
          return new Content(
              markdown: 'mail.post.created',
              with: [
                  'post' => $this->post,
                  'author' => $this->post->user?->name,
                  'published_at' => $this->post->published_at?->format('d/m/Y H:i'),
              ],
          );
      }
  
      /**
       * Adjuntar la imagen de portada (cover_image)
       *
       * @return array<int, \Illuminate\Mail\Mailables\Attachment>
       */
      public function attachments(): array
      {
          $attachments = [];
  
          if ($this->post->cover_image && Storage::disk('public')->exists($this->post->cover_image)) {
              $attachments[] = Attachment::fromPath(
                  Storage::disk('public')->path($this->post->cover_image)
              )->as('cover_' . $this->post->id . '.' . pathinfo($this->post->cover_image, PATHINFO_EXTENSION));
          }
  
          return $attachments;
      }
  }
  
  ```
  
  Ejemplo de implementación de plantilla con Blade:
  
  ```php
  @component('mail::message')
  # Nueva publicación creada 🚀
  
  **Título:** {{ $post->title }}
  
  **Autor:** {{ $author ?? 'Desconocido' }}
  
  **Fecha de publicación:** {{ $published_at ?? 'No definida' }}
  
  ---
  
  {{ Str::limit($post->content, 200) }}
  
  @component('mail::button', ['url' => url('/posts/' . $post->id)])
  Ver publicación completa
  @endcomponent
  
  Gracias,<br>
  {{ config('app.name') }}
  @endcomponent
  ```
  
  Ejemplo de implementación de plantilla Blade con Imagen Enbebida:
  ```php
  @php
      use Illuminate\Support\Facades\Storage;
      use Illuminate\Support\Facades\File;
  
      $cid = null;
      if (!empty($post->cover_image)) {
          $absPath = Storage::disk('public')->path($post->cover_image);
          if (File::exists($absPath)) {
              // $message está disponible en las vistas de Mailables
              $cid = $message->embed($absPath);
          }
      }
  @endphp
  
  @component('mail::message')
  # Nueva publicación creada 🚀
  
  @if($cid)
  <p style="text-align:center;margin: 0 0 16px;">
      <img src="{{ $cid }}" alt="Portada del post" style="max-width:100%;height:auto;border-radius:8px;">
  </p>
  @endif
  
  **Título:** {{ $post->title }}  
  **Autor:** {{ $author ?? 'Desconocido' }}  
  **Fecha de publicación:** {{ $published_at ?? 'No definida' }}
  
  ---
  
  {{ Str::limit($post->content, 240) }}
  
  @component('mail::button', ['url' => url('/posts/' . $post->slug)])
  Ver publicación completa
  @endcomponent
  
  Gracias,<br>
  {{ config('app.name') }}
  @endcomponent
  
  ```
  
  
  
  Ejemplo de implementación del controlador:
  
  ```php
  <?php
  
  namespace App\Http\Controllers;
  
  use App\Http\Requests\StorePostRequest;
  use App\Http\Requests\UpdatePostRequest;
  use App\Http\Resources\PostResource;
  use App\Models\Post;
  use App\Traits\ApiResponse;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\Log;
  use Illuminate\Support\Facades\Storage;
  use App\Mail\PostCreatedMail;
  use Illuminate\Support\Facades\Mail;
  
  class PostController extends Controller
  {
      use ApiResponse;
  		//...
  
      /**
       * Store a newly created resource in storage.
       */
      public function store(StorePostRequest $request): JsonResponse
      {
          $data = $request->validated();
          // Nunca se acepta user_id desde el Request: Siempre se toma del token
          $data['user_id'] = $request->user()->id;
  
          if ($request->hasFile('cover_image')) {
              $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
          }
  
          $newPost = Post::create($data);
  
          if (!empty($data['category_ids'])) {
              $newPost->categories()->syncWithoutDetaching($data['category_ids']);
          }
        	//Notificación por Email
          Mail::to('admin@tusitio.com')->queue(new PostCreatedMail($newPost));
          $newPost->load(['user', 'categories']);
          return $this->success(new PostResource($newPost), 'Post creado correctamente', 201);
      }   
  
    	//...
  }
  
  ```
  
  

---

## 9. Testing y calidad de código

### 9.1 Testing con PHPUnit y Pest

- **9.1.1** ¿Qué es **PHPUnit** en Laravel?

  **PHPUnit** es el **framework de pruebas unitarias más usado en PHP** y viene integrado por defecto en Laravel.
  ​Se utiliza para **escribir y ejecutar pruebas automatizadas** que validan que el código funciona como se espera.

  **Características**

  - Permite pruebas **unitarias** (funciones o clases individuales).

  - Soporta pruebas de **integración y funcionales** (interacción entre componentes).


  - Usa una **sintaxis orientada a clases y métodos**.


  - Genera reportes detallados de éxito o fallo.

  ```php
  public function test_homepage_returns_successful_response()
  {
      $response = $this->get('/');
  
      $response->assertStatus(200);
  }
  ```

  

- **9.1.2** ¿Qué es **Pest** en Laravel?

  **Pest** es un **framework de testing moderno y minimalista** para PHP que se integra perfectamente con Laravel.
  Está construido sobre PHPUnit, pero ofrece una **sintaxis más simple, expresiva y legible**.

  **Características**

  - Sintaxis más **concisa y limpia**.

  - Soporta pruebas unitarias, de integración y de snapshots.

  - Incluye **plugins y extensiones** para mejorar la experiencia.

  - Se enfoca en ser **fácil de leer y mantener**.

  ```php
  test('la página de inicio carga correctamente', function () {
      $response = $this->get('/');
  
      $response->assertStatus(200);
  });
  
  ```

- **9.1.3 Configuración inicial** 
  
  - **9.1.3.1** Configuración básica para `phpunit.xml`:
  
    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
             bootstrap="vendor/autoload.php"
             colors="true"
    >
        <testsuites>
            <testsuite name="Unit">
                <directory>tests/Unit</directory>
            </testsuite>
            <testsuite name="Feature">
                <directory>tests/Feature</directory>
            </testsuite>
        </testsuites>
        <source>
            <include>
                <directory>app</directory>
            </include>
        </source>
        <php>
            <env name="APP_ENV" value="testing"/>
            <env name="APP_MAINTENANCE_DRIVER" value="file"/>
            <env name="BCRYPT_ROUNDS" value="4"/>
            <env name="CACHE_STORE" value="array"/>
            <!-- DB en memoria -->
            <env name="DB_CONNECTION" value="sqlite"/>
            <env name="DB_DATABASE" value=":memory:"/>
            <!-- Mail a array para no enviar correos reales -->
            <env name="MAIL_MAILER" value="array"/>
            <env name="PULSE_ENABLED" value="false"/>
            <env name="QUEUE_CONNECTION" value="sync"/>
            <env name="SESSION_DRIVER" value="array"/>
            <env name="TELESCOPE_ENABLED" value="false"/>
           <!-- Passports Env -->
          	<env name="PASSPORT_PRIVATE_KEY" value=""/>
            <env name="PASSPORT_PUBLIC_KEY" value=""/>
            <env name="PASSPORT_LOAD_KEYS" value="true"/>
        </php>
    </phpunit>
    
    ```
  
    > En los tests usa `use Illuminate\Foundation\Testing\RefreshDatabase;` para migrar y limpiar DB automáticamente.
  
  - **9.1.3.2** **Alistamiento de los Helpers o Factories para pruebas**
  
    **UserFactory**
  
    ```php
    <?php
    
    namespace Database\Factories;
    
    use App\Models\Role;
    use App\Models\User;
    use Illuminate\Database\Eloquent\Factories\Factory;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Str;
    
    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
     */
    class UserFactory extends Factory
    {
        protected $model = User::class;
    
        /**
         * The current password being used by the factory.
         */
        protected static ?string $password;
    
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition(): array
        {
            return [
                'name'              => fake()->name(),
                'email'             => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password'          => static::$password ??= Hash::make('password'),
                'remember_token'    => Str::random(10),
            ];
        }
    
        /**
         * Estado: email sin verificar.
         */
        public function unverified(): static
        {
            return $this->state(fn(array $attributes) => [
                'email_verified_at' => null,
            ]);
        }
    
        /**
         * Estado: con un rol asignado.
         * Crea o usa un rol existente.
         */
        public function withRole(string $roleName = 'user'): static
        {
            return $this->afterCreating(function (User $user) use ($roleName) {
                $role = Role::firstOrCreate(['name' => $roleName]);
                $user->roles()->attach($role->id);
            });
        }
    
        /**
         * Estado: con múltiples roles.
         */
        public function withRoles(array $roleNames = ['user']): static
        {
            return $this->afterCreating(function (User $user) use ($roleNames) {
                foreach ($roleNames as $name) {
                    $role = Role::firstOrCreate(['name' => $name]);
                    $user->roles()->attach($role->id);
                }
            });
        }
    
        /**
         * Estado: con posts asociados.
         */
        public function withPosts(int $count = 3): static
        {
            return $this->has(\App\Models\Post::factory()->count($count), 'posts');
        }
    }
    
    ```
  
    **RoleFactory**
  
    ```bash
    php artisan make:factory RoleFactory --model=Role
    ```
  
    ```php
    <?php
    
    namespace Database\Factories;
    
    use App\Models\Role;
    use Illuminate\Database\Eloquent\Factories\Factory;
    
    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
     */
    class RoleFactory extends Factory
    {
        protected $model = Role::class;
    
        public function definition(): array
        {
            return [
                'name'  => fake()->unique()->randomElement([
                    'admin',
                    'editor',
                    'user',
                    'moderator',
                ]),
                'label' => fake()->sentence(3), // "Administrador del sistema"
            ];
        }
    }
    
    ```
  
    **CategoryFactory**
  
    ```bash
    php artisan make:factory CategoryFactory --model=Category
    ```
  
    ```php
    <?php
    
    namespace Database\Factories;
    
    use App\Models\Category;
    use Illuminate\Database\Eloquent\Factories\Factory;
    use Illuminate\Support\Str;
    
    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
     */
    class CategoryFactory extends Factory
    {
        protected $model = Category::class;
    
        public function definition(): array
        {
            $name = fake()->unique()->words(2, true);
    
            return [
                'name' => ucfirst($name),
                'slug' => Str::slug($name),
            ];
        }
    }
    
    ```
  
    **PostFactory**
  
    ```bash
    php artisan make:factory PostFactory --model=Post
    ```
  
    ```php
    <?php
    
    namespace Database\Factories;
    
    use App\Models\Post;
    use App\Models\User;
    use Illuminate\Database\Eloquent\Factories\Factory;
    use Illuminate\Support\Str;
    
    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
     */
    class PostFactory extends Factory
    {
        protected $model = Post::class;
    
        public function definition(): array
        {
            $title = fake()->unique()->sentence(6);
    
            return [
                'title'        => $title,
                'content'      => fake()->paragraphs(3, true),
                'slug'         => Str::slug($title) . '-' . fake()->unique()->numberBetween(1, 9999),
                'status'       => fake()->randomElement(['draft', 'published', 'archived']),
                'published_at' => now()->subDays(rand(0, 365)),
                'cover_image'  => fake()->imageUrl(800, 600, 'posts', true),
                'tags'         => fake()->randomElements(
                    ['laravel', 'php', 'backend', 'eloquent', 'testing', 'api'],
                    rand(1, 3)
                ),
                'meta' => [
                    'seo_title' => fake()->sentence(4),
                    'seo_desc'  => fake()->sentence(8),
                ],
                'user_id' => User::factory(), // Asigna automáticamente un usuario
            ];
        }
        /**
         * Estado: publicado.
         */
        public function published(): static
        {
            return $this->state(fn() => [
                'status' => 'published',
                'published_at' => now(),
            ]);
        }
    
        /**
         * Estado: borrador.
         */
        public function draft(): static
        {
            return $this->state(fn() => [
                'status' => 'draft',
                'published_at' => null,
            ]);
        }
    }
    
    ```
  
  - **9.1.3.3 Crear el test con Artisan con PHPUnit**
  
    ```bash
    php artisan make:test CreatesUsersTest
    ```
  
    Esto creará el archivo:
  
    ```text
    tests/Unit/CreatesUsersTest.php
    ```
  
    Prueba o ejecuta los test usando:
    ```bash
    php artisan test
    ```
  
    Ejemplo de implementación de test para `User`:
    ```php
    <?php
    
    namespace Tests\Unit;
    
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;
    
    
    class CreatesUsersTest extends TestCase
    {
    
        use RefreshDatabase;  // Reinicia la BD en cada test
    
        public function test_puede_crear_un_usuario()
        {
            $user = User::factory()->create([
                'name' => 'Adrian',
                'email' => 'adrian@example.com',
            ]);
    
            // Verificar que se creó en la base de datos
            $this->assertDatabaseHas('users', [
                'email' => 'adrian@example.com',
            ]);
    
            // Verificar que el nombre corresponde
            $this->assertEquals('Adrian', $user->name);
        }
    
        public function test_el_usuario_tiene_un_email_valido(): void
        {
            $user = User::factory()->create();
    
            $this->assertNotNull($user->email);
            $this->assertStringContainsString('@', $user->email);
        }
    }
    
    ```
  
    Ejecutar pruebas:
  
    ```bash
    php artisan test
    ```
  
    
  
    ****
  
- **9.1.4 Pruebas unitarias con Eloquent**
  
  **PostTest.php**
  
  ```php
  <?php
  
  namespace Tests\Feature\Models;
  
  use App\Models\Post;
  use App\Models\User;
  use App\Models\Category;
  use App\Models\CategoryPost;
  use Illuminate\Database\QueryException;
  use Illuminate\Foundation\Testing\RefreshDatabase;
  use Illuminate\Support\Carbon;
  use Illuminate\Support\Str;
  use Tests\TestCase;
  
  class PostTest extends TestCase
  {
      use RefreshDatabase;
  
      public function test_factory_crea_post_valido_y_casts_funcionan(): void
      {
          $tags = ['laravel', 'php'];
          $meta = ['seo_title' => 't', 'seo_desc' => 'd'];
  
          $post = Post::factory()->create([
              'tags' => $tags,
              'meta' => $meta,
          ]);
  
          $this->assertDatabaseHas('posts', ['id' => $post->id]);
          $this->assertIsArray($post->tags);
          $this->assertSame($tags, $post->tags);
          $this->assertIsArray($post->meta);
          $this->assertSame($meta, $post->meta);
          $this->assertInstanceOf(\Carbon\CarbonInterface::class, $post->created_at);
      }
  
      public function test_post_belongs_to_user(): void
      {
          $post = Post::factory()->create(); // User::factory() viene en PostFactory
          $this->assertInstanceOf(User::class, $post->user);
          $this->assertEquals($post->user_id, $post->user->id);
      }
  
      public function test_post_categories_many_to_many_con_pivot_y_timestamps(): void
      {
          $post = Post::factory()->create();
          $category = Category::factory()->create();
  
          // ATTACH desde Category->posts() porque Category::posts() tiene withTimestamps() garantizado
          $category->posts()->attach($post->id);
  
          $post->refresh();
          $this->assertCount(1, $post->categories);
          $this->assertInstanceOf(Category::class, $post->categories->first());
          $this->assertInstanceOf(CategoryPost::class, $post->categories->first()->pivot);
          $this->assertNotNull($post->categories->first()->pivot->created_at);
          $this->assertNotNull($post->categories->first()->pivot->updated_at);
      }
  
      public function test_soft_delete_y_restore(): void
      {
          $post = Post::factory()->create();
          $post->delete();
  
          $this->assertSoftDeleted('posts', ['id' => $post->id]);
          $this->assertNull(Post::find($post->id));
  
          $restored = Post::onlyTrashed()->findOrFail($post->id);
          $restored->restore();
  
          $this->assertDatabaseHas('posts', ['id' => $post->id, 'deleted_at' => null]);
      }
  
      public function test_unique_slug_en_bd(): void
      {
          // Si tu migración define unique en slug, este test garantiza la restricción
          Post::factory()->create(['slug' => 'mi-slug-unico']);
  
          $this->expectException(QueryException::class);
          Post::factory()->create(['slug' => 'mi-slug-unico']); // Debe violar unique y lanzar excepción
      }
  
      public function test_estados_de_factory_published_y_draft(): void
      {
          Carbon::setTestNow('2025-09-11 10:00:00');
  
          $published = Post::factory()->published()->create();
          $this->assertEquals('published', $published->status);
          $this->assertNotNull($published->published_at);
          $this->assertTrue($published->published_at->lessThanOrEqualTo(now()));
  
          $draft = Post::factory()->draft()->create();
          $this->assertEquals('draft', $draft->status);
          $this->assertNull($draft->published_at);
  
          Carbon::setTestNow(); // reset
      }
  
      public function test_tags_y_meta_persisten_y_se_recuperan_como_array(): void
      {
          $post = Post::factory()->create([
              'tags' => ['testing', 'eloquent'],
              'meta' => ['seo_title' => 'A', 'seo_desc' => 'B'],
          ]);
  
          $fresh = Post::find($post->id);
          $this->assertSame(['testing', 'eloquent'], $fresh->tags);
          $this->assertSame(['seo_title' => 'A', 'seo_desc' => 'B'], $fresh->meta);
      }
  }
  
  ```
  
  **CategoryTest.php**
  
  ```php
  <?php
  
  namespace Tests\Feature\Models;
  
  use App\Models\Category;
  use App\Models\Post;
  use Illuminate\Foundation\Testing\RefreshDatabase;
  use Illuminate\Support\Str;
  use Tests\TestCase;
  
  class CategoryTest extends TestCase
  {
      use RefreshDatabase;
  
      public function test_factory_crea_category_con_slug_valido(): void
      {
          $category = Category::factory()->create();
          $this->assertDatabaseHas('categories', ['id' => $category->id]);
  
          $this->assertEquals(Str::slug($category->name), $category->slug);
      }
  
      public function test_category_posts_many_to_many(): void
      {
          $category = Category::factory()->create();
          $post = Post::factory()->create();
  
          $category->posts()->attach($post->id);
  
          $this->assertCount(1, $category->posts()->get());
          $this->assertEquals($post->id, $category->posts()->first()->id);
      }
  
      public function test_no_duplicados_en_pivote(): void
      {
          $category = Category::factory()->create();
          $post = Post::factory()->create();
  
          // Si tu pivote tiene unique([post_id, category_id]), esto se mantiene
          $category->posts()->syncWithoutDetaching([$post->id]);
          $category->posts()->syncWithoutDetaching([$post->id]);
  
          $this->assertCount(1, $category->posts()->get());
      }
  }
  
  ```
  
  **UserTest.php**
  
  ```php
  <?php
  
  namespace Tests\Feature\Models;
  
  use App\Models\Role;
  use App\Models\User;
  use Illuminate\Foundation\Testing\RefreshDatabase;
  use Illuminate\Support\Facades\Hash;
  use Tests\TestCase;
  
  class UserTest extends TestCase
  {
      use RefreshDatabase;
  
      public function test_factory_por_defecto(): void
      {
          $user = User::factory()->create();
  
          $this->assertNotNull($user->email_verified_at);
          $this->assertTrue(Hash::check('password', $user->password));
          $this->assertDatabaseHas('users', ['id' => $user->id]);
      }
  
      public function test_estado_unverified(): void
      {
          $user = User::factory()->unverified()->create();
  
          $this->assertNull($user->email_verified_at);
      }
  
      public function test_withRole_asigna_rol_existente_o_crea(): void
      {
          $user = User::factory()->withRole('editor')->create();
  
          $this->assertTrue($user->roles()->where('name', 'editor')->exists());
      }
  
      public function test_withRoles_asigna_multiples_roles(): void
      {
          $user = User::factory()->withRoles(['admin', 'moderator'])->create();
  
          $this->assertTrue($user->roles()->where('name', 'admin')->exists());
          $this->assertTrue($user->roles()->where('name', 'moderator')->exists());
          $this->assertEquals(2, $user->roles()->count());
      }
  
      public function test_withPosts_crea_posts_asociados(): void
      {
          $user = User::factory()->withPosts(3)->create();
  
          $this->assertCount(3, $user->posts); // requiere relación User->posts()
          $this->assertDatabaseCount('posts', 3);
          $this->assertTrue($user->posts->every(fn($p) => $p->user_id === $user->id));
      }
  }
  
  ```
  
  **RoleTest.php**
  
  ```php
  <?php
  
  namespace Tests\Feature\Models;
  
  use App\Models\Role;
  use App\Models\User;
  use Illuminate\Foundation\Testing\RefreshDatabase;
  use Tests\TestCase;
  
  class RoleTest extends TestCase
  {
      use RefreshDatabase;
  
      public function test_factory_crea_rol_valido(): void
      {
          $role = Role::factory()->create();
  
          $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => $role->name]);
          $this->assertNotEmpty($role->label);
      }
  
      public function test_roles_y_users_many_to_many(): void
      {
          $role = Role::factory()->create(['name' => 'admin']);
          $user = User::factory()->create();
  
          $user->roles()->attach($role->id);
  
          $this->assertTrue($user->roles()->where('name', 'admin')->exists());
          $this->assertTrue($role->users()->where('users.id', $user->id)->exists()); // requiere Role->users()
      }
  }
  
  ```
  
  **PostConfigTest.php**
  
  ```php
  <?php
  
  namespace Tests\Unit\Models;
  
  use App\Models\Post;
  use PHPUnit\Framework\TestCase;
  
  class PostConfigTest extends TestCase
  {
      public function test_fillable_y_casts_configurados(): void
      {
          $post = new Post;
  
          $this->assertEqualsCanonicalizing(
              ['title','content','slug','status','published_at','cover_image','tags','meta','user_id'],
              $post->getFillable()
          );
  
          $this->assertArrayHasKey('tags', $post->getCasts());
          $this->assertArrayHasKey('meta', $post->getCasts());
          $this->assertSame('array', $post->getCasts()['tags']);
          $this->assertSame('array', $post->getCasts()['meta']);
      }
  }
  
  ```
  
  **9.1.4.1 Assert de PHPUnit / Laravel**
  
  | Método                                   | Pertenece a     | ¿Para qué se usa?                                         | Ejemplo                                                      |
  | ---------------------------------------- | --------------- | --------------------------------------------------------- | ------------------------------------------------------------ |
  | `assertDatabaseHas($tabla, $data)`       | Laravel Testing | Verifica que exista un registro con esos campos en la BD. | `assertDatabaseHas('posts', ['id' => $post->id]);`           |
  | `assertDatabaseCount($tabla, $n)`        | Laravel Testing | Verifica el número de registros en una tabla.             | `assertDatabaseCount('posts', 3);`                           |
  | `assertSoftDeleted($tabla, $data)`       | Laravel Testing | Verifica que el registro esté “soft deleted”.             | `assertSoftDeleted('posts', ['id' => $post->id]);`           |
  | `assertIsArray($valor)`                  | PHPUnit         | Comprueba que el valor sea un arreglo.                    | `assertIsArray($post->tags);`                                |
  | `assertSame($esperado, $actual)`         | PHPUnit         | Igualdad estricta (tipo y valor).                         | `assertSame($tags, $post->tags);`                            |
  | `assertInstanceOf($clase, $obj)`         | PHPUnit         | Comprueba la clase/tipo de un objeto.                     | `assertInstanceOf(User::class, $post->user);`                |
  | `assertEquals($esp, $act)`               | PHPUnit         | Igualdad por valor.                                       | `assertEquals('published', $post->status);`                  |
  | `assertCount($n, $iterable)`             | PHPUnit         | Verifica la cantidad de elementos.                        | `assertCount(1, $post->categories);`                         |
  | `assertNotNull($valor)`                  | PHPUnit         | Verifica que el valor no sea `null`.                      | `assertNotNull($pivot->created_at);`                         |
  | `assertNull($valor)`                     | PHPUnit         | Verifica que el valor sea `null`.                         | `assertNull($draft->published_at);`                          |
  | `assertTrue($condición)`                 | PHPUnit         | Verifica que la condición sea verdadera.                  | `assertTrue($user->roles()->where('name','admin')->exists());` |
  | `assertNotEmpty($valor)`                 | PHPUnit         | Verifica que el valor no esté vacío.                      | `assertNotEmpty($role->label);`                              |
  | `assertEqualsCanonicalizing($esp, $act)` | PHPUnit         | Compara arrays ignorando orden.                           | `assertEqualsCanonicalizing($esperado, $post->getFillable());` |
  | `expectException($ClaseExcepción)`       | PHPUnit         | Espera que se lance una excepción.                        | `expectException(QueryException::class);`                    |
  
  **Utilidades**
  
  | Método/Función                  | Pertenece a                       | ¿Para qué se usa?                           | Ejemplo (resumen)                                |
  | ------------------------------- | --------------------------------- | ------------------------------------------- | ------------------------------------------------ |
  | `Carbon::setTestNow($fecha)`    | Carbon                            | Fijar “ahora” para pruebas determinísticas. | `Carbon::setTestNow('2025-09-11 10:00:00');`     |
  | `Carbon::setTestNow()`          | Carbon                            | Restablecer “ahora” a la hora real.         | `Carbon::setTestNow();`                          |
  | `lessThanOrEqualTo($otraFecha)` | Carbon                            | Compara fechas (≤).                         | `$post->published_at->lessThanOrEqualTo(now());` |
  | `now()`                         | Helper Laravel                    | Obtiene la fecha/hora actual (Carbon).      | `now()->subDays(3);`                             |
  | `Str::slug($texto)`             | `Illuminate\Support\Str`          | Genera un slug normalizado.                 | `Str::slug($category->name);`                    |
  | `Hash::check($plain, $hashed)`  | `Illuminate\Support\Facades\Hash` | Verifica contraseña contra hash.            | `Hash::check('password',$user->password);`       |
  
  **9.1.4.2 Resultado**
  
  ```text
  php artisan test
  
     PASS  Tests\Unit\ExampleTest
    ✓ that true is true
  
     PASS  Tests\Feature\Models\CategoryTest
    ✓ factory crea category con slug valido                                                                                                                           0.15s  
    ✓ category posts many to many                                                                                                                                     0.01s  
    ✓ no duplicados en pivote                                                                                                                                         0.01s  
  
     .........
  
    Tests:    21 passed (54 assertions)
    Duration: 0.30s
  ```
  
  

### 9.2 Testing de APIs

- **9.2.1 HTTP Test (simulación de requests)**
  
  **Api/HealthEndpointsTest**:
  
  ```bash
  php artisan make:test Api/HealthEndpointsTest
  ```
  
  Ejemplo de implementación de test para `Api/HealthEndpointsTest`:
  
  ```php
  <?php
  
  namespace Tests\Feature\Api;
  
  use App\Models\User;
  use Illuminate\Foundation\Testing\RefreshDatabase;
  use Laravel\Passport\Passport;
  use Tests\TestCase;
  
  /**
   * Verifica endpoints de salud y gates con roles/scopes.
   */
  class HealthEndpointsTest extends TestCase
  {
      use RefreshDatabase;
  
      public function test_health_publico_responde_200(): void
      {
          // Ruta pública debe responder siempre en JSON (ForceJsonResponse).
          $this->getJson('/api/health')
              ->assertOk()
              ->assertJson(['ok' => true]);
      }
  }
  
  ```
  
  
  
- **9.2.2 Uso de Factories en pruebas y Assertions en respuestas JSON** 
  
  ```php
  <?php
  
  namespace Tests\Feature\Api;
  
  use App\Models\User;
  use Illuminate\Foundation\Testing\RefreshDatabase;
  use Laravel\Passport\Passport;
  use Tests\TestCase;
  
  /**
   * Verifica endpoints de salud y gates con roles/scopes.
   */
  class HealthEndpointsTest extends TestCase
  {
      use RefreshDatabase;
  
      public function test_health_publico_responde_200(): void
      {
          // Ruta pública debe responder siempre en JSON (ForceJsonResponse).
          $this->getJson('/api/health')
              ->assertOk()
              ->assertJson(['ok' => true]);
      }
  
      public function health_any_auth_requiere_auth_y_gate_view_health(): void
      {
          // Debe exigir autenticación Y pasar Gate 'view-health' (viewer/editor).
          $viewer = \App\Models\User::factory()->withRole('viewer')->create();
  
          Passport::actingAs($viewer, ['posts.read']); // scope por defecto
  
          $this->getJson('/api/health-any-auth')
              ->assertOk()
              ->assertJson(['ok' => true]);
      }
  
  }
  
  ```
  
- **9.2.3** **Testing en nuestra API**
  
  **TestCase**
  
  ```php
  <?php
  
  namespace Tests;
  
  use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
  use Laravel\Passport\Passport;
  
  abstract class TestCase extends BaseTestCase
  {
  
      protected function setUp(): void
      {
          parent::setUp();
  
          // Solo configurar Passport si está siendo usado
          if (class_exists(Passport::class)) {
              $this->setupPassportForTests();
          }
      }
  
      protected function setupPassportForTests(): void
      {
          // Crear claves RSA válidas para testing
          $this->createTestKeys();
  
          // Configurar Passport para usar las claves de test
          Passport::loadKeysFrom($this->getTestKeysPath());
      }
  
      protected function createTestKeys(): void
      {
          $keysPath = $this->getTestKeysPath();
  
          // Crear directorio si no existe
          if (!is_dir($keysPath)) {
              mkdir($keysPath, 0755, true);
          }
  
          $privateKeyPath = $keysPath . '/oauth-private.key';
          $publicKeyPath = $keysPath . '/oauth-public.key';
  
          // Solo crear las claves si no existen
          if (!file_exists($privateKeyPath) || !file_exists($publicKeyPath)) {
              $this->generateRSAKeys($privateKeyPath, $publicKeyPath);
          }
      }
  
      protected function generateRSAKeys(string $privateKeyPath, string $publicKeyPath): void
      {
          // Generar par de claves RSA
          $config = [
              "digest_alg" => "sha512",
              "private_key_bits" => 2048,
              "private_key_type" => OPENSSL_KEYTYPE_RSA,
          ];
  
          $resource = openssl_pkey_new($config);
  
          if (!$resource) {
              // Si falla la generación, usar claves predefinidas
              $this->createPredefinedKeys($privateKeyPath, $publicKeyPath);
              return;
          }
  
          // Exportar clave privada
          openssl_pkey_export($resource, $privateKey);
          file_put_contents($privateKeyPath, $privateKey);
  
          // Exportar clave pública
          $publicKeyDetails = openssl_pkey_get_details($resource);
          file_put_contents($publicKeyPath, $publicKeyDetails['key']);
      }
  
      protected function createPredefinedKeys(string $privateKeyPath, string $publicKeyPath): void
      {
          // Clave privada RSA válida para testing
          $privateKey = <<<'EOD'
  -----BEGIN PRIVATE KEY-----
  MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDGtJCyEz4MXSF2
  tBx4q8QMF0UjPszyIMQvGtiFfaWbHHBvYrNNgLM/Cim7oNRXP3C++QE2YvqTCa/q
  7+C4vFj3bFLNNr5KtNKLCF2QwGqrNNzKKZHKqzrqzrNzKqzrqzrNzKqzrqzrNzKq
  zrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqz
  rqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzr
  qzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrq
  zrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqz
  rNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzKqzrqzrNzAgMBAAE
  CggEBALtJF4rZnKd7pWLEMbFZ1NVsGnNNzLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  wKBgBHQ8UNMdSJiTgXQl4qLF2QQKBgQDcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  cLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLMcLGtzQ2MXqqAOKNKN4tQwXTzZKNMNqLM
  -----END PRIVATE KEY-----
  EOD;
  
          // Clave pública correspondiente
          $publicKey = <<<'EOD'
  -----BEGIN PUBLIC KEY-----
  MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxrSQshM+DF0hdrQceKvE
  DBdFIz7M8iDELxrYhX2lmxxwb2KzTYCzPwqpu6DUVz9wvvkBNmL6kwmv6u/guLxY
  92xSzTa+SrTSiwhDkMBqqzTcyimRyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zc
  yqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcy
  qs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyq
  s66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs6
  6s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6
  zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcyqs66s6zcwIDAQAB
  -----END PUBLIC KEY-----
  EOD;
  
          file_put_contents($privateKeyPath, $privateKey);
          file_put_contents($publicKeyPath, $publicKey);
      }
  
      protected function getTestKeysPath(): string
      {
          return storage_path('testing/oauth-keys');
      }
  
      protected function tearDown(): void
      {
          // Opcional: limpiar las claves después de los tests
          // $this->cleanupTestKeys();
  
          parent::tearDown();
      }
  
      protected function cleanupTestKeys(): void
      {
          $keysPath = $this->getTestKeysPath();
          if (is_dir($keysPath)) {
              array_map('unlink', glob("$keysPath/*"));
              rmdir($keysPath);
          }
      }
  }
  
  ```
  
  
  
  **Api/HealthEndpointsTest**:
  
  ```php
  <?php
  
  namespace Tests\Feature\Api;
  
  use App\Models\User;
  use Illuminate\Foundation\Testing\RefreshDatabase;
  use Laravel\Passport\Passport;
  use Tests\TestCase;
  
  /**
   * Verifica endpoints de salud y gates con roles/scopes.
   */
  class HealthEndpointsTest extends TestCase
  {
      use RefreshDatabase;
  
      protected function setUp(): void
      {
          parent::setUp();
      }
  
      public function test_health_publico_responde_200(): void
      {
          // Ruta pública debe responder siempre en JSON (ForceJsonResponse).
          $this->getJson('/api/health')
              ->assertOk()
              ->assertJson(['ok' => true]);
      }
  
      public function health_any_auth_requiere_auth_y_gate_view_health(): void
      {
          // Debe exigir autenticación Y pasar Gate 'view-health' (viewer/editor).
          $viewer = \App\Models\User::factory()->withRole('viewer')->create();
  
          Passport::actingAs($viewer, ['posts.read']); // scope por defecto
  
          $this->getJson('/api/health-any-auth')
              ->assertOk()
              ->assertJson(['ok' => true]);
      }
  
      public function test_health_any_auth_sin_auth_responde_401(): void
      {
          // Sin token => manejador de excepciones regresa 401 JSON uniforme.
          $this->getJson('/api/health-any-auth')
              ->assertStatus(401)
              ->assertJson([
                  'status'  => 'error',
                  'message' => 'No autenticado.',
              ]);
      }
  
      public function test_health_admin_permiso_por_rol_admin_o_editor_o_scope_posts_admin(): void
      {
          // Gate 'view-health-admin' permite editor/admin o token con scope posts.admin.
          $viewer = User::factory()->withRole('viewer')->create();
  
          // Caso 1: viewer + scope especial posts.admin (debe pasar)
          Passport::actingAs($viewer, ['posts.read', 'posts.admin']);
          $this->getJson('/api/health-admin')->assertOk();
  
          // Caso 2: editor (sin scope especial) también debe pasar
          $editor = User::factory()->withRole('editor')->create();
          Passport::actingAs($editor, ['posts.read']);
          $this->getJson('/api/health-admin')->assertOk();
      }
  
      public function test_health_admin_sin_permisos_responde_403(): void
      {
          // Usuario autenticado pero sin rol/scope requerido => 403 AuthorizationException.
          $viewer = User::factory()->withRole('viewer')->create();
          Passport::actingAs($viewer, ['posts.read']); // sin posts.admin
  
          $this->getJson('/api/health-admin')
              ->assertStatus(403)
              ->assertJson([
                  'status'  => 'error',
                  'message' => 'This action is unauthorized.',
              ]);
      }
  }
  
  ```
  
  
  
  **Api/AuthEndpointsTest**:
  
  ```bash
  php artisan make:test Api/AuthEndpointsTest
  ```
  
  Ejemplo de implementación de test para `Api/AuthEndpointsTest`:
  
  ```php
  <?php
  
  namespace Tests\Feature\Api;
  
  use App\Mail\UserRegisteredMail;
  use App\Models\Role;
  use App\Models\User;
  use Illuminate\Foundation\Testing\RefreshDatabase;
  use Illuminate\Support\Facades\Mail;
  use Laravel\Passport\Client;
  use Tests\TestCase;
  use Illuminate\Support\Str;
  
  class AuthEndpointsTest extends TestCase
  {
      use RefreshDatabase;
  
      protected function setUp(): void
      {
          parent::setUp();
          $this->setupPassportClient();
      }
  
      protected function setupPassportClient(): void
      {
  
          Client::create([
              'name' => 'Laravel' . Str::random(10),
              'secret' => 'test-secret-for-testing',
              'provider' => 'users',
              'redirect_uris' => [],
              'grant_types' => ["personal_access"],
              'revoked' => false,
          ]);
      }
  
      public function test_signup_crea_usuario_con_rol_viewer_y_responde_201(): void
      {
          Role::factory()->create(['name' => 'viewer']);
  
          $payload = [
              'name'                  => 'Ada Lovelace',
              'email'                 => 'ada@example.com',
              'password'              => 'password123',
              'password_confirmation' => 'password123',
          ];
  
          $this->postJson('/api/auth/signup', $payload)
              ->assertStatus(201)
              ->assertJson([
                  'status'  => 'success',
                  'message' => 'Usuario creado correctamente',
              ])
              ->assertJsonPath('data.email', 'ada@example.com')
              ->assertJsonStructure(['data' => ['id', 'name', 'email', 'roles']]);
  
          $this->assertDatabaseHas('users', ['email' => 'ada@example.com']);
      }
  
      public function test_signup_valida_y_envuelve_errores_422(): void
      {
          Role::factory()->create(['name' => 'viewer']);
          // 422 con formato de error ValidationException.
          $this->postJson('/api/auth/signup', [])
              ->assertStatus(422)
              ->assertJson([
                  'status'  => 'error',
                  'message' => 'Los datos proporcionados no son válidos.',
              ])
              ->assertJsonStructure(['errors' => ['name', 'email', 'password']]);
      }
  
      public function test_login_ok_devuelve_token_y_envia_mail_queue(): void
      {
          Role::factory()->create(['name' => 'viewer']);
          // Auth::attempt >> estructura de respuesta >> Mail encolado.
          Mail::fake();
  
          $user = User::factory()->create([
              'email'    => 'user@test.com',
              'password' => bcrypt('password123'),
          ]);
  
          $this->postJson('/api/auth/login', [
              'email' => 'user@test.com',
              'password' => 'password123',
          ])->assertOk();
  
          Mail::assertQueued(UserRegisteredMail::class, fn($m) => $m->hasTo('user@test.com'));
      }
  
      public function test_login_credenciales_invalidas_401(): void
      {
          // Error 401 envuelto por helper ApiResponse->error.
          User::factory()->create(['email' => 'user@test.com', 'password' => bcrypt('password123')]);
  
          $this->postJson('/api/auth/login', [
              'email' => 'user@test.com',
              'password' => 'password124',
          ])->assertStatus(401)
              ->assertJson([
                  'status'  => 'error',
                  'message' => 'Credenciales invalidas',
              ]);
      }
  
      public function test_me_y_logout_requieren_auth(): void
      {
          Role::factory()->create(['name' => 'viewer']);
          // rutas bajo auth:api deben rechazar sin token y responder bien con token.
          $user = User::factory()->create();
          \Laravel\Passport\Passport::actingAs($user, ['posts.read']);
  
          $this->getJson('/api/auth/me')
              ->assertOk()
              ->assertJson(['status' => 'success']);
  
          $this->postJson('/api/auth/logout')
              ->assertOk()
              ->assertJson(['status' => 'success']);
      }
  }
  
  ```
  
  **Api/PostReadEndpointsTest**:
  
  ```bash
  php artisan make:test Api/PostReadEndpointsTest
  ```
  
  Ejemplo de implementación de test para `Api/PostReadEndpointsTest`:
  
  ```php
  <?php
  
  namespace Tests\Feature\Api;
  
  use App\Models\Category;
  use App\Models\Post;
  use App\Models\User;
  use Illuminate\Foundation\Testing\RefreshDatabase;
  use Laravel\Passport\Passport;
  use Laravel\Passport\Client;
  use Tests\TestCase;
  use Illuminate\Support\Str;
  
  class PostReadEndpointsTest extends TestCase
  {
      use RefreshDatabase;
  
  
      protected function setUp(): void
      {
          parent::setUp();
          $this->setupPassportClient();
      }
  
      protected function setupPassportClient(): void
      {
  
          Client::create([
              'name' => 'Laravel' . Str::random(10),
              'secret' => 'test-secret-for-testing',
              'provider' => 'users',
              'redirect_uris' => [],
              'grant_types' => ["personal_access"],
              'revoked' => false,
          ]);
      }
  
      private function actingAsWithRole(string $roleName, array $scopes = ['posts.read']): User
      {
          $user = User::factory()->withRole($roleName)->create();
          Passport::actingAs($user, $scopes);
          return $user;
      }
  
      // GET /posts y /posts/{id} con middleware auth y role.
  
      public function test_index_requiere_auth_y_rol_viewer_editor_admin(): void
      {
          // 401 sin token; 403 con rol no permitido; 200 con viewer.
          $this->getJson('/api/posts')->assertStatus(401);
  
          $noRole = User::factory()->create();
          Passport::actingAs($noRole, ['posts.read']);
          $this->getJson('/api/posts')->assertStatus(403);
  
          $this->actingAsWithRole('viewer');
  
          Post::factory()->count(2)->create();
          $this->getJson('/api/posts')
              ->assertOk()
              ->assertJson(['status' => 'success'])
              ->assertJsonStructure(['data' => [['id', 'title', 'slug', 'status', 'user', 'categories', 'tags', 'meta', 'published_at']]]);
      }
  
      public function test_show_devuelve_200_y_resource_correcto(): void
      {
          // PostResource con relaciones cargadas.
          $this->actingAsWithRole('viewer');
          $post = Post::factory()->create();
          $cat  = Category::factory()->create();
          $post->categories()->attach($cat->id);
  
          $this->getJson("/api/posts/{$post->id}")
              ->assertOk()
              ->assertJsonPath('data.id', $post->id)
              ->assertJsonPath('data.categories.0.id', $cat->id);
      }
  
      public function test_show_not_found_devuelve_404_con_mensaje_custom(): void
      {
          // Rama personalizada del controlador show()
          $this->actingAsWithRole('viewer');
  
          $this->getJson('/api/posts/999999')
              ->assertStatus(404)
              ->assertJson([
                  'status'  => 'error',
                  'message' => 'Todo mal, como NO dijo el Pibe',
              ]);
      }
  }
  
  ```
  
  **Api/PostStoreEndpointsTest**:
  
  ```bash
  php artisan make:test Api/PostStoreEndpointsTest
  ```
  
  Ejemplo de implementación de test para `Api/PostStoreEndpointsTest`:
  
  ```php
  <?php
  
  namespace Tests\Feature\Api;
  
  use App\Mail\PostCreatedMail;
  use App\Models\Category;
  use App\Models\User;
  use Illuminate\Foundation\Testing\RefreshDatabase;
  use Illuminate\Http\UploadedFile;
  use Illuminate\Support\Facades\Mail;
  use Illuminate\Support\Facades\Storage;
  use Laravel\Passport\Passport;
  use Tests\TestCase;
  use Laravel\Passport\Client;
  use Illuminate\Support\Str;
  
  class PostStoreEndpointsTest extends TestCase
  {
      use RefreshDatabase;
  
      protected function setUp(): void
      {
          parent::setUp();
          $this->setupPassportClient();
      }
  
      protected function setupPassportClient(): void
      {
  
          Client::create([
              'name' => 'Laravel' . Str::random(10),
              'secret' => 'test-secret-for-testing',
              'provider' => 'users',
              'redirect_uris' => [],
              'grant_types' => ["personal_access"],
              'revoked' => false,
          ]);
      }
  
      private function actingAsEditor(array $scopes = ['posts.read', 'posts.write']): User
      {
          $user = User::factory()->withRole('editor')->create();
          Passport::actingAs($user, $scopes);
          return $user;
      }
  
      // POST /posts (validación, scopes, subida de archivos, sync categorias, mail con cola de vaca).
  
      public function test_store_requiere_auth_rol_editor_o_admin_y_scope_posts_write(): void
      {
          // middleware encadenados -> auth, role, scope.
          $this->postJson('/api/posts', [])->assertStatus(401);
  
          $viewer = User::factory()->withRole('viewer')->create();
          Passport::actingAs($viewer, ['posts.read', 'posts.write']); // rol no valido
          $this->postJson('/api/posts', [])->assertStatus(403);
  
          $editor = User::factory()->withRole('editor')->create();
          Passport::actingAs($editor, ['posts.read']);
          $this->postJson('/api/posts', [])->assertStatus(403);
      }
  
      public function test_store_valida_422_y_mensaje_uniforme(): void
      {
          $this->actingAsEditor();
  
          $this->postJson('/api/posts', [])
              ->assertStatus(422)
              ->assertJson([
                  'status'  => 'error',
                  'message' => 'Los datos proporcionados no son válidos.',
              ])
              ->assertJsonStructure(['errors' => ['title', 'slug', 'content', 'status']]);
      }
  
      public function test_store_autocompleta_slug_y_guarda_imagen_y_sincroniza_categorias_y_envia_mail(): void
      {
          Mail::fake();
          Storage::fake('public');
  
          $this->actingAsEditor();
  
          $cats = Category::factory()->count(2)->create();
  
          $payload = [
              'title'   => 'Mi Primer Post',
              // slug omitido -> prepareForValidation debe generarlo
              'content' => str_repeat('contenido ', 5),
              'status'  => 'draft',
              'tags'    => ['laravel', 'testing'],
              'meta'    => ['seo_title' => 'SEO', 'seo_desc' => 'DESC'],
              'category_ids' => $cats->pluck('id')->all(),
          ];
  
          $file = UploadedFile::fake()->image('cover.png', 800, 600);
  
          $res = $this->post('/api/posts', array_merge($payload, ['cover_image' => $file]));
          $res->assertStatus(201)
              ->assertJson([
                  'status'  => 'success',
                  'message' => 'Post creado correctamente',
              ])
              ->assertJsonPath('data.title', 'Mi Primer Post');
  
          // Comprobar archivo guardado
          $path = $res->json('data.cover_image');
          $this->assertNotEmpty($path);
          $this->assertTrue(Storage::disk('public')->exists($path));
  
          // Categorias sincronizadas
          $this->assertCount(2, auth()->user()->posts()->first()->categories);
  
          // Email en cola de vaca
          Mail::assertQueued(PostCreatedMail::class);
      }
  }
  
  ```
  
  

---

## 10. Documentación

### 10.1 Documentación

- **10.1.1 Swagger / OpenAPI con Laravel**  

  ```bash
  composer require "darkaonline/l5-swagger"
  php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
  ```

- **10.1.2 Configuración**

  Configuración del `.env`:

  ```env
  L5_SWAGGER_CONST_HOST=http://localhost:8000
  ```

  Creación de **App\Swagger\OpenApi.php**

  ```php
  <?php
  
  namespace App\Swagger;
  
  use OpenApi\Annotations as OA;
  
  /**
   * @OA\Info(
   *   title="API Blog",
   *   version="1.0.0",
   *   description="Documentación de la API (Laravel 11 + Passport)."
   * )
   *
   * @OA\Server(
   *   url=L5_SWAGGER_CONST_HOST,
   *   description="Servidor base"
   * )
   *
   * @OA\SecurityScheme(
   *   securityScheme="bearerAuth",
   *   type="http",
   *   scheme="bearer",
   *   bearerFormat="JWT"
   * )
   *
   * @OA\Tag(name="Auth", description="Autenticación y perfil")
   * @OA\Tag(name="Posts", description="Gestión de posts")
   */
  
  class OpenApi {}
  
  ```

  

- **10.1.3 Anotaciones**

  Ejemplo de implementación de Controlador `AuthController`:

  ```php
  <?php
  
  namespace App\Http\Controllers;
  
  use App\Mail\UserRegisteredMail;
  use App\Models\Role;
  use App\Models\User;
  use App\Traits\ApiResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Support\Facades\Mail;
  
  class AuthController extends Controller
  {
      use ApiResponse;
  
      /**
       * @OA\Post(
       *   path="/api/auth/login",
       *   tags={"Auth"},
       *   summary="Login y emisión de token",
       *   @OA\RequestBody(
       *     required=true,
       *     @OA\JsonContent(
       *       required={"email","password"},
       *       @OA\Property(property="email", type="string", format="email", example="user@test.com"),
       *       @OA\Property(property="password", type="string", minLength=8, example="pa55Word123$.")
       *     )
       *   ),
       *   @OA\Response(
       *     response=200, description="OK",
       *     @OA\JsonContent(
       *       @OA\Property(property="status", type="string", example="success"),
       *       @OA\Property(property="message", type="string", nullable=true),
       *       @OA\Property(property="data", type="object",
       *         @OA\Property(property="token_type", type="string", example="Bearer"),
       *         @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1Qi..."),
       *         @OA\Property(property="user", type="object",
       *           @OA\Property(property="email", type="string", example="user@test.com"),
       *           @OA\Property(property="roles", type="array", @OA\Items(type="string"), example={"viewer","editor"})
       *         )
       *       )
       *     )
       *   ),
       *   @OA\Response(response=401, description="Credenciales inválidas")
       * )
       */
      function login(Request $request)
      {
          $data = $request->validate([
              'email' => 'required|email',
              'password' => 'required|string|min:8',
          ]);
          //!Auth::attempt($data)
          if (!Auth::attempt($request->only('email', 'password'))) {
              return $this->error('Credenciales invalidas', 401);
          }
  
          $user = $request->user();
  
          $tokenResult = $user->createToken('api-token', ['posts.read', 'posts.write']);
  
          $token = $tokenResult->accessToken;
  
          Mail::to($user->email)->queue(new UserRegisteredMail($user)); //Queue
  
          return $this->success([
              'token_type' => 'Bearer',
              'access_token' => $token,
              'user' => [
                  'email' => $user->email,
                  'roles' => $user->roles()->pluck('name'),
              ]
          ]);
      }
  
      /**
       * @OA\Post(
       *   path="/api/auth/signup",
       *   tags={"Auth"},
       *   summary="Registro de usuario",
       *   @OA\RequestBody(
       *     required=true,
       *     @OA\JsonContent(
       *       required={"name","email","password","password_confirmation"},
       *       @OA\Property(property="name", type="string", example="Ada Lovelace"),
       *       @OA\Property(property="email", type="string", format="email", example="ada@example.com"),
       *       @OA\Property(property="password", type="string", minLength=8, example="pa55Word123$."),
       *       @OA\Property(property="password_confirmation", type="string", example="pa55Word123$.")
       *     )
       *   ),
       *   @OA\Response(response=201, description="Creado")
       * )
       */
      function signup(Request $request)
      {
          $data = $request->validate([
              'name' => 'required|string|max:255',
              'email' => 'required|string|email|max:255|unique:users',
              'password' => 'required|string|min:8|confirmed',
          ]);
  
          $user = User::create([
              'name' => $data['name'],
              'email' => $data['email'],
              'password' => Hash::make($data['password']),
          ]);
  
          $defaultRole = Role::where('name', 'viewer')->first();
          if ($defaultRole) {
              $user->roles()->syncWithoutDetaching([$defaultRole->id]);
          }
          return $this->success($user->load('roles'), 'Usuario creado correctamente', 201);
      }
  
      /**
       * @OA\Get(
       *   path="/api/auth/me",
       *   tags={"Auth"},
       *   summary="Perfil basico",
       *   security={{"bearerAuth":{}}},
       *   @OA\Response(response=200, description="OK")
       * )
       */
      function me(Request $request)
      {
          return $this->success("Hellou Camper!");
      }
  
      /**
       * @OA\Post(
       *   path="/api/auth/logout",
       *   tags={"Auth"},
       *   summary="Cerrar sesión",
       *   security={{"bearerAuth":{}}},
       *   @OA\Response(response=200, description="OK")
       * )
       */
      function logout(Request $request)
      {
          return $this->success("Hellou Camper!");
      }
  }
  
  ```

  Ejemplo de implementación de Controlador `PostController`:

  ```php
  <?php
  
  namespace App\Http\Controllers;
  
  use App\Http\Requests\StorePostRequest;
  use App\Http\Requests\UpdatePostRequest;
  use App\Http\Resources\PostResource;
  use App\Mail\PostCreatedMail;
  use App\Models\Post;
  use App\Traits\ApiResponse;
  use Illuminate\Database\RecordsNotFoundException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\Log;
  use Illuminate\Support\Facades\Mail;
  use Illuminate\Support\Facades\Storage;
  
  class PostController extends Controller
  {
      use ApiResponse;
  
      /**
       * @OA\Get(
       *   path="/api/posts",
       *   tags={"Posts"},
       *   summary="Listar posts",
       *   security={{"bearerAuth":{}}},
       *   @OA\Response(
       *     response=200, description="OK",
       *     @OA\JsonContent(
       *       @OA\Property(property="status", type="string", example="success"),
       *       @OA\Property(property="data", type="array",
       *         @OA\Items(type="object",
       *           @OA\Property(property="id", type="integer", example=1),
       *           @OA\Property(property="title", type="string"),
       *           @OA\Property(property="slug", type="string"),
       *           @OA\Property(property="status", type="string", example="draft"),
       *           @OA\Property(property="cover_image", type="string", nullable=true),
       *           @OA\Property(property="user", type="object",
       *             @OA\Property(property="id", type="integer"),
       *             @OA\Property(property="name", type="string"),
       *             @OA\Property(property="email", type="string")
       *           ),
       *           @OA\Property(property="categories", type="array",
       *             @OA\Items(type="object",
       *               @OA\Property(property="id", type="integer"),
       *               @OA\Property(property="name", type="string")
       *             )
       *           ),
       *           @OA\Property(property="tags", type="array", @OA\Items(type="string")),
       *           @OA\Property(property="meta", type="object"),
       *           @OA\Property(property="published_at", type="string", format="date-time", nullable=true)
       *         )
       *       )
       *     )
       *   )
       * )
       */
      public function index(): JsonResponse
      {
          $posts = Post::with('user', 'categories')->get();
          //use App\Http\Resources\PostResource
          return $this->success(PostResource::collection($posts));
      }
  
      /**
       * @OA\Post(
       *   path="/api/posts",
       *   tags={"Posts"},
       *   summary="Crear post",
       *   security={{"bearerAuth":{}}},
       *   @OA\RequestBody(
       *     required=true,
       *     @OA\MediaType(
       *       mediaType="multipart/form-data",
       *       @OA\Schema(
       *         required={"title","content","status"},
       *         @OA\Property(property="title", type="string", example="Mi primer post"),
       *         @OA\Property(property="slug", type="string", example="mi-primer-post"),
       *         @OA\Property(property="content", type="string", example="Contenido..."),
       *         @OA\Property(property="status", type="string", enum={"draft","published","archived","default"}),
       *         @OA\Property(property="published_at", type="string", format="date-time", nullable=true),
       *         @OA\Property(property="tags[]", type="array", @OA\Items(type="string")),
       *         @OA\Property(property="meta[seo_title]", type="string", maxLength=60),
       *         @OA\Property(property="meta[seo_desc]", type="string", maxLength=120),
       *         @OA\Property(property="category_ids[]", type="array", @OA\Items(type="integer")),
       *         @OA\Property(property="cover_image", type="string", format="binary", nullable=true)
       *       )
       *     )
       *   ),
       *   @OA\Response(response=201, description="Creado")
       * )
       */
      public function store(StorePostRequest $request): JsonResponse
      {
          $data = $request->validated();
  
          //Body no voy a recibir id del usuario
          $data['user_id'] = $request->user()->id; //Siempre se toma del Token
  
          if ($request->hasFile('cover_image')) {
              $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
          }
  
          $newPost = Post::create($data);
  
          if (!empty($data['category_ids'])) {
              $newPost->categories()->sync($data['category_ids']);
          }
  
          $newPost->load(['user', 'categories']);
          Log::debug('Email to send: ' . $newPost->user->email);
          Mail::to($newPost->user->email)->queue(new PostCreatedMail($newPost));
  
          return $this->success(new PostResource($newPost), 'Post creado correctamente', 201);
      }
  
      /**
       * @OA\Get(
       *   path="/api/posts/{id}",
       *   tags={"Posts"},
       *   summary="Detalle de post",
       *   security={{"bearerAuth":{}}},
       *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
       *   @OA\Response(response=200, description="OK"),
       *   @OA\Response(response=404, description="No encontrado")
       * )
       */
      public function show(string $id): JsonResponse // Post $post
      {
          //$result = Post::findOrFail($id);
          $result = Post::find($id);
          if ($result) {
              $result->load(['user', 'categories']);
              return $this->success(new PostResource($result), "Todo ok, como dijo el Pibe");
          } else {
              return $this->error("Todo mal, como NO dijo el Pibe", 404, ['id' => 'No se encontro el recurso con el id']);
          }
      }
  
      /**
       * @OA\Put(
       *   path="/api/posts/{id}",
       *   tags={"Posts"},
       *   summary="Actualizar post",
       *   security={{"bearerAuth":{}}},
       *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
       *   @OA\RequestBody(
       *     required=false,
       *     @OA\MediaType(
       *       mediaType="multipart/form-data",
       *       @OA\Schema(
       *         @OA\Property(property="title", type="string"),
       *         @OA\Property(property="slug", type="string"),
       *         @OA\Property(property="content", type="string"),
       *         @OA\Property(property="status", type="string", enum={"draft","published","archived"}),
       *         @OA\Property(property="published_at", type="string", format="date-time", nullable=true),
       *         @OA\Property(property="tags[]", type="array", @OA\Items(type="string")),
       *         @OA\Property(property="meta[seo_title]", type="string"),
       *         @OA\Property(property="meta[seo_desc]", type="string"),
       *         @OA\Property(property="category_ids[]", type="array", @OA\Items(type="integer")),
       *         @OA\Property(property="cover_image", type="string", format="binary", nullable=true)
       *       )
       *     )
       *   ),
       *   @OA\Response(response=200, description="OK"),
       *   @OA\Response(response=403, description="No autorizado"),
       *   @OA\Response(response=422, description="Validación")
       * )
       */
      public function update(UpdatePostRequest $request, Post $post)
      {
          //use Illuminate\Support\Facades\Log;
          Log::debug('all:', $request->all());
          Log::debug('files:', array_keys($request->allFiles()));
          $data = $request->validated();
          if ($request->hasFile('cover_image')) {
              //Borrado (Opcional)
              if ($post->cover_image) {
                  //use Illuminate\Support\Facades\Storage;
                  Storage::disk('public')->delete($post->cover_image);
              }
              $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
          }
          $post->update($data);
  
          //$post->refresh();
  
          if (array_key_exists('category_ids', $data)) {
              $post->categories()->sync($data['category_ids'] ?? []);
          }
  
          $post->load(['user', 'categories']);
          return $this->success(new PostResource($post));
      }
  
      /**
       * @OA\Delete(
       *   path="/api/posts/{id}",
       *   tags={"Posts"},
       *   summary="Eliminar (soft-delete) post",
       *   security={{"bearerAuth":{}}},
       *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
       *   @OA\Response(response=204, description="Sin contenido")
       * )
       */
      public function destroy(Post $post): JsonResponse
      {
          $post->delete(); //Soft delete
          return $this->success(null, 'Post eliminado', 204);
      }
  
      /**
       * @OA\Post(
       *   path="/api/posts/{id}/restore",
       *   tags={"Posts"},
       *   summary="Restaurar post eliminado",
       *   security={{"bearerAuth":{}}},
       *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
       *   @OA\Response(response=200, description="OK"),
       *   @OA\Response(response=500, description="Error al no encontrar registro (RecordsNotFoundException)")
       * )
       */
      public function restore(int $id): JsonResponse
      {
          Log::debug('restore: ' . $id);
          $post = Post::onlyTrashed()->find($id);
          if (!$post) {
              //throw new ModelNotFoundException('Post no encontrado', 404);
              Log::debug('restore: ' . $id);
              throw new RecordsNotFoundException('Post no encontrado', 404);
          }
          Log::debug('restore: start');
          $post->restore();
          $post->load(['user', 'categories']);
          Log::debug('restore: success');
          return $this->success($post, 'Post restaurado correctamente');
      }
  }
  
  ```

- **10.1.3 Generación**

  ```bash
  php artisan optimize:clear
  php artisan l5-swagger:generate
  ```

- **10.1.4 Listado de Anotaciones**

  | Anotación                   | ¿Dónde se usa?     | Estructura mínima                                         | Props clave                                                  | Ejemplo                                                 |
  | --------------------------- | ------------------ | --------------------------------------------------------- | ------------------------------------------------------------ | ------------------------------------------------------- |
  | `@OA\Info`                  | Global             | `@OA\Info(title="...", version="...", description="...")` | `title`, `version`, `description`, `@OA\Contact`, `@OA\License` | `@OA\Info(title="API", version="1.0.0")`                |
  | `@OA\Server`                | Global (múltiples) | `@OA\Server(url=..., description=...)`                    | `url`, `description`                                         | `@OA\Server(url=L5_SWAGGER_CONST_HOST)`                 |
  | `@OA\Tag`                   | Global             | `@OA\Tag(name=..., description=...)`                      | `name`, `description`                                        | `@OA\Tag(name="Posts", description="Gestión de posts")` |
  | `@OA\ExternalDocumentation` | Global / operación | `@OA\ExternalDocumentation(description=..., url=...)`     | `description`, `url`                                         | `@OA\ExternalDocumentation(url="https://docs")`         |

  **Seguridad**

  | Anotación            | ¿Dónde se usa?                        | Estructura mínima                                            | Props clave                                        | Ejmplo                                                       |
  | -------------------- | ------------------------------------- | ------------------------------------------------------------ | -------------------------------------------------- | ------------------------------------------------------------ |
  | `@OA\SecurityScheme` | Global (`components.securitySchemes`) | `@OA\SecurityScheme(securityScheme="...", type="http", scheme="bearer", bearerFormat="JWT")` | `securityScheme`, `type`, `scheme`, `bearerFormat` | `@OA\SecurityScheme(securityScheme="bearerAuth", type="http", scheme="berer")` |

  **Rutas y operaciones**

  | Anotación                                                    | ¿Dónde se usa?                      | Estructura mínima                                            | Props clave                                                  | Ejemplo                                                      |
  | ------------------------------------------------------------ | ----------------------------------- | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ |
  | `@OA\PathItem`                                               | Global (opcional)                   | `@OA\PathItem(path="/....", @OA\Get(...))`                   | `path`, sub-operaciones                                      | `@OA\PathItem(path="/api/ping", @OA\Get(...))`               |
  | `@OA\Get` / `@OA\Post` / `@OA\Put` / `@OA\Delete` / `@OA\Patch` / `@OA\Options` / `@OA\Head` / `@OA\Trace` | Operación                           | `@OA\Get(path="...", tags={...}, summary="...", @OA\Response(...))` | `path`, `tags`, `summary`, `description`, `operationId`, `security`, `@OA\Parameter`, `@OA\RequestBody`, `@OA\Response` | `@OA\Get(path="/api/posts", tags={"Posts"}, @OA\Response(response=200, description="OK"))` |
  | `@OA\Parameter`                                              | Operación o `components.parameters` | `@OA\Parameter(name="...", in="path`                         | query                                                        | header                                                       |
  | `@OA\RequestBody`                                            | Operación                           | `@OA\RequestBody(required=..., @OA\MediaType(...))`          | `required`, `@OA\MediaType`                                  | `@OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json", @OA\JsonContent(ref="#/components/schemas/PostCreate")))` |
  | `@OA\Response`                                               | Operación o `components.responses`  | `@OA\Response(response=..., description="...", @OA\MediaType(...))` | `response`, `description`, `@OA\Header`, `@OA\MediaType`     | `@OA\Response(response=200, description="OK")`               |
  | `@OA\Header`                                                 | Dentro de `@OA\Response`            | `@OA\Header(header="...", @OA\Schema(type="..."))`           | `header`, `@OA\Schema`                                       | `@OA\Header(header="X-RateLimit-Remaining", @OA\Schema(type="integer"))` |
  | `@OA\MediaType`                                              | En `@OA\RequestBody`/`@OA\Response` | `@OA\MediaType(mediaType="...",` `@OA\Schema(...)`           | `@OA\JsonContent(...)`                                       | `mediaType`, `@OA\Schema`/`@OA\JsonContent`, `@OA\Example`, `@OA\Encoding` |

  **Contenido y esquemas**

  | ¿Dónde se usa?                            | Estructura minima                                            | Props clave                                         | Ejemplos                                                     |
  | ----------------------------------------- | ------------------------------------------------------------ | --------------------------------------------------- | ------------------------------------------------------------ |
  | `components.schemas` o inline             | `@OA\Schema(schema="Nombre", type="object`                   | array                                               | string                                                       |
  | Dentro de `@OA\Schema(object)`            | `@OA\Property(property="...", type="...", example="...")`    | `property`, `type`, `format`, `example`, `nullable` | `@OA\Property(property="title", type="string", example="Mi post")` |
  | En `@OA\Schema(type="array")`             | `@OA\Items(type="...", ref="...")`                           | `type`, `format`, `ref`                             | `@OA\Schema(type="array", @OA\Items(type="string"))`         |
  | En `@OA\MediaType`                        | `@OA\JsonContent(ref="#/components/schemas/...")`o con props | Igual que `Schema`, específico JSON                 | `@OA\JsonContent(ref="#/components/schemas/Post")`           |
  | En `@OA\MediaType`/`@OA\Header`           | `@OA\Example(example="nombre", value=...)`                   | `example`, `summary`, `value`                       | `@OA\Example(example="ok", value={"status":"success"})`      |
  | En `@OA\MediaType`(`multipart/form-data`) | `@OA\Encoding(property="...", contentType="...")`            | `property`, `contentType`                           | `@OA\Encoding(property="cover_image", contentType="image/png")` |

   

> **Con mucho Cariño**: Adrián R. 🗿

# 🚀 Laravel: Un Vistazo Rápido a Mi Boilerplate

¡Hola, dev! Este es un proyecto de Laravel que he configurado como base para futuros trabajos. Aquí te explico cómo está todo montado, desde la base de datos hasta la autenticación. Espero que te sirva tanto como a mí.

---

## 🛠️ Conexión con Bases de Datos

He configurado este proyecto para que sea flexible. Puedes usar **MySQL**, **PostgreSQL** o incluso **Supabase** sin problemas, ya que las migraciones están listas para las tres.

### Iniciar proyectos con Supabase:

1. Inicia sesion en: https://supabase.com/

2. Crea una organizacion, si no un proyecto: 

   ![Organizaciones](https://i.imgur.com/NyuNlBg.png)

3. Entra en tu proyecto, en este caso se uso Laravel.

   ![Proyectos](https://i.imgur.com/2HMfhtH.png)

4. Entra en Crud-Laravel que es el proyecto:

   ![Crud-Laravel](https://i.imgur.com/a3L8N5B.png)

5. En la parte de conexion en donde dice connect no vamos a centrar:

   ![Connection](https://i.imgur.com/ChbUCGp.png)

   ![image-20250921155640959](/home/camper/.config/Typora/typora-user-images/image-20250921155640959.png)

**Para empezar, solo necesitas:**

1. Copia el archivo `.env.example` y renómbralo a `.env`.

   1. ```
      # Local MySQL Connection
      DB_HOST_MYSQL=db
      DB_PORT_MYSQL=3306
      DB_DATABASE_MYSQL=laravel
      DB_USERNAME_MYSQL=root
      DB_PASSWORD_MYSQL=admin
      
      # Local PostgreSQL Connection
      DB_HOST_PGSQL=pgsql
      DB_PORT_PGSQL=5432
      DB_DATABASE_PGSQL=laravel
      DB_USERNAME_PGSQL=laravel
      DB_PASSWORD_PGSQL=admin
      
      # Supabase Connection
      DB_HOST_SUPABASE=aws-1-us-east-2.pooler.supabase.com
      DB_PORT_SUPABASE=5432
      DB_DATABASE_SUPABASE=postgres
      DB_USERNAME_SUPABASE=postgres.bmcnrnnhyazclvwgrieb
      DB_PASSWORD_SUPABASE=............
      ```

      

2. Abre `.env` y busca la sección de base de datos.
3. Cambia `DB_CONNECTION` a `mysql`, `pgsql`, `supabase`  o la opción que prefieras:

    Tu database config deberia de quedar con esto: 

   ​	

   ```php
   <?php
   
   use Illuminate\Support\Str;
   
   return [
   
       /*
       |--------------------------------------------------------------------------
       | Default Database Connection Name
       |--------------------------------------------------------------------------
       |
       | Here you may specify which of the database connections below you wish
       | to use as your default connection for database operations. This is
       | the connection which will be utilized unless another connection
       | is explicitly specified when you execute a query / statement.
       |
       */
   
       'default' => env('DB_CONNECTION', 'sqlite'),
   
       /*
       |--------------------------------------------------------------------------
       | Database Connections
       |--------------------------------------------------------------------------
       |
       | Below are all of the database connections defined for your application.
       | An example configuration is provided for each database system which
       | is supported by Laravel. You're free to add / remove connections.
       |
       */
   
       'connections' => [
   
           'sqlite' => [
               'driver' => 'sqlite',
               'url' => env('DB_URL'),
               'database' => env('DB_DATABASE', database_path('database.sqlite')),
               'prefix' => '',
               'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
               'busy_timeout' => null,
               'journal_mode' => null,
               'synchronous' => null,
           ],
   
           'mysql' => [
               'driver' => 'mysql',
               'url' => env('DB_URL'),
               'host' => env('DB_HOST_MYSQL', '127.0.0.1'),
               'port' => env('DB_PORT_MYSQL', '3306'),
               'database' => env('DB_DATABASE_MYSQL', 'laravel'),
               'username' => env('DB_USERNAME_MYSQL', 'root'),
               'password' => env('DB_PASSWORD_MYSQL', ''),
               'unix_socket' => env('DB_SOCKET', ''),
               'charset' => env('DB_CHARSET', 'utf8mb4'),
               'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
               'prefix' => '',
               'prefix_indexes' => true,
               'strict' => true,
               'engine' => null,
               'options' => extension_loaded('pdo_mysql') ? array_filter([
                   PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
               ]) : [],
           ],
   
           'supabase' => [
               'driver' => 'pgsql',
               'url' => env('DB_URL_SUPABASE'),
               'host' => env('DB_HOST_SUPABASE'),
               'port' => env('DB_PORT_SUPABASE'),
               'database' => env('DB_DATABASE_SUPABASE'),
               'username' => env('DB_USERNAME_SUPABASE'),
               'password' => env('DB_PASSWORD_SUPABASE'),
               'charset' => 'utf8',
               'prefix' => '',
               'prefix_indexes' => true,
               'search_path' => 'public',
               'sslmode' => 'prefer',
           ],
   
           'mariadb' => [
               'driver' => 'mariadb',
               'url' => env('DB_URL'),
               'host' => env('DB_HOST', '127.0.0.1'),
               'port' => env('DB_PORT', '3306'),
               'database' => env('DB_DATABASE', 'laravel'),
               'username' => env('DB_USERNAME', 'root'),
               'password' => env('DB_PASSWORD', ''),
               'unix_socket' => env('DB_SOCKET', ''),
               'charset' => env('DB_CHARSET', 'utf8mb4'),
               'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
               'prefix' => '',
               'prefix_indexes' => true,
               'strict' => true,
               'engine' => null,
               'options' => extension_loaded('pdo_mysql') ? array_filter([
                   PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
               ]) : [],
           ],
   
           'pgsql' => [
               'driver' => 'pgsql',
               'url' => env('DB_URL'),
               'host' => env('DB_HOST_PGSQL', '127.0.0.1'),
               'port' => env('DB_PORT_PGSQL', '5432'),
               'database' => env('DB_DATABASE_PGSQL', 'laravel'),
               'username' => env('DB_USERNAME_PGSQL', 'root'),
               'password' => env('DB_PASSWORD_PGSQL', ''),
               'charset' => env('DB_CHARSET', 'utf8'),
               'prefix' => '',
               'prefix_indexes' => true,
               'search_path' => 'public',
               'sslmode' => 'prefer',
           ],
   
           'sqlsrv' => [
               'driver' => 'sqlsrv',
               'url' => env('DB_URL'),
               'host' => env('DB_HOST', 'localhost'),
               'port' => env('DB_PORT', '1433'),
               'database' => env('DB_DATABASE', 'laravel'),
               'username' => env('DB_USERNAME', 'root'),
               'password' => env('DB_PASSWORD', ''),
               'charset' => env('DB_CHARSET', 'utf8'),
               'prefix' => '',
               'prefix_indexes' => true,
               // 'encrypt' => env('DB_ENCRYPT', 'yes'),
               // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
           ],
   
       ],
   
       /*
       |--------------------------------------------------------------------------
       | Migration Repository Table
       |--------------------------------------------------------------------------
       |
       | This table keeps track of all the migrations that have already run for
       | your application. Using this information, we can determine which of
       | the migrations on disk haven't actually been run on the database.
       |
       */
   
       'migrations' => [
           'table' => 'migrations',
           'update_date_on_publish' => true,
       ],
   
       /*
       |--------------------------------------------------------------------------
       | Redis Databases
       |--------------------------------------------------------------------------
       |
       | Redis is an open source, fast, and advanced key-value store that also
       | provides a richer body of commands than a typical key-value system
       | such as Memcached. You may define your connection settings here.
       |
       */
   
       'redis' => [
   
           'client' => env('REDIS_CLIENT', 'phpredis'),
   
           'options' => [
               'cluster' => env('REDIS_CLUSTER', 'redis'),
               'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
           ],
   
           'default' => [
               'url' => env('REDIS_URL'),
               'host' => env('REDIS_HOST', '127.0.0.1'),
               'username' => env('REDIS_USERNAME'),
               'password' => env('REDIS_PASSWORD'),
               'port' => env('REDIS_PORT', '6379'),
               'database' => env('REDIS_DB', '0'),
           ],
   
           'cache' => [
               'url' => env('REDIS_URL'),
               'host' => env('REDIS_HOST', '127.0.0.1'),
               'username' => env('REDIS_USERNAME'),
               'password' => env('REDIS_PASSWORD'),
               'port' => env('REDIS_PORT', '6379'),
               'database' => env('REDIS_CACHE_DB', '1'),
           ],
   
       ],
   
   ];
   ```

   
4. Llena las credenciales correspondientes.
5. Ejecuta las migraciones con `php artisan migrate`, esta pro default tu la configuras, pero si quieres una especifica por ejemplo postgress entonces ejecuta: `php artisan migrate --database pgsql`

¡Y listo! La base de datos estará lista para tu proyecto.

---

## 📧 Notificaciones: De Prueba a Producción

Para los correos electrónicos, me gusta tener dos opciones:

* **Para desarrollo local**, uso **Mailpit**. Es genial para atrapar y ver los correos sin enviarlos realmente.
* **Para el entorno real**, la configuración está lista para usar un servicio de **SMTP** real.

Solo tienes que ajustar las variables de `MAIL_*` en tu archivo `.env` para cambiar entre uno y otro. ¡Sencillo y efectivo!:

Configuracion con email: 

 1. Ve a donde este link: https://myaccount.google.com/apppasswords 

 2. Crea tu aplicacion:

    ![Aplicacion](https://i.imgur.com/WjDfDBK.png)

 3. Crea tu Contraseña: 

    ![Contraseña](https://copilot.microsoft.com/th/id/BCO.355c1b4d-4047-4808-83a4-0b64e2098018.png)

## Configuracion en tu .env: 

```
MAIL_MAILER=mailpit
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_ENCRYPTION=tls
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=no-reply@tudominio.test
MAIL_FROM_NAME="${APP_NAME}"

MAIL_MAILER_REAL=smtp
MAIL_HOST_REAL=smtp.gmail.com
MAIL_PORT_REAL=587
MAIL_USERNAME_REAL=correo-remitente
MAIL_PASSWORD_REAL=clave que le das
MAIL_ENCRYPVITE_DEV_SERVER_URL=http://localhost:5173
```

## Archivo Config de Mail: 

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send all email
    | messages unless another mailer is explicitly specified when sending
    | the message. All additional mailers can be configured within the
    | "mailers" array. Examples of each type of mailer are provided.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers that can be used
    | when delivering an email. You may specify which one you're using for
    | your mailers below. You may also add additional mailers if needed.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |            "postmark", "resend", "log", "array",
    |            "failover", "roundrobin"
    |
    */

    'mailers' => [

        'mailpit' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        'real' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST_REAL', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT_REAL', 587),
            'username' => env('MAIL_USERNAME_REAL'),
            'password' => env('MAIL_PASSWORD_REAL'),
            'encryption' => env('MAIL_ENCRYPTION_REAL', 'tls'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],


        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all emails sent by your application to be sent from
    | the same address. Here you may specify a name and address that is
    | used globally for all emails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

];
```

## Aplicaciones:

AuthController.php:

```php
function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        //!Auth::attempt($data)
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Credenciales invalidas', 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('api-token', ['posts.read', 'posts.write']);

        $token = $tokenResult->accessToken;

        Mail::to($user->email)->queue(new UserRegisteredMail($user)); //QueueMail::to($user->email)->queue(new UserRegisteredMail($user)); // Mailpit
        Mail::mailer('real')->to($user->email)->queue(new UserRegisteredMail($user)); // Gmail
        
        return $this->success([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => [
                'email' => $user->email,
                'roles' => $user->roles()->pluck('name'),
            ]
        ]);
    }
```



---

## 🔒 Autenticación con Oauth2 y Supabase

En lugar de construir la autenticación desde cero, la manejo con **Supabase**, que simplifica todo el proceso de **Oauth2**. Puedes iniciar sesión con cuentas de **Google** y **GitHub**.

**Cómo funciona:**

1. Configura tus aplicaciones de Google y GitHub para obtener el `client ID` y el `client secret`.

   1. Google:

      1. Ir a google cloud, y seleccionar un proyecto o crear uno, en este caso use uno ya existente:

         ![Proyecto Google](https://i.imgur.com/l4TBTC4.png)
      2. Ir a apis y servicios:

         ![Apis y Servicios](https://i.imgur.com/ouLchCB.png)
      3. En donce dice pantalla de consentimiento veras esto:

         ![Panel](https://i.imgur.com/P4gcHTf.png)
      4. En donde dice Clientes crea uno o edita si ya lo tienes:

         ![Cliente Google](https://i.imgur.com/qKpxoal.png)

   2. Github:

      1. Ve a las configuraciones de tu perfil: 

         ![Config Github](https://i.imgur.com/19FEbEI.png)

      2. Ve a developer settings a Oauth Apps:

         ![Developer Settings](https://i.imgur.com/3E1VJER.png)

      3. Y luego o creas una o la editas: 

         ![App de github](https://i.imgur.com/g8cNcKC.png)

2. Ve a tu proyecto de Supabase y habilita estos proveedores.

   1. Google, asi tiene que quedar: 

      ![Paso 1](https://i.imgur.com/qSuxYlp.png)

      ![Providers](https://i.imgur.com/AcaVcyz.png)

      ![Providers](https://i.imgur.com/HWgLDsk.png)

      ![Github](https://i.imgur.com/GhnmuRv.png)

      ![Google](https://i.imgur.com/62KgJkb.png)

3. Copia tus credenciales en el archivo `.env` de tu proyecto de Laravel.

   1. Api key de Supabase: 

      ![Api Key Supabase](https://i.imgur.com/bOb4J8m.png)

   2. Jwt de Supabase: 

      ![Jwt de Supabase](https://i.imgur.com/FLmRUJ2.png)

   ```
   VITE_SUPABASE_URL=TU Url de supabase
   VITE_SUPABASE_ANON_KEY=api key de supabase
   # Valor de Supabase > Project Settings > API > JWT Secret
   SUPABASE_JWT_SECRET=jwt de supabase
   ```

   

De esta forma, la autenticación es segura y rápida, sin tener que reinventar la rueda.

Ya solo faltaria que lo implementes en tu codigo ya sea php o React, para eso use laravel con breeze, mas info en: https://github.com/addsdev-campuslands/skill_laravel_a1_introduccion.git  ve a la rama de:

​	![Repo](https://i.imgur.com/ScccTTx.png)



Ahi en resources/js/Pages/Login o Register.jsx esta el como se implemento, nota ellos usaron routes/auth.php

---

¡Espero que esta configuración te ahorre tiempo y te sirva como una buena base para tus proyectos!

**¡A codear!**

**Con mucho Cariño**: Kevin A. 🗿
