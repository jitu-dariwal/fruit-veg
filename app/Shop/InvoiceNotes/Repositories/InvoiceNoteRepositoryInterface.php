<?php

namespace App\Shop\InvoiceNotes\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\InvoiceNotes\InvoiceNote;
use Illuminate\Support\Collection;

interface InvoiceNoteRepositoryInterface extends BaseRepositoryInterface
{
    public function createInvoiceNote(array $data): InvoiceNote;

    public function findInvoiceNoteById(int $id) : InvoiceNote;

    public function updateInvoiceNote(array $data) : bool;

    public function deleteInvoiceNote() : bool;

    public function listInvoiceNotes($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection;

    
}
