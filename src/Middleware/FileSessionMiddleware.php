<?php

namespace Otoi\Middleware;

use function GuzzleHttp\Psr7\stream_for;
use GuzzleHttp\Psr7\UploadedFile;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Http\Server\RequestHandlerInterface;

// No post use session and keep everything internal
class FileSessionMiddleware
{
    private $identifier = "_files-session";
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        if ($request->getMethod() !== "POST") {
            return $handler->handle($request);
        }

        $files = $request->getUploadedFiles();
        $parsed = $request->getParsedBody();

        $newFiles = array_merge($this->getCachedFiles($parsed), $files);
        $parsed[$this->identifier] = $this->cacheFiles($files);

        $newRequest = $request
            ->withParsedBody($parsed)
            ->withUploadedFiles($newFiles);

        return $handler->handle($newRequest);
    }

    private function getCachedFiles($parsed)
    {
        $files = [];
        if (isset($parsed[$this->identifier])) {
            $fileIds = $parsed[$this->identifier];
            if (!is_array($fileIds)) {
                $fileIds = [$fileIds];
            }
            foreach ($fileIds as $id) {
                $file = $this->cache->get($id, "fsm_none");
                if ($file === "fsm_none") {
                    continue;
                }
                $cached = $this->getFromCache($id);
                $files[$cached[0]] = $cached[1];
                $this->cache->delete($id);
            }
        }
        return $files;
    }

    private function getFromCache($id)
    {
        $file = $this->cache->get($id);
        $reInit = new UploadedFile(
            stream_for($file["content"]),
            $file["size"],
            $file["error"],
            $file["filename"],
            $file["type"]
        );
        return [$file["name"], $reInit];
    }

    private function cacheFiles($files)
    {
        $ids = [];
        foreach ($files as $name => $file) {
            if ($file->getError() === UPLOAD_ERR_OK) {
                $ids[] = $this->cacheFile($name, $file);
            }
        }
        return $ids;
    }

    private function cacheFile($name, UploadedFileInterface $file)
    {
        $file = [
            "name" => $name,
            "filename" => $file->getClientFilename(),
            "size" => $file->getSize(),
            "error" => $file->getError(),
            "content" => $file->getStream()->getContents(),
            "type" => $file->getClientMediaType()
        ];
        $unique_id = uniqid("fsm_", true);
        $this->cache->set($unique_id, $file);
        return $unique_id;
    }
}