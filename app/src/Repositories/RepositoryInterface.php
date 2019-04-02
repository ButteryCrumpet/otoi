<?php

namespace Otoi\Repositories;

interface RepositoryInterface
{
    public function load($name);

    public function all();

    public function listing();

    //TODO: Update, Delete, ETC (CRUD Repo)
}