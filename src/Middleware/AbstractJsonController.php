<?php

namespace Reliv\RcmApiLib\Middleware;

use Psr\Http\Message\ResponseInterface;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Http\PsrApiResponse;
use Reliv\RcmApiLib\Service\PsrResponseService;

/**
 * Class AbstractJsonController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
abstract class AbstractJsonController
{
    /**
     * @var PsrResponseService
     */
    protected $psrResponseService;

    /**
     * AbstractJsonController constructor.
     *
     * @param PsrResponseService $psrResponseService
     */
    public function __construct(
        PsrResponseService $psrResponseService
    ) {
        $this->psrResponseService = $psrResponseService;
    }

    /**
     * getApiResponse
     *
     * @param ResponseInterface $response
     * @param mixed             $data
     * @param int               $statusCode
     * @param null              $apiMessagesData
     *
     * @return PsrApiResponse|ApiResponseInterface
     */
    protected function getApiResponse(
        ResponseInterface $response,
        $data,
        $statusCode = 200,
        $apiMessagesData = null
    ) {
        return $this->psrResponseService->getPsrApiResponse(
            $response,
            $data,
            $statusCode,
            $apiMessagesData
        );
    }
}
