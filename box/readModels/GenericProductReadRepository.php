<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;


use box\forms\SearchForm;
use Elasticsearch\Client;
use yii\helpers\Inflector;

class GenericProductReadRepository
{
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(SearchForm $form)
    {
        $text = $form->text;
        $alterText = $form->getAlternateText($form->getTypeOfText());
        $transliterateText = Inflector::slug($text);
        $response = $this->query($text) ?? $this->query($alterText) ?? $this->query($transliterateText) ?? [];
        return $response;
    }
    protected function query($text)
    {
        $result = $this->client->search([
            'index' => 'watch',
            'type' => 'generic_products',
            'body' => [
                '_source' => [
                    'name',
                    'categoryId',
                    'categoryName',
                    'brandId',
                    'brandName',
                    'categoryBreadcrumbs'
                ],
                'from' => 0,
                'size' => 12,
                'query' => [
                    'query_string' => [
                        'query' => '*' . $text . '*',
                        'fields' => ['name','categoryName','brandName'],
                    ],
                ]
            ]
        ]);

        return $result['hits']['hits'] ?: null;
    }
}