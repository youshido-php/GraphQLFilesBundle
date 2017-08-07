<?php

namespace Youshido\GraphQLFilesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class GraphQLFilesExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $container->setParameter('graphql_files.image_driver', $config['image_driver']);
        $container->setParameter('graphql_files.storage', $config['storage']);
        $container->setParameter('graphql_files.platform', $config['platform']);

        $container->setParameter('graphql_files.storage_config', $config['local']);
        if ('s3' === $config['storage']) {
            if (!isset($config['s3'])) {
                throw new \InvalidArgumentException('S3 configuration section required');
            }

            $container->setParameter('graphql_files.storage_config', $config['s3']);
        }

        $container->setParameter('graphql_files.image_validation_model', $config['models']['image_validation_model']);
        $container->setParameter('graphql_files.file_validation_model', $config['models']['file_validation_model']);

        $container->setParameter('graphql_files.image_model', $config['models']['orm']['image']);
        $container->setParameter('graphql_files.file_model', $config['models']['orm']['file']);
        if ('odm' === $config['platform']) {
            $container->setParameter('graphql_files.image_model', $config['models']['odm']['image']);
            $container->setParameter('graphql_files.file_model', $config['models']['odm']['file']);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
