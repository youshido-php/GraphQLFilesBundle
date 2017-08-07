<?php

namespace Youshido\GraphQLFilesBundle\Model;

/**
 * Class PathAwareInterface
 */
interface PathAwareInterface
{
    /**
     * @return string
     */
    public function getPath(): string;
}
