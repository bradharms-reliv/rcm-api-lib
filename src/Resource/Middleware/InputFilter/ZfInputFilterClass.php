<?php

namespace Reliv\RcmApiLib\Resource\Middleware\InputFilter;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Reliv\RcmApiLib\Model\InputFilterApiMessages;
use Reliv\RcmApiLib\Resource\Middleware\AbstractMiddleware;
use Reliv\RcmApiLib\Resource\Middleware\Middleware;
use Zend\InputFilter\InputFilter;

/**
 * Class ZfInputFilterClass
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ZfInputFilterClass extends AbstractMiddleware implements Middleware
{
    /**
     * __invoke
     *
     * @param Request       $request
     * @param Response      $response
     * @param callable|null $out
     *
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $filterClass = $this->getOption($request, 'class', '');

        /** @var InputFilter $inputFilter */
        $inputFilter = new $filterClass();

        $dataModel = $this->getRequestData($request, []);

        $inputFilter->setData($dataModel);

        if($inputFilter->isValid()) {
            return $out($request, $response);
        }

        $messages = new InputFilterApiMessages(
            $inputFilter,
            $this->getOption($request, 'primaryMessage', 'An Error Occurred'),
            $this->getOption($request, 'messageParams', [])
        );

        return $this->withDataResponse($response, $messages);
    }
}
