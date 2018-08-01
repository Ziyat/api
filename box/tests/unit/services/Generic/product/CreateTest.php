<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace tests\unit\services\Generic\product;

use box\forms\generic\ProductCreateForm;
use box\forms\generic\ValueForm;
use box\services\generic\ProductService;
use box\repositories\generic\ProductRepository;

use box\entities\user\User;
use box\repositories\BrandRepository;
use box\repositories\CategoryRepository;
use box\repositories\TagRepository;
use box\services\TransactionManager;
use Codeception\Test\Unit;
use common\fixtures\shop\BrandFixture;
use common\fixtures\shop\CategoryFixture;
use common\fixtures\shop\CharacteristicFixture;
use common\fixtures\shop\TagFixture;
use yii\helpers\ArrayHelper;

class GenericProductServiceCreateTest extends Unit
{

    public function _fixtures()
    {
        return [
            'brands' => [
                'class' => BrandFixture::class,
                'dataFile' => codecept_data_dir() . 'brand.php'
            ],
            'categories' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'category.php'
            ],
            'tags' => [
                'class' => TagFixture::class,
                'dataFile' => codecept_data_dir() . 'tag.php'
            ],
            'characteristics' => [
                'class' => CharacteristicFixture::class,
                'dataFile' => codecept_data_dir() . 'characteristic.php'
            ]
        ];
    }

    public $service;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new ProductService(
            new ProductRepository(),
            new BrandRepository(),
            new CategoryRepository(),
            new TagRepository(),
            new TransactionManager()
        );
    }

    public function testSuccess()
    {
        \Yii::$app->request->cookieValidationKey = 'dadadadada';
        \Yii::$app->user->login(User::findOne(1));
        $name = 'rolex';
        $description = 'desc';

        $tagTextNew = 'watch,hand';
        $tagExisting = [1,2];


        $value = 'jojo';

        $form = new ProductCreateForm();

        $characteristic = new ValueForm();
        $characteristic->id = 1;
        $characteristic->value = $value;

        $form->brandId = 1;
        $form->categories->main = 2;
        $form->categories->others = [3];
        $form->characteristics = [$characteristic];

        $form->name = $name;
        $form->description = $description;
        $form->tags->textNew = $tagTextNew;
        $form->tags->existing = $tagExisting;


        $product = $this->service->create($form);
        $this->assertNotNull($product);
        $this->assertEquals($product->name, $name);
        $this->assertEquals($product->description, $description);

        $this->assertEquals($product->category_id, 2);
        $this->assertEquals($product->brand_id, 1);

        // check Tags

        $this->assertTrue(ArrayHelper::isIn('watch',ArrayHelper::map($product->tags,'name','name')));
        $this->assertTrue(ArrayHelper::isIn('hand',ArrayHelper::map($product->tags,'name','name')));

        // check main Category

        $this->assertEquals($product->category->name,'Notebook');

        // check others Categories

        $this->assertTrue(ArrayHelper::isIn('Notebook',ArrayHelper::map($product->categories,'name','name')));

        // check brand

        $this->assertEquals($product->brand->name,'name');

        // check characteristic values

        $this->assertEquals($product->values[0]->value,$value);


    }


}