# 🗣️🧠 Backend: Clean Architecture con Laravel (ComunicaTech)🗣️🧠

Este es el backend de la Plataforma Interactiva de Comunicación y Aprendizaje, construido sobre una arquitectura robusta para garantizar la escalabilidad, la mantenibilidad y la testabilidad del sistema. 🚀

## 🏛️ Patrones de Diseño & Arquitectura 🛡️

El corazón de este backend es la **Arquitectura Limpia (Clean Architecture)**. Este patrón divide la aplicación en capas concéntricas, donde cada capa interna no conoce los detalles de las capas externas. Esto asegura que la lógica de negocio principal sea independiente de la tecnología, facilitando el cambio de bases de datos, frameworks o cualquier dependencia externa sin afectar las reglas del negocio.

### 📚 Principios Clave de la Arquitectura Limpia

- **Independencia de Frameworks:** La lógica central (Entidades y Casos de Uso) no depende de Laravel. Si en el futuro se necesita cambiar a otro framework (ej. Symfony), el núcleo de la aplicación permanece intacto.
- **Independencia de la Base de Datos:** La lógica no está atada a MySQL o PostgreSQL. Puedes cambiar la base de datos sin reescribir las reglas de negocio.
- **Testabilidad:** Cada capa se puede probar de forma aislada, sin necesidad de bases de bases de datos o servidores web.

### 🧩 Implementación en Laravel

Se utilizarán los siguientes patrones para implementar Clean Architecture en Laravel:

- **Patrón de Repositorio (`Repository Pattern`):** Para separar la lógica de acceso a la base de datos de la lógica de negocio. Los controladores interactuarán con una interfaz de repositorio, no directamente con Eloquent.
- **Patrón de Estrategia (`Strategy Pattern`):** Como prototipo para manejar los diferentes métodos de comunicación (visual, auditivo, táctil), garantizando que la lógica de negocio pueda cambiar de un método a otro de forma dinámica y sin acoplamiento.
- **Servicios (`Services`):** Se crearán clases de servicio para encapsular la lógica de negocio compleja que no pertenece a los modelos o controladores, como la gestión de lecciones o el registro de progreso.

## 📂 Estructura de Carpetas 🌳

La organización refleja las capas de la Arquitectura Limpia, manteniendo el orden y la claridad del proyecto.

```
.Backend
├── app/ 📂                     # 🧠 El núcleo de la aplicación con la Arquitectura Limpia
│   ├── Console/ 🧠                # El núcleo de la aplicación que gestiona conexion con buckets de almacenamiento.
│   ├── Core/ 🧠                # El núcleo de la aplicación, independiente de Laravel
│   │   ├── Entities/ 🛡️        # Las reglas de negocio (tarjetas, usuarios, etc.)
│   │   ├── Repositories/ 🗄️    # Interfaces para la capa de persistencia
│   │   ├── Services/ ⚙️         # Lógica de negocio reusable
│   │   └── Interfaces/ ♟️        # Implementaciones del Repository con interfaces
│   │   └── Strategies/ ♟️        # Implementaciones del Strategy Pattern
│   ├── Http/ 🌐                # La capa de comunicación (Controllers, Requests)
│   │   ├── Controllers/ 🌐      # Controladores que manejan las peticiones
│   │   ├── Middlewares/ 🔑      # Lógica para filtrar peticiones HTTP
│   │   └── Requests/ 📝         # Validaciones y autorización de peticiones
│   ├── Providers/ 🤝           # Binding de interfaces a implementaciones
│   ├── Console/ 💻             # Comandos de consola personalizados
│   ├── Models/ 📊              # Modelos de Eloquent
│   ├── Mail/ 📧                # Notificaciones por correo electrónico
│   ├── Services/ 📡            # Servicios de terceros y utilidades
│   ├── Traits/ 🧬              # Lógica reutilizable entre clases
│   ├── Jobs/ 👷                # Procesos que se ejecutan en segundo plano
│   ├── Events/ 📢              # Eventos para desacoplar la lógica
│   └── Listeners/ 👂           # Oyentes de los eventos
├── bootstrap/ 🚀               # ⚙️ Archivos para inicializar el framework y la carga automática
├── config/ ⚙️                  # ⚙️ Todos los archivos de configuración
├── database/ 🗄️                # 💾 Migraciones, seeders y factorías para la base de datos
├── lang/ 🌐                    # 🗣️ Archivos de localización (traducciones)
├── public/ 🌎                  # 📦 Archivos públicos (CSS, JS, imágenes, etc.)
├── resources/ 🎨               # 🖼️ Archivos de vistas y assets sin compilar
├── routes/ 🛣️                  # 🧭 Todas las definiciones de rutas web y API
├── storage/ 📦                 # 💾 Archivos generados (logs, cache, etc.)
├── tests/ ✅                   # 🧪 Pruebas unitarias y de características
├── vendor/ 📦                  # 📦 Dependencias de Composer
├── .env ⚙️                     # 🔑 Variables de entorno
├── .env.example ⚙️             # 🔑 Ejemplo de variables de entorno
├── .editorconfig ✍️             # 📝 Configuración de estilo del editor
├── .gitattributes ⚙️           # 📝 Configuración de Git
├── .gitignore 👻               # 📝 Archivos y carpetas ignorados por Git
├── artisan 💻                  # 🧙 Comando de línea para Laravel
├── composer.json 🎼            # 📦 Dependencias de PHP
├── composer.lock 🔒            # 📦 Versiones exactas de las dependencias
├── postcss.config.js ⚙️        # ⚙️ Configuración de PostCSS
├── tailwind.config.js ⚙️       # ⚙️ Configuración de Tailwind CSS
├── vite.config.js ⚙️           # ⚙️ Configuración de Vite
├── package.json 📦             # 📦 Dependencias de Node (para frontend)
├── phpunit.xml 🧪              # 🧪 Configuración para pruebas PHPUnit
└── README.md 📖                # 📝 Este mismo archivo
```

## 🛠️ Tecnologías Principales 💻

- **Framework:** Laravel 11 ✨
- **Base de datos:** MySQL / PostgreSQL / Supabase 🗄️
- **Docker:** Para el entorno de desarrollo y despliegue 🐳
- **Autenticación (OAuth 2 & JWT):** Para inicio de sesión con Google y GitHub. 🔑
- **Multilenguaje:** Laravel Localization 🗣️
- **Traducción:** `libre-translate` 📖
- **Documentación API:** Swagger API 📝
- **Correos:** Laravel Mail 📧
- **Almacenamiento:** Laravel File Storage 📁
- **Validaciones:** Validaciones a nivel de Form Request 🛡️

## 🧑‍💻 Desarrollador Principal 👨‍💻

- **kevincito0987**: [GitHub](https://github.com/kevincito0987) 🚀

## ✨ Frase Estelar ✨

**La arquitectura es la base de un proyecto duradero. Construir con lógica sólida nos permite crear un mundo digital que perdura y crece con cada necesidad. 🏗️**
