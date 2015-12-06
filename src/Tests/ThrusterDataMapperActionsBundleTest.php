<?php

namespace Thruster\Bundle\DataMapperActionsBundle\Tests;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Thruster\Bundle\DataMapperActionsBundle\ThrusterDataMapperActionsBundle;

/**
 * Class ThrusterDataMapperActionsBundleTest
 *
 * @package Thruster\Bundle\DataMapperActionsBundle\Tests
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class ThrusterDataMapperActionsBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testAddCompilerPass()
    {
        $builderMock = $this->getMock('\Symfony\Component\DependencyInjection\ContainerBuilder');
        $builderMock->expects($this->once())
            ->method('addCompilerPass')
            ->will(
                $this->returnCallback(
                    function ($compilerPass) use ($builderMock) {
                        $this->assertInstanceOf(
                            '\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface',
                            $compilerPass
                        );
                        $compilerPass->process($builderMock);
                    }
                )
            );
        $builderMock->expects($this->once())
            ->method('setDefinition')
            ->will(
                $this->returnCallback(
                    function ($id, $definition) {
                        /** @var Definition $definition */
                        $this->assertSame('thruster_data_mapper_actions.map.executor', $id);
                        $this->assertInstanceOf(
                            '\Symfony\Component\DependencyInjection\Definition',
                            $definition
                        );
                        $this->assertSame(
                            'Thruster\Component\DataMapper\DataMappers',
                            $definition->getClass()
                        );
                        /** @var Reference $reference */
                        $reference = $definition->getArgument(0);
                        $this->assertSame('thruster_data_mappers', (string) $reference);
                        $this->assertEquals(['thruster_action_executor' => [[]]], $definition->getTags());
                    }
                )
            );
        $bundle = new ThrusterDataMapperActionsBundle();
        $bundle->build($builderMock);
    }
}
