<?php
declare(strict_types = 1);

namespace SONFin\Repository;

use Illuminate\Database\Eloquent\Model;

class DefaultRepository implements RepositoryInterface
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var Model
     */
    private $model;

    /**
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->model = new $modelClass;
    }

    public function all() : array
    {
        return $this->model->all()->toArray(); // eloquent retorna um Colletion
    }

    public function create(array $data)
    {
        $this->model->fill($data);
        $this->model->save();

        return $this->model;
    }

    public function update(int $id, array $data)
    {
        $model = $this->find($id);
        $model->fill($data);
        $model->save();
        return $model;
    }
    public function delete(int $id)
    {
        $repository->delete();
    }

    public function find(int $id, bool $failIfNotExixt = true)
    {
        return $failIfNotExixt?$this->model->findOrFail($id): $this->model->find($id);
    }


    public function findByField(string $field, $value)
    {
        return $this->model->where($field, '=', $value)->get();
    }
}
