<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @author Daniel Hoffmann <daniel.hoffmann@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Core\Model\Model;
use jtl\Connector\Core\Utilities\Singleton;
use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\OpenCart\Utility\Date;
use jtl\Connector\Type\DataType;

abstract class BaseMapper extends Singleton
{
    /**
     * @var $type DataType
     */
    protected $type = null;
    protected $model = null;
    protected $endpointModel = null;
    protected $push = [];
    protected $pull = [];

    public function __construct($subClass = null)
    {
        if (is_null($subClass)) {
            $reflect = new \ReflectionClass($this);
        } else {
            $reflect = new \ReflectionClass($subClass);
        }
        $shortName = $reflect->getShortName();
        $typeClass = Constants::CORE_TYPE_NAMESPACE . $shortName;
        $this->model = Constants::CORE_MODEL_NAMESPACE . $shortName;
        $this->type = new $typeClass();
    }

    /**
     * @param $data array
     * @return Model
     */
    public function toHost($data)
    {
        $model = new $this->model();
        foreach ($this->pull as $host => $endpoint) {
            $setter = 'set' . ucfirst($host);
            $fnName = strtolower($host);
            if (method_exists($this, $fnName) && is_null($endpoint)) {
                $value = $this->$fnName($data);
            } else {
                $value = (isset($data[$endpoint])) ? $data[$endpoint] : null;
                $property = $this->type->getProperty($host);
                if ($property->isNavigation()) {
                    $subControllerName = Constants::CONTROLLER_NAMESPACE . $endpoint;
                    if (class_exists($subControllerName)) {
                        $subController = new $subControllerName();
                        if ($subController instanceof BaseController) {
                            $value = $subController->pullData($data, $model);
                        }
                    }
                } elseif ($property->isIdentity()) {
                    $value = new Identity($value);
                } elseif ($property->getType() == 'boolean') {
                    $value = (bool)$value;
                } elseif ($property->getType() == 'integer') {
                    $value = intval($value);
                } elseif ($property->getType() == 'double') {
                    $value = floatval($value);
                } elseif ($property->getType() == 'DateTime') {
                    $value = Date::open_date($value) ? null : new \DateTime($value);
                }
            }
            if (!empty($value)) {
                $model->$setter($value);
            }
        }
        return $model;
    }

    public function toEndpoint($data, $customData = null)
    {
        $model = [];
        foreach ($this->push as $endpoint => $host) {
            $fnName = strtolower($endpoint);
            // Extra defined methods
            if (method_exists($this, $fnName) && is_null($host)) {
                $model[$endpoint] = $this->$fnName($data, $customData);
            } else {
                $getter = 'get' . ucfirst($host);
                $value = $data->$getter();
                $property = $this->type->getProperty($host);
                if ($property->isNavigation()) {
                    $subControllerName = Constants::CONTROLLER_NAMESPACE . $endpoint;
                    if (class_exists($subControllerName)) {
                        $subController = new $subControllerName();
                        if ($subController instanceof BaseController) {
                            $subController->pushData($data, $model);
                        }
                    }
                } else {
                    if ($property->isIdentity()) {
                        $value = $value->getEndpoint();
                    } elseif ($property->getType() == 'DateTime') {
                        $value = $value === null ? '0000-00-00 00:00:00' : $value->format('Y-m-d H:i:s');
                    }
                    $model[$endpoint] = $value;
                }
            }
        }
        return $model;
    }

}