# 📚 Diseño de Base de Datos para Plataforma de Comunicación 🗣️

Este documento describe el Modelo Entidad-Relación (MER) de nuestra plataforma. Su diseño sigue rigurosamente la **Quinta Forma Normal (5FN)**, garantizando una arquitectura de datos robusta, escalable y sin redundancias. Cada tabla y relación ha sido optimizada para un rendimiento profesional.

------



## 🔑 Entidades Principales

### 1. `roles` 📜

Esta tabla es el corazón de la gestión de permisos. Define y categoriza los niveles de acceso para cada usuario.

- **Propósito**: Controlar qué partes de la aplicación puede ver o modificar un usuario.

| Campo         | Descripción                                        | Propiedades                       |
| ------------- | -------------------------------------------------- | --------------------------------- |
| **role_id**   | Identificador único y primario del rol.            | `PK`, `BigInt`, `autoincremental` |
| **role_name** | El nombre funcional del rol (ej: 'admin', 'user'). | `String`, `único`                 |

**Relación:**

- **Uno a Muchos (`1:N`)** con la tabla `users`. 🧑‍🤝‍🧑
  - **Explicación**: Un solo rol, como 'user' o 'admin', puede ser asignado a **múltiples** usuarios.
  - **Ejemplo**: El `role_id` `1` corresponde al rol 'admin'. Al crear un nuevo administrador, su registro en la tabla `users` recibirá el `role_id` `1`. Esto le otorgará acceso automático a todas las funcionalidades exclusivas para administradores.

### 2. `users` 🧑‍💻

Aquí se almacena toda la información vital de los usuarios de la plataforma, incluyendo su identidad visual.

- **Propósito**: Autenticar y personalizar la experiencia de cada usuario.

| Campo                  | Descripción                                                  | Propiedades                       |
| ---------------------- | ------------------------------------------------------------ | --------------------------------- |
| **user_id**            | Identificador primario y único del usuario.                  | `PK`, `BigInt`, `autoincremental` |
| **role_id**            | Clave foránea que lo asocia con un rol en la tabla `roles`.  | `FK`, `BigInt`                    |
| **name**               | Nombre completo del usuario para una experiencia personalizada. | `String`                          |
| **email**              | Correo electrónico, clave para el inicio de sesión.          | `String`, `único`                 |
| **password**           | Contraseña cifrada de forma segura.                          | `String`                          |
| **profile_image_path** | Ruta del archivo de la imagen de perfil del usuario.         | `String`                          |

**Relación:**

- **Muchos a Uno (`N:1`)** con la tabla `roles`.
  - **Explicación**: Varios usuarios pueden tener el mismo `role_id`, vinculándolos de vuelta a un único rol. Cada usuario está asociado de forma individual a un solo rol.
  - **Ejemplo**: Los usuarios 'Juan' y 'María' tienen un `role_id` de `2`, que corresponde al rol 'Terapeuta'. Ambos pueden acceder a las funcionalidades de terapeuta sin que la información del rol se duplique en cada registro.

### 3. `communication_methods` 🗣️

Define los diferentes tipos de interacción que las tarjetas pueden tener, ya sean visuales, auditivas o táctiles.

- **Propósito**: Categorizar las tarjetas según la forma en que el usuario interactúa con ellas.

| Campo           | Descripción                                                  | Propiedades                       |
| --------------- | ------------------------------------------------------------ | --------------------------------- |
| **method_id**   | Identificador único del método.                              | `PK`, `BigInt`, `autoincremental` |
| **method_name** | El nombre descriptivo del método (ej: 'visual', 'auditivo'). | `String`, `único`                 |

**Relación:**

- **Uno a Muchos (`1:N`)** con la tabla `cards`. 🃏
  - **Explicación**: Un solo método de comunicación (ej. 'auditivo') puede aplicarse a **muchas** tarjetas, pero cada tarjeta está vinculada a un único método.
  - **Ejemplo**: El `method_id` `3` corresponde al método 'visual'. Un administrador puede crear una lección seleccionando todas las tarjetas que tienen ese `method_id`, asegurando que todas las tarjetas de la lección sean de tipo visual.

