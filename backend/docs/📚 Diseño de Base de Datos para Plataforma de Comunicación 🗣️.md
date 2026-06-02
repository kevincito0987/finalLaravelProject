# 📚 Diseño de Base de Datos para Plataforma de Comunicación e Inclusión Lenguaje 🗣️

Este documento describe el Modelo Entidad-Relación (MER) definitivo de nuestra plataforma interactiva de comunicación y aprendizaje para personas con trastornos del lenguaje en Colombia. Su diseño sigue rigurosamente las reglas de normalización hasta la **Cuarta Forma Normal (4NF)**, aislando de manera independiente las relaciones multivaloradas multimedia, de localización y los canales de interacción sensorial para garantizar una arquitectura de datos robusta, escalable y sin redundancias.

Toda la estructura multimedia de almacenamiento de rutas (`paths` y `URLs`) ha sido configurada con el tipo de dato **`TEXT`** para reflejar con exactitud el modelado técnico y dar soporte a URLs extensas firmadas en la nube.

## 🔑 Entidades Principales

### 1. `roles` 📜

Esta tabla gestiona los niveles de acceso y perfiles de seguridad fundamentales de la aplicación.

- **Propósito**: Controlar los privilegios funcionales de los actores del sistema (ej: Administradores, Terapeutas, Cuidadores, Pacientes).

| **Campo** | **Descripción**                                   | **Propiedades**                    |
| --------- | ------------------------------------------------- | ---------------------------------- |
| **id**    | Identificador único y primario del rol.           | `PK`, `INTEGER`, `autoincremental` |
| **name**  | Nombre único del rol administrativo o de usuario. | `VARCHAR(50)`, `único`             |

**Relación:**

- **Uno a Muchos (`1:N`)** con la tabla `users`. 🧑‍🤝‍🧑
  - **Explicación**: Un único rol del sistema puede ser asignado a múltiples usuarios de la plataforma de forma individual.
  - **Ejemplo**: El rol 'admin' posee el `id` `1`. Al registrar a los desarrolladores o terapeutas principales en el panel de control, sus registros en la tabla `users` heredarán el `role_id` `1`, otorgándoles middleware de protección de rutas automático.

### 2. `users` 🧑‍💻

Almacena la identidad, credenciales de acceso y la personalización visual de los usuarios de la plataforma.

- **Propósito**: Autenticar las sesiones y asociar el progreso cognitivo a una persona específica.

| **Campo**              | **Descripción**                                              | **Propiedades**                    |
| ---------------------- | ------------------------------------------------------------ | ---------------------------------- |
| **id**                 | Identificador primario y único del usuario.                  | `PK`, `INTEGER`, `autoincremental` |
| **role_id**            | Clave foránea que asocia al usuario con sus permisos de rol. | `FK`, `INTEGER`                    |
| **name**               | Nombre completo del usuario, cuidador o paciente.            | `VARCHAR(255)`                     |
| **email**              | Correo electrónico único para el inicio de sesión seguro.    | `VARCHAR(255)`, `único`            |
| **password**           | Hash de la contraseña cifrada de forma segura.               | `VARCHAR(255)`                     |
| **profile_photo_path** | Ruta relativa al storage para la foto de perfil del usuario. | `TEXT`, `nullable`                 |

**Relación:**

- **Muchos a Uno (`N:1`)** con la tabla `roles`.
  - **Explicación**: Múltiples usuarios de la fundación o entorno clínico comparten las mismas propiedades del rol sin duplicar datos.
  - **Ejemplo**: Los pacientes 'Carlos' y 'Diana' tienen asignado el `role_id` `2` ('user'). Ambos comparten las restricciones de rutas del middleware pero mantienen su progreso aislado.

### 3. `communication_methods` 📡

Define los diferentes tipos de interacción sensorial adaptados a las condiciones de los pacientes (TEA, afasia, disartria) bajo el esquema del patrón Strategy.

