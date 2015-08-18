<?php
namespace jtl\Connector\OpenCart\Controller;

class CategoryI18n extends DataController
{
    const PULL_QUERY = 'SELECT * FROM oc_category_description WHERE category_id = ';

    public function pullData($data, $model)
    {
        $i18ns = [];
        $category_descriptions = $this->db->query(self::PULL_QUERY . $data['category_id']);
        foreach ($category_descriptions as $desc) {
            $model = $this->mapper->toHost($desc);
            $i18ns[] = $model;
        }
        return $i18ns;
    }

    protected function pullQuery($data, $limit)
    {
    }

}
