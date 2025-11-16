<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Loader;
Loc::loadMessages(__FILE__);

class beeralex_gigachat extends CModule
{

    public function __construct()
    {
        if (is_file(__DIR__ . '/version.php')) {
            include_once(__DIR__ . '/version.php');
            $this->MODULE_ID           = 'beeralex.gigachat';
            $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            $this->MODULE_NAME         = Loc::getMessage('BEERALEX_GIGACHAT_NAME');
            $this->MODULE_DESCRIPTION  = Loc::getMessage('BEERALEX_GIGACHAT_DESCRIPTION');
            $this->PARTNER_NAME = 'beeralex';
            $this->PARTNER_URI = '#';
        } else {
            CAdminMessage::showMessage(
                Loc::getMessage('BEERALEX_GIGACHAT_FILE_NOT_FOUND') . ' version.php'
            );
        }
    }

    public function DoInstall()
    {
        global $APPLICATION;
        if ($this->checkRequirements()) {
            ModuleManager::registerModule($this->MODULE_ID);
            Loader::includeModule($this->MODULE_ID);
            Option::set($this->MODULE_ID, 'base_oauth_url', 'https://ngw.devices.sberbank.ru:9443');
            Option::set($this->MODULE_ID, 'base_gigachat_url', 'https://gigachat.devices.sberbank.ru');
        } else {
            $APPLICATION->ThrowException('Нет поддержки d7 в главном модуле или не установлен модуль beeralex.core');
        }
        $APPLICATION->IncludeAdminFile('Установка модуля',
            __DIR__ . '/step.php');
    }

    public function checkRequirements(): bool
    {
        return version_compare(ModuleManager::getVersion('main'), '14.00.00') >= 0 && Loader::includeModule('beeralex.core');
    }

    public function doUninstall()
    {
        global $APPLICATION;

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->includeAdminFile(
            Loc::getMessage('BEERALEX_GIGACHAT_UNINSTALL_TITLE') . ' «' . Loc::getMessage('BEERALEX_GIGACHAT_NAME') . '»',
            __DIR__ . '/unstep.php'
        );
    }
}
