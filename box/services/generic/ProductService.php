<?php

namespace box\services\generic;

use box\entities\shop\Tag;
use box\entities\generic\GenericProduct;
use box\forms\generic\ProductCreateForm;
use box\forms\generic\ProductEditForm;
use box\forms\generic\PhotosForm;
use box\repositories\BrandRepository;
use box\repositories\CategoryRepository;
use box\repositories\generic\ProductRepository;
use box\repositories\NotFoundException;
use box\repositories\TagRepository;
use box\services\TransactionManager;

class ProductService
{
    private $products;
    private $brands;
    private $categories;
    private $tags;
    private $transaction;

    public function __construct(
        ProductRepository $products,
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
    }

    /**
     * @param ProductCreateForm $form
     * @return GenericProduct|string
     * @throws \box\repositories\NotFoundException
     */
    public function create(ProductCreateForm $form)
    {
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);

        $product = GenericProduct::create(
            $brand->id,
            $category->id,
            $form->name,
            $form->description
        );


        foreach ($form->categories->others as $otherId) {
            $category = $this->categories->get($otherId);
            $product->assignCategory($category->id);
        }

        foreach ($form->characteristics as $characteristic) {
            $product->setValue($characteristic->id, $characteristic->value);
        }
        foreach ($form->modifications as $modification) {
            $product->setModification(
                $modification->characteristic_id,
                $modification->value,
                $modification->main_photo_id
            );
        }

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
            return $e->getMessage();
        }


        return $product;
    }

    /**
     * @param $id
     * @param ProductEditForm $form
     * @return GenericProduct
     * @throws \box\repositories\NotFoundException|\DomainException
     */

    public function edit($id, ProductEditForm $form)
    {
        $product = $this->products->get($id);
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);

        $product->edit(
            $brand->id,
            $form->name,
            $form->description
        );
        $product->changeMainCategory($category->id);


        try {
            $this->transaction->wrap(function () use ($product, $form) {
                $product->revokeCategories();
                $product->revokeTags();

                $this->products->save($product);

                foreach ($form->categories->others as $otherId) {
                    $category = $this->categories->get($otherId);
                    $product->assignCategory($category->id);
                }

                foreach ($form->characteristics as $characteristic) {
                    $product->setValue($characteristic->id, $characteristic->value);
                }

                foreach ($form->modifications as $modification) {
                    $product->setModification(
                        $modification->characteristic_id,
                        $modification->value,
                        $modification->main_photo_id,
                        $modification->id
                    );
                }

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
     * @param $product_id
     * @param $photo_id
     * @param $modification_id
     * @throws \box\repositories\NotFoundException|\RuntimeException
     * @return GenericProduct
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
     * @param $id
     * @param PhotosForm $form
     * @return GenericProduct
     * @throws NotFoundException|\RuntimeException
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
     * @throws NotFoundException|\RuntimeException|\DomainException
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
     * @throws NotFoundException|\DomainException|\RuntimeException
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
     * @throws NotFoundException|\DomainException|\RuntimeException
     */
    public function removePhoto($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->removePhoto($photoId);
        $this->products->save($product);
    }
}