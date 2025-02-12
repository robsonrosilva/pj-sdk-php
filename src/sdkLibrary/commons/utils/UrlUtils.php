<?php

namespace Inter\Sdk\sdkLibrary\commons\utils;

use Inter\Sdk\sdkLibrary\commons\models\Config;

class UrlUtils
{
    public static function buildUrl(Config $config, string $url): string
    {
        return $config->getEnvironment()->getUrlBase().$url;
    }
}
