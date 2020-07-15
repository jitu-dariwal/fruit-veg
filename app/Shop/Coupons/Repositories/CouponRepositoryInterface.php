<?php

namespace App\Shop\Coupons\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Coupons\Coupon;
use Illuminate\Support\Collection;

interface CouponRepositoryInterface extends BaseRepositoryInterface
{
    public function createCoupon(array $data): Coupon;

    public function findCouponById(int $id) : Coupon;

    public function updateCoupon(array $data) : bool;

    public function deleteCoupon() : bool;

    public function listCoupons($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection;

    
}
