<?php
namespace jtl\Connector\OpenCart\Controller;

class CategoryI18n extends BaseController
{

    public function pullData($data, $model, $limit = null)
    {
        var_dump($data);
        $return = [];
        $query = $this->pullQuery($data);
        $result = $this->db->query($query);
        foreach ($result as $row) {
            $model = $this->mapper->toHost($row);
            $return[] = $model;
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT c.*, l.name
            FROM oc_category_description c
            INNER JOIN oc_language l ON c.language_id=l.language_id
            WHERE c.category_id = %d',
            $data['category_id']
        );
    }

    /*public function pushData($data, $model)
    {
        foreach ($data->getI18ns() as $i18n) {
            $id = Utils::getInstance()->getLanguageIdByIso($i18n->getLanguageISO());

            $model->name[$id] = $i18n->getName();
            $model->description[$id] = $i18n->getDescription();
            //$model->link_rewrite[$id] = \Tools::str2url(empty($i18n->getUrlPath()) ? $i18n->getName() : $i18n->getUrlPath());
            $model->meta_title[$id] = $i18n->getTitleTag();
            $model->meta_keywords[$id] = $i18n->getMetaKeywords();
            $model->meta_description[$id] = $i18n->getMetaDescription();
        }
    }*/

    protected function pushData($data, $model)
    {
        // TODO: Implement pushData() method.
    }

    protected function deleteData($data, $model)
    {
        // TODO: Implement deleteData() method.
    }

    protected function getStats()
    {
        // TODO: Implement getStats() method.
    }

}
