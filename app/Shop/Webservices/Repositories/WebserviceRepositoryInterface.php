<?php

namespace App\Shop\Webservices\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface WebserviceRepositoryInterface extends BaseRepositoryInterface
{
    public function checkUserLogin(string $email, string $password): WebserviceRepository;

    //public function findManufacturerById(int $id) : Manufacturer;

    //public function updateManufacturer(array $data) : bool;

    //public function deleteManufacturer() : bool;

   // public function listManufacturers($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection;

    
}
