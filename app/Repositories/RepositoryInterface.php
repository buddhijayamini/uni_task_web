<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function findByIdList($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
