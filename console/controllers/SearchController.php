<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace console\controllers;


use box\entities\generic\GenericProduct;
use box\entities\shop\Brand;
use box\entities\shop\product\Product;
use box\entities\user\User;
use box\services\search\BrandIndexer;
use box\services\search\GenericProductIndexer;
use box\services\search\UserIndexer;
use box\services\search\UserProductIndexer;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use yii\console\Controller;

class SearchController extends Controller
{
    public $genericProductIndexer;
    public $brandIndexer;
    public $userIndexer;
    public $userProductIndexer;
    public $client;

    public function __construct(
        string $id,
        $module,
        GenericProductIndexer $genericProductIndexer,
        BrandIndexer $brandIndexer,
        UserIndexer $userIndexer,
        UserProductIndexer $userProductIndexer,
        Client $client,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->genericProductIndexer = $genericProductIndexer;
        $this->userProductIndexer = $userProductIndexer;
        $this->brandIndexer = $brandIndexer;
        $this->userIndexer = $userIndexer;
        $this->client = $client;
    }

    public function actionDelete()
    {
        try {

            $this->client->indices()->delete([
                'index' => 'watch_user_products'
            ]);

            $this->client->indices()->delete([
                'index' => 'watch_generic_products'
            ]);

            $this->client->indices()->delete([
                'index' => 'watch_brands'
            ]);

            $this->client->indices()->delete([
                'index' => 'watch_users'
            ]);

        } catch (Missing404Exception $e) {
            $this->stdout('Index is empty' . PHP_EOL);
        }
    }

    public function actionReindex()
    {


        $this->stdout('Cleaning | ' . date('Y-m-d H:i:s') . PHP_EOL);
        try {
            $this->genericProductIndexer->clear();
            $this->brandIndexer->clear();
            $this->userIndexer->clear();
            $this->userProductIndexer->clear();
        } catch (Missing404Exception $e) {
            $this->stdout('Index is empty' . PHP_EOL);
        }

        $queryGenericProducts = GenericProduct::find()
            ->with(['categoryAssignments', 'tagAssignments', 'modifications', 'values'])
            ->orderBy('id');

        // genericProductIndexer

        $this->stdout('Indexing of Generic Products' . PHP_EOL);
        foreach ($queryGenericProducts->each() as $product)
        {
            /**
             * @var GenericProduct $product
             */
            $this->stdout('Generic Products #' . $product->id . PHP_EOL);
            $this->genericProductIndexer->index($product);
        }
        $this->stdout('Indexing of Generic Products Done!' . PHP_EOL . PHP_EOL);


        // brandIndexer

        $queryBrands = Brand::find()->orderBy('id');
        $this->stdout('Indexing of Brands' . PHP_EOL);
        foreach ($queryBrands->each() as $brand) {
            /**
             * @var Brand $brand
             */
            $this->stdout('Brands #' . $brand->id . PHP_EOL);
            $this->brandIndexer->index($brand);
        }
        $this->stdout('Indexing of Brands Done!' . PHP_EOL . PHP_EOL);


        // userIndexer

        $queryUsers = User::find()->orderBy('id')->roleUser()->active();
        $this->stdout('Indexing of Users' . PHP_EOL);
        foreach ($queryUsers->each() as $user) {
            /**
             * @var User $user
             */
            $this->stdout('Users #' . $user->id . PHP_EOL);
            $this->userIndexer->index($user);
        }
        $this->stdout('Indexing of Users Done!' . PHP_EOL . PHP_EOL);



        // userProductIndexer

        $queryUsers = Product::find()
            ->orderBy('id')
            ->andWhere(['status'=> [Product::STATUS_MARKET, Product::STATUS_ACTIVE]]);

        $this->stdout('Indexing of User products' . PHP_EOL);
        foreach ($queryUsers->each() as $products) {
            /**
             * @var Product $products
             */
            $this->stdout('User products #' . $products->id . PHP_EOL);
            $this->userProductIndexer->index($products);
        }
        $this->stdout('Indexing of User products Done!' . PHP_EOL . PHP_EOL);


    }
}