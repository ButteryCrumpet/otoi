<?php

namespace Otoi\Repositories;


Interface ValidationRepositoryInterface
{
    public function list(): array;

    public function create($id, $rules): bool;

    public function read($id): array;

    public function update($id, $rules): bool;
}