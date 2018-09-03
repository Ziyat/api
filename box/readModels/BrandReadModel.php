<?php

namespace box\readModels;

use box\entities\shop\Brand;
use box\repositories\NotFoundException;
use Elasticsearch\Client;
use yii\data\ActiveDataProvider;

class BrandReadModel
{

    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $id
     * @return Brand
     * @throws NotFoundException
     */
    public function get($id): Brand
    {
        if (!$brand = Brand::findOne($id)) {
            throw new NotFoundException('Brand is not found.');
        }
        return $brand;
    }

    public function getBrands(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Brand::find()
        ]);
    }

    /**
     * @param $id
     * @return ActiveDataProvider
     * @throws NotFoundException
     */
    public function getUsers($id): ActiveDataProvider
    {
        $brand = $this->get($id);
        return new ActiveDataProvider([
            'query' => $brand->getUsers()
        ]);
    }

    /**
     * @param $id
     * @return ActiveDataProvider
     * @throws NotFoundException
     */
    public function getUserProducts($id): ActiveDataProvider
    {
        $brand = $this->get($id);
        return new ActiveDataProvider([
            'query' => $brand->getUserProducts()
        ]);
    }

    /**
     * @param $id
     * @return ActiveDataProvider
     * @throws NotFoundException
     */
    public function getGenericProducts($id): ActiveDataProvider
    {
        $brand = $this->get($id);
        return new ActiveDataProvider([
            'query' => $brand->getGenericProducts()
        ]);
    }
}