### 4. `categories` 🏷️

Organiza las tarjetas en temas coherentes (ej: 'Frutas', 'Números').

- **Propósito**: Facilitar la navegación, búsqueda y creación de lecciones basadas en temas específicos.

| Campo             | Descripción                          | Propiedades                       |
| ----------------- | ------------------------------------ | --------------------------------- |
| **category_id**   | Identificador único de la categoría. | `PK`, `BigInt`, `autoincremental` |
| **category_name** | El nombre de la categoría.           | `String`, `único`                 |

**Relación:**

- **Uno a Muchos (`1:N`)** con la tabla `cards`. 📁
  - **Explicación**: Una sola categoría (ej. 'Animales') puede agrupar a **muchas** tarjetas, pero cada tarjeta solo pertenece a una categoría.
  - **Ejemplo**: Un administrador busca crear una lección de "Vocabulario de la casa". En lugar de buscar tarjetas una por una, el sistema le permite seleccionar todas las tarjetas de la `category_name` 'Objetos del hogar', ahorrando tiempo y asegurando la coherencia del contenido.

------



## 🗂️ Entidades de Contenido

### 5. `cards` 🃏

Contiene la información principal de las tarjetas, independiente del idioma.

- **Propósito**: Ser la representación central de cada tarjeta física o digital.

| Campo                | Descripción                                                  | Propiedades                       |
| -------------------- | ------------------------------------------------------------ | --------------------------------- |
| **card_id**          | Identificador único de la tarjeta.                           | `PK`, `BigInt`, `autoincremental` |
| **uuid**             | Código único (simulación de RFID) para la interacción.       | `String`, `único`                 |
| **image_path**       | Ruta de la imagen principal de la tarjeta.                   | `String`                          |
| **method_id**        | Clave foránea que enlaza con el método de comunicación.      | `FK`, `BigInt`                    |
| **category_id_card** | Clave foránea que enlaza con la categoría a la que pertenece la tarjeta. | `FK`, `BigInt`                    |

**Relación:**

- **Muchos a Uno (`N:1`)** con la tabla `communication_methods`.
  - **Explicación**: Múltiples tarjetas pueden estar asociadas con un solo método de comunicación.
  - **Ejemplo**: La tarjeta 'manzana' y la tarjeta 'plátano' tienen el mismo `method_id` que las vincula al método 'visual'.
- **Muchos a Uno (`N:1`)** con la tabla `categories`.
  - **Explicación**: Múltiples tarjetas pueden pertenecer a una sola categoría.
  - **Ejemplo**: La tarjeta 'manzana' y la tarjeta 'plátano' tienen el mismo `category_id` que las vincula a la categoría 'Frutas'.
- **Uno a Muchos (`1:N`)** con la tabla `card_translations`.
  - **Explicación**: Una sola tarjeta puede tener **muchas** traducciones asociadas, una por cada idioma.
  - **Ejemplo**: La tarjeta 'manzana' tiene un `card_translations` para 'Spanish', uno para 'English', etc., cada uno con su `key_phrase` y `audio_path` correspondientes.
- **Muchos a Muchos (`N:M`)** con la tabla `lessons`.
  - **Explicación**: Una tarjeta puede estar en muchas lecciones, y una lección puede contener muchas tarjetas. Esta relación se gestiona a través de la tabla intermedia `lesson_cards`.
  - **Ejemplo**: La tarjeta de la 'manzana' se usa en la lección "Frutas", pero también en la lección "Alimentos saludables".

### 6. `card_translations` 🗣️💬

Almacena las frases y audios traducidos para cada tarjeta, manteniendo el contenido dinámico separado del estático.

- **Propósito**: Mantener el contenido dinámico y traducible separado del contenido estático de las tarjetas.

