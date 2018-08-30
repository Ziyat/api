<?php

namespace box\entities\shop\product\queries;

use box\entities\shop\product\Product;
use yii\db\ActiveQuery;

class ProductQuery extends ActiveQuery
{
    /**
     * @param null $alias
     * @return $this
     */
    public function active($alias = null)
    {
        return $this->andWhere([
            '!=',
            ($alias ? $alias . '.' : '') . 'status',
            Product::STATUS_DELETED,
        ]);
    }
}