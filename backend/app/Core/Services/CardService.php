<?php
namespace App\Core\Services;

use App\Core\CommunicationStrategies\CommunicationStrategyInterface;
use App\Core\CommunicationStrategies\VisualStrategy;
use App\Core\CommunicationStrategies\AudioStrategy;
use App\Core\CommunicationStrategies\TactileStrategy;
use App\Core\Entities\Card\CardEntity;
use App\Core\Interfaces\CardRepositoryInterface;
use Illuminate\Support\Collection;

class CardService
{
    protected CardRepositoryInterface $cardRepository;
    /** @var array<int, CommunicationStrategyInterface> */
    protected array $communicationStrategies;

    public function __construct(
        CardRepositoryInterface $cardRepository,
        VisualStrategy $visualStrategy,
        AudioStrategy $audioStrategy,
        TactileStrategy $tactileStrategy
    ) {
        $this->cardRepository = $cardRepository;
        
        // Mapeo del ID de Método de Comunicación (asumido de la BD) a la Estrategia
        $this->communicationStrategies = [
            1 => $visualStrategy, // Asumimos ID 1 es Visual
            2 => $audioStrategy,  // Asumimos ID 2 es Auditivo
            3 => $tactileStrategy, // Asumimos ID 3 es Táctil
            // Se puede añadir una estrategia por defecto si el ID no se encuentra.
        ];
    }

    /**
     * Recupera una colección de todas las tarjetas y las adapta a su estrategia.
     * @return Collection<CardEntity>
     */
    public function getCards(): Collection
    {
        // 1. Obtiene la colección desordenada (ej: IDs 3, 4, 7, 9...)
        $cards = $this->cardRepository->getAll();
        
        // 2. Agrupa las tarjetas por su ID de método de comunicación
        $groupedCards = $cards->groupBy(fn (CardEntity $card) => $card->methodId);
        
        $adaptedCards = new Collection();
        
        // 3. Itera sobre los grupos y aplica la estrategia correspondiente
        foreach ($groupedCards as $methodId => $cardGroup) {
            // Obtiene la estrategia basada en el methodId, usando VisualStrategy como fallback
            $strategy = $this->communicationStrategies[$methodId] ?? $this->communicationStrategies[1]; 

            // Aplica la adaptación a todo el grupo
            $adaptedGroup = $strategy->adapt($cardGroup);
            
            // Combina los resultados
            $adaptedCards = $adaptedCards->merge($adaptedGroup);
        }

        // 4. Añade un índice consecutivo (1, 2, 3, 4...)
        $indexedCards = $adaptedCards->map(function (CardEntity $card, $key) {
            // Clonamos la entidad o la convertimos a array para inyectar el campo
            // Usamos toArray() del CardEntity y agregamos consecutiveId
            $cardArray = $card->toArray(); 
            $cardArray['consecutiveId'] = $key + 1; // El índice $key empieza en 0
            return $cardArray;
        });

        return collect($indexedCards);
    }
    
    // ... otros métodos del servicio ...
    
    /**
     * Recupera una tarjeta por su ID (PK).
     * @param int $id El ID primario de la tarjeta.
     * @return ?CardEntity
     */
    public function getCard(int $id): ?CardEntity
    {
        $card = $this->cardRepository->find($id);
        
        if ($card) {
            // Para una sola tarjeta, obtenemos la estrategia y la aplicamos a una colección temporal
            $strategy = $this->communicationStrategies[$card->methodId] ?? $this->communicationStrategies[1];
            
            $adaptedCollection = $strategy->adapt(collect([$card]));
            return $adaptedCollection->first();
        }
        
        return null;
    }
    
    /**
     * Recupera una tarjeta por su UUID único.
     * @param string $uuid El identificador único de la tarjeta.
     * @return ?CardEntity
     */
    public function getCardByUuid(string $uuid): ?CardEntity
    {
        $card = $this->cardRepository->findByUuid($uuid);
        
        if ($card) {
            // Para una sola tarjeta, obtenemos la estrategia y la aplicamos a una colección temporal
            $strategy = $this->communicationStrategies[$card->methodId] ?? $this->communicationStrategies[1];
            
            $adaptedCollection = $strategy->adapt(collect([$card]));
            return $adaptedCollection->first();
        }
        
        return null;
    }
    
    /**
     * Crea una nueva tarjeta.
     * @param CardEntity $card La entidad de tarjeta a crear.
     * @return CardEntity
     */
    public function createCard(CardEntity $card): CardEntity
    {
        // Se podría añadir lógica de negocio aquí, como generar el UUID si no viene.
        return $this->cardRepository->create($card);
    }

    /**
     * Actualiza una tarjeta existente por su ID.
     * @param int $id El ID de la tarjeta a actualizar.
     * @param CardEntity $card La entidad con los datos actualizados (parciales o completos).
     * @return CardEntity
     */
    public function updateCard(int $id, CardEntity $card): CardEntity
    {
        return $this->cardRepository->update($id, $card);
    }

    /**
     * Elimina una tarjeta por su ID, lo que también reordena las posiciones.
     * @param int $id El ID de la tarjeta a eliminar.
     * @return bool
     */
    public function deleteCard(int $id): bool
    {
        return $this->cardRepository->delete($id);
    }
}
