# 🗣️🧠 Plataforma Interactiva de Comunicación y Aprendizaje para Personas con Trastornos de Lenguaje 🗣️🧠

En Colombia, miles de personas enfrentan retos comunicativos debido a condiciones como el **Trastorno del Espectro Autista (TEA)**, la **afasia**, la **disartria** y otras alteraciones del lenguaje. 🇨🇴 Según estimaciones del Ministerio de Salud y Protección Social, uno de cada 160 niños presenta algún tipo de autismo, y el país aún enfrenta desafíos significativos en términos de inclusión educativa, accesibilidad comunicativa y atención especializada. 🏥

Frente a esta necesidad, surge la oportunidad de construir una plataforma interactiva que integre **tecnología accesible** 💻, herramientas visuales 👁️, auditivas 👂 y táctiles, así como recursos multilingües, para promover la autonomía y el aprendizaje de las personas con dificultades del lenguaje en Colombia. Este tipo de solución también puede ser utilizada por docentes de educación especial, terapeutas del lenguaje y cuidadores. 👩‍🏫👨‍⚕️

------



## 💻 Frontend: Arquitectura y Desarrollo ⚙️

Para el desarrollo del frontend, se utilizará **React** con una arquitectura de patrones de diseño que asegura la escalabilidad, mantenibilidad y accesibilidad del proyecto.

### 🏗️ Patrones de Diseño del Frontend ✨

1. **Patrón de Hooks Personalizados (`Custom Hooks`):** Permite encapsular y reutilizar la lógica de negocio entre diferentes componentes. Se crearán hooks específicos para manejar interacciones con la API (ej. `useFetchCards`), el estado del audio (`useAudioPlayer`), y el seguimiento del progreso del usuario (`useProgressTracker`), evitando la duplicación de código.
2. **Patrón de Proveedor (`Provider Pattern`):** Esencial para gestionar el estado global de la aplicación utilizando la Context API de React. Se establecerán contextos para compartir datos cruciales como la configuración de idioma, el estado de autenticación del usuario y el método de comunicación preferido, evitando el "prop drilling".
3. **Patrón de Componentes Compuestos (`Compound Components`):** Se utilizará para construir componentes de la interfaz de usuario con múltiples partes que trabajan en conjunto, como un selector de lecciones o un visor de tarjetas. Este patrón mejora la flexibilidad y la legibilidad del código.

------



## 📂 Estructura de Carpetas 🌳

La organización del proyecto se basará en los patrones de diseño para mantener el código modular y escalable.

```
src/ 📁
├── assets/ 🖼️                      # Imágenes, íconos, fuentes
├── components/ 🏗️                  # Componentes de presentación y compuestos
│   ├── Card/ 🎟                    # Componente compuesto para las tarjetas
│   │   ├── Card.jsx ⚛️
│   │   └── Card.css 💅
│   ├── LessonSelector/ 📖          # Componente compuesto para lecciones
│   │   └── LessonSelector.jsx ⚛️
│   └── Shared/ 🤝                  # Componentes reutilizables (botones, modales, etc.)
├── context/ 🌐                     # Contextos globales de la aplicación
│   ├── AuthContext.jsx 🔒
│   ├── LanguageContext.jsx 🗣️
│   └── ProgressContext.jsx 📈
├── hooks/ 🪝                       # Hooks personalizados para lógica reutilizable
│   ├── useFetch.js 📡
│   ├── useAudioPlayer.js 🎧
│   └── useProgressTracker.js 🎯
├── pages/ 📄                       # Componentes que representan páginas/vistas
│   ├── HomePage.jsx 🏠
│   ├── LessonsPage.jsx 🧑‍🏫
│   └── AdminDashboard.jsx 📊
├── services/ 📡                    # Lógica de comunicación con la API
│   ├── cardService.js 🃏
│   └── userService.js 👤
├── App.jsx 🚀                      # Componente principal de la aplicación
└── main.jsx 🏁                     # Punto de entrada de la aplicación
```

------



## 🛠️ Tecnologías Principales 💻

- **Frontend:** React ✅
- **Estilos:** Tailwind CSS 🎨
- **Despliegue:** Docker 🐳
- **Gestor de paquetes:** npm / yarn 📦

------



## 🧑‍💻 Desarrollador Principal 👨‍💻

- **kevincito0987**: [GitHub](https://github.com/kevincito0987) 🚀

------



## ✨ Frase Estelar ✨

**Cada componente 🧩 que escribimos es un paso hacia la creación de una experiencia fluida 🌊 y un mundo digital más accesible. 💻**
