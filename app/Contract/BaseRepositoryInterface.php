<?php

namespace App\Contract;

interface BaseRepositoryInterface
{
    public function fillable();

    public function rule();

    public function setAttributeModel();

    public function getValidated();

    public function validate(array $data = []);

    public function create(array $data = [], $next = null);

    public function save($data = []);

    public function update($id, array $data);

    public function delete($id);

    public function all();

    public function find($id);

    public function getWhere($obj);

    public function findWhere($obj);

    public function findWhereWith($where, $with = []);

    // public function transformer(array $data): self;
}
