<?php

namespace Youshido\GraphQLFilesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Youshido\GraphQLFilesBundle\Document\File as ODMFile;
use Youshido\GraphQLFilesBundle\Entity\File as ORMFile;
use Youshido\GraphQLFilesBundle\Document\Image as ODMImage;
use Youshido\GraphQLFilesBundle\Entity\Image as ORMImage;
use Youshido\GraphQLFilesBundle\Factory\ImagineFactory;
use Youshido\GraphQLFilesBundle\Model\Validation\FileValidationModel;
use Youshido\GraphQLFilesBundle\Model\Validation\ImageValidationModel;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('graph_ql_files');

        $rootNode
            ->children()
                ->enumNode('image_driver')
                    ->values([
                        ImagineFactory::DRIVER_GD,
                        ImagineFactory::DRIVER_GMAGICK,
                        ImagineFactory::DRIVER_IMAGICK,
                    ])
                    ->cannotBeEmpty()
                    ->defaultValue(ImagineFactory::DRIVER_GD)
                ->end()
                ->enumNode('storage')
                    ->values(['s3', 'local'])->cannotBeEmpty()->defaultValue('local')
                ->end()
                ->enumNode('platform')
                    ->values(['odm', 'orm'])->cannotBeEmpty()->defaultValue('orm')
                ->end()
                ->arrayNode('local')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('web_root')->cannotBeEmpty()->defaultValue('%kernel.root_dir%/../web')->end()
                        ->scalarNode('path_prefix')->cannotBeEmpty()->defaultValue('uploads')->end()
                    ->end()
                ->end()
                    ->arrayNode('s3')
                    ->children()
                        ->scalarNode('client')->cannotBeEmpty()->end()
                        ->scalarNode('bucket')->cannotBeEmpty()->end()
                        ->scalarNode('directory')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('models')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('image_validation_model')->cannotBeEmpty()->defaultValue(ImageValidationModel::class)->end()
                        ->scalarNode('file_validation_model')->cannotBeEmpty()->defaultValue(FileValidationModel::class)->end()
                        ->arrayNode('orm')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('image')->cannotBeEmpty()->defaultValue(ORMImage::class)->end()
                                ->scalarNode('file')->cannotBeEmpty()->defaultValue(ORMFile::class)->end()
                            ->end()
                        ->end()
                        ->arrayNode('odm')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('image')->cannotBeEmpty()->defaultValue(ODMImage::class)->end()
                                ->scalarNode('file')->cannotBeEmpty()->defaultValue(ODMFile::class)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
