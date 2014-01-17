<?php
/**
 * Created by PhpStorm.
 * User: yeugone
 * Date: 17/01/14
 * Time: 14:35
 */

namespace WillCorp\ZombieBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ProcessorCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('will_corp_zombie.game.processor.chain_collector')) {
            return;
        }

        $definition = $container->getDefinition('will_corp_zombie.game.processor.chain_collector');

        $taggedServices = $container->findTaggedServiceIds('will_corp_zombie.game.collector');
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addCollector',
                array($attributes[0]['alias'], new Reference($id))
            );
        }
    }
}