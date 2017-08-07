<?php

namespace Youshido\GraphQLFilesBundle\Model\Validation;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface ValidationModelInterface
 */
interface ValidationModelInterface
{
    /**
     * @param UploadedFile $file
     *
     * @return void
     */
    public function setFile(UploadedFile $file);
}
