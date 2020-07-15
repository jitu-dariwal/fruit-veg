<?php

namespace App\Shop\Manufacturers\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Manufacturers\Manufacturer;
use App\Shop\Manufacturers\Exceptions\ManufacturerNotFoundErrorException;
use App\Shop\Manufacturers\Exceptions\CreateManufacturerErrorException;
use App\Shop\Manufacturers\Exceptions\UpdateManufacturerErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class ManufacturerRepository extends BaseRepository implements ManufacturerRepositoryInterface
{
    /**
     * BrandRepository constructor.
     *
     * @param Brand $brand
     */
    public function __construct(Manufacturer $manufacturer)
    {
        parent::__construct($manufacturer);
        $this->model = $manufacturer;
    }

    /**
     * @param array $data
     *
     * @return Brand
     * @throws CreateBrandErrorException
     */
    public function createManufacturer(array $data) : Manufacturer
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new CreateManufacturerErrorException($e);
        }
    }

    /**
     * @param int $id
     *
     * @return Brand
     * @throws BrandNotFoundErrorException
     */
    public function findManufacturerById(int $id) : Manufacturer
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ManufacturerNotFoundErrorException($e);
        }
    }

    /**
     * @param array $data
     * @param int $id
     *
     * @return bool
     * @throws UpdateBrandErrorException
     */
    public function updateManufacturer(array $data) : bool
    {
        try {
            return $this->update($data);
        } catch (QueryException $e) {
            throw new UpdateManufacturerErrorException($e);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteManufacturer() : bool
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
    public function listManufacturers($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }

  
}
