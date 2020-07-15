<?php

namespace App\Shop\Faqs\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Faqs\Faq;
use Illuminate\Support\Collection;

interface FaqRepositoryInterface extends BaseRepositoryInterface
{
    public function createFaq(array $data): Faq;

    public function findFaqById(int $id) : Faq;

    public function updateFaq(array $data) : bool;

    public function deleteFaq() : bool;

    public function listFaqs($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection;    
}
