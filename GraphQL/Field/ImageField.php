<?php

namespace Youshido\GraphQLFilesBundle\GraphQL\Field;

use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQLFilesBundle\GraphQL\Type\ImageType;
use Youshido\GraphQLFilesBundle\Model\FileModelInterface;

/**
 * Class ImageField
 */
class ImageField extends AbstractField
{
    /** @var string */
    protected $fieldName;

    /**
     * ImageField constructor.
     *
     * @param string $fieldName
     */
    public function __construct(string $fieldName = 'image')
    {
        $this->fieldName = $fieldName;

        parent::__construct([
            'name' => $fieldName,
            'type' => $this->getType(),
        ]);
    }

    /**
     * @param mixed       $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return FileModelInterface
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $info->getContainer()->get('graphql_files.upload_graphql_resolver')->resolveProperty($value, $this->fieldName);
    }

    /**
     * @return AbstractObjectType|AbstractType
     */
    public function getType()
    {
        return new ImageType();
    }
}
