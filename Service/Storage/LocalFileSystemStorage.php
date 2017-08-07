<?php

namespace Youshido\GraphQLFilesBundle\Service\Storage;

use Symfony\Component\Routing\RequestContext;
use Youshido\GraphQLFilesBundle\Exception\StorageException;

/**
 * Class LocalFileSystemStorage
 */
class LocalFileSystemStorage implements StorageInterface
{
    /** @var string */
    private $uploadDir;

    /** @var string */
    private $rootDir;

    /** @var  RequestContext */
    private $requestContext;

    /**
     * LocalFileSystemStorage constructor.
     *
     * @param string         $rootDir
     * @param string         $uploadDir
     * @param RequestContext $requestContext
     */
    public function __construct(string $rootDir, string $uploadDir, RequestContext $requestContext)
    {
        $this->rootDir        = rtrim($rootDir, '/');
        $this->uploadDir      = rtrim($uploadDir, '/');
        $this->requestContext = $requestContext;
    }

    /**
     * @param string $path
     * @param string $data
     *
     * @return void
     *
     * @throws StorageException
     */
    public function write(string $path, string $data)
    {
        $path = $this->preparePath($path);

        $directory = dirname($path);
        if (!file_exists($directory) && !@mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new StorageException('Can\'t write file to filesystem');
        }

        $result = file_put_contents($path, $data);

        if (false === $result) {
            throw new StorageException('Can\'t write file to filesystem');
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($this->preparePath($path));
    }

    /**
     * @param string $path
     *
     * @return string
     *
     * @throws StorageException
     */
    public function read(string $path): string
    {
        if (!$this->exists($path)) {
            throw new StorageException('File not exists');
        }

        return file_get_contents($this->preparePath($path));
    }

    /**
     * @param string $path
     *
     * @return void
     */
    public function remove(string $path)
    {
        if ($this->exists($path)) {
            unlink($this->preparePath($path));
        }
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getWebUrl(string $path): string
    {
        return sprintf('%s://%s/%s/%s', $this->requestContext->getScheme(), $this->requestContext->getHost(), $this->uploadDir, $path);
    }

    /**
     * @return bool
     */
    public function needInstantResize(): bool
    {
        return false;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function preparePath(string $path): string
    {
        return $this->rootDir . DIRECTORY_SEPARATOR . $this->uploadDir . DIRECTORY_SEPARATOR . $path;
    }
}
