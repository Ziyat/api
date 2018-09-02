<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers;

use box\forms\SearchForm;
use box\services\search\SearchService;
use Yii;
use yii\rest\Controller;

class SearchController extends Controller
{
    public $service;

    public function __construct(
        string $id,
        $module,
        SearchService $service,
        array $config = []
    )
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }


    public function actionBrands()
    {
        $response = null;
        $form = new SearchForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            return $this->service->brands($form);
        }
        return $form;
    }

    public function actionGenericProducts()
    {
        $response = null;
        $form = new SearchForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            return $this->service->genericProducts($form);
        }
        return $form;
    }

    public function actionUsers()
    {
        $response = null;
        $form = new SearchForm();
        $form->load(Yii::$app->request->bodyParams, '');
        if ($form->validate()) {
            return $this->service->users($form);
        }
        return $form;
    }
}