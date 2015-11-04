<?php


namespace jtl\Connector\OpenCart\Utility;


use jtl\Connector\Core\Utilities\Singleton;

class CategoryTree extends Singleton
{
    private $database;

    public function __construct()
    {
        $this->database = Db::getInstance();
    }


    public function fillCategoryLevelTable(array $parentIds = null, $level = 0)
    {
        $where = 'WHERE parent_id = 0';
        if ($parentIds === null) {
            $parentIds = [];
            $this->database->query('TRUNCATE TABLE jtl_connector_category_level');
        } else {
            $where = 'WHERE parent_id IN (' . implode(',', $parentIds) . ')';
            $parentIds = [];
        }
        $categories = $this->database->query('SELECT category_id FROM ' . DB_PREFIX . 'category ' . $where);
        if ($categories->num_rows > 0) {
            foreach ($categories->rows as $category) {
                $parentIds[] = (int)$category['category_id'];
                $sql = '
                    INSERT IGNORE INTO jtl_connector_category_level (category_id, level)
                    VALUES (%d, %d)';
                $this->database->query(sprintf($sql, $category['category_id'], $level));
            }
            $this->fillCategoryLevelTable($parentIds, $level + 1);
        }
    }

    /**
     * @return CategoryTree
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }
}