<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Embedding;

class Embedding
{
    /**
     * Тип объекта.
     */
    public readonly string $object;

    /**
     * @var float[] Массив чисел, представляющих значения эмбеддинга для предоставленного текста.
     */
    public readonly array $embedding;

    /**
     * Индекс соответствующий индексу текста, полученного в массиве input запроса.
     */
    public readonly int $index;

    public readonly Usage $usage;

    public function __construct(array $embedding)
    {   
        $this->object = $embedding['object'];
        $this->embedding = $embedding['embedding'];
        $this->index = $embedding['index'];
        $this->usage = new Usage($embedding['usage']);
    }
}