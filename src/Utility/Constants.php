<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 19.08.2015
 * Time: 08:27
 */

namespace jtl\Connector\OpenCart\Utility;


class Constants
{
    const CORE_NAMESPACE = "\\jtl\\Connector\\";
    const CONNECTOR_NAMESPACE = self::CORE_NAMESPACE . "OpenCart\\";
    const MAPPER_NAMESPACE = self::CONNECTOR_NAMESPACE . "Mapper\\";
    const CONTROLLER_NAMESPACE = self::CONNECTOR_NAMESPACE . "Controller\\";
    const CORE_MODEL_NAMESPACE = self::CORE_NAMESPACE . "Model\\";
}