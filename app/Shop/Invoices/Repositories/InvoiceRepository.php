<?php

namespace App\Shop\Invoices\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Invoices\Invoice;
use App\Shop\Invoices\Exceptions\InvoiceNotFoundErrorException;
use App\Shop\Invoices\Exceptions\CreateInvoiceErrorException;
use App\Shop\Invoices\Exceptions\UpdateInvoiceErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class InvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface
{
    /**
     * InvoiceRepository constructor.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        parent::__construct($invoice);
        $this->model = $invoice;
    }

    /**
     * @param array $data
     *
     * @return Invoice
     * @throws CreateInvoiceErrorException
     */
    public function createInvoice(array $data) : Invoice
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new CreateInvoiceErrorException($e);
        }
    }

    /**
     * @param int $id
     *
     * @return Invoice
     * @throws InvoiceNotFoundErrorException
     */
    public function findInvoiceById(int $id) : Invoice
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new InvoiceNotFoundErrorException($e);
        }
    }

    /**
     * @param array $data
     * @param int $id
     *
     * @return bool
     * @throws UpdateInvoiceErrorException
     */
    public function updateInvoice(array $data) : bool
    {
        try {
            return $this->update($data);
        } catch (QueryException $e) {
            throw new UpdateInvoiceErrorException($e);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteInvoice() : bool
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
    public function listInvoices($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }

  
}
