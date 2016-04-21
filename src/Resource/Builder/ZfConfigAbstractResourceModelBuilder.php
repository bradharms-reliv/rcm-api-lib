<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Reliv\RcmApiLib\Resource\Options\Options;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ZfConfigAbstractResourceModelBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
abstract class ZfConfigAbstractResourceModelBuilder
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;

    /**
     * @var Options
     */
    protected $defaultOptions;

    /**
     * @var Options
     */
    protected $resourcesOptions;

    /**
     * ZfConfigResourceModelBuilder constructor.
     *
     * @param array                   $config
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(
        $config,
        ServiceLocatorInterface $serviceManager
    ) {
        $this->serviceManager = $serviceManager;
        $this->defaultOptions = new GenericOptions(
            $config['Reliv\\RcmApiLib']['resource']['default']
        );

        $this->resourcesOptions = new GenericOptions(
            $config['Reliv\\RcmApiLib']['resource']['resources']
        );
    }

    /**
     * getResourceValue
     *
     * @param string $resourceKey
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    protected function getResourceValue($resourceKey, $key, $default = null)
    {
        $resourceOptions = $this->resourcesOptions->getOptions($resourceKey);
        $value = $resourceOptions->get($key, $default);

        return $value;
    }

    /**
     * getDefaultValue
     *
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    protected function getDefaultValue($key, $default = null)
    {
        return $this->defaultOptions->get($key, $default);
    }

    /**
     * buildValue
     *
     * @param string $resourceKey
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    protected function buildValue($resourceKey, $key, $default = null)
    {
        $defaultValue = $this->getDefaultValue($key, $default);

        return $this->getResourceValue($resourceKey, $key, $defaultValue);
    }

    /**
     * buildOptions
     *
     * @param string $resourceKey
     * @param string $key
     *
     * @return GenericOptions
     */
    protected function buildOptions($resourceKey, $key)
    {
        $array = $this->buildValue($resourceKey, $key, []);

        return new GenericOptions($array);
    }
}
