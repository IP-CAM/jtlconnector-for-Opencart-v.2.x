<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Core\Database\Mysql;
use jtl\Connector\Core\Utilities\Singleton;

class Product extends Singleton
{
    protected $db;

    protected function __construct()
    {
        $mysql = Mysql::getInstance();
        $mysql->connect([
            'host' => 'localhost',
            'user' => 'wordpress',
            'password' => 'Xpt7uW2vZdDSfNFj',
            'name' => 'wordpress'
        ]);

        $this->db = $mysql;
    }

    public function find($id)
    {
        $id = (int)$id;
        if ($id > 0) {
            return $this->db->fetchSingle(sprintf('SELECT data FROM wp_posts WHERE ID = %s', $id));
        }

        return null;
    }

    public function findAll($limit = 100)
    {
        $result = [];
        $rows = $this->db->query(sprintf('SELECT *
                                  FROM wp_posts p
                                  WHERE p.post_type = "product" LIMIT %s', $limit));

        if ($rows !== null) {
            foreach ($rows as $row) {
                $result[] = $row['data'];
            }
        }

        return $result;
    }

    public function save(\jtl\Connector\Model\Product &$model)
    {
        if ($model->getId()->getEndpoint() > 0) {
            $stmt = $this->db->prepare(sprintf('UPDATE wp_posts SET post_title = :name WHERE id = :id'));
            $stmt->bindValue(':name', $model->getI18ns()[0]->getName());
            $stmt->bindValue(':id', $model->getId()->getEndpoint(), SQLITE3_INTEGER);

            $stmt->execute();
        } else {
            $stmt = $this->db->prepare(sprintf('INSERT INTO wp_posts (id, wp_title) VALUES (null, :name)'));
            $stmt->bindValue(':name', $model->getI18ns()[0]->getName());

            $result = $stmt->execute();
            if ($result) {
                $model->getId()->setEndpoint($this->db->getLastInsertRowId());
            }
        }
    }

    public function remove($id)
    {
        $id = (int)$id;
        if ($id > 0) {
            return $this->db->query(sprintf('DELETE FROM wp_posts WHERE id = %s', $id));
        }

        return false;
    }

    public function fetchCount()
    {
        return (int)$this->db->fetchSingle(sprintf('SELECT count(*)
                                                      FROM wp_posts p
                                                      WHERE p.post_type = "product"'));
    }
}