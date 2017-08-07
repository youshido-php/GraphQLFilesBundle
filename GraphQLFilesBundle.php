<?php

namespace Youshido\GraphQLFilesBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Youshido\GraphQLFilesBundle\DependencyInjection\CompillerPass\ConfigurationCompilerPass;

/**
 * Class GraphQLFilesBundle
 */
class GraphQLFilesBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ConfigurationCompilerPass());
    }
}
