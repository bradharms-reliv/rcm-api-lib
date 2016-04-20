<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Exception\ServiceMissingException;
use Reliv\RcmApiLib\Resource\Middleware\Middleware;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class AbstractServiceModel
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Resource\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class AbstractServiceModel
{
    /**
     * @var string
     */
    protected $alias;

    /**
     * @var Middleware compatible
     */
    protected $service;

    /**
     * @var Options
     */
    protected $serviceOptions;

    /**
     * AbstractServiceModel constructor.
     *
     * @param string        $alias
     * @param object        $service
     * @param Options $serviceOptions
     */
    public function __construct(
        $alias,
        $service,
        Options $serviceOptions
    ) {
        $this->alias = $alias;
        $this->service = $service;
        $this->serviceOptions = $serviceOptions;
    }

    /**
     * getAlias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * getService
     *
     * @return object Middleware compatible
     * @throws ServiceMissingException
     */
    public function getService()
    {
        if (empty($this->service)) {
            throw new ServiceMissingException('Service not set');
        }

        return $this->service;
    }

    /**
     * getPreOptions
     *
     * @return Options
     */
    public function getOptions()
    {
        return $this->serviceOptions;
    }
}
