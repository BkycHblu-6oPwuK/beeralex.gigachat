<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Services;

use Beeralex\Core\Exceptions\ApiClientUnauthorizedException;
use Bitrix\Main\Web\Uri;
use Beeralex\Gigachat\Entity\Balance\Balance;

/**
 * @link https://developers.sber.ru/docs/ru/gigachat/api/reference/rest/get-balance
 */
class BalanceService extends AuthService
{
    /**
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function getBalance() : Balance
    {
        try {
            $result = $this->makeRequest();
        } catch (ApiClientUnauthorizedException $e){
            $this->refreshToken();
            $result = $this->makeRequest();
        }
        if(empty($result['balance'])){
            throw new \RuntimeException("Ошибка получения баланса");
        }
        return new Balance($result);
    }

    protected function makeRequest()
    {
        return $this->get(new Uri("{$this->options->baseGigaChatUrl}/api/v1/balance"), null, $this->getHeaders());
    }

    protected function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ];
    }
}
