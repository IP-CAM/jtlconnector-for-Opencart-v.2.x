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
use jtl\Connector\Model\DataModel;
use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\OpenCart\Utility\Date;
use jtl\Connector\Type\DataType;

abstract class BaseMapper extends Singleton
{
    protected $type;
    protected $model;
    protected $push = [];
    protected $pull = [];

    /**
     * BaseMapper constructor.
     *
     * @param null $subClass Test classes chave to specify their parent class as they should not be used to build the
     * controller and mapper.
     */
    public function __construct($subClass = null)
    {
        $reflect = (is_null($subClass)) ? new \ReflectionClass($this) : new \ReflectionClass($subClass);
        $shortName = $reflect->getShortName();
        $typeClass = Constants::CORE_TYPE_NAMESPACE . $shortName;
        $this->model = Constants::CORE_MODEL_NAMESPACE . $shortName;
        $this->type = new $typeClass();
    }

    /**
     * Method which maps the endpoint model to an host version.
     *
     * @param array $data The endpoint model which should be mapped.
     *
     * @return DataModel The result of mapping the OpenCart model to a host model.
     */
    public function toHost(array $data)
    {
        $model = new $this->model();
        foreach ($this->pull as $host => $endpoint) {
            $setter = 'set' . ucfirst($host);
            $functionName = strtolower($host);
            if (method_exists($this, $functionName) && is_null($endpoint)) {
                $value = $this->$functionName($data);
            } else {
                $value = isset($data[$endpoint]) ? $data[$endpoint] : null;
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
                    $value = Date::isOpenDate($value) ? null : new \DateTime($value);
                }
            }
            if (!empty($value)) {
                $model->$setter($value);
            }
        }
        return $model;
    }

    /**
     * Method which maps the host model to an endpoint version.
     *
     * @param DataModel $data The host model which should be mapped.
     * @param null $customData Additional data which should be past to an extra defined method.
     *
     * @return array The result of mapping the host model to an OpenCart model.
     */
    public function toEndpoint(DataModel $data, $customData = null)
    {
        $model = [];
        foreach ($this->push as $endpoint => $host) {
            $functionName = strtolower($endpoint);
            if (method_exists($this, $functionName) && is_null($host)) {
                $model[$endpoint] = $this->$functionName($data, $customData);
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