<?php

namespace box\services\search;

use box\entities\shop\product\Product;
use Elasticsearch\Client;
use box\entities\shop\Category;
use yii\helpers\ArrayHelper;

class UserProductIndexer
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function clear(): void
    {
        $this->client->deleteByQuery([
            'index' => 'watch_user_products',
            'type' => 'user_products',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);
    }

    public function index(Product $product): void
    {
        $this->client->index([
            'index' => 'watch_user_products',
            'type' => 'user_products',
            'id' => $product->id,
            'body' => [
                'name' => $product->name,
                'categoryId' => $product->category->id,
                'categoryName' => $product->category->name,
                'categoryBreadcrumbs' => implode(' / ', array_filter(ArrayHelper::getColumn($product->category->parents, function (Category $category) {
                        return $category->depth > 0 ? $category->name : null;
                    }))) . ' / ' . $product->category->name,
                'brandId' => $product->brand->id,
                'brandName' => $product->brand->name,
                'characteristics' => ArrayHelper::getColumn($product->values,'value'),
                'createdBy' => $product->created_by,
            ],
        ]);
    }

    public function remove(Product $product): void
    {
        $this->client->delete([
            'index' => 'watch_user_products',
            'type' => 'user_products',
            'id' => $product->id,
        ]);
    }
}