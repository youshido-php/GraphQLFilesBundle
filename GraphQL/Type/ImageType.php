<?php

namespace Youshido\GraphQLFilesBundle\GraphQL\Type;

use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQLFilesBundle\GraphQL\Field\ResizableImageField;

/**
 * Class ImageType
 */
class ImageType extends FileType
{
    /**
     * @param ObjectTypeConfig $config
     */
    public function build($config)
    {
        parent::build($config);

        $config->addField(new ResizableImageField());
    }
}
