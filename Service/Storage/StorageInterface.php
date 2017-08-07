<?php

namespace Youshido\GraphQLFilesBundle\Service\Storage;

/**
 * Interface StorageInterface
 */
interface StorageInterface
{
    /**
     * @param string $path
     * @param string $data
     *
     * @return void
     */
    public function write(string $path, string $data);

    /**
     * @param string $path
     *
     * @return bool
     */
    public function exists(string $path): bool;

    /**
     * @param string $path
     *
     * @return string
     */
    public function read(string $path): string;

    /**
     * @param string $path
     *
     * @return void
     */
    public function remove(string $path);

    /**
     * @param string $path
     *
     * @return string
     */
    public function getWebUrl(string $path): string;

    /**
     * @return bool
     */
    public function needInstantResize(): bool;
}
