<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace services\notification;


use box\repositories\ProductRepository;
use box\repositories\generic\ProductRepository as GenericProductRepository;
use box\repositories\UserRepository;
use repositories\notification\NotificationRepository;

/**
 * Class NotificationService
 * @package services\notification
 *
 * @property NotificationRepository $notifications,
 * @property UserRepository $users,
 * @property ProductRepository $products,
 * @property GenericProductRepository $genericProducts,
 */

class NotificationService
{
    private $users;
    private $products;
    private $notifications;
    private $genericProducts;

    public function __construct(
        GenericProductRepository $genericProductRepository,
        NotificationRepository $notificationRepository,
        ProductRepository $productRepository,
        UserRepository $userRepository
    )
    {
        $this->users = $userRepository;
        $this->products = $productRepository;
        $this->notifications = $notificationRepository;
        $this->genericProducts = $genericProductRepository;
    }

    /**
     * @param $from_id
     * @param $type
     * @param $type_id
     * @throws \box\repositories\NotFoundException
     */

    public function create($from_id, $type, $type_id)
    {
        $user = $this->users->find($from_id);
        try{
            $item = $this->products->get($type_id);
            $item = $this->genericProducts->get($type_id);
        }catch (\Exception $e){};
    }
}