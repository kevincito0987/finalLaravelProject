# 🏛️ Arquitectura Limpia con Laravel: Flujo de Desarrollo Completo

Este documento sirve como guía para la implementación de cualquier funcionalidad (*feature*) dentro de la aplicación, siguiendo la estricta separación de responsabilidades de la Arquitectura Limpia.

El flujo se presenta de abajo hacia arriba: desde la base de datos (Infraestructura) hasta el punto de entrada de la aplicación (Presentación).

## 1. Capa de Infraestructura (Persistencia)

Esta capa es la más externa y maneja los detalles técnicos de la base de datos.

### 1.1. Migración, Seeder y Factory

| Componente           | Descripción                                                  | Importancia                                                  |
| -------------------- | ------------------------------------------------------------ | ------------------------------------------------------------ |
| **Migración**        | Define la estructura física de la tabla en la BD (`user_progress`). | Establece el contrato de almacenamiento de datos.            |
| **Factory & Seeder** | Creación de datos de prueba para testing y desarrollo.       | Esencial para asegurar la testabilidad y la reproducibilidad del entorno. |

### 1.2. Modelo Eloquent

**Ubicación:** `app/Models/UserProgress.php`

**Descripción:** Representa la tabla de la base de datos. Es un **Adaptador de Base de Datos** de Laravel. **Dependencias:** Depende exclusivamente del framework (Eloquent).

```
// app/Models/UserProgress.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property int $progress_percentage
 */
class UserProgress extends Model
{
    use HasFactory;
    protected $table = 'user_progress';
    protected $fillable = ['user_id', 'course_id', 'progress_percentage'];
}
```

## 2. Capa de Dominio (El Núcleo)

Esta capa es el corazón de la aplicación y es **totalmente independiente** de Laravel, bases de datos o HTTP.

### 2.1. Entidad de Dominio

**Ubicación:** `app/Core/Entities/UserProgressEntity.php`

**Descripción:** Objeto de negocio puro que encapsula el estado y las reglas de negocio intrínsecas. **Dependencias:** Ninguna.

```
// app/Core/Entities/UserProgressEntity.php
namespace App\Core\Entities;

class UserProgressEntity
{
    public function __construct(
        public ?int $id,
        public int $userId,
        public int $courseId,
        public int $percentage,
        public ?\DateTimeInterface $lastUpdatedAt = null,
    ) {}

    // Lógica de Negocio: La Entidad sabe cómo validarse
    public function isCompleted(): bool
    {
        return $this->percentage >= 100;
    }
}
```

### 2.2. Interface de Repositorio (Contrato de Persistencia)

**Ubicación:** `app/Core/Repositories/UserProgressRepositoryInterface.php`

**Descripción:** Define los métodos **necesarios** para la persistencia de la **Entidad**. Es el **Puerto de Salida** del Dominio. **Importancia:** Aplica el **Principio de Inversión de Dependencias (DIP)**, permitiendo que el Dominio dependa de una abstracción (la Interfaz) y no de la implementación de Eloquent.

```
// app/Core/Repositories/UserProgressRepositoryInterface.php
namespace App\Core\Repositories;

use App\Core\Entities\UserProgressEntity;

interface UserProgressRepositoryInterface
{
    public function getByUserIdAndCourseId(int $userId, int $courseId): ?UserProgressEntity;
    public function save(UserProgressEntity $progress): UserProgressEntity;
    public function delete(int $id): bool;
}
```

### 2.3. Interface de Servicio (Contrato de Caso de Uso)

**Ubicación:** `app/Core/Services/UserProgressServiceInterface.php`

**Descripción:** Define los **Casos de Uso** (acciones específicas de la aplicación, como `updateProgress`). Es el **Puerto de Entrada** del Dominio. **Importancia:** Separa lo que se hace de cómo se hace.

```
// app/Core/Services/UserProgressServiceInterface.php
namespace App\Core\Services;

use App\Core\Entities\UserProgressEntity;

interface UserProgressServiceInterface
{
    public function findOrCreateProgress(int $userId, int $courseId): UserProgressEntity;
    public function updateProgress(int $userId, int $courseId, int $newPercentage): UserProgressEntity;
}
```

## 3. Capa de Aplicación (Casos de Uso)

Contiene la implementación de las reglas de negocio y la orquestación.

### 3.1. Servicio (Lógica de Negocio)

**Ubicación:** `app/Core/Services/UserProgressService.php`

**Descripción:** Implementa el `UserProgressServiceInterface`. Contiene la **lógica de negocio específica** que orquesta las Entidades y el Repositorio. **Dependencias:** Únicamente depende de la **Interfaz de Repositorio**.

