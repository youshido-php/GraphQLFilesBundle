<?php

namespace Youshido\GraphQLFilesBundle\DependencyInjection\CompillerPass;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Youshido\GraphQLFilesBundle\Service\PersistentManager\ODM\PersistentManager as ODMPersistentManager;
use Youshido\GraphQLFilesBundle\Service\PersistentManager\ORM\PersistentManager as ORMPersistentManager;
use Youshido\GraphQLFilesBundle\Service\Storage\AmazonS3Storage;
use Youshido\GraphQLFilesBundle\Service\Storage\LocalFileSystemStorage;

/**
 * Class ConfigurationCompilerPass
 */
class ConfigurationCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     *
     * @param ContainerBuilder $container
     *
     * @throws InvalidConfigurationException
     */
    public function process(ContainerBuilder $container)
    {
        $storageDefinition = new Definition();
        $storage           = $container->getParameter('graphql_files.storage');
        $config            = $container->getParameter('graphql_files.storage_config');
        if ('s3' === $storage) {
            if (!$container->hasDefinition($config['client'])) {
                throw new InvalidConfigurationException(sprintf('Service "%s" not found', $config['client']));
            }

            $storageDefinition->setClass(AmazonS3Storage::class);
            $storageDefinition->setArguments([new Reference($config['client']), $config['bucket'], $config['directory']]);
        } else {
            $storageDefinition->setClass(LocalFileSystemStorage::class);
            $storageDefinition->setArguments([$config['web_root'], $config['path_prefix'], new Reference('graphql_files.request_context')]);
        }

        $container->setDefinition('graphql_files.storage', $storageDefinition);

        $managerDefinition = new Definition();
        if ('orm' === $container->getParameter('graphql_files.platform')) {
            $managerDefinition->setClass(ORMPersistentManager::class);
            $managerDefinition->setArguments([new Reference('doctrine.orm.entity_manager')]);
        } else {
            $managerDefinition->setClass(ODMPersistentManager::class);
            $managerDefinition->setArguments([new Reference('doctrine.odm.mongodb.document_manager')]);
        }

        $container->setDefinition('graphql_files.persistent_manager', $managerDefinition);
    }
}
