<?php

namespace Youshido\GraphQLFilesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Youshido\GraphQLFilesBundle\Model\ImageModelInterface;

/**
 * Class Image
 *
 * @ORM\Entity()
 * @ORM\Table(name="images")
 */
class Image implements ImageModelInterface
{
    use FileTrait;
}
