<?php

namespace App\Shop\OrderDetails\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\OrderDetails\Exceptions\OrderDetailInvalidArgumentException;
use App\Shop\OrderDetails\Exceptions\OrderDetailNotFoundException;
use App\Shop\OrderDetails\OrderDetail;
use App\Shop\OrderDetails\Repositories\Interfaces\OrderDetailRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class OrderDetailRepository extends BaseRepository implements OrderDetailRepositoryInterface
{
    /**
     * OrderDetailRepository constructor.
     * @param OrderDetail $orderStatus
     */
    public function __construct(OrderDetail $orderDetail)
    {
        parent::__construct($orderDetail);
        $this->model = $orderDetail;
    }

    /**
     * Create the order Detail
     *
     * @param array $params
     * @return OrderDetail
     * @throws OrderDetailInvalidArgumentException
     */
    public function createOrderDetail(array $params) : OrderDetail
    {
        try {
            return $this->create($params);
        } catch (QueryException $e) {
            throw new OrderDetailInvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Update the order Detail
     *
     * @param array $data
     *
     * @return bool
     * @throws OrderDetailInvalidArgumentException
     */
    public function updateOrderDetail(array $data) : bool
    {
        try {
            return $this->update($data);
        } catch (QueryException $e) {
            throw new OrderDetailInvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return OrderDetail
     * @throws OrderDetailNotFoundException
     */
    public function findOrderDetailById(int $id) : OrderDetail
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new OrderDeatailNotFoundException('Order detail not found.');
        }
    }

    /**
     * @return mixed
     */
    public function listOrderDetails()
    {
        return $this->all();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteOrderDetail() : bool
    {
        return $this->delete();
    }

    /**
     * @return Collection
     */
    public function findOrderDetail() : Collection
    {
        return $this->model->orders()->get();
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function findByOrderid(int $id)
    {
        return $this->model->where('order_id', $id)->first();
    }
}