- **Propósito**: Permitir el filtrado dinámico en el Frontend (plantilla Habita) según el canal sensorial preferido del usuario.

| **Campo**          | **Descripción**                                              | **Propiedades**                    |
| ------------------ | ------------------------------------------------------------ | ---------------------------------- |
| **id**             | Identificador único del método sensorial.                    | `PK`, `INTEGER`, `autoincremental` |
| **name**           | Nombre del canal (ej: 'visual', 'auditivo', 'tactil').       | `VARCHAR(50)`, `único`             |
| **strategy_class** | Namespace de la clase que implementa la lógica algorítmica en PHP. | `VARCHAR(255)`                     |

**Relación:**

- **Muchos a Muchos (`N:M`)** con la tabla `cards` a través de la tabla pivote `card_communication_methods`. 🖐️
  - **Explicación**: Un método sensorial engloba múltiples tarjetas adaptadas, y una misma tarjeta puede ser idónea para más de un canal sensorial de forma independiente.
  - **Ejemplo**: El método 'visual' (id `1`) mapea todas las tarjetas con pictogramas e imágenes grandes para usuarios con deficiencia auditiva o autismo severo.

### 4. `categories` 🏷️

Organiza las lecciones e interacciones en temas coherentes y estructurados.

- **Propósito**: Clasificar temáticamente el entorno de comunicación alternativa para facilitar la navegación, búsqueda y creación de lecciones (ej: 'Necesidades Básicas', 'Alimentos').

| **Campo**       | **Descripción**                               | **Propiedades**                    |
| --------------- | --------------------------------------------- | ---------------------------------- |
| **id**          | Identificador único de la categoría.          | `PK`, `INTEGER`, `autoincremental` |
| **name**        | Nombre representativo de la categoría.        | `VARCHAR(100)`                     |
| **description** | Detalle clínico o pedagógico de la categoría. | `TEXT`, `nullable`                 |

**Relación:**

- **Uno a Muchos (`1:N`)** con la tabla `lessons`. 📁
  - **Explicación**: Una categoría madre puede agrupar a múltiples lecciones lógicas de aprendizaje secuencial.
  - **Ejemplo**: La categoría 'Emociones' (id `3`) contiene las lecciones individuales "Expresar Alegría" y "Manejo de Frustración".

## 🗂️ Entidades de Contenido Multimedial y Localización

### 5. `cards` 🃏

Representación central abstracta de las tarjetas de comunicación (Flashcards), independizada de la traducción idiomática para cumplir la **4ta Forma Normal (4NF)**.

- **Propósito**: Consolidar la identidad interactiva de la tarjeta, su recurso visual universal (imagen de fondo interactiva) y su simulación de hardware RFID mediante códigos únicos.

| **Campo**                 | **Descripción**                                              | **Propiedades**                    |
| ------------------------- | ------------------------------------------------------------ | ---------------------------------- |
| **id**                    | Identificador único de la tarjeta de comunicación.           | `PK`, `INTEGER`, `autoincremental` |
| **uuid**                  | Código UUID universal simulado para el escaneo o proximidad RFID. | `VARCHAR(36)`, `único`             |
| **background_image_path** | Ruta/URL de la imagen interactiva de fondo para renderizar en el contenedor. | `TEXT`, `nullable`                 |

**Relación:**

- **Uno a Muchos (`1:N`)** con la tabla `card_translations`. 🌍
  - **Explicación**: Una tarjeta abstracta de fondo posee múltiples traducciones de audios y textos según Laravel Localization de manera atómica.
  - **Ejemplo**: La tarjeta con `id` `5` (que muestra una manzana) tiene una traducción en español ("Manzana") y otra en inglés ("Apple"), aislando el cambio de idioma del recurso de almacenamiento.
- **Muchos a Muchos (`N:M`)** con la tabla `lessons` a través de la tabla intermedia `lesson_cards`.
  - **Explicación**: Una tarjeta puede reutilizarse a lo largo de múltiples módulos de aprendizaje diarios o de refuerzo.
