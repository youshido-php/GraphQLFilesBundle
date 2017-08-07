<?php

namespace Youshido\GraphQLFilesBundle\Service\PathGenerator;

use Youshido\GraphQLFilesBundle\Model\Resize\ResizeConfig;

/**
 * Interface PathGeneratorInterface
 */
interface PathGeneratorInterface
{
    /**
     * @param string $extension
     *
     * @return string
     */
    public function generate(string $extension): string;

    /**
     * @param string       $path
     * @param ResizeConfig $config
     *
     * @return string
     */
    public function generateResized(string $path, ResizeConfig $config): string;
}
