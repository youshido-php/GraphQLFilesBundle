<?php

namespace Youshido\GraphQLFilesBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Youshido\GraphQLFilesBundle\Model\ImageModelInterface;

/**
 * Class Image
 *
 * @MongoDB\Document()
 */
class Image extends File implements ImageModelInterface
{

}
