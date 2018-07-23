<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

return [
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
        'GET,HEAD /' => 'site/index',

        // auth

        'POST login' => 'auth/login',
        'POST signup' => 'auth/signup',
        'GET activate/<token:[\d_]+>' => 'auth/activate-user',
        'POST forgot' => 'auth/password-reset',
        'POST forgot/set-password/<token:[\d_]+>' => 'auth/set-password',

        // profile

        'GET profile' => 'user/profile/index',
        'POST profile/edit' => 'user/profile/edit',

        // user product

        'GET user/products' => 'user/product/index',
        'POST user/products' => 'user/product/create',
        'POST user/products/<id:\d+>' => 'user/product/edit',

        'GET user/brand-list' => 'user/product/brands-list',


        'GET shop/products/<id:\d+>' => 'shop/product/view',
        'GET shop/products/category/<id:\d+>' => 'shop/product/category',
        'GET shop/products/brand/<id:\d+>' => 'shop/product/brand',
        'GET shop/products/tag/<id:\d+>' => 'shop/product/tag',
        'GET shop/products' => 'shop/product/index',

        // shop Brands

        'GET shop/brands' => 'shop/brand/index',
        'POST shop/brands' => 'shop/brand/create',
        'GET shop/brands/<id:\d+>' => 'shop/brand/view',
        'POST shop/brands/<id:\d+>' => 'shop/brand/update',
        'DELETE shop/brands/<id:\d+>' => 'shop/brand/delete',

        // shop Categories

        'GET shop/categories/<id:\d+>/parent' => 'shop/category/parent',
        'GET shop/categories/<id:\d+>/parents' => 'shop/category/parents',
        'GET shop/categories/<id:\d+>/children' => 'shop/category/children',
        'GET shop/categories' => 'shop/category/index',
        'POST shop/categories' => 'shop/category/create',
        'GET shop/categories/<id:\d+>' => 'shop/category/view',
        'POST shop/categories/<id:\d+>' => 'shop/category/update',
        'DELETE shop/categories/<id:\d+>' => 'shop/category/delete',

        // shop Categories

        'GET shop/characteristics' => 'shop/characteristic/index',
        'POST shop/characteristics' => 'shop/characteristic/create',
        'GET shop/characteristics/<id:\d+>' => 'shop/characteristic/view',
        'POST shop/characteristics/<id:\d+>' => 'shop/characteristic/update',
        'DELETE shop/characteristics/<id:\d+>' => 'shop/characteristic/delete',

        // public

        'GET public/users' => 'public/users',
        'GET public/user/<id:\d+>' => 'public/user',
        'GET public/user/<id:\d+>/products' => 'public/user-products',
    ],
];