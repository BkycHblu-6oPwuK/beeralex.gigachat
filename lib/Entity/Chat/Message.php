<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Chat;

/**
 * Сообщение
 */
class Message
{
    /**
     * Содержимое сообщения
     */
    public readonly string $content;

    /**
     * Роль автора сообщения.
     * Возможные значения: [assistant, function_in_progress], function_in_progress не реализован
     */
    public readonly string $role;

    public function __construct(array $message)
    {   
        $this->content = $message['content'];
        $this->role = $message['role'];
    }
}