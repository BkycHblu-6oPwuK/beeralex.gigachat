<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Chat;

/**
 * Ответы моделей
 */
class Choices
{
    /**
     * @var Choice[] массив объектов ответов модели
     */
    public readonly array $choices;

    public function __construct(array $choices)
    {   
        $this->choices = array_map(fn($choice) => new Choice($choice), $choices);
    }

    public function first() : ?Choice
    {
        return $this->choices[0];
    }
}