```
// app/Core/Services/UserProgressService.php
namespace App\Core\Services;

use App\Core\Entities\UserProgressEntity;
use App\Core\Repositories\UserProgressRepositoryInterface;

class UserProgressService implements UserProgressServiceInterface
{
    // Dependencia Invertida: SOLO conoce la Interfaz
    public function __construct(
        private readonly UserProgressRepositoryInterface $repository 
    ) {}

    public function updateProgress(int $userId, int $courseId, int $newPercentage): UserProgressEntity
    {
        // 1. Obtiene la Entidad (del repositorio, que es una abstracción)
        $progress = $this->repository->getByUserIdAndCourseId($userId, $courseId);
        
        // Manejo de entidad nula (Regla de negocio 1: Siempre hay progreso, aunque sea 0)
        if (!$progress) {
             $progress = new UserProgressEntity(null, $userId, $courseId, 0);
        }

        // 2. Regla de Negocio 2: El progreso nunca debe disminuir.
        if ($newPercentage > $progress->percentage) {
            $progress->percentage = min(100, $newPercentage); // Asegura máximo 100
        }
        
        // 3. Persiste y retorna la Entidad actualizada.
        return $this->repository->save($progress);
    }
    // ...
}
```

## 4. Capa de Infraestructura (Adaptadores de Conexión)

Esta capa se encarga de traducir los objetos del mundo real (BD, HTTP) a las Entidades de Dominio, y viceversa.

### 4.1. Mapper (Transformador de Datos) ⚠️ OBLIGATORIO

**Ubicación:** `app/Infrastructure/Mappers/UserProgressMapper.php`

**Descripción:** Clase dedicada a la transformación de datos entre `Modelo Eloquent` y `Entidad de Dominio`. **Importancia:** Mantiene al Repositorio libre de la lógica de conversión, haciendo que cada clase cumpla el **Principio de Responsabilidad Única (SRP)**.

```
// app/Infrastructure/Mappers/UserProgressMapper.php
namespace App\Infrastructure\Mappers;

use App\Models\UserProgress;
use App\Core\Entities\UserProgressEntity;

class UserProgressMapper
{
    /**
     * Convierte un Modelo Eloquent a una Entidad de Dominio.
     */
    public static function toEntity(UserProgress $model): UserProgressEntity
    {
        return new UserProgressEntity(
            id: $model->id,
            userId: $model->user_id,
            courseId: $model->course_id,
            percentage: $model->progress_percentage,
            lastUpdatedAt: $model->updated_at,
        );
    }

    /**
     * Rellena un Modelo Eloquent con datos de una Entidad.
     */
    public static function toModel(UserProgressEntity $entity, UserProgress $model): UserProgress
    {
        $model->user_id = $entity->userId;
        $model->course_id = $entity->courseId;
        $model->progress_percentage = $entity->percentage;
        return $model;
    }
}
```

### 4.2. Repositorio Eloquent (Implementación Concreta)

**Ubicación:** `app/Infrastructure/Repositories/UserProgressEloquentRepository.php`

**Descripción:** La implementación concreta que usa el Modelo Eloquent y los **Mappers** para cumplir el contrato de la `UserProgressRepositoryInterface`. **Dependencias:** Depende del Modelo Eloquent y del Mapper.

```
// app/Infrastructure/Repositories/UserProgressEloquentRepository.php
namespace App\Infrastructure\Repositories;

use App\Core\Repositories\UserProgressRepositoryInterface;
use App\Core\Entities\UserProgressEntity;
use App\Models\UserProgress;
use App\Infrastructure\Mappers\UserProgressMapper; // Usa el Mapper

class UserProgressEloquentRepository implements UserProgressRepositoryInterface
{
    public function getByUserIdAndCourseId(int $userId, int $courseId): ?UserProgressEntity
    {
        $model = UserProgress::where('user_id', $userId)
                             ->where('course_id', $courseId)
                             ->first();
        
        return $model ? UserProgressMapper::toEntity($model) : null;
    }

    public function save(UserProgressEntity $progress): UserProgressEntity
    {
        // 1. Encuentra o crea el modelo
        $model = $progress->id
            ? UserProgress::findOrFail($progress->id)
            : new UserProgress();

        // 2. Mapea Entidad a Modelo
        $model = UserProgressMapper::toModel($progress, $model);
        
        // 3. Persiste
        $model->save();

        // 4. Mapea el Modelo persistido de vuelta a Entidad (para asegurar el ID y timestamps)
        return UserProgressMapper::toEntity($model); 
    }
}
```

## 5. Capa de Presentación (Transporte HTTP)

Maneja la entrada y salida de datos a través de peticiones web.

### 5.1. Request (Validación)

**Ubicación:** `app/Http/Requests/UpdateProgressRequest.php`

**Descripción:** Valida y sanitiza los datos recibidos por la petición HTTP. **Importancia:** Protege el Contrato del Servicio de Dominio; garantiza que solo lleguen datos limpios.

### 5.2. Policy (Autorización)

**Ubicación:** `app/Policies/UserProgressPolicy.php`

