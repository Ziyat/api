<?php

namespace box\services;

use box\entities\Meta;
use box\entities\shop\product\Product;
use box\entities\shop\Tag;
use box\forms\shop\product\PhotosForm;
use box\forms\shop\product\ProductCreateForm;
use box\forms\shop\product\ProductEditForm;
use box\repositories\BrandRepository;
use box\repositories\CategoryRepository;
use box\repositories\generic\ProductRepository as GenericProductRepository;
use box\repositories\NotFoundException;
use box\repositories\ProductRepository;
use box\repositories\TagRepository;

class ProductService
{
    private $products;
    private $brands;
    private $categories;
    private $tags;
    private $transaction;
    private $genericProducts;

    public function __construct(
        ProductRepository $products,
        GenericProductRepository $genericProducts,
        BrandRepository $brands,
        CategoryRepository $categories,
        TagRepository $tags,
        TransactionManager $transaction
    )
    {
        $this->products = $products;
        $this->categories = $categories;
        $this->brands = $brands;
        $this->tags = $tags;
        $this->transaction = $transaction;
        $this->genericProducts = $genericProducts;
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
            throw $e;
        }


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
     * @throws \box\repositories\NotFoundException
     */
    public function activate($id): void
    {
        $product = $this->products->get($id);
        $product->activate();
        $this->products->save($product);
    }

    /**
     * @param $id
     * @throws \box\repositories\NotFoundException
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
     */
    public function removePhoto($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->removePhoto($photoId);
        $this->products->save($product);
    }
}