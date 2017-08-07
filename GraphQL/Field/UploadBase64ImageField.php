<?php

namespace Youshido\GraphQLFilesBundle\GraphQL\Field;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQLFilesBundle\GraphQL\Type\ImageType;
use Youshido\GraphQLFilesBundle\Model\FileModelInterface;

/**
 * Class UploadBase64ImageField
 */
class UploadBase64ImageField extends AbstractField
{
    /**
     * @param FieldConfig $config
     */
    public function build(FieldConfig $config)
    {
        $config->addArguments([
            'data' => new NonNullType(new StringType()),
        ]);
    }

    /**
     * @param null        $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return FileModelInterface
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $info->getContainer()->get('graphql_files.upload_graphql_resolver')->resolveUploadBase64Image($args['data']);
    }

    /**
     * @return AbstractObjectType|AbstractType
     */
    public function getType()
    {
        return new ImageType();
    }
}
