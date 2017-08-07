<?php

namespace Youshido\GraphQLFilesBundle\Service\Storage;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Youshido\GraphQLFilesBundle\Exception\StorageException;

/**
 * Class AmazonS3Storage
 */
class AmazonS3Storage implements StorageInterface
{
    /** @var  S3Client */
    protected $client;

    /** @var  string */
    protected $bucketName;

    /** @var  string */
    private $directory;

    /**
     * AmazonS3Storage constructor.
     *
     * @param S3Client $client
     * @param string   $bucketName
     * @param string   $directory
     */
    public function __construct(S3Client $client, string $bucketName, string $directory)
    {
        $this->client     = $client;
        $this->bucketName = $bucketName;
        $this->directory  = $directory;
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
        $path = $this->processDirectory($path);

        try {
            $this->client->putObject([
                'Bucket' => $this->bucketName,
                'Key'    => $path,
                'Body'   => $data,
                'ACL'    => 'public-read',
            ]);
        } catch (S3Exception $e) {
            throw new StorageException($e->getMessage());
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     *
     * @throws StorageException
     */
    public function exists(string $path): bool
    {
        try {
            $path = $this->processDirectory($path);

            return $this->client->doesObjectExist($this->bucketName, $path);
        } catch (S3Exception $e) {
            throw new StorageException($e->getMessage());
        }
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
        try {
            $path = $this->processDirectory($path);

            $result = $this->client->getObject([
                'Bucket' => $this->bucketName,
                'Key'    => $path,
            ]);

            return $result['Body'];
        } catch (S3Exception $e) {
            throw new StorageException($e->getMessage());
        }
    }

    /**
     * @param string $path
     *
     * @return void
     *
     * @throws StorageException
     */
    public function remove(string $path)
    {
        try {
            $path = $this->processDirectory($path);

            $this->client->deleteObject([
                'Bucket' => $this->bucketName,
                'Key'    => $path,
            ]);
        } catch (S3Exception $e) {
            throw new StorageException($e->getMessage());
        }
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getWebUrl(string $path): string
    {
        $path = $this->processDirectory($path);

        return $this->client->getObjectUrl($this->bucketName, $path);
    }

    /**
     * @return bool
     */
    public function needInstantResize(): bool
    {
        return true;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function processDirectory(string $path): string
    {
        if (!$this->directory) {
            return $path;
        }

        return $this->directory . DIRECTORY_SEPARATOR . ltrim($path, '/');
    }
}
