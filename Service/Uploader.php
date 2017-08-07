<?php

namespace Youshido\GraphQLFilesBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Youshido\GraphQLFilesBundle\Exception\UploadException;
use Youshido\GraphQLFilesBundle\Model\FileModelInterface;
use Youshido\GraphQLFilesBundle\Model\Validation\ValidationModelInterface;
use Youshido\GraphQLFilesBundle\Service\Guesser\MimeTypeGuesser;
use Youshido\GraphQLFilesBundle\Service\PathGenerator\PathGeneratorInterface;
use Youshido\GraphQLFilesBundle\Service\PersistentManager\PersistentManagerInterface;
use Youshido\GraphQLFilesBundle\Service\Storage\StorageInterface;

/**
 * Class Uploader
 *
 * This class contains two required properties (fileModelClass, validationModelClass) witch are not passed through
 * constructor and must be passed through setter.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Uploader
{
    /** @var PathGeneratorInterface */
    private $pathGenerator;

    /** @var StorageInterface */
    private $storage;

    /** @var PersistentManagerInterface */
    private $persistentManager;

    /** @var ValidatorInterface */
    private $validator;

    /** @var string */
    private $fileModelClass;

    /** @var string */
    private $validationModelClass;

    /**
     * Uploader constructor.
     *
     * @param PathGeneratorInterface     $pathGenerator
     * @param StorageInterface           $storage
     * @param ValidatorInterface         $validator
     * @param PersistentManagerInterface $persistentManager
     */
    public function __construct(PathGeneratorInterface $pathGenerator, StorageInterface $storage, ValidatorInterface $validator, PersistentManagerInterface $persistentManager)
    {
        $this->pathGenerator     = $pathGenerator;
        $this->storage           = $storage;
        $this->validator         = $validator;
        $this->persistentManager = $persistentManager;
    }

    /**
     * @param string $fileModelClass
     */
    public function setFileModelClass(string $fileModelClass)
    {
        $this->fileModelClass = $fileModelClass;
    }

    /**
     * @param string $validationModelClass
     */
    public function setValidationModelClass(string $validationModelClass)
    {
        $this->validationModelClass = $validationModelClass;
    }

    /**
     * @param string $path
     *
     * @return FileModelInterface
     */
    public function uploadFromFile(string $path): FileModelInterface
    {
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $mimeType = mime_content_type($path);
        $size     = filesize($path);

        $uploadedFile = new UploadedFile($path, $filename, $mimeType, $size, null, true);

        return $this->uploadFromUploadedFile($uploadedFile);
    }

    /**
     * @param string $base64
     *
     * @return FileModelInterface
     *
     * @throws UploadException
     */
    public function uploadBase64File(string $base64): FileModelInterface
    {
        $data = explode(';', $base64);

        if (2 !== count($data)) {
            throw new UploadException('Invalid data format. Must be "data:mimeType;base64,content"', 400);
        }
        if (0 !== strpos($data[0], 'data')) {
            throw new UploadException('Invalid data format. Must be "data:mimeType;base64,content"', 400);
        }

        $mimeType  = substr($data[0], 5);
        $extension = MimeTypeGuesser::guessExtension($mimeType);

        $fileContentData = explode(',', $data[1]);
        if (2 !== count($fileContentData)) {
            throw new UploadException('Invalid data format. Must be "data:mimeType;base64,content"', 400);
        }
        if ('base64' !== $fileContentData[0]) {
            throw new UploadException('Invalid data format. Must be "data:mimeType;base64,content"', 400);
        }

        $content = base64_decode($fileContentData[1]);
        if (!$content) {
            throw new UploadException();
        }

        $path = $this->createTempFile($extension, $content);
        $size = filesize($path);

        $uploadedFile = new UploadedFile($path, uniqid('', false) . '.' . $extension, $mimeType, $size, null, true);

        $file = $this->uploadFromUploadedFile($uploadedFile);

        $this->deleteTempFile($path);

        return $file;
    }

    /**
     * @param string $url
     *
     * @return FileModelInterface
     *
     * @throws UploadException
     */
    public function uploadFromUrl(string $url): FileModelInterface
    {
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        if (strpos($extension, '?') !== false) {
            $extension = substr($extension, 0, strpos($extension, '?'));
        }

        $filename = pathinfo($url, PATHINFO_FILENAME);
        $content  = $this->getFileContent($url);

        if (!$content) {
            throw new UploadException();
        }

        $path     = $this->createTempFile($extension, $content);
        $mimeType = mime_content_type($path);
        $size     = filesize($path);

        $uploadedFile = new UploadedFile($path, $filename, $mimeType, $size, null, true);

        $file = $this->uploadFromUploadedFile($uploadedFile);

        $this->deleteTempFile($path);

        return $file;
    }

    /**
     * @param UploadedFile $uploadedFile
     *
     * @return FileModelInterface
     *
     * @throws UploadException
     */
    public function uploadFromUploadedFile(UploadedFile $uploadedFile): FileModelInterface
    {
        /** @var ValidationModelInterface $validationObject */
        $validationObject = new $this->validationModelClass; // @codingStandardsIgnoreLine
        $validationObject->setFile($uploadedFile);

        $errors = $this->validator->validate($validationObject);
        if (0 !== count($errors)) {
            throw new UploadException($errors->get(0)->getMessage(), 400);
        }

        $path = $this->pathGenerator->generate($uploadedFile->getExtension() ?: $uploadedFile->getClientOriginalExtension());
        $this->storage->write($path, $this->getFileContent($uploadedFile->getPathname()));

        /** @var FileModelInterface $file */
        $file = new $this->fileModelClass; // @codingStandardsIgnoreLine
        $file->setUploadedAt(new \DateTime());
        $file->setFileName($uploadedFile->getClientOriginalName());
        $file->setSize($uploadedFile->getSize());
        $file->setMimeType($uploadedFile->getMimeType());
        $file->setPath($path);
        $file->setExtension($uploadedFile->getExtension());

        $this->persistentManager->save($file);

        return $file;
    }

    /**
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    /**
     * @param string $extension
     * @param string $content
     *
     * @return string
     *
     * @throws UploadException
     */
    private function createTempFile(string $extension, string $content)
    {
        $tempPath = sprintf('%s.' . $extension, tempnam(sys_get_temp_dir(), 'file_'));
        $handle   = fopen($tempPath, 'wb');

        if (!$handle) {
            throw new UploadException();
        }

        fwrite($handle, $content);
        fclose($handle);

        return $tempPath;
    }

    /**
     * @param string $path
     */
    private function deleteTempFile(string $path)
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * @param string $path
     *
     * @return string|bool
     */
    private function getFileContent(string $path)
    {
        $contextOptions = [
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];

        return file_get_contents($path, null, stream_context_create($contextOptions));
    }
}
