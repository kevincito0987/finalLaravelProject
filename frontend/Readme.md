# 🗣️🧠 Plataforma Interactiva de Comunicación y Aprendizaje (ComunicaTech)



## Visión General del Proyecto



PLATICA es una **Plataforma Interactiva de Comunicación y Aprendizaje** diseñada para mitigar los retos comunicativos que enfrentan miles de personas con trastornos del lenguaje (como TEA, afasia y disartria) en Colombia. Integrando tecnología accesible, herramientas visuales, auditivas y táctiles, buscamos fomentar la **autonomía** y el **aprendizaje inclusivo**.

La plataforma está dirigida a los propios usuarios, así como a **docentes de educación especial**, **terapeutas del lenguaje**, y **cuidadores**.



## 🛠️ Tecnologías Principales



| Área             | Tecnología               | Propósito                                                    |
| ---------------- | ------------------------ | ------------------------------------------------------------ |
| **Frontend**     | **React & TypeScript** ✅ | Desarrollo de la interfaz de usuario con **tipado estático** para robustez y mantenibilidad. |
| **Estilos**      | **Tailwind CSS** 🎨       | Framework de CSS *utility-first* para un desarrollo rápido y diseño accesible. |
| **Tooling**      | **Vite** ⚡               | Entorno de desarrollo rápido, *bundler* y soporte nativo para TypeScript. |
| **Linting**      | **ESLint** ⚙️             | Asegura la calidad del código y el cumplimiento de las guías de estilo. |
| **Contenedores** | **Docker** 🐳             | Empaquetado y despliegue consistente del entorno.            |

Exportar a Hojas de cálculo

------



## 💻 Arquitectura Frontend: React & TypeScript



El frontend se construye sobre **React** y utiliza **TypeScript** para garantizar la **escalabilidad**, **mantenibilidad** y **robustez** del código. Aplicamos una arquitectura basada en **patrones de diseño de React** que se refuerzan con el tipado estático de TS.



### 🏗️ Patrones de Diseño Centrales (Tipados con TypeScript)



El uso de **TypeScript** obliga a definir explícitamente las *interfaces* y *tipos*, lo cual potencia la efectividad de estos patrones:

| Patrón                                   | Propósito                                                    | Beneficio con TypeScript                                     |
| ---------------------------------------- | ------------------------------------------------------------ | ------------------------------------------------------------ |
| **1. Patrón de Hooks Personalizados**    | Encapsula y reutiliza la lógica de negocio (API, audio, progreso). | Garantiza que la **lógica reutilizable** sea predecible al definir estrictamente el tipo de retorno (`[estado, controladores]`). |
| **2. Patrón de Proveedor (Context API)** | Gestiona el estado global (idioma, autenticación) y lo comparte sin *prop drilling*. | Permite **tipar el estado global** (`ContextType`), asegurando que cualquier componente consumidor sepa exactamente qué datos y funciones usar. |
| **3. Patrón de Componentes Compuestos**  | Construye componentes de UI complejos con partes que trabajan juntas (ej. `Card.Image`). | Asegura que la **API interna del componente** sea usada de manera correcta gracias a las *Interfaces* y el tipado de *props*. |

Exportar a Hojas de cálculo

------



## 📂 Estructura de Carpetas (Completa)



La organización sigue una **estructura modular** e incluye todos los archivos de configuración en la raíz del proyecto, esenciales para el ecosistema TypeScript/Vite.

```
.Frontend
├── assets/ 🖼️                       # Archivos estáticos (imágenes, íconos, etc.)
├── src/ 📁                         # 📦 El código fuente del proyecto
│   ├── components/ 🏗️               # Componentes de presentación y compuestos (UI)
│   ├── context/ 🌐                   # Contextos globales (Provider Pattern)
│   ├── hooks/ 🪝                     # Hooks personalizados
│   ├── pages/ 📄                     # Componentes que representan páginas/vistas
│   ├── services/ 📡                  # Lógica de comunicación con la API
│   ├── types/ 📝                     # Definiciones de tipos e interfaces globales
│   ├── App.tsx 🚀
│   └── main.tsx 🏁
|
├── .gitignore 👻                     # Archivos y carpetas ignorados por Git
├── eslint.config.js ⚙️              # Configuración de ESLint para la calidad del código
├── index.html 📄                     # El punto de entrada principal del HTML
├── package.json 📦                   # Dependencias de Node
├── postcss.config.mjs ⚙️            # Configuración de PostCSS (para Tailwind CSS)
├── README.md 📄
├── tsconfig.json ⚙️                 # Configuración base de TypeScript (extiende a los demás)
├── tsconfig.app.json ⚙️             # Configuración de TypeScript específica para el código de la aplicación
├── tsconfig.node.json ⚙️            # Configuración de TypeScript específica para el entorno de Node (e.g., scripts, Vite)
└── vite.config.ts ⚡                 # Configuración de Vite (utiliza TS para tipado y auto-completado)
```

------



## 🚀 Despliegue y Contribución





### Instalación



1. Clona el repositorio:

   Bash

   ```
   git clone [URL_DEL_REPOSITORIO]
   cd platica-frontend
   ```

2. Instala las dependencias:

   Bash

   ```
   npm install
   ```



### Desarrollo Local



Inicia el servidor de desarrollo con Vite:

Bash

```
npm run dev
```



### Build para Producción



Genera la *build* optimizada para despliegue:

Bash

```
npm run build
```



## 🧑‍💻 Desarrollador Principal



**kevincito0987**: [GitHub Profile](https://github.com/kevincito0987) 🚀

------



### ✨ Frase Estelar ✨



> Cada componente 🧩 que escribimos es un paso hacia la creación de una experiencia fluida 🌊 y un mundo digital **más accesible**. **TypeScript** no solo hace nuestro código más seguro, sino también **más claro y escalable** para este noble propósito.