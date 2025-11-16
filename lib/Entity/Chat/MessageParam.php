<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Chat;

use Beeralex\Gigachat\Enum\RoleChat;

/**
 * Хранит сообщение к модели
 */
class MessageParam
{
    /**
     * Роль сообщения.
     */
    public readonly RoleChat $role;

    /**
     * Содержимое сообщения.
     */
    public readonly string $content;

    /**
     * Идентификатор, который объединяет массив функций, переданных в запросе. Возвращается в ответе модели (сообщение с "role": "assistant") при вызове встроенных или собственных функций. Позволяет сохранить контекст вызова функции и повысить качество работы модели. Для этого нужно передать идентификатор в запросе на генерацию в сообщении с ролью assistant.
     */
    //public readonly ?string $functionsStateId;

    /**
     * Массив идентификаторов файлов, которые нужно использовать при генерации. Идентификатор присваивается файлу при загрузке в хранилище. Посмотреть список файлов в хранилище можно с помощью метода GET /files.
     */
    //public readonly ?array $attachments;

    public function __construct(RoleChat $role, string $content)
    {
        $this->role = $role;
        $this->content = $content;
        //$this->functionsStateId = null;
        //$this->attachments = null;
    }
}
