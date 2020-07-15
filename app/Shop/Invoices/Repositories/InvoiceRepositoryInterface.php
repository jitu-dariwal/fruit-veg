<?php

namespace App\Shop\Invoices\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Invoices\Invoice;
use Illuminate\Support\Collection;

interface InvoiceRepositoryInterface extends BaseRepositoryInterface
{
    public function createInvoice(array $data): Invoice;

    public function findInvoiceById(int $id) : Invoice;

    public function updateInvoice(array $data) : bool;

    public function deleteInvoice() : bool;

    public function listInvoices($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection;

    
}
