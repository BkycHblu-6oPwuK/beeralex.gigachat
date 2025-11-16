<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Embedding;

/**
 * Объект эмбеддинга, результат ответа api
 * @link https://developers.sber.ru/docs/ru/gigachat/api/reference/rest/post-embeddings
 */
class Embeddings
{
    /**
     * Формат структуры данных.
     */
    public readonly string $object;

    /**
     * @var Embedding[]
     */
    public readonly array $embeddings;

    /**
     * Название модели, которая используется для вычисления эмбеддинга.
     */
    public readonly string $model;

    public function __construct(array $result)
    {   
        $this->object = $result['object'];
        $this->embeddings = array_map(fn($item) => new Embedding($item), $result['data']);
        $this->model = $result['model'];
    }
}