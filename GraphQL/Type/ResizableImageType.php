<?php

namespace Youshido\GraphQLFilesBundle\GraphQL\Type;

use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class ResizableImageType
 */
class ResizableImageType extends AbstractObjectType
{
    /**
     * @param ObjectTypeConfig $config
     */
    public function build($config)
    {
        $config->addFields([
            'width'  => new IntType(),
            'height' => new IntType(),
            'mode'   => new ResizeModeType(),
            'url'    => new StringType(),
        ]);
    }
}
