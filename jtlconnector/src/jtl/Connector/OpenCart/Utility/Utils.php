<?php

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\Utilities\Language;
use jtl\Connector\Core\Utilities\Singleton;
use jtl\Connector\Session\SessionHelper;

class Utils extends Singleton
{
    private $session = null;

    public function __construct()
    {
        $this->session = new SessionHelper("opencart");
        $this->session->languages = [];
    }

    public function getLanguageId($iso)
    {
        if (in_array($iso, $this->session->languages)) {
            return $this->session->languages[$iso];
        } else {
            $id = Db::getInstance()->queryOne(sprintf('
                SELECT language_id
                FROM oc_language
                WHERE code = "%s"',
                Language::convert(null, $iso)
            ));
            if (!is_null($id)) {
                $this->session->languages[$iso] = $id;
            }
            return $id;
        }
    }

    /**
     * Removes an item from the array and returns its value.
     *
     * @param $arr array  The input array
     * @param $key string The key pointing to the desired value
     * @return mixed The value mapped to $key or null if none
     */
    public function array_remove(array &$arr, $key)
    {
        if (array_key_exists($key, $arr)) {
            $val = $arr[$key];
            unset($arr[$key]);
            return $val;
        }
        return null;
    }

    /**
     * @return Utils
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }


}