<?php

namespace Thruster\Bundle\DataMapperActionsBundle;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ThrusterDataMapperActionsBundle
 *
 * @package Thruster\Bundle\DataMapperActionsBundle
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class ThrusterDataMapperActionsBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new class implements CompilerPassInterface
            {
                /**
                 * @inheritDoc
                 */
                public function process(ContainerBuilder $container)
                {
                    $executorId         = 'thruster_data_mapper_actions.map.executor';
                    $executorDefinition = new Definition(
                        'Thruster\Component\DataMapper\DataMappers',
                        [new Reference('thruster_data_mappers')]
                    );

                    $executorDefinition->addTag('thruster_action_executor', []);
                    $container->setDefinition($executorId, $executorDefinition);
                }
            }
        );
    }

}
