<?php

namespace Youshido\GraphQLFilesBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Youshido\GraphQLFilesBundle\Exception\ResizeImageException;
use Youshido\GraphQLFilesBundle\Model\Resize\ResizeConfig;
use Youshido\GraphQLFilesBundle\Service\Guesser\MimeTypeGuesser;
use Youshido\GraphQLFilesBundle\Service\Resizer;

/**
 * Class ResizeImageController
 */
class ResizeImageController
{
    /** @var  Resizer */
    private $resizer;

    /**
     * ResizeImageController constructor.
     *
     * @param Resizer $resizer
     */
    public function __construct(Resizer $resizer)
    {
        $this->resizer = $resizer;
    }

    /**
     * @param string $width
     * @param string $height
     * @param string $mode
     * @param string $path
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function resizeAction($width, $height, $mode, $path)
    {
        try {
            $config = new ResizeConfig($width, $height, $mode);
            $this->resizer->resize($path, $config, true);
            $content = $this->resizer->getResizedImageContent($path, $config);

            return new Response($content, 200, [
                'Content-Type' => MimeTypeGuesser::getMimeType(pathinfo($path, PATHINFO_EXTENSION)),
            ]);
        } catch (ResizeImageException $e) {
            throw  new NotFoundHttpException('Not found', $e);
        }
    }
}
