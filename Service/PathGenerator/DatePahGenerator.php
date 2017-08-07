<?php

namespace Youshido\GraphQLFilesBundle\Service\PathGenerator;

use Youshido\GraphQLFilesBundle\Model\Resize\ResizeConfig;

/**
 * Class DateGenerator
 */
class DatePahGenerator implements PathGeneratorInterface
{
    /**
     * @param string $extension
     *
     * @return string
     */
    public function generate(string $extension): string
    {
        $now = new \DateTime();

        return sprintf('%s/%s/%s/%s.%s', $now->format('Y'), $now->format('m'), $now->format('d'), uniqid('', false), $extension);
    }

    /**
     * @param string       $path
     * @param ResizeConfig $config
     *
     * @return string
     */
    public function generateResized(string $path, ResizeConfig $config): string
    {
        return sprintf('%s_%sx%s/%s', $config->getMode(), $config->getWidth(), $config->getHeight(), $path);
    }
}