| Campo                   | Descripción                                     | Propiedades                       |
| ----------------------- | ----------------------------------------------- | --------------------------------- |
| **card_translation_id** | Identificador único del registro.               | `PK`, `BigInt`, `autoincremental` |
| **card_id_translation** | Clave foránea que enlaza con `cards`.           | `FK`, `BigInt`                    |
| **language_code**       | Código del idioma.                              | `String`                          |
| **key_phrase**          | La frase principal de la tarjeta en ese idioma. | `Text`                            |
| **audio_path**          | Ruta del archivo de audio para la frase.        | `String`                          |
| **Clave Única**         | `(card_id, language_code)`                      |                                   |

**Relación:**

- **Muchos a Uno (`N:1`)** con la tabla `cards`.
  - **Explicación**: Cada traducción pertenece a una única tarjeta, pero una tarjeta puede tener **muchas** traducciones.
  - **Ejemplo**: La traducción para el español (`language_code = 'es'`) y la traducción para el inglés (`language_code = 'en'`) para la tarjeta de la 'manzana' apuntan al mismo `card_id` de la tabla `cards`.

------



## 🎯 Entidades de Lecciones y Evaluaciones

### 7. `lessons` ✍️

Define las lecciones disponibles para los usuarios.

- **Propósito**: Organizar el contenido de la aplicación en unidades de aprendizaje coherentes.

| Campo           | Descripción                        | Propiedades                       |
| --------------- | ---------------------------------- | --------------------------------- |
| **lesson_id**   | Identificador único de la lección. | `PK`, `BigInt`, `autoincremental` |
| **lesson_name** | Título de la lección.              | `String`                          |
| **description** | Breve descripción de la lección.   | `Text`                            |
| **lesson_type** | Tipo de lección.                   | `String`                          |

**Relación:**

- **Muchos a Muchos (`N:M`)** con la tabla `cards` a través de la tabla `lesson_cards`.
  - **Explicación**: Una lección puede contener **muchas** tarjetas, y una misma tarjeta puede ser parte de **muchas** lecciones.
  - **Ejemplo**: El administrador puede crear una nueva lección (ej: "Vocales") y asociarle un conjunto de tarjetas (`card_a`, `card_e`, etc.) a través de la tabla `lesson_cards`. La tarjeta `card_a` también puede ser usada en otra lección, "Abecedario".

### 8. `lesson_cards` 🧩

Es una tabla pivote que asocia las tarjetas a las lecciones y define el orden en que se presentan.

- **Propósito**: Establecer la relación `N:M` entre `lessons` y `cards`, y definir el orden de las tarjetas dentro de cada lección.

| Campo                        | Descripción                                     | Propiedades    |
| ---------------------------- | ----------------------------------------------- | -------------- |
| **lesson_id_sesion**         | Enlaza con la lección.                          | `FK`, `BigInt` |
| **card_id_sesion**           | Enlaza con la tarjeta.                          | `FK`, `BigInt` |
| **order_in_lesson**          | La posición de la tarjeta dentro de la lección. | `Integer`      |
| **Clave Primaria Compuesta** | `(lesson_id, card_id)`                          |                |

**Relación:**

- **Muchos a Uno (`N:1`)** con la tabla `lessons`.
  - **Explicación**: Varios registros en esta tabla se vinculan a una sola lección, permitiendo que una lección tenga múltiples tarjetas asociadas.
  - **Ejemplo**: La lección `1` tiene múltiples entradas en `lesson_cards`, cada una con un `card_id` diferente para las tarjetas que contiene.
- **Muchos a Uno (`N:1`)** con la tabla `cards`.
  - **Explicación**: Varios registros en esta tabla se vinculan a una sola tarjeta, permitiendo que una tarjeta se use en múltiples lecciones.
  - **Ejemplo**: La tarjeta `10` está presente en la lección `1` y en la lección `5`, por lo que ambos registros en `lesson_cards` apuntan al mismo `card_id` `10`.

