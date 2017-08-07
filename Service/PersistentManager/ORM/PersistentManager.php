<?php

namespace Youshido\GraphQLFilesBundle\Service\PersistentManager\ORM;

use Doctrine\ORM\EntityManager;
use Youshido\GraphQLFilesBundle\Model\FileModelInterface;
use Youshido\GraphQLFilesBundle\Service\PersistentManager\PersistentManagerInterface;

/**
 * Class PersistentManager
 */
class PersistentManager implements PersistentManagerInterface
{
    /** @var  EntityManager */
    private $em;

    /**
     * PersistentManager constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $class
     * @param string $id
     *
     * @return FileModelInterface
     */
    public function get(string $class, string $id): FileModelInterface
    {
        return $this->em->getRepository($class)->find($id);
    }

    /**
     * @param FileModelInterface $file
     *
     * @return void
     */
    public function save(FileModelInterface $file)
    {
        $this->em->persist($file);
        $this->em->flush($file);
    }
}