**Descripción:** Implementa la lógica de autorización (quién tiene permiso para ejecutar el Caso de Uso). **Debe operar con Entidades** si la regla de permiso es de dominio.

### 5.3. Controller (Coordinación)

**Ubicación:** `app/Http/Controllers/UserProgressController.php`

**Descripción:** El Controller es el punto de entrada. Es **"tonto"** (no contiene lógica de negocio). **Dependencias:** Solo depende de la **Interfaz de Servicio**.

```
// app/Http/Controllers/UserProgressController.php
namespace App\Http\Controllers;

use App\Core\Services\UserProgressServiceInterface; // Interfaz
use App\Http\Requests\UpdateProgressRequest;
use App\Http\Resources\UserProgressResource;

class UserProgressController extends Controller
{
    // Dependencia Invertida: Inyecta la interfaz, recibe la implementación
    public function __construct(
        private readonly UserProgressServiceInterface $service 
    ) {}

    public function update(UpdateProgressRequest $request)
    {
        // El Controller se encarga de obtener el contexto del usuario autenticado
        $userId = auth()->id();
        $courseId = $request->validated('course_id');
        $newPercentage = $request->validated('new_percentage');

        // 1. Llama al Caso de Uso (Servicio)
        $updatedProgress = $this->service->updateProgress($userId, $courseId, $newPercentage);

        // 2. Devuelve la Entidad formateada
        return new UserProgressResource($updatedProgress);
    }
}
```

### 5.4. Resource (Formato de Salida)



**Ubicación:** `app/Http/Resources/UserProgressResource.php`

**Descripción:** Transforma la **Entidad de Dominio** de vuelta a una estructura JSON para el cliente. **Importancia:** Permite cambiar el formato de la API sin afectar la Entidad ni el Servicio.

------



## 6. Configuración Final: Service Provider (IoC)



Este paso cierra el bucle de la Inversión de Control (IoC).



### 6.1. Binding de Interfaces a Implementaciones



**Ubicación:** `app/Providers/AppServiceProvider.php` (o un `DomainServiceProvider` dedicado)

**Descripción:** Le dice al Contenedor de Servicios de Laravel (*Service Container*) qué clase concreta debe instanciar cuando se pide una Interfaz.



------



## 7. Aplicaciones Avanzadas (Microservicios y WebSockets)



La Arquitectura Limpia facilita la adaptación a arquitecturas distribuidas y asíncronas.



### 7.1. Adaptación a Microservicios



Si decides separar el módulo `UserProgress` en un microservicio dedicado, las capas de Dominio y Aplicación son directamente reutilizables.

| Componente de Laravel (Monolito) | Función en Microservicio | Adaptador/Transporte Reemplazado                             |
| -------------------------------- | ------------------------ | ------------------------------------------------------------ |
| `UserProgressController`         | **Se elimina.**          | El transporte ahora es gRPC, RabbitMQ o Kafka.               |
| `UserProgressServiceInterface`   | **Se mantiene intacta.** | El servicio se expone a través del nuevo protocolo.          |
| `UserProgressService`            | **Se mantiene intacta.** | La lógica de negocio es la misma, solo se llama de manera diferente. |
| `UserProgressEloquentRepository` | **Se mantiene intacto.** | Aún maneja la persistencia de ese servicio.                  |

**Conexión Clave:** En lugar de un Controller HTTP llamando al Servicio, un **Consumer de Cola** (ej. un Job o un Listener de eventos asíncronos) llama al mismo `UserProgressServiceInterface` inyectado. La lógica de negocio no cambia.



### 7.2. Integración con WebSockets (Broadcasting)

Para actualizar el progreso en tiempo real, el flujo tradicional de HTTP se omite.

| Paso          | Componente Involucrado                | Explicación                                                  |
| ------------- | ------------------------------------- | ------------------------------------------------------------ |
| **Trigger**   | `Observer` / `Event Listener` / `Job` | Un evento desencadena la actualización (ej. `LessonCompletedEvent`). |
| **Ejecución** | **`UserProgressServiceInterface`**    | El Event Listener o Job **inyecta y llama directamente** al Servicio para ejecutar el caso de uso (`updateProgress`). |
| **Broadcast** | `UserProgressUpdatedEvent`            | Dentro del Servicio (o inmediatamente después de la llamada al servicio), se emite un evento de Laravel que contiene la **Entidad** o el **Resource** de salida. |
| **Salida**    | `UserProgressResource`                | El evento utiliza el `UserProgressResource` para formatear los datos a través del canal de WebSocket, asegurando que el cliente reciba el mismo formato JSON que la API. |

**Flujo Asíncrono:** `Event Listener` -> **`UserProgressServiceInterface`** -> `save(Entity)` -> `Broadcast(Resource)`. El **Controller nunca se usa**, pero el **Servicio de Dominio** sí, demostrando su independencia del protocolo de transporte.