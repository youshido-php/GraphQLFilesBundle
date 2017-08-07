<?php

namespace Youshido\GraphQLFilesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait FileTrait
 */
trait FileTrait
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $mimeType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $extension;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $uploadedAt;

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     *
     * @return FileTrait
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return FileTrait
     */
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     *
     * @return FileTrait
     */
    public function setMimeType(string $mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUploadedAt(): \DateTime
    {
        return $this->uploadedAt;
    }

    /**
     * @param \DateTime $uploadedAt
     *
     * @return FileTrait
     */
    public function setUploadedAt(\DateTime $uploadedAt)
    {
        $this->uploadedAt = clone $uploadedAt;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return FileTrait
     */
    public function setSize(int $size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension(string $extension)
    {
        $this->extension = $extension;
    }
}
