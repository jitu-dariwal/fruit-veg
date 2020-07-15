<?php

namespace App\Shop\PostCodes\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\PostCodes\PostCode;
use App\Shop\PostCodes\Exceptions\PostCodeNotFoundErrorException;
use App\Shop\PostCodes\Exceptions\CreatePostCodeErrorException;
use App\Shop\PostCodes\Exceptions\UpdatePostCodeErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class PostCodeRepository extends BaseRepository implements PostCodeRepositoryInterface
{
    /**
     * FaqRepository constructor.
     *
     * @param PostCode $postcode
     */
    public function __construct(PostCode $postcode)
    {
        parent::__construct($postcode);
        $this->model = $postcode;
    }

    /**
     * @param array $data
     *
     * @return PostCode
     * @throws CreatePostCodeErrorException
     */
    public function createPostCode(array $data) : PostCode
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new CreatePostCodeErrorException($e);
        }
    }

    /**
     * @param int $id
     *
     * @return PostCode
     * @throws PostCodeNotFoundErrorException
     */
    public function findPostCodeById(int $id) : PostCode
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new PostCodeNotFoundErrorException($e);
        }
    }

    /**
     * @param array $data
     * @param int $id
     *
     * @return bool
     * @throws UpdatePostCodeErrorException
     */
    public function updatePostCode(array $data) : bool
    {
        try {
            return $this->update($data);
        } catch (QueryException $e) {
            throw new UpdatePostCodeErrorException($e);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deletePostCode() : bool
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
    public function listPostCodes($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }
}
