<?php

namespace Youshido\GraphQLFilesBundle\Service\PersistentManager\ODM;

use Doctrine\ODM\MongoDB\DocumentManager;
use Youshido\GraphQLFilesBundle\Exception\ObjectNotFoundException;
use Youshido\GraphQLFilesBundle\Model\FileModelInterface;
use Youshido\GraphQLFilesBundle\Service\PersistentManager\PersistentManagerInterface;

/**
 * Class PersistentManager
 */
class PersistentManager implements PersistentManagerInterface
{
    /** @var  DocumentManager */
    private $dm;

    /**
     * PersistentManager constructor.
     *
     * @param DocumentManager $dm
     */
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * @param string $class
     * @param string $id
     *
     * @return FileModelInterface
     *
     * @throws ObjectNotFoundException
     */
    public function get(string $class, string $id): FileModelInterface
    {
        /** @var FileModelInterface $object */
        $object = $this->dm->getRepository($class)->find($id);

        if (!$object) {
            throw new ObjectNotFoundException();
        }

        return $object;
    }

    /**
     * @param FileModelInterface $file
     *
     * @return void
     */
    public function save(FileModelInterface $file)
    {
        $this->dm->persist($file);
        $this->dm->flush($file);
    }
}
