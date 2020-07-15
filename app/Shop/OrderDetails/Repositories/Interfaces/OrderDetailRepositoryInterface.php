<?php

namespace App\Shop\OrderDetails\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\OrderDetails\OrderDetail;
use Illuminate\Support\Collection;

interface OrderDetailRepositoryInterface extends BaseRepositoryInterface
{
    public function createOrderDetail(array $orderDetailData) : OrderDetail;

    public function updateOrderDetail(array $data) : bool;

    public function findOrderDetailById(int $id) : OrderDetail;

    public function listOrderDetails();

    public function deleteOrderDetail() : bool;

    public function findOrderDetail(): Collection;

    public function findByOrderid(int $id);
}
