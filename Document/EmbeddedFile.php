<?php

namespace Youshido\GraphQLFilesBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Youshido\GraphQLFilesBundle\Model\FileModelInterface;

/**
 * Class EmbeddedFile
 *
 * @MongoDB\EmbeddedDocument()
 */
class EmbeddedFile implements FileModelInterface
{
    /**
     * @var string
     *
     * @MongoDB\Id()
     */
    private $id;

    /**
     * @var string
     *
     * @MongoDB\ObjectId()
     */
    private $referenceId;

    /**
     * @var string
     *
     * @MongoDB\Field()
     */
    private $path;

    /**
     * @var string
     *
     * @MongoDB\Field()
     */
    private $fileName;

    /**
     * @var string
     *
     * @MongoDB\Field()
     */
    private $mimeType;

    /**
     * @var string
     *
     * @MongoDB\Field()
     */
    private $extension;

    /**
     * @var int
     *
     * @MongoDB\Field()
     */
    private $size;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date")
     */
    private $uploadedAt;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->referenceId;
    }

    /**
     * @return string
     */
    public function getOriginalId()
    {
        return $this->id;
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
     * @return FileModelInterface
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
     * @return FileModelInterface
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
     * @return FileModelInterface
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
     * @return FileModelInterface
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
     * @return FileModelInterface
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
