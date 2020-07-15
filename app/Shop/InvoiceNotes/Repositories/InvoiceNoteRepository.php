<?php

namespace App\Shop\InvoiceNotes\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\InvoiceNotes\InvoiceNote;
use App\Shop\InvoiceNotes\Exceptions\InvoiceNoteNotFoundErrorException;
use App\Shop\InvoiceNotes\Exceptions\CreateInvoiceNoteErrorException;
use App\Shop\InvoiceNotes\Exceptions\UpdateInvoiceNoteErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class InvoiceNoteRepository extends BaseRepository implements InvoiceNoteRepositoryInterface
{
    /**
     * BrandRepository constructor.
     *
     * @param InvoiceNote $invoiceNote
     */
    public function __construct(InvoiceNote $invoiceNote)
    {
        parent::__construct($invoiceNote);
        $this->model = $invoiceNote;
    }

    /**
     * @param array $data
     *
     * @return InvoiceNote
     * @throws CreateInvoiceNoteErrorException
     */
    public function createInvoiceNote(array $data) : InvoiceNote
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new CreateInvoiceNoteErrorException($e);
        }
    }

    /**
     * @param int $id
     *
     * @return InvoiceNote
     * @throws InvoiceNoteNotFoundErrorException
     */
    public function findInvoiceNoteById(int $id) : InvoiceNote
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new InvoiceNoteNotFoundErrorException($e);
        }
    }

    /**
     * @param array $data
     * @param int $id
     *
     * @return bool
     * @throws UpdateInvoiceNoteErrorException
     */
    public function updateInvoiceNote(array $data) : bool
    {
        try {
            return $this->update($data);
        } catch (QueryException $e) {
            throw new UpdateInvoiceNoteErrorException($e);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteInvoiceNote() : bool
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
    public function listInvoiceNotes($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }

  
}
