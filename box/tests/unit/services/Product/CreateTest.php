<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace tests\unit\services\Product;

use box\entities\shop\Characteristic;
use box\entities\shop\product\Product;
use box\entities\shop\product\Value;
use box\entities\user\User;
use box\forms\shop\product\ValueForm;
use box\repositories\BrandRepository;
use box\repositories\CategoryRepository;
use box\repositories\ProductRepository;
use box\repositories\TagRepository;
use box\services\ProductService;
use box\services\TransactionManager;
use Codeception\Test\Unit;
use common\fixtures\shop\BrandFixture;
use common\fixtures\shop\CategoryFixture;
use box\forms\shop\product\ProductCreateForm;
use common\fixtures\shop\CharacteristicFixture;
use common\fixtures\shop\TagFixture;
use yii\helpers\ArrayHelper;

class ProductServiceCreateTest extends Unit
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
        $metaTitle = 'titleMeta';
        $metaDesc = 'descMeta';
        $metaKey = 'KeyMeta';

        $tagTextNew = 'watch,hand';
        $tagExisting = [1,2];
        $price = 22.5;


        $value = 'jojo';

        $form = new ProductCreateForm();

        $form->brandId = 1;
        $form->categories->main = 2;
        $form->categories->others = [3];
        $form->values = [new ValueForm(Characteristic::findOne(1),Value::create(1,$value))];

        $form->name = $name;
        $form->description = $description;
        $form->priceType = Product::PRICE_TYPE_FIX;
        $form->quantity = 20;

        $form->meta->title = $metaTitle;
        $form->meta->description = $metaDesc;
        $form->meta->keywords = $metaKey;

        $form->tags->textNew = $tagTextNew;
        $form->tags->existing = $tagExisting;

        $form->price->curPrice = $price;

        $product = $this->service->create($form);

        $this->assertNotNull($product);
        $this->assertEquals($product->name, $name);
        $this->assertEquals($product->description, $description);
        $this->assertEquals($product->price_type, Product::PRICE_TYPE_FIX);

        $this->assertEquals($product->meta->title, $metaTitle);
        $this->assertEquals($product->meta->description, $metaDesc);
        $this->assertEquals($product->meta->keywords, $metaKey);

        $this->assertEquals($product->category_id, 2);
        $this->assertEquals($product->brand_id, 1);
        $this->assertEquals($product->quantity, 20);

        // check Tags

        $this->assertTrue(ArrayHelper::isIn('watch',ArrayHelper::map($product->tags,'name','name')));
        $this->assertTrue(ArrayHelper::isIn('hand',ArrayHelper::map($product->tags,'name','name')));

        // check main Category

        $this->assertEquals($product->category->name,'Notebook');

        // check others Categories

        $this->assertTrue(ArrayHelper::isIn('Notebook',ArrayHelper::map($product->categories,'name','name')));

        // check brand

        $this->assertEquals($product->brand->name,'name');


        $this->assertEquals($product->values[0]->value,$value);

        $this->assertEquals($product->prices[0]->cur_price, $price);

    }


}