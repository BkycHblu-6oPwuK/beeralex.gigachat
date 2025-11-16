<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Balance;

class BalanceItem
{
    /**
     * Название модели, например, GigaChat или embeddings.
     */
    public readonly string $usage;

    /**
     * Остаток токенов
     */
    public readonly int $value;

    public function __construct(array $item)
    {   
        $this->usage = $item['usage'];
        $this->value = $item['value'];
    }
}