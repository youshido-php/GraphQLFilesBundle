<?php

namespace Youshido\GraphQLFilesBundle\Service;

use Imagine\Image\Box;
use Youshido\GraphQLFilesBundle\Exception\ImageNotFoundException;
use Youshido\GraphQLFilesBundle\Exception\ResizeImageException;
use Youshido\GraphQLFilesBundle\Factory\ImagineFactory;
use Youshido\GraphQLFilesBundle\Model\Resize\ResizeConfig;
use Youshido\GraphQLFilesBundle\Service\PathGenerator\PathGeneratorInterface;
use Youshido\GraphQLFilesBundle\Service\Storage\StorageInterface;

/**
 * Class Resizer
 */
class Resizer
{
    /** @var  StorageInterface */
    private $storage;

    /** @var  PathGeneratorInterface */
    private $pathGenerator;

    /** @var  string */
    private $driver;

    /**
     * Resizer constructor.
     *
     * @param StorageInterface       $storage
     * @param PathGeneratorInterface $pathGenerator
     * @param string                 $driver
     */
    public function __construct(StorageInterface $storage, PathGeneratorInterface $pathGenerator, string $driver)
    {
        $this->storage       = $storage;
        $this->pathGenerator = $pathGenerator;
        $this->driver        = $driver;
    }

    /**
     * @param string       $path
     * @param ResizeConfig $config
     * @param bool         $force
     *
     * @return string
     *
     * @throws ResizeImageException
     */
    public function resize(string $path, ResizeConfig $config, bool $force = true): string
    {
        $targetPath = $this->pathGenerator->generateResized($path, $config);

        if ($force || $this->storage->needInstantResize()) {
            try {
                $this->doResize($path, $targetPath, $config);
            } catch (\Exception $e) {
                throw new ResizeImageException($e->getMessage());
            } catch (\Throwable $e) {
                throw new ResizeImageException($e->getMessage());
            }
        }

        return $this->storage->getWebUrl($targetPath);
    }

    /**
     * @param string       $path
     * @param ResizeConfig $config
     *
     * @return string
     */
    public function getResizedImageContent(string $path, ResizeConfig $config)
    {
        $targetPath = $this->pathGenerator->generateResized($path, $config);

        return $this->storage->read($targetPath);
    }

    /**
     * @param string       $sourcePath
     * @param string       $targetPath
     * @param ResizeConfig $config
     *
     * @throws ImageNotFoundException
     */
    private function doResize(string $sourcePath, string $targetPath, ResizeConfig $config)
    {
        if (!$this->storage->exists($sourcePath)) {
            throw new ImageNotFoundException();
        }

        if ($this->storage->exists($targetPath)) {
            return;
        }

        $imagine        = ImagineFactory::createImagine($this->driver);
        $resizedContent = $imagine
            ->load($this->storage->read($sourcePath))
            ->thumbnail(new Box($config->getWidth(), $config->getHeight()))
            ->get(pathinfo($sourcePath, PATHINFO_EXTENSION));

        $this->storage->write($targetPath, $resizedContent);
    }
}
