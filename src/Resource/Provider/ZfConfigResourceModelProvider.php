<?php

namespace Reliv\RcmApiLib\Resource\Provider;

use Reliv\RcmApiLib\Resource\Model\BaseControllerModel;
use Reliv\RcmApiLib\Resource\Model\BaseMethodModel;
use Reliv\RcmApiLib\Resource\Model\BaseResourceModel;
use Reliv\RcmApiLib\Resource\Model\BaseServiceModelCollection;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Reliv\RcmApiLib\Resource\Model\ServiceModelCollection;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ZFConfigResourceModelProvider
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ZfConfigResourceModelProvider extends ZfConfigAbstractResourceModelProvider implements ResourceModelProvider
{
    /**
     * @var array
     */
    protected $resourceModels = [];

    /**
     * getMethodsAllowed
     *
     * @param $resourceKey
     *
     * @return mixed
     */
    protected function getMethodsAllowed($resourceKey)
    {
        return $this->getResourceValue($resourceKey, 'methodsAllowed', []);
    }

    /**
     * buildServiceModelCollection
     *
     * @param array $serviceNames
     * @param array $serviceOptionsArrays
     * @param array $servicePriorities
     *
     * @return BaseServiceModelCollection
     */
    protected function buildServiceModelCollection(
        array $serviceNames,
        array $serviceOptionsArrays,
        array $servicePriorities
    ) {
        $services = [];
        foreach ($serviceNames as $serviceAlias => $serviceName) {
            $services[$serviceAlias] = $this->serviceManager->get($serviceName);
        }

        $serviceOptions = [];
        foreach ($serviceOptionsArrays as $serviceAlias => $serviceOptionsArray) {
            $serviceOptions[$serviceAlias] = new GenericOptions($serviceOptionsArray);
        }

        return new BaseServiceModelCollection(
            $services,
            $serviceOptions,
            $servicePriorities
        );
    }

    /**
     * buildMethodModels
     *
     * @param string $resourceKey
     *
     * @return array
     */
    protected function buildMethodModels($resourceKey)
    {
        $methods = $this->getDefaultValue('methods', []);
        $userMethods = $this->getResourceValue($resourceKey, 'methods', []);

        $customMethods = [];

        foreach ($userMethods as $key => $method) {
            if (array_key_exists($key, $methods)) {
                $methods[$key] = array_merge_recursive($methods[$key], $method);
            } else {
                $customMethods[$key] = $method;
            }
        }

        $methods = array_merge($customMethods, $methods);

        $returnMethods = [];

        foreach ($methods as $key => $method) {
            $methodOptions = new GenericOptions($method);

            $preServiceModel = $this->buildServiceModelCollection(
                $methodOptions->get('preServiceNames', []),
                $methodOptions->get('preServiceOptions', []),
                $methodOptions->get('preServicePriority', [])
            );

            $postServiceModel = $this->buildServiceModelCollection(
                $methodOptions->get('postServiceNames', []),
                $methodOptions->get('postServiceOptions', []),
                $methodOptions->get('postServicePriority', [])
            );

            $options = $methodOptions->getOptions('options');

            $name = $methodOptions->get('name', $key);

            $returnMethods[$name] = new BaseMethodModel(
                $name,
                $methodOptions->get('description'),
                $methodOptions->get('httpVerb'),
                $methodOptions->get('path'),
                $preServiceModel,
                $postServiceModel,
                $options
            );
        }

        return $returnMethods;
    }

    /**
     * buildMethodPriorities
     *
     * @param $resourceKey
     *
     * @return mixed
     */
    public function getMethodPriorities($resourceKey)
    {
        $defaultMethodPriorities = $this->getDefaultValue('methodPriority', []);
        $userMethodPriorities = $this->getResourceValue($resourceKey, 'methodPriority', []);

        return array_merge($defaultMethodPriorities, $userMethodPriorities);
    }

    /**
     * get
     *
     * @param string $resourceKey
     *
     * @return ResourceModel
     */
    public function get($resourceKey)
    {
        if (array_key_exists($resourceKey, $this->resourceModels)) {
            return $this->resourceModels[$resourceKey];
        }

        // ControllerModel
        $controllerServiceAlias = $this->buildValue(
            $resourceKey,
            'controllerServiceName',
            'UNDEFINED'
        );

        $controllerService = $this->serviceManager->get(
            $controllerServiceAlias
        );

        $controllerOptions = $this->buildOptions(
            $resourceKey,
            'controllerServiceOptions'
        );

        $controllerModel = new BaseControllerModel(
            $controllerServiceAlias,
            $controllerService,
            $controllerOptions
        );

        // Methods
        $methodsAllowed = $this->getMethodsAllowed($resourceKey);
        $methodPriorities = $this->getMethodPriorities($resourceKey);
        $methodModels = $this->buildMethodModels($resourceKey);
        $path = $this->getResourceValue($resourceKey, 'path');

        $preServiceModel = $this->buildServiceModelCollection(
            $this->buildMergeValue($resourceKey, 'preServiceNames', []),
            $this->buildMergeValue($resourceKey, 'preServiceOptions', []),
            $this->buildMergeValue($resourceKey, 'preServicePriority', [])
        );

        $postServiceModel = $this->buildServiceModelCollection(
            $this->buildMergeValue($resourceKey, 'postServiceNames', []),
            $this->buildMergeValue($resourceKey, 'postServiceOptions', []),
            $this->buildMergeValue($resourceKey, 'postServicePriority', [])
        );

        $options = $this->buildOptions($resourceKey, 'options');

        $resourceModel = new BaseResourceModel(
            $controllerModel,
            $methodsAllowed,
            $methodModels,
            $methodPriorities,
            $path,
            $preServiceModel,
            $postServiceModel,
            $options
        );

        $this->resourceModels[$resourceKey] = $resourceModel;

        return $this->resourceModels[$resourceKey];
    }
}