### 9. `evaluations` 📝

Almacena las evaluaciones. Una lección de tipo 'evaluacion' tendrá un registro en esta tabla.

- **Propósito**: Definir una prueba de conocimiento asociada a una lección.

| Campo                    | Descripción                            | Propiedades                       |
| ------------------------ | -------------------------------------- | --------------------------------- |
| **evaluation_id**        | Identificador único.                   | `PK`, `BigInt`, `autoincremental` |
| **lesson_id_evaluation** | Enlaza con la lección correspondiente. | `FK`, `BigInt`, `único`           |

**Relación:**

- **Uno a Uno (`1:1`)** con la tabla `lessons`. 📋
  - **Explicación**: Cada lección de tipo 'evaluacion' puede tener una y solo una evaluación asociada. El campo `lesson_id` es único para asegurar que no se creen evaluaciones duplicadas.
  - **Ejemplo**: Al finalizar una lección, la aplicación verifica si la `lesson_id` tiene un registro en esta tabla. Si es así, se inicia el módulo de evaluación.

### 10. `evaluation_questions` ❓

Contiene las preguntas de cada evaluación.

- **Propósito**: Almacenar las preguntas específicas que se harán en una evaluación.

| Campo                      | Descripción                                            | Propiedades                       |
| -------------------------- | ------------------------------------------------------ | --------------------------------- |
| **question_id**            | Identificador único de la pregunta.                    | `PK`, `BigInt`, `autoincremental` |
| **evaluation_id_question** | Enlaza con la evaluación a la que pertenece.           | `FK`, `BigInt`                    |
| **card_id_evaluation**     | Enlaza con la tarjeta a la que se refiere la pregunta. | `FK`, `BigInt`                    |
| **question_text**          | El texto de la pregunta.                               | `Text`                            |
| **correct_answer**         | La respuesta correcta esperada.                        | `String`                          |
| **options**                | Las opciones de respuesta.                             | `JSON`                            |

**Relación:**

- **Muchos a Uno (`N:1`)** con la tabla `evaluations`.
  - **Explicación**: Una evaluación puede tener **muchas** preguntas, pero cada pregunta solo pertenece a una evaluación.
  - **Ejemplo**: Cuando un usuario inicia una evaluación, la aplicación carga todas las preguntas asociadas a esa `evaluation_id`.

## 📈 Entidades de Progreso del Usuario

### 11. `user_lessons` ✅

Registra las lecciones que los usuarios han completado.

- **Propósito**: Llevar un registro del avance del usuario a nivel de lección.

| Campo                        | Descripción                      | Propiedades    |
| ---------------------------- | -------------------------------- | -------------- |
| **user_id_lesson**           | Enlaza con el usuario.           | `FK`, `BigInt` |
| **lesson_id_lesson**         | Enlaza con la lección.           | `FK`, `BigInt` |
| **completed_at**             | Marca de tiempo de finalización. | `Timestamp`    |
| **Clave Primaria Compuesta** | `(user_id, lesson_id)`           |                |

**Relación:**

- **Muchos a Muchos (`N:M`)** entre la tabla `users` y la tabla `lessons`.
  - **Explicación**: Un usuario puede completar **muchas** lecciones, y una lección puede ser completada por **muchos** usuarios. Esta tabla de unión registra cada instancia de finalización.
  - **Ejemplo**: Permite al panel de administrador generar un reporte de "lecciones completadas" por usuario y al usuario ver su progreso en la plataforma.

### 12. `user_progress` 📊

Registra el uso y progreso del usuario con tarjetas individuales.

- **Propósito**: Recopilar datos sobre la interacción del usuario con cada tarjeta, útil para análisis de comportamiento y personalización.

