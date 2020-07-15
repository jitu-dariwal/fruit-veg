<?php

namespace App\Shop\PostCodes\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\PostCodes\PostCode;
use Illuminate\Support\Collection;

interface PostCodeRepositoryInterface extends BaseRepositoryInterface
{
    public function createPostCode(array $data): PostCode;

    public function findPostCodeById(int $id) : PostCode;

    public function updatePostCode(array $data) : bool;

    public function deletePostCode() : bool;

    public function listPostCodes($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection;    
}
