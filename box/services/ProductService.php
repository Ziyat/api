<?php

namespace box\services;

use box\entities\Meta;
use box\entities\notification\Notification;
use box\entities\shop\product\Product;
use box\entities\shop\Tag;
use box\events\notification\NotificationEvent;
use box\forms\shop\product\PhotosForm;
use box\forms\shop\product\ProductCreateForm;
use box\forms\shop\product\ProductEditForm;
use box\forms\shop\product\ProductShippingForm;
use box\repositories\BrandRepository;
use box\repositories\CategoryRepository;
use box\repositories\generic\ProductRepository as GenericProductRepository;
use box\repositories\NotFoundException;
use box\repositories\ProductRepository;
use box\repositories\TagRepository;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class ProductService
 * @package box\services
 * @property ProductRepository $products
 * @property GenericProductRepository $genericProducts
 * @property BrandRepository $brands
 * @property CategoryRepository $categories
 * @property TagRepository $tags
 * @property TransactionManager $transaction
 * @property NotificationEvent $notificationEvent
 */

class ProductService
{
    private $products;
    private $brands;
    private $categories;
    private $tags;
    private $transaction;
    private $genericProducts;
    private $notificationEvent;

    public function __construct(
        ProductRepository $products,
        GenericProductRepository $genericProducts,
        BrandRepository $brands,
        CategoryRepository $categories,
        TagRepository $tags,
        TransactionManager $transaction,
        NotificationEvent $notificationEvent
    )
    {
        $this->products = $products;
        $this->categories = $categories;
        $this->brands = $brands;
        $this->tags = $tags;
        $this->transaction = $transaction;
        $this->genericProducts = $genericProducts;

        $this->notificationEvent = $notificationEvent;
    }

    /**
     * @param ProductCreateForm $form
     * @return Product|string
     * @throws \box\repositories\NotFoundException
     */
    public function create(ProductCreateForm $form)
    {
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);
        $product = Product::create(
            $brand->id,
            $category->id,
            $form->name,
            $form->description,
            $form->genericProductId,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );


        $product->setPriceType($form->priceType);

        $product->setPrice(
            $form->price->current,
            $form->price->end,
            $form->price->max,
            $form->price->deadline,
            $form->price->buyNow
        );

        $product->setCondition($form->condition);

        foreach ($form->categories->others as $otherId) {
            $category = $this->categories->get($otherId);
            $product->assignCategory($category->id);
        }
        if(isset($form->characteristics)){
            foreach ($form->characteristics as $characteristic) {
                $product->setValue($characteristic->id, $characteristic->value);
            }
        }
        if(isset($form->modifications)){
            foreach ($form->modifications as $modification) {
                $product->setModification(
                    $modification->characteristic_id,
                    $modification->value,
                    $modification->price,
                    $modification->quantity,
                    $modification->main_photo_id
                );
            }
        }


        $product->setQuantity($form->quantity);

        foreach ($form->tags->existing as $tagId) {
            $tag = $this->tags->get($tagId);
            $product->assignTag($tag->id);
        }

        if (is_array($form->photos->files)) {
            foreach ($form->photos->files as $file) {
                $product->addPhoto($file);
            }
        }

        try {
            $this->transaction->wrap(function () use ($product, $form) {
                foreach ($form->tags->newNames as $tagName) {
                    if (!$tag = $this->tags->findByName($tagName)) {
                        $tag = Tag::create($tagName, $tagName);
                        $this->tags->save($tag);
                    }
                    $product->assignTag($tag->id);
                }
                $this->products->save($product);

            });
        } catch (\Exception $e) {
            throw new $e;
        }

        //-- notification --//

//        $this->notificationEvent->from_id = \Yii::$app->user->id;
//        $this->notificationEvent->type = Notification::TYPE_NEW_PRODUCT;
//        $this->notificationEvent->type_id = $product->id;
//
//        $product->trigger($product::EVENT_NEW_PRODUCT, $this->notificationEvent);

