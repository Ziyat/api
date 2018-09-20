<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\shop\shipping;

use box\entities\shop\shipping\ShippingService;
use box\entities\shop\shipping\ShippingServiceRates;
use box\forms\CompositeForm;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * Class ShippingServiceForm
 * @package forms\shop\shipping
 * @property $name
 * @property $description
 * @property $photo
 *
 * @property ShippingServiceRateForm $rates[]
 */
class ShippingServiceForm extends CompositeForm
{
    public $name;
    public $description;
    public $photo;

    public $_shippingService;
    public function __construct(ShippingService $shippingService = null, array $config = [])
    {
        if ($shippingService) {
            $this->name = $shippingService->name;
            $this->description = $shippingService->description;
            $this->photo = $shippingService->photo;
            foreach ($shippingService->shippingServiceRates as $rate){
                /**
                 * @var ShippingServiceRates $rate
                 */
                $rates[] = new ShippingServiceRateForm($rate);
            }
            $this->rates = $rates;
            $this->_shippingService = $shippingService;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            [['name', 'description'], 'string'],
            ['photo', 'file', 'extensions' => 'jpeg, gif, png, jpg'],
        ];
    }


    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstanceByName('photo');
            $forms = [];
            $requestRates = ArrayHelper::getValue(\Yii::$app->request->bodyParams, 'rates');
            if (count($requestRates) > 0) {
                for ($i = 0; $i < count($requestRates); $i++) {
                    $forms[$i] = new ShippingServiceRateForm();
                }

                $this->rates = $forms;

                $this->load(\Yii::$app->request->bodyParams, '');
            }

            return true;
        }
        return false;
    }

    protected function internalForms(): array
    {
        return ['rates'];
    }
}