<?php

namespace DevWellington\Shop\Service;

use DevWellington\Shop\Entity\EntityInterface;
use DevWellington\Shop\Mapper\MapperInterface;

class ProductService implements ServiceInterface
{
    /**
     * @var EntityInterface
     */
    private $productEntity;

    /**
     * @var MapperInterface
     */
    private $productMapper;

    /**
     * @param EntityInterface $productEntity
     * @param MapperInterface $productMapper
     */
    public function __construct(
        EntityInterface $productEntity,
        MapperInterface $productMapper
    ){
        $this->productEntity = $productEntity;
        $this->productMapper = $productMapper;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function insert(array $data)
    {
        $this->productEntity->setName($data['name']);
        $this->productEntity->setDescription($data['description']);
        $this->productEntity->setValue($data['value']);

        return $this->productMapper
            ->setEntity($this->productEntity)
            ->insert()
        ;
    }

    public function update(array $data)
    {
        $this->productEntity->setId($data['id']);
        $this->productEntity->setName($data['name']);
        $this->productEntity->setDescription($data['description']);
        $this->productEntity->setValue($data['value']);

        return $this->productMapper
            ->setEntity($this->productEntity)
            ->update()
        ;
    }

    public function delete($id)
    {
        $this->productEntity->setId($id);

        return $this->productMapper
            ->setEntity($this->productEntity)
            ->delete()
        ;
    }
} 