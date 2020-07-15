<?php

namespace App\Shop\Bankholidays\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Bankholidays\Bankholiday;
use App\Shop\Bankholidays\Exceptions\BankholidayNotFoundErrorException;
use App\Shop\Bankholidays\Exceptions\CreateBankholidayErrorException;
use App\Shop\Bankholidays\Exceptions\UpdateBankholidayErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class BankholidayRepository extends BaseRepository implements BankholidayRepositoryInterface
{
    /**
     * BrandRepository constructor.
     *
     * @param Brand $brand
     */
    public function __construct(Bankholiday $bankholiday)
    {
        parent::__construct($bankholiday);
        $this->model = $bankholiday;
    }

    /**
     * @param array $data
     *
     * @return Brand
     * @throws CreateBrandErrorException
     */
    public function createBankholiday(array $data) : Bankholiday
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new CreateBankholidayErrorException($e);
        }
    }

    /**
     * @param int $id
     *
     * @return Brand
     * @throws BrandNotFoundErrorException
     */
    public function findBankholidayById(int $id) : Bankholiday
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new BankholidayNotFoundErrorException($e);
        }
    }

    /**
     * @param array $data
     * @param int $id
     *
     * @return bool
     * @throws UpdateBrandErrorException
     */
    public function updateBankholiday(array $data) : bool
    {
        try {
            return $this->update($data);
        } catch (QueryException $e) {
            throw new UpdateBankholidayErrorException($e);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteBankholiday() : bool
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
    public function listBankholidays($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }

  
}
