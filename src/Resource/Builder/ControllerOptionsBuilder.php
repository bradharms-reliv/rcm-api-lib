<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ResourceOptionsBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ResourceOptionsBuilder extends AbstractResourceBuilder
{
    /**
     * build
     *
     * @param string $resourceControllerKey
     * @param null   $default
     *
     * @return null
     */
    public function build($resourceControllerKey, $default = null)
    {
        $controllerOptions = $this->getOptions($resourceControllerKey);

        $controllerConfig = $controllerOptions->get('controller');

        if (empty($controllerConfig) && empty($controllerConfig['options'])) {
            return $default;
        }

        return $controllerConfig['options'];
    }
}
