<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Contract\AttributesFeature\Utils\AttributeExtractor;
use App\Contract\BaseRepositoryInterface;
use App\Utils\ErrorResponse;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Callback;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected array $validated;
    protected string | int | null $id = null;
    protected array $data = [];
    public $model;
    public function __construct(
        $model = null
    ) {
        if ($model == null) {
            $this->setAttributeModel();
        }
    }

    public function __call($name, $arguments)
    {
        $this->setAttributeModel();
    }


    public function fillable()
    {
        return $this->model->getFillable();
    }

    public function rule()
    {
        if ($rules = $this->model::rules($this->getId() ?? null))
            return $rules;
        return [];
    }

    public function setAttributeModel()
    {
        $models =  (new AttributeExtractor())->setClass(get_called_class())
            ->setAttribute(Repository::class)
            ->extractAttributes()
            ->getAttributes();
        $this->model = new $models['model']();
    }

    public function getValidated()
    {
        return $this->validated;
    }

    public function validate(array $data = [])
    {
        try {
            if ($this->model == null)
                $this->setAttributeModel();
            if (count($data) == 0)
                $data = $this->data;
            else if (count($data) == 0 && count($this->data) == 0) {
                throw new \Exception("Data is empty");
            } else {
                if (!$data) {
                    throw new \Exception("Data is empty");
                }
            }

            $validation = Validator::make($data, $this->rule(($this->getId() ?? null)));
            if ($validation->fails()) {
                (ErrorResponse::getInstance())->setError("validate", $validation->errors());
                throw new \Exception($validation->errors()->first());
            }
            $this->validated = $validation->validated();
            return $this;
        } catch (\Throwable $th) {
            // dd($this->getId(), $this->rule(($this->getId() ?? null)));
            throw new \Exception($th->getMessage());
        }
    }

    // findBy
    public function findBy($field, $value)
    {
        return $this->model->where($field, $value)->first();
    }

    //crud here
    public function create(array $data = [], $next = null)
    {
        try {
            if (count($data) == 0)
                $data = $this->validated;
            else if (count($data) == 0 && count($this->validated) == 0) {
                $data = $this->data;
            } else {
                if (!$data) {
                    throw new \Exception("Data is empty");
                }
            }
            $created = $this->model->create($data);
            if ($next)
                $next($created);
            return $created;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function save($data = [])
    {
        try {
            if (empty($this->id))
                return $this->model->create($this->validated ?? $this->getData());
            else
                return $this->model->whereId($this->getId())->update($this->validated ?? $this->getData())
                    ? $this->model->find($this->getId()) : null;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function update($id, array $data)
    {
        try {
            $model = $this->model->find($id);
            $model->update($data);
            return $model;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function delete($id = null)
    {
        try {
            $model = $this->model->find(!empty($id) ? $id : $this->getId());
            $model->delete();
            return $model;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function getWhere($obj)
    {
        return $this->model->where(function ($q) use (&$obj) {
            return $obj($q);
        })->get();
    }
    public function findWhere($obj)
    {
        return $this->model->where(function ($q) use (&$obj) {
            return $obj($q);
        })->first();
    }

    public function findWhereWith($where, $with = [])
    {
        return $this->model->where(function ($q) use (&$where) {
            return $where($q);
        })->with($with)->first();
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getId()
    {
        return $this->id ?? $this->data['id'] ?? null;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function set($id, array $data)
    {
        $this->model->find($id)->update($data);
    }
    // public function transformer(array $data);
}
