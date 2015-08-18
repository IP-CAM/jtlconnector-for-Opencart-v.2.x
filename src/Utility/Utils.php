<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 17.08.2015
 * Time: 12:08
 */

namespace jtl\Connector\OpenCart\Utility;


use jtl\Connector\Core\Utilities\Language;
use jtl\Connector\Session\SessionHelper;

class Utils
{
    private static $instance;
    private $session = null;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->session = new SessionHelper("openCartConnector");
    }

    public function getLanguages()
    {
        if (is_null($this->session->languages)) {
            $languages = [];
            $supportedLanguages = Db::getInstance()->query('SELECT * FROM oc_language');
            $column = 0;
            foreach ($supportedLanguages as $language) {
                $obj = new \stdClass;
                $obj->iso2 = $language['code'];
                $obj->iso3 = Language::convert($language['code']);
                $obj->name = $language['name'];
                $obj->column = $column;
                $languages[$column] = $obj;
                $column++;
            }
            $this->session->languages = $languages;
        }
        return $this->session->languages;
    }

    public function getLanguageId($iso)
    {
        foreach ($this->getLanguages() as $key => $language) {
            if ($language->iso3 === $iso) {
                return $key;
            }
        }
        return false;
    }

    public function getCountryIso($id)
    {
        $db = DB::getInstance();
        $country = $db->query('SELECT iso_code_2 FROM oc_country WHERE country_id="' . $id . '"');
        if ($country) {
            return $country;
        }
    }
}