<?php

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
}