- **Muchos a Muchos (`N:M`)** con la tabla `communication_methods` a través de `card_communication_methods`.

### 6. `card_translations` 🗣️💬

Almacena la capa semántica y de audio de las tarjetas por código de idioma, implementando el soporte multilenguaje requerido por la aplicación.

- **Propósito**: Proveer internacionalización limpia y archivos de voz para la reproducción automática multisensorial al interactuar con el recurso.

| **Campo**         | **Descripción**                                              | **Propiedades**                    |
| ----------------- | ------------------------------------------------------------ | ---------------------------------- |
| **id**            | Identificador único del registro de traducción.              | `PK`, `INTEGER`, `autoincremental` |
| **card_id**       | Clave foránea que enlaza la traducción con su tarjeta base.  | `FK`, `INTEGER`                    |
| **language_code** | Código del idioma bajo estándar ISO (ej: 'es', 'en').        | `VARCHAR(5)`                       |
| **key_phrase**    | Palabra o frase de comunicación que leerá o escuchará el paciente. | `VARCHAR(255)`                     |
| **audio_path**    | Ruta/URL del archivo de audio almacenado en el storage para reproducción. | `TEXT`, `nullable`                 |
| **Clave Única**   | Restricción compuesta para evitar duplicar un idioma en una misma tarjeta. | `UNIQUE(card_id, language_code)`   |

**Relación:**

- **Muchos a Uno (`N:1`)** con la tabla `cards`.
  - **Explicación**: Cada traducción e instrucción por voz pertenece estrictamente a una única tarjeta del sistema.
  - **Ejemplo**: El registro con `language_code = 'es'` mapea el texto "Quiero agua" y su respectivo audio `.mp3` en español hacia la tarjeta base de solicitud de hidratación.

### 7. `card_communication_methods` 🔀

Tabla pivote intermedia que rompe la relación de muchos a muchos entre las tarjetas y sus canales sensoriales preferidos.

- **Propósito**: Ejecutar la normalización de la base de datos evitando la redundancia de colecciones u opciones en vectores, cumpliendo la 4NF de dependencias multivaloradas independientes.

| **Campo**     | **Descripción**                                              | **Propiedades**       |
| ------------- | ------------------------------------------------------------ | --------------------- |
| **card_id**   | Clave foránea constituyente vinculada a la tarjeta interactiva. | `PK`, `FK`, `INTEGER` |
| **method_id** | Clave foránea constituyente vinculada al método sensorial.   | `PK`, `FK`, `INTEGER` |

## 🎯 Entidades de Lecciones y Evaluaciones

### 8. `lessons` ✍️

Estructura las unidades didácticas o de entrenamiento diario que la plataforma asignará de forma automatizada al iniciar sesión.

- **Propósito**: Organizar el contenido de la aplicación en unidades de aprendizaje secuenciales y coherentes.

| **Campo**       | **Descripción**                                              | **Propiedades**                    |
| --------------- | ------------------------------------------------------------ | ---------------------------------- |
| **id**          | Identificador único de la lección del sistema.               | `PK`, `INTEGER`, `autoincremental` |
| **category_id** | Clave foránea que vincula la lección con su categoría contenedora. | `FK`, `INTEGER`                    |
| **title**       | Título de la unidad didáctica (ej: 'Saludos de Cortesía').   | `VARCHAR(255)`                     |

**Relación:**

- **Muchos a Muchos (`N:M`)** con la tabla `cards` a través de la tabla intermedia `lesson_cards`.
  - **Explicación**: Una lección agrupa un set secuencial de flashcards de comunicación, y una flashcard puede servir de refuerzo en múltiples lecciones.
- **Uno a Muchos (`1:N`)** con la tabla `evaluations`. 📝
  - **Explicación**: Una lección posee cuestionarios interactivos asociados para auditar el avance cognitivo del alumno.
  - **Ejemplo**: La lección "Objetos del Aula" tiene asignada su respectiva evaluación diagnóstica multimedia al final del recorrido.

