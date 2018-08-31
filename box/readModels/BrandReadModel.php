<?php

namespace box\readModels;

use box\entities\shop\Brand;
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
            'type' => 'brands',
            'body' => [
                '_source' => [
                    'name',
                ],
                'from' => 0,
                'size' => 12,
                'query' => [
                    'query_string' => [
                        'query' => '*' . $text . '*',
                        'fields' => ['name'],
                    ],
                ]
            ]
        ]);

        return $result['hits']['hits'] ?: null;
    }

}