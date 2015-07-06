<?php

namespace Reliv\RcmApiLib\Model;

use Reliv\RcmApiLib\Http\ApiResponse;

/**
 * Class ApiMessage
 *
 * API message format
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Message
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiMessage extends AbstractApiModel
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $source = null;

    /**
     * @var string|null
     */
    protected $code = null;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var bool Is this the primary error
     */
    protected $primary = null;

    /**
     * @var array
     */
    protected $params = [];


    /**
     * @param string $type
     * @param string $value
     * @param null   $source
     * @param null   $code
     * @param null   $primary
     * @param array  $params
     */
    public function __construct(
        $type,
        $value = '',
        $source = null,
        $code = null,
        $primary = null,
        $params = []
    ) {
        $this->setType($type);
        $this->setValue($value);
        $this->setSource($source);
        $this->setCode($code);
        $this->setPrimary($primary);
        $this->setParams($params);
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * setType
     *
     * @param string $type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->type = (string)$type;
    }

    /**
     * getSource
     *
     * @return null|string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * setSource
     *
     * @param string $source
     *
     * @return void
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * getCode
     *
     * @return null|string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * setCode
     *
     * @param string $code
     *
     * @return void
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * isPrimary
     *
     * @return bool
     */
    public function isPrimary()
    {
        return (bool)$this->primary;
    }

    /**
     * getPrimary
     *
     * @return bool|null
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * setPrimary
     *
     * @param boolean $primary
     *
     * @return void
     */
    public function setPrimary($primary = true)
    {
        $this->primary = $primary;
    }

    /**
     * getKey
     *
     * @return string
     */
    public function getKey()
    {
        $key = lcfirst((string)$this->type);

        if ($this->source !== null) {
            $key .= '.' . lcfirst((string)$this->source);
        }

        if ($this->code !== null) {
            $key .= '.' . lcfirst((string)$this->code);
        }

        return $key;
    }

    /**
     * getValue
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * setValue
     *
     * @param $value
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->value = (string)$value;
    }

    /**
     * getParams
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * setParams
     *
     * @param $params
     *
     * @return void
     */
    public function setParams($params)
    {
        $this->params = (array)$params;
    }

    /**
     * getParam
     *
     * @param string $key
     * @param null   $default
     *
     * @return null|mixed
     */
    public function getParam($key, $default = null)
    {
        $key = (string)$key;
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }

        return $default;
    }

    /**
     * setParam
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function setParam($key, $value)
    {
        $key = (string)$key;
        $this->params[$key] = $value;
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['key'] = $this->getKey();

        return $array;
    }
}
