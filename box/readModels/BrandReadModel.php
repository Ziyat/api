<?php

namespace box\readModels;

use box\entities\shop\Brand;
use box\entities\user\User;
use box\forms\SearchForm;
use box\repositories\NotFoundException;
use Elasticsearch\Client;
use yii\data\ActiveDataProvider;
use yii\helpers\Inflector;

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

    public function getUsers($id): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Brand::find()->andWhere(['id' => $id])->with('users')
        ]);
    }
}