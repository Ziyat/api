<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\readModels;


use box\entities\generic\GenericProduct;
use box\repositories\NotFoundException;
use Elasticsearch\Client;

class GenericProductReadRepository
{
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $id
     * @return GenericProduct|null
     * @throws NotFoundException
     */
    public function find($id)
    {
        if (!$product = GenericProduct::findOne($id)) {
            throw new NotFoundException('Generic product not found');
        }
        return $product;
    }
}