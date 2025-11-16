<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Chat;

/**
 * Объект чата, результат ответа api
 * @link https://developers.sber.ru/docs/ru/gigachat/api/reference/rest/post-chat
 */
class Chat
{
    /**
     * Ответы модели
     */
    public readonly Choices $choices;

    /**
     * Дата и время создания ответа
     */
    public readonly \DateTime $created;

    /**
     * Модель сформировавшая ответ, должна соответствовать выбранной в настройках модуля
     */
    public readonly string $model;

    /**
     * Название вызываемого метода в api
     */
    public readonly string $object;

    /**
     * Данные об использовании модели
     */
    public readonly Usage $usage;

    public function __construct(array $result)
    {   
        $this->choices = new Choices($result['choices']);
        $this->created = new \DateTime();
        $this->created->setTimestamp($result['created']);
        $this->model = $result['model'];
        $this->object = $result['object'];
        $this->usage = new Usage($result['usage']);
    }
}