### 9. `lesson_cards` 🧩

Tabla pivote de desambiguación `N:M` que gestiona las tarjetas que componen cada lección y define su flujo secuencial.

- **Propósito**: Controlar el orden jerárquico de visualización interactiva para el usuario dentro del módulo de aprendizaje.

| **Campo**           | **Descripción**                                              | **Propiedades**       |
| ------------------- | ------------------------------------------------------------ | --------------------- |
| **lesson_id**       | Clave foránea constituyente vinculada a la lección.          | `PK`, `FK`, `INTEGER` |
| **card_id**         | Clave foránea constituyente vinculada a la tarjeta.          | `PK`, `FK`, `INTEGER` |
| **order_in_lesson** | Orden numérico consecutivo para la presentación en el frontend. | `SMALLINT`            |

### 10. `evaluations` 📝

Representa el contenedor de la prueba de conocimiento o test interactivo adjunto a una lección completada.

- **Propósito**: Agrupar preguntas para emitir métricas cuantitativas del progreso del paciente.

| **Campo**     | **Descripción**                                              | **Propiedades**                    |
| ------------- | ------------------------------------------------------------ | ---------------------------------- |
| **id**        | Identificador primario de la evaluación.                     | `PK`, `INTEGER`, `autoincremental` |
| **lesson_id** | Clave foránea que conecta la prueba con la lección de origen. | `FK`, `INTEGER`                    |
| **title**     | Nombre descriptivo del examen interactivo.                   | `VARCHAR(255)`                     |

**Relación:**

- **Uno a Muchos (`1:N`)** con la tabla `evaluation_questions`. ❓
  - **Explicación**: Una evaluación está integrada por múltiples preguntas de opción múltiple u opcionales con imágenes.
- **Uno a Muchos (`1:N`)** con la tabla `user_evaluations`.
  - **Explicación**: Una misma evaluación recopila los diferentes intentos realizados por los alumnos de la plataforma a lo largo del tiempo.

### 11. `evaluation_questions` 🖼️❓

Almacena las interrogantes de la prueba, permitiendo de forma opcional el despliegue de multimedia accesible (imágenes) bajo la arquitectura de Atributo Opcional Controlado por Estado.

- **Propósito**: Soportar preguntas con apoyos visuales para niños o pacientes con dificultades cognitivas agudas (TEA).

| **Campo**               | **Descripción**                                              | **Propiedades**                    |
| ----------------------- | ------------------------------------------------------------ | ---------------------------------- |
| **id**                  | Identificador único de la pregunta.                          | `PK`, `INTEGER`, `autoincremental` |
| **evaluation_id**       | Clave foránea vinculada a la evaluación contenedora.         | `FK`, `INTEGER`                    |
| **question_text**       | Enunciado o instrucción de la pregunta interactiva.          | `TEXT`                             |
| **question_image_path** | Campo opcional (`TEXT`). Si contiene ruta, el frontend dibuja la imagen de apoyo; si es `NULL`, muestra texto limpio. | `TEXT`, `nullable`                 |
| **correct_answer**      | Cadena de texto exacta que representa la solución correcta esperada. | `VARCHAR(255)`                     |

**Relación:**

- **Uno a Muchos (`1:N`)** con la tabla `user_evaluation_answers`.
  - **Explicación**: Almacena de forma histórica cada respuesta individual dada a esta pregunta en específico para reportes avanzados de error.

## 📈 Entidades de Progreso y Métricas del Estudiante

### 12. `user_lessons` 📊

Tabla intermedia para la asignación y control de estados de las lecciones diarias asignadas automáticamente mediante la capa de Servicios en el backend.

- **Propósito**: Mapear qué lecciones tiene asignadas o completadas un usuario específico de forma histórica.

