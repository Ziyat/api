<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace console\controllers;


use box\entities\generic\GenericProduct;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use yii\console\Controller;

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
            ->with(['categoryAssignments','tagAssignments','modifications','values'])
            ->orderBy('id');

        $this->stdout('Cleaning' . PHP_EOL);

        try{
            $this->client->indices()->delete([
                'index' => 'watch'
            ]);
        }catch (Missing404Exception $e)
        {
            $this->stdout('Index is empty' . PHP_EOL);
        }

        $this->client->ping();

        $this->stdout('Indexing of Generic Products' . PHP_EOL);

        foreach ($query->each() as $product){
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
                    'description' => $product->description,
                ],
            ]);
        }

        $this->stdout('Done!' . PHP_EOL);
    }
}