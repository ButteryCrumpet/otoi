<?php

namespace Otoi\Repositories;


class FileValidationRepository implements ValidationRepositoryInterface
{
    /**
     * @var \DirectoryIterator
     */
    private $directory;

    public function __construct($dir)
    {
        $this->dir = new \DirectoryIterator($dir);
        // check readable writable etc
    }

    public function list(): array
    {
        $list = [];
        foreach ($this->directory as $file) {
            if ($file->isFile() && !$file->isDot()) {
                $list[] = $file->getFilename();
            }
        }
        return $list;
    }

    public function create($id, $rules): bool
    {
        // TODO: Implement create() method.
    }

    public function read($id): array
    {
        // TODO: Implement read() method.
    }

    public function update($id, $rules): bool
    {
        // TODO: Implement update() method.
    }
}