<?php

namespace box\repositories;

use box\entities\shop\Brand;
use box\repositories\NotFoundException;
use yii\data\ActiveDataProvider;

class BrandReadModel
{
    /**
     * @param $id
     * @return Brand
     * @throws \box\repositories\NotFoundException
     */
    public function get($id): Brand
    {
        if (!$brand = Brand::findOne($id)) {
            throw new NotFoundException('Brand is not found.');
        }
        return $brand;
    }

    public function getCarousels(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Brand::find()
        ]);
    }
}