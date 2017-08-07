<?php

namespace Youshido\GraphQLFilesBundle\Model\Resize;

/**
 * Class ResizeConfig
 */
class ResizeConfig
{
    /** @var  int */
    private $width;

    /** @var  int */
    private $height;

    /** @var  string */
    private $mode;

    /**
     * ResizeConfig constructor.
     *
     * @param int    $width
     * @param int    $height
     * @param string $mode
     */
    public function __construct(int $width, int $height, string $mode)
    {
        $this->width  = $width;
        $this->height = $height;
        $this->mode   = $mode;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }
}
