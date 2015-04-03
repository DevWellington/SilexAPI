<?php

namespace DevWellington\Shop\Mapper;

use DevWellington\Shop\Entity\EntityInterface;

class ProductMapper implements MapperInterface
{
    /**
     * @var EntityInterface
     */
    private $productEntity;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return EntityInterface
     */
    public function getEntity()
    {
        return $this->productEntity;
    }

    /**
     * @param EntityInterface $productEntity
     * @return $this
     */
    public function setEntity(EntityInterface $productEntity)
    {
        $this->productEntity = $productEntity;
        return $this;
    }

    /**
     * @return bool|EntityInterface
     */
    public function insert()
    {
        $data[':name'] = $this->productEntity->getName();
        $data[':description'] = $this->productEntity->getDescription();
        $data[':value'] = $this->productEntity->getValue();

        $sql = "INSERT INTO product (name, description, value)
                VALUES (:name, :description, :value) ";

        $stmt = $this->pdo->prepare($sql);
        if($stmt->execute($data))
            return $this->returnData();

        return false;
    }

    /**
     * @return bool|EntityInterface
     */
    public function update()
    {
        $data[':id'] = $this->productEntity->getId();
        $data[':name'] = $this->productEntity->getName();
        $data[':description'] = $this->productEntity->getDescription();
        $data[':value'] = $this->productEntity->getValue();

        $sql = "UPDATE product SET
                    name = :name,
                    description = :description,
                    value = :value
                WHERE
                    id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        if($stmt->execute($data))
            return $this->returnData();

        return false;
    }

    /**
     * @return bool|EntityInterface
     */
    public function delete()
    {
        $data[':id'] = $this->productEntity->getId();

        $sql = "DELETE FROM product WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        if($stmt->execute($data))
            return $this->returnData();

        return false;
    }

    private function returnData()
    {
        $data['id'] =
            ($this->productEntity->getId() == '') ?
                $this->pdo->lastInsertId() :
                $this->productEntity->getId()
        ;
        $data['name'] = $this->productEntity->getName();
        $data['description'] = $this->productEntity->getDescription();
        $data['value'] = $this->productEntity->getValue();

        return $data;
    }
}