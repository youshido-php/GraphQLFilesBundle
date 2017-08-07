<?php

namespace Youshido\GraphQLFilesBundle\GraphQL\Type;

use Youshido\GraphQL\Type\Enum\AbstractEnumType;

/**
 * Class ResizeModeType
 */
class ResizeModeType extends AbstractEnumType
{
    const OUTBOUND = 'outbound';
    const INSET    = 'inset';

    /**
     * @return array
     */
    public function getValues()
    {
        return [
            [
                'name'  => 'INSET',
                'value' => self::INSET,
            ],
            [
                'name'  => 'OUTBOUND',
                'value' => self::OUTBOUND,
            ],
        ];
    }
}
