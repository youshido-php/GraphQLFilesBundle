<?php

namespace Youshido\GraphQLFilesBundle\Service\GraphQLResolver;

use Youshido\GraphQLFilesBundle\Model\ImageModelInterface;
use Youshido\GraphQLFilesBundle\Model\Resize\ResizeConfig;
use Youshido\GraphQLFilesBundle\Service\Resizer;

/**
 * Class ResizeGraphQLResolver
 */
class ResizeGraphQLResolver
{
    /** @var  Resizer */
    private $resizer;

    /**
     * ResizeGraphQLResolver constructor.
     *
     * @param Resizer $resizer
     */
    public function __construct(Resizer $resizer)
    {
        $this->resizer = $resizer;
    }

    /**
     * @param ImageModelInterface $object
     * @param array               $config
     *
     * @return array
     */
    public function resolveResize(ImageModelInterface $object, array $config)
    {
        $configObj = new ResizeConfig($config['width'], $config['height'], $config['mode']);
        $url       = $this->resizer->resize($object->getPath(), $configObj, false);

        return array_merge($config, ['url' => $url]);
    }
}