        return $product;
    }

    /**
     * @param $id
     * @param ProductEditForm $form
     * @return Product
     * @throws NotFoundException
     * @throws \DomainException
     */

    public function edit($id, ProductEditForm $form)
    {
        $product = $this->products->get($id);
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);

        $product->edit(
            $brand->id,
            $form->name,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );


        $product->setCondition($form->condition);

        $product->changeMainCategory($category->id);

        try {
            $this->transaction->wrap(function () use ($product, $form) {
                $product->revokeCategories();
                $product->revokeTags();
                $product->revokeCharacteristics();
                $product->setQuantity(0);

                $this->products->save($product);


                $product->setPriceType($form->priceType);

                $product->setPrice(
                    $form->price->current,
                    $form->price->end,
                    $form->price->max,
                    $form->price->deadline,
                    $form->price->buyNow
                );

                foreach ($form->categories->others as $otherId) {
                    $category = $this->categories->get($otherId);
                    $product->assignCategory($category->id);
                }

                if(isset($form->characteristics)){
                    foreach ($form->characteristics as $characteristic) {
                        $product->setValue($characteristic->id, $characteristic->value);
                    }
                }

                if(isset($form->modifications))
                {
                    foreach ($form->modifications as $modification) {
                        $product->setModification(
                            $modification->characteristic_id,
                            $modification->value,
                            $modification->price,
                            $modification->quantity,
                            $modification->main_photo_id,
                            $modification->id
                        );
                    }
                }

                $product->setQuantity();

                foreach ($form->tags->existing as $tagId) {
                    $tag = $this->tags->get($tagId);
                    $product->assignTag($tag->id);
                }

                foreach ($form->tags->newNames as $tagName) {
                    if (!$tag = $this->tags->findByName($tagName)) {
                        $tag = Tag::create($tagName, $tagName);
                        $this->tags->save($tag);
                    }
                    $product->assignTag($tag->id);
                }

                $this->products->save($product);

            });
        } catch (\Exception $e) {
            throw new \DomainException($e->getMessage());
        }

        return $product;
    }

    /**
     * @param $id
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function activate($id): void
    {
        $product = $this->products->get($id);
        $product->activate();
        $this->products->save($product);
    }

    /**
     * @param $id
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function draft($id): void
    {
        $product = $this->products->get($id);
        $product->draft();
        $this->products->save($product);
    }

    /**
     * @param $id
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function market($id): void
    {
        $product = $this->products->get($id);
        $product->market();
        $this->products->save($product);
    }

    /**
     * @param $id
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function sold($id): void
    {
        $product = $this->products->get($id);
        $product->sold();
        $this->products->save($product);
    }

    /**
     * @param $id
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function deleted($id): void
    {
        $product = $this->products->get($id);
        $product->deleted();
        $this->products->save($product);
    }

    /**
     * @param $product_id
     * @param $modification_id
     * @param $photo_id
     * @return Product
     * @throws NotFoundException
     * @throws \RuntimeException
     */
    public function setModificationPhoto($product_id,$modification_id,$photo_id)
    {
        $product = $this->products->get($product_id);
        if(empty($product->photos))
        {
            throw new NotFoundException('photo not found.');
        }
        $modifications = $product->modifications;
        foreach ($modifications as $k => $modification)
        {
            if($modification->id == $modification_id)
            {
                $modification->changeMainPhoto($photo_id);
                $modifications[$k] = $modification;
            }
        }
        $product->modifications = $modifications;
        $this->products->save($product);
        return $product;
    }

    /**
     * @param $product_id
     * @param $modification_id
     * @return Product
     * @throws NotFoundException
     * @throws \RuntimeException
     */

    public function removeModification($product_id, $modification_id)
    {
        $product = $this->products->get($product_id);
        $modifications = $product->modifications;
        foreach ($modifications as $k => $modification)
        {
            if($modification->id == $modification_id)
            {
                unset($modifications[$k]);
                break;
            }
        }
        $product->modifications = $modifications;
        $this->products->save($product);

        return $product;
    }

    /**
     * @param $id
     * @param PhotosForm $form
     * @return Product
     * @throws NotFoundException
     * @throws \RuntimeException
     */
    public function addPhotos($id, PhotosForm $form)
    {
        $product = $this->products->get($id);
        foreach ($form->files as $file) {
            $product->addPhoto($file);
        }
        $this->products->save($product);
        return $product;
    }

    /**
     * @param $id
     * @param $photoId
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function movePhotoUp($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoUp($photoId);
        $this->products->save($product);
    }

    /**
     * @param $id
     * @param $photoId
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function movePhotoDown($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoDown($photoId);
        $this->products->save($product);
    }

    /**
     * @param $id
     * @param $photoId
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function removePhoto($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->removePhoto($photoId);
        $this->products->save($product);
    }

    /**
     * @param $id
     * @param ProductShippingForm $form
     * @return \box\entities\shop\product\Shipping
     * @throws NotFoundException
     * @throws \RuntimeException
     */
    public function setShipping($id, ProductShippingForm $form)
    {
        $product = $this->products->get($id);
        $product->assignShipping($form->rate_id, $form->countryIds, $form->free_shipping_type, $form->price);
        $this->products->save($product);

        return $product->shipping;
    }

    /**
     * @param $product_id
     * @param $shipping_id
     * @return \box\entities\shop\product\Shipping
     * @throws NotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function freeShipping($product_id, $shipping_id)
    {
        $product = $this->products->get($product_id);
        $product->freeShipping($shipping_id);
        $this->products->save($product);

        return $product->shipping;
    }

    /**
     * @param $product_id
     * @param $shipping_id
     * @return \box\entities\shop\product\Shipping
     * @throws NotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function noFreeShipping($product_id, $shipping_id)
    {
        $product = $this->products->get($product_id);
        $product->noFreeShipping($shipping_id);
        $this->products->save($product);

        return $product->shipping;
    }

    /**
     * @param $product_id
     * @param $shipping_id
     * @return \box\entities\shop\product\Shipping
     * @throws NotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function pickupShipping($product_id, $shipping_id)
    {
        $product = $this->products->get($product_id);
        $product->pickupShipping($shipping_id);
        $this->products->save($product);

        return $product->shipping;
    }

    /**
     * @param $product_id
     * @param $shipping_id
     * @throws NotFoundException
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function removeShipping($product_id, $shipping_id)
    {
        $product = $this->products->get($product_id);
        $product->revokeShipping($shipping_id);
        $this->products->save($product);
    }


}