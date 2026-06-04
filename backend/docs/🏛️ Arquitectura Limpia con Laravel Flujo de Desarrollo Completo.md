# 🏛️ Arquitectura Limpia con Laravel: Flujo de Desarrollo Completo

Este documento sirve como guía para la implementación de cualquier funcionalidad (*feature*) dentro de la aplicación, siguiendo la estricta separación de responsabilidades de la Arquitectura Limpia.

El flujo se presenta de abajo hacia arriba: desde la base de datos (Infraestructura) hasta el punto de entrada de la aplicación (Presentación).

## 🏗️ 1. Plano Maestro de Carpetas Absoluto (Enterprise + Security + Full CQRS)

app/
├── 📁 Domain/                           # Capa 1: Núcleo de Negocio Puro (Agnóstico al Framework)
│   ├── 📁 Entities/                     # 🟢 LAS ENTIDADES RICOS (Clases PHP puras con lógica interna)
│   │   ├── 📄 User.php                  # Entidad de Usuario (Maneja estados de MFA, roles, perfiles)
│   │   └── 📄 Post.php                  # Entidad de Post (Maneja estados de publicación, validación de 								contenido)
│   ├── 📁 ValueObjects/                 # Objetos inmutables autovalidados (Email.php, Password.php, 								PostTitle.php)
│   ├── 📁 RepositoriesInterfaces/       # 📜 CONTRATOS PUROS DE ACCESO A DATOS (Principio de 									Inversión de Dependencias)
│   │   ├── 📄 IUserRepository.php       # Interfaz/Contrato para el Repositorio de Usuarios
│   │   └── 📄 IPostRepository.php       # Interfaz/Contrato para el Repositorio de Posts
│   └── 📁 Exceptions/                   # Errores exclusivos de las reglas de negocio (PostDomainException.php)
│
├── 📁 Application/                      # Capa 2: Casos de Uso (Lógica de la Aplicación)
│   ├── 📁 Features/                     # 🟢 VERTICAL SLICES (Módulos organizados por funcionalidad)
│   │   ├── 📁 Users/                    # Contexto completo de Usuarios y Auth
│   │   │   ├── 📁 Commands/             # 🛠️ ACCIONES DE ESCRITURA (Modifican Estado)
│   │   │   │   ├── 📁 LoginWithGoogle/  # Paso 1: Autenticación Social
│   │   │   │   │   ├── 📄 LoginWithGoogleCommand.php
│   │   │   │   │   └── 📄 LoginWithGoogleHandler.php
│   │   │   │   ├── 📁 VerifyMfaCode/    # Paso 2: Validación del Segundo Factor
│   │   │   │   │   ├── 📄 VerifyMfaCodeCommand.php
│   │   │   │   │   └── 📄 VerifyMfaCodeHandler.php
│   │   │   │   ├── 📁 CreateUser/       
│   │   │   │   │   ├── 📄 CreateUserCommand.php
│   │   │   │   │   └── 📄 CreateUserHandler.php
│   │   │   │   └── 📁 UpdateUser/       
│   │   │   │       ├── 📄 UpdateUserCommand.php
│   │   │   │       └── 📄 UpdateUserHandler.php
│   │   │   ├── 📁 Queries/              # 🔍 ACCIONES DE LECTURA (Consultas Rápidas)
│   │   │   │   ├── 📁 GetUserById/      
│   │   │   │   │   ├── 📄 GetUserByIdQuery.php
│   │   │   │   │   └── 📄 GetUserByIdHandler.php
│   │   │   │   └── 📁 GetUsersList/     
│   │   │   │       ├── 📄 GetUsersListQuery.php
│   │   │   │       └── 📄 GetUsersListHandler.php
│   │   │   └── 📁 Mappers/              # 🔄 Sincronizan Entidad Pura <-> Modelo Eloquent
│   │   │       └── 📄 UserMapper.php    
│   │   │
│   │   └── 📁 Posts/                    # 📦 CONTEXTO COMPLETO DE POSTS
│   │       ├── 📁 Commands/             # 🛠️ ESCRITURA DE POSTS
│   │       │   ├── 📁 CreatePost/       # Caso de Uso: Crear Publicación
│   │       │   │   ├── 📄 CreatePostCommand.php  <── DTO de entrada (title, content, user_id)
│   │       │   │   └── 📄 CreatePostHandler.php  <── 🧠 MANEJADOR (Orquesta la creación)
│   │       │   ├── 📁 UpdatePost/       # Caso de Uso: Editar Publicación
│   │       │   │   ├── 📄 UpdatePostCommand.php  <── DTO de entrada (post_id, title, content)
│   │       │   │   └── 📄 UpdatePostHandler.php  <── 🧠 MANEJADOR (Valida propiedad y edita)
│   │       │   └── 📁 DeletePost/       # Caso de Uso: Eliminar Publicación
│   │       │       ├── 📄 DeletePostCommand.php
│   │       │       └── 📄 DeletePostHandler.php
│   │       ├── 📁 Queries/              # 🔍 LECTURA DE POSTS
│   │       │   ├── 📁 GetPostById/      # Caso de Uso: Ver Detalle de un Post (¡Inyecta ITranslationService!)
│   │       │   │   ├── 📄 GetPostByIdQuery.php   <── Incluye opcionalmente el 'target_lang' en la consulta
│   │       │   │   └── 📄 GetPostByIdHandler.php <── Orquesta traducción interactiva al vuelo mediante la 												API local
│   │       │   └── 📁 GetPostsList/     # Caso de Uso: Feed Completo (Paginación, filtros por autor)
│   │       │       ├── 📄 GetPostsListQuery.php
│   │       │       └── 📄 GetPostsListHandler.php
│   │       └── 📁 Mappers/              # 🔄 Sincroniza Entidad Post <-> Modelo Post de Laravel
│   │           └── 📄 PostMapper.php    
│   │
│   └── 📁 ServicesInterfaces/           # 📜 CONTRATOS DE SERVICIOS EXTERNOS (Estrategias y APIs de 									Terceros)
│       ├── 📁 Cryptography/             # Interfaz para Cifrado y Hashing Seguro (OWASP A04)
│       ├── 📁 SocialAuth/               # Interfaz abstracta para Google API / Autenticación Federada (OWASP 								A07)
│       ├── 📁 Translation/              # 🌐 INTERFACES DE TRADUCCIÓN E INTERNACIONALIZACIÓN
│       │   └── 📄 ITranslationService.php # Contrato de traducción automática (Agnóstico al motor externo)
│       └── 📁 Messaging/                
│           ├── 📄 NotificationStrategyInterface.php 
│           └── 📄 NotificationResolver.php          
│
├── 📁 Infrastructure/                   # Capa 3: Implementación de Servicios, APIs y Blindaje OWASP
│   ├── 📁 Persistence/                  # Almacenamiento e Implementación de Contratos
│   │   └── 📁 Repositories/             
│   │       ├── 📄 UserRepository.php    # Implementa IUserRepository usando Eloquent y UserMapper
│   │       └── 📄 PostRepository.php    # Implementa IPostRepository usando Eloquent y PostMapper
│   ├── 📁 Services/                     # Conexiones y Adaptadores con SDKs Reales
│   │   ├── 📁 Cryptography/             # Implementación real de Hasheo seguro (Argon2id)
│   │   ├── 📁 Google/                   # Implementación real del SDK de Google Sign-In
│   │   ├── 📁 Mfa/                      # Implementación del Motor TOTP matemático (Google Authenticator)
│   │   └── 📁 Translation/              # 🌐 IMPLEMENTACIÓN TÉCNICA DE ADAPTADORES LINGÜÍSTICOS
│   │       └── 📄 LibreTranslateService.php # Conector al SDK/API del contenedor Docker local (Consumo 										<500MB RAM)
│   └── 📁 Security/                     # 🛡️ PROTECCIÓN PASIVA OWASP
│       ├── 📁 Headers/                  # Inyección de CSP, X-Frame-Options, HSTS (OWASP A02)
│       └── 📁 Logging/                  # Sanitizador de logs (Evita escribir passwords o tokens) (OWASP A09)
│
├── 📁 Providers/                        # 🔌 CAPA DE ENLACE NATIVA (El pegamento de Inversión de 								Dependencias)
│   ├── 📄 AppServiceProvider.php        # Configuración por defecto de Laravel
│   ├── 📄 DependencyInjectionProvider.php # Enlaza todas las interfaces de repositorios y servicios con 											sus clases reales
│   └── 📄 AuthCustomServiceProvider.php  # Inicializa y blinda las políticas de guardias personalizados 											para JWT/MFA
│
├── 📁 Models/                           # 🛢️ CARPETA NATIVA DE LARAVEL (Sin tocar namespaces por defecto)
│   ├── 📄 User.php                      # Modelo Eloquent de Usuario (Mapeo físico de tabla y relaciones)
│   └── 📄 Post.php                      # Modelo Eloquent de Post (Mapeo físico de tabla y relación belongsTo)
│
├── 📁 Swagger/                          # 📄 CONFIGURACIÓN CENTRALIZADA DE SWAGGER (Soluciona error 								@OA\Info)
│   ├── 📄 SwaggerConfig.php             # Anotaciones de Raíz: @OA\Info(), @OA\Server(), 									@OA\SecurityScheme()
│   └── 📄 Paths/                        # Opcional: Separación semántica de documentación si es muy grande
│
└── 📁 Presentation/                     # Capa 4: Frontera HTTP / Entrada al Sistema
    └── 📁 Http/
        ├── 📁 Controllers/              # Controladores ultra-delgados con Atributos de Swagger
        │   ├── 📄 AuthController.php    # Endpoints: login-google, verify-mfa
        │   ├── 📄 UserController.php    # Endpoints del CRUD de usuarios
        │   └── 📄 PostController.php    # Endpoints: index, show, store, update, destroy de Posts
        ├── 📁 Middleware/               # 🚨 LOS CENTINELAS OWASP
        │   ├── 📄 RateLimiterMiddleware.php  # Control de fuerza bruta (A07) y DDOS (A06)
        │   ├── 📄 RoleAuthorizationMiddleware.php # Previene manipulación de IDs ajenos (A01)
        │   ├── 📄 SanitizeInputMiddleware.php    # Limpieza Automática de SQLi y XSS (A05)
        │   └── 📄 SetLocaleMiddleware.php        # 🌐 Captura el header 'Accept-Language' para traducción 											interactiva
        ├── 📁 Requests/                 # FormRequests (Validación estricta en servidor, frena inputs nulos)
        │   ├── 📁 Users/
        │   │   ├── 📄 GoogleLoginRequest.php
        │   │   ├── 📄 MfaVerificationRequest.php
        │   │   ├── 📄 CreateUserRequest.php
        │   │   └── 📄 UpdateUserRequest.php
        │   └── 📁 Posts/
        │       ├── 📄 CreatePostRequest.php   # Valida longitudes, tipos de datos (OWASP A10)
        │       └── 📄 UpdatePostRequest.php        
        └── 📁 Resources/                # 📤 DTOS DE SALIDA (Ocultan campos físicos de la DB al Frontend)
            ├── 📁 Users/
            │   ├── 📄 UserResource.php                  
            │   └── 📄 UserCollection.php                
            └── 📁 Posts/
                ├── 📄 PostResource.php    # Formatea la salida de un solo Post (Envía el contenido traducido si 									aplica)
                └── 📄 PostCollection.php  # Formatea colecciones completas con paginación nativa



