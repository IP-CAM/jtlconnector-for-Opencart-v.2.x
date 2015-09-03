<?php

namespace jtl\Connector\OpenCart\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @package jtl\Connector\OpenCart\Entities
 *
 * @ORM\Table(name="oc_category")
 * @ORM\Entity
 */
class Category
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(name="category_id", type="int")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}