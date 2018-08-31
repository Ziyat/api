<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace console\controllers;


use box\entities\generic\GenericProduct;
use box\entities\generic\GenericValue;
use box\entities\shop\Category;
use box\entities\shop\product\Value;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use function foo\func;
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
        $query = GenericProduct::find()
            ->with(['categoryAssignments', 'tagAssignments', 'modifications', 'values'])
            ->orderBy('id');

        $this->stdout('Start reindex generic product > ' . date('Y-m-d H:i:s') . PHP_EOL);
        $this->stdout('Cleaning' . PHP_EOL);

        try {
            $this->client->indices()->delete([
                'index' => 'watch'
            ]);
        } catch (Missing404Exception $e) {
            $this->stdout('Index is empty' . PHP_EOL);
        }

        $this->client->ping();

        $this->stdout('Indexing of Generic Products' . PHP_EOL);

        foreach ($query->each() as $product) {
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

        $this->stdout('Done!' . PHP_EOL);
    }
}