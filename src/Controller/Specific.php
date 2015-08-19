<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

class Specific extends BaseController
{


    /**
     * Called on a pull on the main model controllers including their sub model controllers.
     *
     * @param $data  array  For sub models their parent models data.
     * @param $model object For sub models their parent model.
     * @param $limit int    The limit.
     * @return array A list of models resulting from the pull query.
     */
    public function pullData($data, $model, $limit = null)
    {
        // TODO: Implement pullData() method.
    }

    /**
     * Just return the query for the the pulling of data.
     *
     * @param $data  array The data.
     * @param $limit int   The limit.
     * @return string The query.
     */
    protected function pullQuery($data, $limit = null)
    {
        // TODO: Implement pullQuery() method.
    }

    protected function pushData($data, $model)
    {
        // TODO: Implement pushData() method.
    }

    protected function deleteData($data, $model)
    {
        // TODO: Implement deleteData() method.
    }

    /**
     * Called on the specific controller in order to get the availability of the model.
     *
     * @return string|int The availability of the model.
     */
    protected function getStats()
    {
        // TODO: Implement getStats() method.
    }
}
