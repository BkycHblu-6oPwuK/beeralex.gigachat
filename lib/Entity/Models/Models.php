<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Models;

/**
 * Модели, результат ответа api
 * @link https://gigachat.devices.sberbank.ru/api/v1/models
 */
class Models
{
    /**
     * @var Model[] массив с объектами моделей
     */
    public readonly array $models;

    /**
     * Тип сущности в ответе, например, список.
     */
    public readonly string $object;

    public function __construct(array $result)
    {   
        $this->models = array_map(fn($model) => new Model($model), $result['data']);
        $this->object = $result['object'];
    }
    
    /**
     * @param string $type тип модели, например chat
     */
    public function getByType(string $type)
    {
        return array_filter($this->models, fn(Model $model) => $model === $type);
    }

    public function first() : ?Model
    {
        return $this->models[0];
    }
}