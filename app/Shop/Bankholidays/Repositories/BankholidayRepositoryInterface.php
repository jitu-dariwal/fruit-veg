<?php

namespace App\Shop\Bankholidays\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Bankholidays\Bankholiday;
use Illuminate\Support\Collection;

interface BankholidayRepositoryInterface extends BaseRepositoryInterface
{
    public function createBankholiday(array $data): Bankholiday;

    public function findBankholidayById(int $id) : Bankholiday;

    public function updateBankholiday(array $data) : bool;

    public function deleteBankholiday() : bool;

    public function listBankholidays($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection;

    
}
