<?php

namespace Youshido\GraphQLFilesBundle\Service\GraphQLResolver;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Youshido\GraphQLFilesBundle\Model\FileModelInterface;
use Youshido\GraphQLFilesBundle\Model\ImageModelInterface;
use Youshido\GraphQLFilesBundle\Model\PathAwareInterface;
use Youshido\GraphQLFilesBundle\Service\Uploader;

/**
 * Class UploadGraphQLResolver
 */
class UploadGraphQLResolver
{
    /** @var  Uploader */
    private $imageUploader;

    /** @var  Uploader */
    private $fileUploader;

    /** @var  RequestStack */
    private $requestStack;

    /**
     * GraphQLResolver constructor.
     *
     * @param Uploader     $imageUploader
     * @param Uploader     $fileUploader
     * @param RequestStack $requestStack
     */
    public function __construct(Uploader $imageUploader, Uploader $fileUploader, RequestStack $requestStack)
    {
        $this->imageUploader = $imageUploader;
        $this->fileUploader  = $fileUploader;
        $this->requestStack  = $requestStack;
    }

    /**
     * @param mixed  $object
     * @param string $property
     *
     * @return mixed|null
     *
     * @throws \InvalidArgumentException
     */
    public function resolveProperty($object, string $property)
    {
        $image = $this->doResolveProperty($object, $property);

        if (null !== $image && !$image instanceof FileModelInterface) {
            throw new \InvalidArgumentException(sprintf('Invalid object type "%s" provided like file', get_class($image)));
        }

        return $image;
    }

    /**
     * @param PathAwareInterface $pathAware
     *
     * @return string
     */
    public function resolveWebPath(PathAwareInterface $pathAware)
    {
        if ($pathAware instanceof ImageModelInterface) {
            return $this->imageUploader->getStorage()->getWebUrl($pathAware->getPath());
        }

        return $this->fileUploader->getStorage()->getWebUrl($pathAware->getPath());
    }

    /**
     * @param string $requestField
     *
     * @return FileModelInterface
     */
    public function resolveUploadImage(string $requestField)
    {
        return $this->imageUploader->uploadFromUploadedFile($this->getRequestFile($requestField));
    }

    /**
     * @param string $data
     *
     * @return FileModelInterface
     */
    public function resolveUploadBase64Image(string $data)
    {
        return $this->imageUploader->uploadBase64File($data);
    }

    /**
     * @param string $requestField
     *
     * @return FileModelInterface
     */
    public function resolveUploadFile(string $requestField)
    {
        return $this->fileUploader->uploadFromUploadedFile($this->getRequestFile($requestField));
    }

    /**
     * @param string $field
     *
     * @return UploadedFile
     *
     * @throws \InvalidArgumentException
     */
    private function getRequestFile(string $field)
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request->files->has($field)) {
            throw new \InvalidArgumentException(sprintf('Request must contain "%s" field with file data', $field), 400);
        }

        return $request->files->get($field);
    }

    /**
     * @param mixed  $object
     * @param string $property
     *
     * @return mixed|null
     */
    private function doResolveProperty($object, string $property)
    {
        if (is_array($object) && isset($object[$property])) {
            return $object[$property];
        }

        if (is_object($object)) {
            $accessor = PropertyAccess::createPropertyAccessor();

            return $accessor->getValue($object, $property);
        }

        return null;
    }
}