## 🔍 2. Radiografía del Módulo de Posts y Gestión de Relaciones

El diseño por rebanadas verticales exige que el contexto de `Posts` contenga todos los componentes necesarios para su ejecución de extremo a extremo, sin acoplar el núcleo de negocio a la capa de persistencia activa del framework.

### 2.1. Desacoplamiento de Relaciones en la Entidad (`Domain/Entities/Post.php`)

A diferencia del modelo nativo de Laravel (`App\Models\Post`), la entidad de dominio puro `Post` no contiene punteros de memoria ni llamadas mágicas (`belongsTo`) a la clase de usuario. La relación se modela utilizando un identificador de tipo primitivo o un objeto de valor (`UserId $userId`).

- **Aislamiento de Ciclos:** Esto evita que al instanciar un objeto de dominio se genere un árbol acoplado bidireccional infinito en la memoria, permitiendo que la entidad sea testeada de forma unitaria en milisegundos mediante PHPUnit sin inicializar conexiones de bases de datos.

### 2.2. Flujo del Orquestador de Casos de Uso (`CreatePostHandler.php`)

Cuando el sistema ejecuta una orden de creación mediante un comando:

1. El `CreatePostHandler` recibe el DTO `CreatePostCommand` con los tipos primitivos ya validados formalmente por la capa HTTP.
2. El manejador utiliza el contrato `IUserRepository` para verificar la validez, existencia y estado operativo del autor. Esto mitiga de forma proactiva la escalación de privilegios y accesos no autorizados.
3. Si los invariants de seguridad se cumplen, se invoca la factoría estática de la entidad `Post::create()`, la cual valida internamente las reglas intrínsecas del negocio (longitudes semánticas, asignación del estado de publicación).
4. Finalmente, se inyecta la entidad en el método `save()` del contrato `IPostRepository`.

