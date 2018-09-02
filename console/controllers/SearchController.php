<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace console\controllers;


use box\entities\generic\GenericProduct;
use box\entities\shop\Brand;
use box\entities\shop\Category;
use box\entities\user\User;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class SearchController extends Controller
{
    public $client;

    public function __construct(
        string $id,
        $module,
        Client $client,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->client = $client;
    }

    public function actionReindex()
    {
        $queryGenericProducts = GenericProduct::find()
            ->with(['categoryAssignments', 'tagAssignments', 'modifications', 'values'])
            ->orderBy('id');

        $this->stdout('Cleaning | ' . date('Y-m-d H:i:s') . PHP_EOL);

        try {
            $this->client->indices()->delete([
                'index' => 'watch'
            ]);
        } catch (Missing404Exception $e) {
            $this->stdout('Index is empty' . PHP_EOL);
        }

        $this->client->ping();

        $this->stdout('Indexing of Generic Products' . PHP_EOL);

        foreach ($queryGenericProducts->each() as $product) {
            /**
             * @var GenericProduct $product
             */
            $this->stdout('Generic Products #' . $product->id . PHP_EOL);
            $this->client->index([
                'index' => 'watch',
                'type' => 'generic_products',
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
                ],
            ]);
        }

        $this->stdout('Indexing of Generic Products Done!' . PHP_EOL . PHP_EOL);

        $queryBrands = Brand::find()->orderBy('id');

        $this->stdout('Indexing of Brands' . PHP_EOL);

        foreach ($queryBrands->each() as $brand) {
            /**
             * @var Brand $brand
             */
            $this->stdout('Brands #' . $brand->id . PHP_EOL);
            $this->client->index([
                'index' => 'watch',
                'type' => 'brands',
                'id' => $brand->id,
                'body' => [
                    'name' => $brand->name,
                ],
            ]);
        }

        $this->stdout('Indexing of Brands Done!' . PHP_EOL . PHP_EOL);


        $queryUsers = User::find()->orderBy('id');

        $this->stdout('Indexing of Brands' . PHP_EOL);

        foreach ($queryUsers->each() as $user) {
            /**
             * @var User $user
             */
            $this->stdout('Brands #' . $user->id . PHP_EOL);
            $this->client->index([
                'index' => 'watch',
                'type' => 'Users',
                'id' => $user->id,
                'body' => [
                    'name' => $user->name,
                ],
            ]);
        }

        $this->stdout('Indexing of Brands Done!' . PHP_EOL . PHP_EOL);


    }
}