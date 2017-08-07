<?php

namespace Youshido\GraphQLFilesBundle\Service\PersistentManager;

use Youshido\GraphQLFilesBundle\Model\FileModelInterface;

/**
 * Class PersistentManagerInterface
 */
interface PersistentManagerInterface
{
    /**
     * @param string $class
     * @param string $id
     *
     * @return FileModelInterface
     */
    public function get(string $class, string $id): FileModelInterface;

    /**
     * @param FileModelInterface $file
     *
     * @return void
     */
    public function save(FileModelInterface $file);
}