### 2.3. Sincronización mediante Mappers (`PostMapper.php`)

El paso intermedio entre la pureza del dominio y los requisitos del framework se gestiona a través de los dos métodos del transformador estático:

- **`toEloquent(Post $entity)`**: Convierte el estado interno de la entidad rica en un mapa primitivo inyectable en las propiedades del modelo nativo `App\Models\Post`. Aquí es donde se define físicamente el campo clave `user_id` para garantizar la integridad referencial de la base de datos SQL.
- **`toDomain(PostModel $model)`**: Extrae el registro procesado por Eloquent y reconstruye el estado exacto de la entidad de dominio puro, protegiendo las capas internas del sistema de modificaciones accidentales originadas por los estados internos de Laravel.

## 🛡️ 3. Matriz de Mitigación OWASP Top 10 (Edición 2025)

Cada categoría crítica de vulnerabilidades se neutraliza mediante componentes específicos distribuidos en las capas de la arquitectura:

| Identificador OWASP | Categoría de Vulnerabilidad              | Componente de Arquitectura Encargado                         | Mecanismo de Control Técnico Implementado                    |
| ------------------- | ---------------------------------------- | ------------------------------------------------------------ | ------------------------------------------------------------ |
| **A01:2025**        | Broken Access Control                    | `Presentation/Http/Middleware/RoleAuthorizationMiddleware.php` | Intercepta la petición HTTP de forma perimetral. Compara los claims de identidad cifrados del token (JWT) contra el recurso solicitado, evitando la alteración de IDs en las rutas e impidiendo el acceso a datos ajenos de manera horizontal y vertical. |
| **A02:2025**        | Security Misconfiguration                | `Infrastructure/Security/Headers/` & `app/Swagger/`          | Configura políticas restrictivas de `Content-Security-Policy` (CSP), deshabilita la exposición de cabeceras de servidor (`X-Powered-By`) y aisla la documentación global de Swagger de los controladores para evitar fugas estrucuturales durante compilaciones automáticas. |
| **A03:2025**        | Software Supply Chain Failures           | Entorno Operacional de CI/CD (GitHub Actions / Jenkins)      | Integración obligatoria de herramientas SCA (*Software Composition Analysis*) como Snyk o OWASP Dependency-Check sobre el archivo `composer.lock` antes de autorizar el despliegue del software de infraestructura. |
| **A04:2025**        | Cryptographic Failures                   | `Infrastructure/Services/Cryptography/` & `Domain/ValueObjects/` | Forzado de protocolos TLS mediante cabeceras HSTS y encapsulación del hashing de contraseñas mediante implementaciones con alta carga computacional (**Argon2id** o **Bcrypt**). Evita el almacenamiento de secretos en texto plano. |
| **A05:2025**        | Injection                                | `Presentation/Http/Middleware/SanitizeInputMiddleware.php`   | Filtra y remueve secuencias de inyección SQL clásicas (`' OR '1'='1`) y etiquetas de scripting (`<script>`) a nivel perimetral de la petición entrante, forzando la conversión segura de tipos primitivos antes de la construcción de DTOs. |
| **A06:2025**        | Insecure Design                          | `Domain/Entities/` & `Presentation/Http/Requests/`           | Validación centralizada de los flujos de control críticos (como el restablecimiento de credenciales o transiciones de estados financieros) dentro de la lógica del núcleo de negocio, aplicando principios de diseño por contrato. |
| **A07:2025**        | Identification & Authentication Failures | `Presentation/Http/Middleware/RateLimiterMiddleware.php`     | Implementación de limitadores de tasa dinámicos basados en algoritmos de *Token Bucket* asignados por IP y por identificador de cuenta, impidiendo ataques automatizados de fuerza bruta sobre las rutas de login. |
| **A08:2025**        | Software & Data Integrity Failures       | Entorno de Despliegue Automatizado (CI/CD Pipeline)          | Firmado digital de los artefactos generados y pruebas automatizadas de regresión que aseguran la inmutabilidad y procedencia legítima del código fuente antes de su ejecución en producción. |
| **A09:2025**        | Security Logging & Alerting Failures     | `Infrastructure/Security/Logging/`                           | Centralización de logs del sistema de auditoría mediante adaptadores que interceptan y ofuscan datos altamente sensibles (passwords, tokens, secretos criptográficos de MFA) antes de escribirse en disco, disparando alertas en tiempo real ante anomalías de acceso masivo. |
| **A10:2025**        | Mishandling Exceptional Conditions       | `Presentation/Http/Requests/` & Manejador Global de Excepciones | Los `FormRequests` abortan de forma inmediata peticiones con formatos truncados, nulos, mal formados o strings con tamaños fuera de límite. El manejador global intercepta excepciones críticas de sistema y las transforma en respuestas seguras de API, ocultando por completo los *stack traces* de depuración al cliente final. |

