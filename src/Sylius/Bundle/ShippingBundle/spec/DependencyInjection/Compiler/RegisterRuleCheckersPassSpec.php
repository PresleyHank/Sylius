<?php

namespace spec\Sylius\Bundle\ShippingBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterRuleCheckersPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ShippingBundle\DependencyInjection\Compiler\RegisterRuleCheckersPass');
    }

    function it_is_a_compiler_pass()
    {
        $this->shouldImplement('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface');
    }

    function it_processes_the_calculators_services(ContainerBuilder $container, Definition $ruleChecker)
    {
        $container->hasDefinition('sylius.shipping_rule_checker_registry')->shouldBeCalled()->willReturn(true);
        $container->getDefinition('sylius.shipping_rule_checker_registry')->shouldBeCalled()->willReturn($ruleChecker);

        $container->findTaggedServiceIds('sylius.shipping_rule_checker')->shouldBeCalled()->willReturn(array(
            'rule_checker_id' => array(
                array(
                    'type' => 'rule_checker_name',
                    'label' => 'rule_checker_label',
                )
            )
        ));

        $ruleChecker->addMethodCall(
            'registerChecker',
            Argument::type('array')
        )->shouldBeCalled();

        $container->setParameter(
            'sylius.shipping_rules',
            array('rule_checker_name' => 'rule_checker_label')
        )->shouldBeCalled();

        $this->process($container);
    }
}
