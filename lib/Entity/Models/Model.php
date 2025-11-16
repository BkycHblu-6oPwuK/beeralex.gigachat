<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Models;

/**
 * Объект модели
 */
class Model
{
    /**
     * id модели, Возможные значения: [GigaChat, GigaChat-Pro, GigaChat-Max]
     */
    public readonly string $id;

    /**
     * Тип сущности в ответе
     */
    public readonly string $object;

    /**
     * Владелец модели
     */
    public readonly string $ownedBy;

    /**
     * Тип модели. Значение chat указывает, что модель используется для генерации.
     */
    public readonly string $type;

    public function __construct(array $model)
    {   
        $this->id = $model['id'];
        $this->object = $model['object'];
        $this->ownedBy = $model['owned_by'];
        $this->type = $model['type'];
    }
}