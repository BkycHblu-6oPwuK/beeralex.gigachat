<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Balance;

/**
 * Баланс, результат ответа api
 * @link https://developers.sber.ru/docs/ru/gigachat/api/reference/rest/get-balance
 */
class Balance
{
    /**
     * @var BalanceItem[]
     */
    public readonly array $items;

    public function __construct(array $result)
    {   
        $this->items = array_map(fn($item) => new BalanceItem($item), $result['balance']);
    }
    
    /**
     * @param string $model тип модели
     */
    public function getByModel(string $model): ?BalanceItem
    {
        return array_filter($this->items, fn(BalanceItem $item) => $item->usage === $model)[0];
    }
}