<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services\search;


use box\forms\SearchForm;
use Elasticsearch\Client;
use yii\helpers\Inflector;
use yii\web\BadRequestHttpException;

class SearchService
{
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function userProducts(SearchForm $form)
    {
        $params = [
            'index' => 'watch_user_products',
            'type' => 'user_products',
            '_source' => [
                'name',
                'categoryId',
                'categoryName',
                'brandId',
                'brandName',
                'categoryBreadcrumbs',
                'characteristics'
            ],
            'fields' => ['name', 'categoryName', 'brandName', 'characteristics']
        ];

        return $this->preOperationToSearch($form, $params);
    }

    public function brands(SearchForm $form)
    {
        $params = [
            'index' => 'watch_brands',
            'type' => 'brands',
            '_source' => ['name'],
            'fields' => ['name']
        ];

        return $this->preOperationToSearch($form, $params);
    }

    public function genericProducts(SearchForm $form)
    {
        $params = [
            'index' => 'watch_generic_products',
            'type' => 'generic_products',
            '_source' => [
                'name',
                'categoryId',
                'categoryName',
                'brandId',
                'brandName',
                'categoryBreadcrumbs',
                'characteristics'
            ],
            'fields' => ['name', 'categoryName', 'brandName', 'characteristics']
        ];

        return $this->preOperationToSearch($form, $params);
    }

    public function users(SearchForm $form)
    {
        $params = [
            'index' => 'watch_users',
            'type' => 'users',
            '_source' => [
                'name',
                'categoryId',
                'categoryName',
                'brandId',
                'brandName',
                'categoryBreadcrumbs',
                'characteristics'
            ],
            'fields' => ['name', 'categoryName', 'brandName', 'characteristics']
        ];

        return $this->preOperationToSearch($form, $params);
    }

    protected function preOperationToSearch(SearchForm $form, array $params)
    {
       $text = $form->text;

        $alterText = $form->getAlternateText($form->getTypeOfText());

        $transliterateText = Inflector::slug($text);

        return $this->search($params, $text) ?? $this->search($params, $alterText)
            ?? $this->search($params, $transliterateText) ?? [];
    }


    protected function search(array $params, string $text, int $from = 0, int $size = 12)
    {
        $result = $this->client->search([
            'index' => $params['index'],
            'type' => $params['type'],
            'body' => [
                '_source' => $params['_source'],
                'from' => $from,
                'size' => $size,
                'query' => [
                    'query_string' => [
                        'query' => '*' . $text . '*',
                        'fields' => $params['fields'],
                    ],
                ]
            ]
        ]);

        return $result['hits']['hits'] ?: null;
    }
}