<?php


namespace Reliv\RcmApiLib\Hydrator;

use Reliv\RcmApiLib\Exception\ApiMessagesHydratorException;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;
use Reliv\RcmApiLib\Model\ExceptionApiMessage;
use Reliv\RcmApiLib\Model\StringApiMessage;

/**
 * Class StringApiMessagesHydrator
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\Hydrator\Http
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class StringApiMessagesHydrator implements ApiMessagesHydratorInterface
{
    /**
     * hydrate
     *
     * @param mixed       $data
     * @param ApiMessages $apiMessages
     *
     * @return ApiMessages
     * @throws ApiMessagesHydratorException
     */
    public function hydrate($data, ApiMessages $apiMessages)
    {
        if (!is_string($data)) {
            throw new ApiMessagesHydratorException(
                get_class($this) . ' cannot hydrate this data type'
            );
        }

        $apiMessage = new StringApiMessage(
            $data
        );

        $apiMessages->add($apiMessage);

        return $apiMessages;
    }
}