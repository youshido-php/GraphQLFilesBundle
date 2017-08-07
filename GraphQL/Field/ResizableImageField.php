<?php

namespace Youshido\GraphQLFilesBundle\GraphQL\Field;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQLFilesBundle\GraphQL\Type\ResizableImageType;
use Youshido\GraphQLFilesBundle\GraphQL\Type\ResizeModeType;
use Youshido\GraphQLFilesBundle\Model\PathAwareInterface;

/**
 * Class ResizableImageField
 */
class ResizableImageField extends AbstractField
{
    /**
     * @param FieldConfig $config
     */
    public function build(FieldConfig $config)
    {
        $config->addArguments([
            'width'  => new NonNullType(new IntType()),
            'height' => new NonNullType(new IntType()),
            'mode'   => [
                'type'    => new ResizeModeType(),
                'default' => ResizeModeType::OUTBOUND,
            ],
        ]);
    }

    /**
     * @param PathAwareInterface $value
     * @param array              $args
     * @param ResolveInfo        $info
     *
     * @return array
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $info->getContainer()->get('graphql_files.resize_graphql_resolver')->resolveResize($value, $args);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'resized';
    }

    /**
     * @return AbstractObjectType|AbstractType
     */
    public function getType()
    {
        return new ResizableImageType();
    }
}