## 🔐 4. Flujo Operacional de Identidad Federada con Google Sign-In + MFA

El sistema delega la autenticación primaria a un proveedor externo e implementa una segunda capa de seguridad mediante contraseñas temporales basadas en tiempo (TOTP), orquestado a través del patrón CQRS.

### 📊 Diagrama Secuencial de Autenticación en Doble Factor (MFA)

Plaintext

```
[Cliente]              [AuthController]          [LoginGoogleHandler]         [VerifyMfaHandler]         [Base Datos / Models]
    |                         |                            |                           |                          |
    |--- 1. Envia IdToken --->|                            |                           |                          |
    |    (Google Login)       |--- 2. Dispara Command ---->|                           |                          |
    |                         |                                                        |                          |
    |                         |<-- 3. Retorna Status Pre-Auth (MFA Requerido) ---------|                          |
    |<-- 4. Exige Código -----|                                                        |                          |
    |                                                                                  |                          |
    |--- 5. Envia Código 2FA --------------------------------------------------------->|                          |
    |    (TOTP App)                                                                    |--- 6. Valida Semilla --->|
    |                                                                                  |       Encriptada SQL     |
    |                                                                                  |<-- 7. Código Válido -----|
    |<-- 8. Entrega Token JWT Acceso Final (Bearer) -----------------------------------|                          |
```



