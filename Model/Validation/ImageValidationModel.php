<?php

namespace Youshido\GraphQLFilesBundle\Model\Validation;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ImageValidationModel
 */
class ImageValidationModel implements ValidationModelInterface
{
    /**
     * @var UploadedFile
     *
     * @Assert\Image()
     */
    private $file;

    /**
     * @param UploadedFile $file
     *
     * @return void
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }
}
