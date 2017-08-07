<?php

namespace Youshido\GraphQLFilesBundle\Model;

/**
 * Interface FileModelInterface
 */
interface FileModelInterface extends PathAwareInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getFileName(): string;

    /**
     * @param string $fileName
     *
     * @return FileModelInterface
     */
    public function setFileName(string $fileName);

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @param string $path
     *
     * @return FileModelInterface
     */
    public function setPath(string $path);

    /**
     * @return string
     */
    public function getMimeType(): string;

    /**
     * @param string $mimeType
     *
     * @return FileModelInterface
     */
    public function setMimeType(string $mimeType);

    /**
     * @return \DateTime
     */
    public function getUploadedAt(): \DateTime;

    /**
     * @param \DateTime $uploadedAt
     *
     * @return FileModelInterface
     */
    public function setUploadedAt(\DateTime $uploadedAt);

    /**
     * @return int
     */
    public function getSize(): int;

    /**
     * @param int $size
     *
     * @return FileModelInterface
     */
    public function setSize(int $size);

    /**
     * @return string
     */
    public function getExtension(): string;

    /**
     * @param string $extension
     */
    public function setExtension(string $extension);
}
