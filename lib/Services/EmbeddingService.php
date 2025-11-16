<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Services;

use Beeralex\Core\Exceptions\ApiClientUnauthorizedException;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Web\Uri;
use Beeralex\Gigachat\Entity\Embedding\Embeddings;

/**
 * @link https://developers.sber.ru/docs/ru/gigachat/api/reference/rest/post-embeddings
 * Для успешного запроса, должен быть куплен соответствующий тариф
 */
class EmbeddingService extends AuthService
{
    /**
     * @param string[] $promt массив строк, который будут использованы для генерации эмбеддинга.
     */
    public function getEmbeddings(array $promt) : Embeddings
    {
        $promt = array_values($promt);
        try {
            $result = $this->makeRequest($promt);
        } catch (ApiClientUnauthorizedException $e){
            $this->refreshToken();
            $result = $this->makeRequest($promt);
        }
        if(empty($result['data'])){
            throw new \RuntimeException("Ошибка получения эмбеддингов");
        }
        return new Embeddings($result);
    }

    protected function makeRequest(array $promt)
    {
        return $this->post(new Uri("{$this->options->baseGigaChatUrl}/api/v1/embeddings"), $this->getData($promt), $this->getHeaders());
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ];
    }

    protected function getData(array $promt): string
    {
        return Json::encode([
            'model' => 'Embeddings',
            'input' => $promt,
        ]);
    }
}
