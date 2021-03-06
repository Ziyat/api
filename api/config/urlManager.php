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
        'POST forgot/set-password/<password_reset_token:[\d_]+>' => 'auth/set-password',
        'PATCH token-refresh/<refresherToken:[A-Za-z0-9_-]+>' => 'auth/token-refresh',
        'PATCH check-token/<token:[A-Za-z0-9_-]+>' => 'auth/check-token',

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
        'PUT user/products/<product_id:\d+>/<modification_id:\d+>/<photo_id:\d+>' => 'user/product/set-modification-photo',
        'POST user/products/<id:\d+>/photos' => 'user/product/add-photos',
        'PATCH user/products/<id:\d+>/photos/<photo_id:\d+>/up' => 'user/product/move-photo-up',
        'PATCH user/products/<id:\d+>/photos/<photo_id:\d+>/down' => 'user/product/move-photo-down',
        'DELETE user/products/<id:\d+>/photos/<photo_id:\d+>' => 'user/product/delete-photo',
        'DELETE user/products/<product_id:\d+>/<modification_id:\d+>' => 'user/product/delete-modification',

        'GET user/products/shipping/<product_id:\d+>' => 'user/product-shipping/index',
        'POST user/products/shipping/<product_id:\d+>' => 'user/product-shipping/add',
        'GET user/products/shipping/<product_id:\d+>/<id:\d+>' => 'user/product-shipping/view',
        'POST user/products/shipping/search' => 'user/product-shipping/search-rates',
        'DELETE user/products/shipping/<product_id:\d+>/<shipping_id:\d+>' => 'user/product-shipping/delete',
        'PATCH user/products/shipping/<product_id:\d+>/<shipping_id:\d+>/free' => 'user/product-shipping/free',
        'PATCH user/products/shipping/<product_id:\d+>/<shipping_id:\d+>/no-free' => 'user/product-shipping/no-free',
        'PATCH user/products/shipping/<product_id:\d+>/<shipping_id:\d+>/pickup' => 'user/product-shipping/pickup',

        // user product change status

        'GET user/products/<id:\d+>/activate' => 'user/product/activate',
        'GET user/products/<id:\d+>/draft' => 'user/product/draft',
        'GET user/products/<id:\d+>/market' => 'user/product/market',
        'GET user/products/<id:\d+>/sold' => 'user/product/sold',
        'GET user/products/<id:\d+>/deleted' => 'user/product/deleted',

        // user addresses
        'GET user/addresses' => 'user/address/index',
        'POST user/addresses' => 'user/address/add',
        'POST user/addresses/<id:\d+>' => 'user/address/edit',
        'DELETE user/addresses/<id:\d+>' => 'user/address/remove',
        'PATCH user/addresses/<id:\d+>/default' => 'user/address/change-default',

        // shop products

        'GET shop/products/<id:\d+>' => 'shop/product/view',
        'GET shop/products/category/<id:\d+>' => 'shop/product/category',
        'GET shop/products/brand/<id:\d+>' => 'shop/product/brand',
        'GET shop/products/tag/<id:\d+>' => 'shop/product/tag',
        'GET shop/products' => 'shop/product/index',


        // shop shipping

        'POST shop/shipping' => 'shop/shipping-service/create',
        'POST shop/shipping/<id:\d+>' => 'shop/shipping-service/update',
        'GET shop/shipping/<id:\d+>' => 'shop/shipping-service/view',
        'GET shop/shipping' => 'shop/shipping-service/index',
        'DELETE shop/shipping/<id:\d+>' => 'shop/shipping-service/delete',
        'GET shop/shipping/params' => 'shop/shipping-service/params',
        'DELETE shop/shipping/rate/<id:\d+>' => 'shop/shipping-service/rate-delete',


        // shop Brands

        'GET shop/brands' => 'shop/brand/index',
        'POST shop/brands' => 'shop/brand/create',
        'GET shop/brands/<id:\d+>' => 'shop/brand/view',
        'POST shop/brands/<id:\d+>' => 'shop/brand/update',
        'DELETE shop/brands/<id:\d+>' => 'shop/brand/delete',

        'GET shop/brands/<brand_id:\d+>/users' => 'shop/brand/users',
        'GET shop/brands/<brand_id:\d+>/generic/products' => 'shop/brand/generic-products',
        'GET shop/brands/<brand_id:\d+>/user/products' => 'shop/brand/user-products',

        // shop Categories

        'GET shop/categories/<id:\d+>/parent' => 'shop/category/parent',
        'GET shop/categories/<id:\d+>/parents' => 'shop/category/parents',
        'GET shop/categories/<id:\d+>/children' => 'shop/category/children',
        'GET shop/categories' => 'shop/category/index',
        'POST shop/categories' => 'shop/category/create',
        'GET shop/categories/<id:\d+>' => 'shop/category/view',
        'POST shop/categories/<id:\d+>' => 'shop/category/update',
        'DELETE shop/categories/<id:\d+>' => 'shop/category/delete',

        // shop Characteristics

        'GET shop/characteristics' => 'shop/characteristic/index',
        'POST shop/characteristics' => 'shop/characteristic/create',
        'GET shop/characteristics/<id:\d+>' => 'shop/characteristic/view',
        'GET shop/characteristics/<id:\d+>/<category_id:\d+>' => 'shop/characteristic/view',
        'POST shop/characteristics/<id:\d+>' => 'shop/characteristic/update',
        'DELETE shop/characteristics/<id:\d+>' => 'shop/characteristic/delete',
        'DELETE shop/characteristics/<id:\d+>/<category_id:\d+>' => 'shop/characteristic/revoke-category',
        'GET shop/characteristics/category/<id:\d+>' => 'shop/characteristic/category',


        // public

        'GET public/users' => 'public/users',
        'GET public/user/<id:\d+>' => 'public/user',
        'GET public/user/<user_id:\d+>/products' => 'public/user-products',
        'GET public/user/products/<product_id:\d+>' => 'public/products-by-id',
        'GET public/user/<user_id:\d+>/following' => 'public/following',
        'GET public/user/<user_id:\d+>/followers' => 'public/followers',


        'GET public/countries' => 'public/countries',
        'GET public/countries/<id:\d+>' => 'public/country',
        'GET public/countries/<code:\w+>' => 'public/country-by-code',

        'POST public/user/products/<product_id:\d+>/shipping' => 'public/user-products-shipping',

        // generic

        'GET generic/products' => 'generic/product/index',
        'POST generic/products' => 'generic/product/create',
        'POST generic/products/<id:\d+>' => 'generic/product/edit',
        'GET generic/products/<id:\d+>' => 'generic/product/view',

        'PUT generic/products/<product_id:\d+>/<modification_id:\d+>/<photo_id:\d+>' => 'generic/product/set-modification-photo',
        'POST generic/products/<id:\d+>/photos' => 'generic/product/add-photos',
        'PATCH generic/products/<id:\d+>/photos/<photo_id:\d+>/up' => 'generic/product/move-photo-up',
        'PATCH generic/products/<id:\d+>/photos/<photo_id:\d+>/down' => 'generic/product/move-photo-down',
        'DELETE generic/products/<id:\d+>/photos/<photo_id:\d+>' => 'generic/product/delete-photo',

        'GET generic/products/<id:\d+>/ratings' => 'generic/product/view-ratings',
        'POST generic/products/<id:\d+>/ratings' => 'generic/product/add-ratings',
        'DELETE generic/products/<id:\d+>/ratings/<rating_id:\d+>' => 'generic/product/delete-rating',

        // Carousel

        'GET carousels' => 'carousel/index',
        'GET carousels/active' => 'carousel/active',
        'POST carousels' => 'carousel/create',
        'GET carousels/<id:\d+>' => 'carousel/view',
        'POST carousels/<id:\d+>' => 'carousel/update',
        'DELETE carousels/<id:\d+>' => 'carousel/delete',

        // Carousel Items

        'GET carousels/<carousel_id:\d+>/items/<item_id:\d+>' => 'carousel/view-item',
        'POST carousels/<carousel_id:\d+>/items' => 'carousel/add-item',
        'POST carousels/<carousel_id:\d+>/items/<item_id:\d+>' => 'carousel/update-item',
        'DELETE carousels/<carousel_id:\d+>/items/<item_id:\d+>' => 'carousel/delete-item',

        'POST carousels/<carousel_id:\d+>/items/<item_id:\d+>/images' => 'carousel/add-item-images',
        'DELETE carousels/<carousel_id:\d+>/items/<item_id:\d+>/images/<image_id:\d+>' => 'carousel/delete-item-image',

        // ocr

        'POST ocr' => 'ocr/index',

        //elasticSearch

        'POST search/brands' => 'search/brands',
        'POST search/generic-products' => 'search/generic-products',
        'POST search/user-products' => 'search/user-products',
        'POST search/users' => 'search/users',
        'POST search/combination' => 'search/combination',

        // reviews
        'GET reviews/<type:\d+>/<item_id:\d+>' => 'review/all-by-item',
        'POST reviews' => 'review/create',
        'POST reviews/<id:\d+>' => 'review/update',
        'DELETE reviews/<id:\d+>' => 'review/delete',

        'GET reviews/<id:\d+>/children' => 'review/children',
        'GET reviews/<id:\d+>/parents' => 'review/parents',
        'GET reviews/<id:\d+>/parent' => 'review/parent',

        'POST reviews/<id:\d+>/photos' => 'review/add-photos',
        'PATCH reviews/<id:\d+>/photos/<photo_id:\d+>/up' => 'review/move-photo-up',
        'PATCH reviews/<id:\d+>/photos/<photo_id:\d+>/down' => 'review/move-photo-down',
        'DELETE reviews/<id:\d+>/photos/<photo_id:\d+>' => 'review/delete-photo',

        //ratings
        'POST ratings' => 'rating/create',
        'POST ratings/<id:\d+>' => 'rating/update',
        'GET ratings/<type:\d+>/<item_id:\d+>' => 'rating/all-by-item',

        //notification

        'GET notification/new' => 'notification/new',
        'GET notification/all' => 'notification/all',
        'POST notification/push-token' => 'notification/push-token'

    ],
];