| Campo                | Descripción                                              | Propiedades                       |
| -------------------- | -------------------------------------------------------- | --------------------------------- |
| **progress_id**      | Identificador único del registro de progreso.            | `PK`, `BigInt`, `autoincremental` |
| **user_id_progress** | Enlaza con el usuario.                                   | `FK`, `BigInt`                    |
| **card_id_progress** | Enlaza con la tarjeta.                                   | `FK`, `BigInt`                    |
| **use_count**        | Contador del número de veces que se ha usado la tarjeta. | `Integer`, `defecto 0`            |
| **last_used_at**     | Fecha y hora del último uso.                             | `Timestamp`                       |
| **Clave Única**      | `(user_id, card_id)`                                     |                                   |

**Relación:**

- **Muchos a Muchos (`N:M`)** entre las tablas `users` y `cards`.
  - **Explicación**: Un usuario interactúa con **muchas** tarjetas, y una tarjeta es utilizada por **muchos** usuarios. Esta tabla registra el historial de uso para cada par usuario-tarjeta.
  - **Ejemplo**: Cuando un usuario interactúa con una tarjeta, este registro se actualiza, lo que permite al sistema identificar qué tarjetas son más utilizadas o si se necesita reforzar alguna.

### 13. `user_review_items` 🔄

Gestiona una lista de tarjetas que el usuario necesita repasar, basada en su rendimiento.

- **Propósito**: Personalizar las lecciones de refuerzo.

| Campo                | Descripción                          | Propiedades                       |
| -------------------- | ------------------------------------ | --------------------------------- |
| **review_id**        | Identificador único del ítem.        | `PK`, `BigInt`, `autoincremental` |
| **user_id_review**   | Enlaza con el usuario.               | `FK`, `BigInt`                    |
| **card_id_review**   | Enlaza con la tarjeta.               | `FK`, `BigInt`                    |
| **review_date**      | Fecha en que se marcó para revisión. | `Date`                            |
| **status**           | Estado de la revisión.               | `String`                          |
| **last_reviewed_at** | Fecha de la última revisión.         | `Timestamp`                       |
| **Clave Única**      | `(user_id, card_id)`                 |                                   |

**Relación:**

- **Muchos a Muchos (`N:M`)** entre las tablas `users` y `cards`.
  - **Explicación**: Un usuario tiene **muchas** tarjetas para repasar, y una tarjeta puede ser marcada para repaso por **muchos** usuarios.
  - **Ejemplo**: Si el usuario responde incorrectamente a una pregunta de la evaluación, se crea un registro en esta tabla. La aplicación puede entonces crear dinámicamente una lección de "refuerzo" con las tarjetas de `status = 'pendiente'`.

### 14. `user_evaluation_answers` 💯

Almacena las respuestas de los usuarios a cada pregunta de la evaluación.

- **Propósito**: Analizar el rendimiento del usuario en las evaluaciones.

| Campo                  | Descripción                                | Propiedades                       |
| ---------------------- | ------------------------------------------ | --------------------------------- |
| **answer_id**          | Identificador único de la respuesta.       | `PK`, `BigInt`, `autoincremental` |
| **user_id_answer**     | Enlaza con el usuario que respondió.       | `FK`, `BigInt`                    |
| **question_id_answer** | Enlaza con la pregunta de la evaluación.   | `FK`, `BigInt`                    |
| **user_answer**        | La respuesta proporcionada por el usuario. | `String`                          |
| **is_correct**         | Indica si la respuesta fue correcta.       | `Boolean`                         |
| **answered_at**        | Fecha y hora de la respuesta.              | `Timestamp`                       |

**Relación:**

- **Muchos a Muchos (`N:M`)** con la tabla `users` y la tabla `evaluation_questions`.
  - **Explicación**: Un usuario puede responder a **muchas** preguntas, y una pregunta puede ser respondida por **muchos** usuarios.
  - **Ejemplo**: Al final de una evaluación, la aplicación recorre esta tabla para contar las respuestas correctas e incorrectas, y así calcular la puntuación del usuario en la lección.