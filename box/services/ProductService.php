<?php

namespace box\services;

use box\entities\Meta;
use box\entities\shop\product\Product;
use box\entities\shop\Tag;
use box\forms\shop\product\ProductEditForm;
use box\repositories\BrandRepository;
use box\repositories\CategoryRepository;
use box\repositories\ProductRepository;
use box\forms\shop\product\ProductCreateForm;
use box\repositories\TagRepository;

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
                $modification->price,
                $modification->quantity,
                $modification->main_photo_id
            );
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
            return $e->getMessage();
        }


        return $product;
    }


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
        $product->changeMainCategory($category->id);


        try {
            $this->transaction->wrap(function () use ($product, $form) {
                $product->revokeCategories();
                $product->revokeTags();
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


                foreach ($form->characteristics as $characteristic) {
                    $product->setValue($characteristic->id, $characteristic->value);
                }
                foreach ($form->modifications as $modification) {
                    $product->setModification(
                        $modification->characteristic_id,
                        $modification->value,
                        $modification->price,
                        $modification->quantity,
                        $modification->main_photo_id
                    );
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

    public function activate($id): void
    {
        $product = $this->products->get($id);
        $product->activate();
        $this->products->save($product);
    }

    public function draft($id): void
    {
        $product = $this->products->get($id);
        $product->draft();
        $this->products->save($product);
    }
}