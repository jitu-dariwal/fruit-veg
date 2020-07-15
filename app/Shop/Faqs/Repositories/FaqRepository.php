<?php

namespace App\Shop\Faqs\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Faqs\Faq;
use App\Shop\Faqs\Exceptions\FaqNotFoundErrorException;
use App\Shop\Faqs\Exceptions\CreateFaqErrorException;
use App\Shop\Faqs\Exceptions\UpdateFaqErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class FaqRepository extends BaseRepository implements FaqRepositoryInterface
{
    /**
     * FaqRepository constructor.
     *
     * @param Faq $faq
     */
    public function __construct(Faq $faq)
    {
        parent::__construct($faq);
        $this->model = $faq;
    }

    /**
     * @param array $data
     *
     * @return Faq
     * @throws CreateFaqErrorException
     */
    public function createFaq(array $data) : Faq
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new CreateFaqErrorException($e);
        }
    }

    /**
     * @param int $id
     *
     * @return Faq
     * @throws FaqNotFoundErrorException
     */
    public function findFaqById(int $id) : Faq
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new FaqNotFoundErrorException($e);
        }
    }

    /**
     * @param array $data
     * @param int $id
     *
     * @return bool
     * @throws UpdateFaqErrorException
     */
    public function updateFaq(array $data) : bool
    {
        try {
            return $this->update($data);
        } catch (QueryException $e) {
            throw new UpdateFaqErrorException($e);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteFaq() : bool
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
    public function listFaqs($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }
}