| **Campo**       | **Descripción**                                             | **Propiedades**                    |
| --------------- | ----------------------------------------------------------- | ---------------------------------- |
| **id**          | Identificador único del registro de asignación.             | `PK`, `INTEGER`, `autoincremental` |
| **user_id**     | Clave foránea vinculada al estudiante.                      | `FK`, `INTEGER`                    |
| **lesson_id**   | Clave foránea vinculada a la lección asignada.              | `FK`, `INTEGER`                    |
| **status**      | Estado actual de la lección (ej: 'assigned', 'completed').  | `VARCHAR(20)`                      |
| **assigned_at** | Marca de tiempo de la asignación automática por middleware. | `TIMESTAMP`                        |

### 13. `user_card_interactions` 🏷️🔊

Registra cada evento interactivo en tiempo real del paciente con las flashcards (clics, reproducciones de audio o escaneos UUID/RFID).

- **Propósito**: Alimentar la analítica de uso del terapeuta para determinar el nivel de comunicación alternativa y configurar de forma automatizada los módulos de refuerzo.

| **Campo**            | **Descripción**                                              | **Propiedades**                    |
| -------------------- | ------------------------------------------------------------ | ---------------------------------- |
| **id**               | Identificador del log de la interacción.                     | `PK`, `INTEGER`, `autoincremental` |
| **user_id**          | Clave foránea que identifica al usuario que interactuó.      | `FK`, `INTEGER`                    |
| **card_id**          | Clave foránea vinculada a la tarjeta accionada.              | `FK`, `INTEGER`                    |
| **interaction_type** | Canal del evento (ej: 'rfid_scan' para tarjetas físicas, 'click'). | `VARCHAR(50)`                      |
| **interacted_at**    | Fecha y hora exacta de la interacción sensorial.             | `TIMESTAMP`                        |

### 14. `user_evaluations` 💯

Modela el intento global de un examen completado por un usuario, guardando la trazabilidad cuantitativa de su calificación.

- **Propósito**: Almacenar los puntajes de las evaluaciones para reportes de progreso en el panel administrativo.

| **Campo**         | **Descripción**                                              | **Propiedades**                    |
| ----------------- | ------------------------------------------------------------ | ---------------------------------- |
| **id**            | Identificador del intento de evaluación.                     | `PK`, `INTEGER`, `autoincremental` |
| **user_id**       | Clave foránea que identifica al alumno que rindió la prueba. | `FK`, `INTEGER`                    |
| **evaluation_id** | Clave foránea vinculada al test realizado.                   | `FK`, `INTEGER`                    |
| **score**         | Calificación numérica obtenida (ej: 4.50, 5.00).             | `DECIMAL(5,2)`                     |
| **completed_at**  | Marca de tiempo del fin de la evaluación.                    | `TIMESTAMP`                        |

**Relación:**

- **Uno a Muchos (`1:N`)** con la tabla `user_evaluation_answers`.
  - **Explicación**: Se conecta directo con el desglose detallado de las respuestas seleccionadas por el alumno en este intento específico.

### 15. `user_evaluation_answers` 📝❌

Detalla la respuesta seleccionada para cada pregunta en un intento de evaluación, registrando el éxito o fallo mediante una bandera booleana (`TINYINT` en StarUML).

- **Propósito**: Auditar minuciosamente el rendimiento pregunta por pregunta para el control y retroalimentación de los cuidadores.

| **Campo**              | **Descripción**                                              | **Propiedades**                    |
| ---------------------- | ------------------------------------------------------------ | ---------------------------------- |
| **id**                 | Identificador primario de la respuesta guardada.             | `PK`, `INTEGER`, `autoincremental` |
| **user_evaluation_id** | Clave foránea que la asocia al intento global en `user_evaluations`. | `FK`, `INTEGER`                    |
| **question_id**        | Clave foránea que referencia a la pregunta respondida.       | `FK`, `INTEGER`                    |
| **user_answer**        | Texto o respuesta ingresada/seleccionada por el usuario.     | `VARCHAR(255)`                     |
| **is_correct**         | Representación booleana de StarUML ($0$ para incorrecto, $1$ para correcto). | `TINYINT(1)`                       |