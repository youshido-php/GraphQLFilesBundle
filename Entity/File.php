<?php

namespace Youshido\GraphQLFilesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Youshido\GraphQLFilesBundle\Model\FileModelInterface;

/**
 * Class File
 *
 * @ORM\Entity()
 * @ORM\Table(name="files")
 */
class File implements FileModelInterface
{
    use FileTrait;
}
