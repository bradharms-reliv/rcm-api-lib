<?php

namespace Reliv\RcmApiLib\Factory;

use Reliv\RcmApiLib\Hydrator\CompositeApiMessagesHydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CompositeApiMessageHydratorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $composite = new CompositeApiMessagesHydrator();
        $config = $serviceLocator->get('config');
        foreach ($config['Reliv\\RcmApiLib']['CompositeApiMessageHydrators'] as $hydratorService) {
            $composite->add($serviceLocator->get($hydratorService));
        }
        return $composite;
    }
}