### 4.1. Fase 1: Autenticación Primaria (`LoginWithGoogle`)

1. El cliente efectúa el inicio de sesión contra el SDK nativo de Google en la interfaz web y obtiene un `IdToken`. Este token se envía al endpoint `/api/auth/google`.
2. El `AuthController` recibe los datos, los valida formalmente en el `GoogleLoginRequest` y despacha el `LoginWithGoogleCommand`.
3. El `LoginWithGoogleHandler` utiliza la abstracción `SocialAuth` de la capa de aplicación para verificar la firma criptográfica del token contra las llaves públicas de la API de Google.
4. Tras validar el correo e identidad del usuario, el Handler consulta el `IUserRepository`. Si el usuario no tiene configurado el segundo factor de autenticación (MFA), el flujo concluye con la emisión directa del token Bearer JWT de acceso final.
5. **Comportamiento Seguro:** Si el usuario tiene activo el flag de seguridad multi-factor en la entidad de dominio `User`, el sistema **aborta la generación de credenciales finales**. El handler genera un token de estado restringido ("Pre-Autenticado"), con tiempo de expiración ultra-corto (por ejemplo, 3 minutos), con permisos exclusivos para consumir el endpoint de verificación.

### 4.2. Fase 2: Desafío de Segundo Factor (`VerifyMfaCode`)

