<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Exception\ServiceMissingException;
use Reliv\RcmApiLib\Resource\Middleware\Middleware;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class ServiceModelCollection
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
interface ServiceModelCollection
{
    /**
     * getServices
     *
     * @return array ['{serviceAlias}' => {Middleware}]
     */
    public function getServices();

    /**
     * getService
     *
     * @param string $serviceAlias
     *
     * @return Middleware
     * @throws ServiceMissingException
     */
    public function getService($serviceAlias);

    /**
     * hasService
     *
     * @param string $serviceAlias
     *
     * @return bool
     */
    public function hasService($serviceAlias);

    /**
     * getOptions
     *
     * @param string $serviceAlias
     *
     * @return Options
     */
    public function getOptions($serviceAlias);

    /**
     * getPriority
     *
     * @param string $serviceAlias
     * 
     * @return int
     */
    public function getPriority($serviceAlias);
}
