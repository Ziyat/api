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
        'PATCH profile/private' => 'user/profile/change-private',

        //followers

        'PATCH user/follow/<follow_id:\d+>' => 'user/follower/follow',
        'PATCH user/unfollow/<follow_id:\d+>' => 'user/follower/un-follow',
        'GET user/following' => 'user/follower/following',
        'GET user/followers' => 'user/follower/followers',
        'PATCH user/following/<following_id:\d+>' => 'user/follower/following',
        'PATCH user/followers/<follower_id:\d+>' => 'user/follower/followers',

        'PATCH user/followers/approve/<follower_id:\d+>' => 'user/follower/approve',
        'PATCH user/followers/disapprove/<follower_id:\d+>' => 'user/follower/disapprove',

        // user product

        'GET user/products' => 'user/product/index',
        'POST user/products' => 'user/product/create',
        'POST user/products/<id:\d+>' => 'user/product/edit',
        'GET user/products/<id:\d+>' => 'user/product/view',
        'GET user/products/<id:\d+>/activate' => 'user/product/activate',
        'GET user/products/<id:\d+>/draft' => 'user/product/draft',
        'PUT user/products/<product_id:\d+>/<modification_id:\d+>/<photo_id:\d+>' => 'user/product/set-modification-photo',
        'POST user/products/<id:\d+>/photos' => 'user/product/add-photos',
        'PATCH user/products/<id:\d+>/photos/<photo_id:\d+>/up' => 'user/product/move-photo-up',
        'PATCH user/products/<id:\d+>/photos/<photo_id:\d+>/down' => 'user/product/move-photo-down',
        'DELETE user/products/<id:\d+>/photos/<photo_id:\d+>' => 'user/product/delete-photo',

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
        'GET shop/characteristics/category/<id:\d+>' => 'shop/characteristic/category',


        // public

        'GET public/users' => 'public/users',
        'GET public/user/<id:\d+>' => 'public/user',
        'GET public/user/<id:\d+>/products' => 'public/user-products',
        'GET public/user/<user_id:\d+>/following' => 'public/following',
        'GET public/user/<user_id:\d+>/followers' => 'public/followers',

        // generic

        'GET generic/products' => 'generic/product/index',
        'POST generic/products' => 'generic/product/create',
        'POST generic/products/<id:\d+>' => 'generic/product/edit',
        'GET generic/products/<id:\d+>' => 'generic/product/view',
        'POST generic/products/search' => 'generic/product/search',

        'PUT generic/products/<product_id:\d+>/<modification_id:\d+>/<photo_id:\d+>' => 'generic/product/set-modification-photo',
        'POST generic/products/<id:\d+>/photos' => 'generic/product/add-photos',
        'PATCH generic/products/<id:\d+>/photos/<photo_id:\d+>/up' => 'generic/product/move-photo-up',
        'PATCH generic/products/<id:\d+>/photos/<photo_id:\d+>/down' => 'generic/product/move-photo-down',
        'DELETE generic/products/<id:\d+>/photos/<photo_id:\d+>' => 'generic/product/delete-photo',

        // Carousel
        'GET carousels' => 'carousel/index',
        'POST carousels' => 'carousel/create',
        'GET carousels/<id:\d+>' => 'carousel/view',
        'POST carousels/<id:\d+>' => 'carousel/update',
        'DELETE carousels/<id:\d+>' => 'carousel/delete',


        // ocr

        'POST ocr' => 'ocr/index',
    ],
];