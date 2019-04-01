<?php

namespace Otoi\Middleware;

use Otoi\Sessions\SessionInterface;
use Slim\Http\UploadedFile;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\ResponseInterface;

class FileSessionMiddleware
{
    private $identifier = "_files-session";
    private $directory;
    private $session;

    public function __construct($directory, SessionInterface $session)
    {
        $path = rtrim($directory, "/") ."/fcache";

        if (!is_dir($path)) {
            if (!mkdir($path, 0775)) {
                throw new \InvalidArgumentException(sprintf(
                    _("% is not a directory"), realpath($path)
                ));
            }
        }

        $dir = new \SplFileInfo($path);

        if (!$dir->isWritable()) {
            throw new \DomainException(sprintf(
                _("% is not writable"), $dir->getRealPath()
            ));
        }

        if (!$dir->isWritable()) {
            throw new \DomainException(sprintf(
                _("% is not readable"), $dir->getRealPath()
            ));
        }

        $this->directory = $dir;
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if ($request->getMethod() !== "POST") {
            return $next($request, $response);
        }

        $files = $request->getUploadedFiles();

        $cached = $this->session->getFlash($this->identifier);
        if (!is_null($cached)) {
            $newFiles = array_merge($this->getCachedFiles($cached), $files);
            $request = $request->withUploadedFiles($newFiles);
        }

        $response = $next($request, $response);

        if (!$this->session->condemned() && $response->getStatusCode() === 200) {
            $newly_cached = $this->cacheFiles($files);
            $this->session->flash($this->identifier, $newly_cached);
        } else {
            foreach ($cached as $info) {
                unlink($this->buildPath($info["id"]));
            }
        }

        return $response;
    }


    /**
     * @param UploadedFileInterface[] $files
     * @return array
     */
    private function cacheFiles($files)
    {
        $ids = [];
        foreach ($files as $name => $file) {
            if ($file->getError() === UPLOAD_ERR_OK) {
                $id = uniqid();

                // handle exceptions?
                $file->moveTo($this->buildPath($id));

                $ids[$name] = [
                    "filename" => $file->getClientFilename(),
                    "size" => $file->getSize(),
                    "type" => $file->getClientMediaType(),
                    "id" => $id
                ];
            }
        }
        return $ids;
    }

    private function getCachedFiles($fileInfo)
    {
        if (!is_array($fileInfo)) {
            $fileInfo = [$fileInfo];
        }
        $files = [];
        foreach ($fileInfo as $name => $info) {
            $file = new \SplFileInfo($this->buildPath($info['id']));

            if (!$file->isFile()) {
                continue;
            }

            if (!$file->isReadable()) {
                // log or throw?
                continue;
            }

            $files[$name] = new UploadedFile(
                $file->getRealPath(),
                $info["filename"],
                $info["type"],
                $info["size"]
            );
        }
        return $files;
    }

    private function buildPath($id) {
        return $this->directory->getRealPath()."/$id";
    }
}