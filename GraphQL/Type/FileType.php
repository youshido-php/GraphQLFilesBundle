<?php

namespace Youshido\GraphQLFilesBundle\GraphQL\Type;

use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQLFilesBundle\Model\FileModelInterface;

/**
 * Class FileType
 */
class FileType extends AbstractObjectType
{
    /**
     * @param ObjectTypeConfig $config
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function build($config)
    {
        $config->addFields([
            'id'        => new IdType(),
            'url'       => [
                'type'    => new StringType(),
                'resolve' => function (FileModelInterface $value, array $args = [], ResolveInfo $info) { // @codingStandardsIgnoreLine
                    return $info->getContainer()->get('graphql_files.upload_graphql_resolver')->resolveWebPath($value);
                },
            ],
            'fileName'  => new StringType(),
            'mimeType'  => new StringType(),
            'extension' => new StringType(),
            'size'      => new IntType(),
        ]);
    }
}
