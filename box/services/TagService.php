<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services;


use box\entities\shop\Tag;
use box\forms\shop\TagForm;
use box\repositories\TagRepository;
use yii\helpers\Inflector;

class TagService
{
    private $tags;

    public function __construct(TagRepository $repository)
    {
        $this->tags = $repository;
    }

    public function create(TagForm $form): Tag
    {
        $tag = Tag::create(
            $form->name,
            $form->slug ?: Inflector::slug($form->name)
        );
        $this->tags->save($tag);
        return $tag;
    }

    public function edit($id, TagForm $form)
    {
        $tag = $this->tags->get($id);
        $tag->edit(
            $form->name,
            $form->slug ?: Inflector::slug($form->name)
        );
        $this->tags->save($tag);
    }

    public function remove($id)
    {
        $tag = $this->tags->get($id);
        $this->tags->remove($tag);
    }
}