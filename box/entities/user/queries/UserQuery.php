<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\entities\user\queries;


use box\entities\user\User;
use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{
    /**
     * @param null $alias
     * @return $this
     */
    public function active($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => User::STATUS_ACTIVE,
        ]);
    }

    public function wait($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => User::STATUS_WAIT,
        ]);
    }

    public function deleted($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => User::STATUS_DELETED,
        ]);
    }

    public function roleUser($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'role' => 'user',
        ]);
    }

    public function roleAdmin($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'role' => 'administrator',
        ]);
    }
}