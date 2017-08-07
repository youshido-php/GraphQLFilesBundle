<?php

namespace Youshido\GraphQLFilesBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Youshido\GraphQLFilesBundle\Model\ImageModelInterface;

/**
 * Class EmbeddedImage
 *
 * @MongoDB\EmbeddedDocument()
 */
class EmbeddedImage extends EmbeddedFile implements ImageModelInterface
{
}
