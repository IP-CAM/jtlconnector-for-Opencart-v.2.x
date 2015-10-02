<?php

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\Utilities\Singleton;

class OptionHelper extends Singleton
{
    /**
     * @var Db
     */
    private $database;
    /**
     * @var Utils
     */
    private $utils;

    public function __construct()
    {
        $this->database = Db::getInstance();
        $this->utils = Utils::getInstance();
    }

    public function buildOptionDescriptions($variation, &$option)
    {
        foreach ($variation->getI18ns() as $i18n) {
            $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
            if ($languageId !== false) {
                $option['option_description'][intval($languageId)] = [
                    'name' => $i18n->getName()
                ];
            }
        }
    }
}