1. El cliente recibe el estado parcial y despliega la interfaz de ingreso de token dinámico de seguridad. El usuario ingresa el código numérico de 6 dígitos provisto por su aplicación de autenticación (Google Authenticator, Bitwarden, etc.).
2. Los datos viajan junto al token temporal hacia el endpoint `/api/auth/mfa/verify`, validados por el `MfaVerificationRequest`.
3. El controlador despacha el `VerifyMfaCodeCommand` hacia el `VerifyMfaCodeHandler`.
4. El manejador lee los datos de pre-autenticación y solicita al `IUserRepository` el secreto inmutable y cifrado del usuario guardado en el backend.
5. El manejador invoca el motor del servicio técnico `Infrastructure/Services/Mfa/`, el cual ejecuta el algoritmo matemático **TOTP (RFC 6238)** comparando el código enviado con la ventana de tiempo del servidor contra la semilla del usuario.
6. Si la validación es exitosa, se actualiza el estado de la entidad, se invalida el token temporal y se genera un token JWT corporativo completo con todos los privilegios asociados al rol del usuario, asegurando un blindaje infranqueable contra fugas de credenciales primarias.

## 🛠️ 5. Resolución Estructural del Motor Documental Swagger

Para solucionar el error recurrente del motor de renderizado de OpenAPI (`Required @OA\Info() not found`), la arquitectura centraliza los metadatos globales en un archivo libre de dependencias HTTP de controladores.

### 5.1. Centralización Global (`app/Swagger/SwaggerConfig.php`)

Este componente técnico agrupa la definición obligatoria del árbol de parsing de OpenAPI. Al agrupar estos bloques en la raíz de la aplicación, el comando de Artisan `php artisan l5-swagger:generate` inicializa correctamente el contexto de la documentación antes de recorrer las rutas de los controladores, evitando interrupciones por dependencias cíclicas o falta de declaraciones de rutas iniciales:

- **Bloque Informativo:** Define los metadatos de visualización (`@OA\Info`), versiones de producción, términos de servicio y datos de contacto de ingeniería.
- **Bloque de Servidores:** Especifica las URLs base de los entornos operativos (`@OA\Server`), aislando los dominios locales de los entornos de staging o producción.
- **Esquemas de Seguridad:** Inicializa las definiciones de seguridad global (`@OA\SecurityScheme`). Configura el comportamiento de los encabezados de autorización HTTP tipo `Bearer JWT Token`, permitiendo que la interfaz web de Swagger inyecte automáticamente el token en cada endpoint de lectura o escritura protegido por los middlewares de la aplicación.