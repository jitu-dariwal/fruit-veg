<?php

namespace App\Shop\Coupons\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Coupons\Coupon;
use App\Shop\Coupons\Exceptions\CouponNotFoundErrorException;
use App\Shop\Coupons\Exceptions\CreateCouponErrorException;
use App\Shop\Coupons\Exceptions\UpdateCouponErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class CouponRepository extends BaseRepository implements CouponRepositoryInterface
{
    /**
     * BrandRepository constructor.
     *
     * @param Brand $brand
     */
    public function __construct(Coupon $coupon)
    {
        parent::__construct($coupon);
        $this->model = $coupon;
    }

    /**
     * @param array $data
     *
     * @return Brand
     * @throws CreateBrandErrorException
     */
    public function createCoupon(array $data) : Coupon
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new CreateCouponErrorException($e);
        }
    }

    /**
     * @param int $id
     *
     * @return Brand
     * @throws BrandNotFoundErrorException
     */
    public function findCouponById(int $id) : Coupon
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new CouponNotFoundErrorException($e);
        }
    }

    /**
     * @param array $data
     * @param int $id
     *
     * @return bool
     * @throws UpdateBrandErrorException
     */
    public function updateCoupon(array $data) : bool
    {
        try {
            return $this->update($data);
        } catch (QueryException $e) {
            throw new UpdateCouponErrorException($e);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteCoupon() : bool
    {
        return $this->delete();
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listCoupons($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }

  
}
