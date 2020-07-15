<?php

namespace App\Shop\Manufacturers\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Manufacturers\Manufacturer;
use Illuminate\Support\Collection;

interface ManufacturerRepositoryInterface extends BaseRepositoryInterface
{
    public function createManufacturer(array $data): Manufacturer;

    public function findManufacturerById(int $id) : Manufacturer;

    public function updateManufacturer(array $data) : bool;

    public function deleteManufacturer() : bool;

    public function listManufacturers($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection;

    
}
