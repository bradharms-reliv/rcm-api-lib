<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Api\Translate\OptionsTranslate;
use Reliv\RcmApiLib\Api\Translate\Translate;
use Reliv\RcmApiLib\Model\ApiMessage;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithTranslatedApiMessageBasic implements WithTranslatedApiMessage
{
    /**
     * @var Translate
     */
    protected $translate;

    /**
     * @param Translate $translate
     */
    public function __construct(
        Translate $translate
    ) {
        $this->translate = $translate;
    }

    /**
     * @param ApiMessage $apiMessage
     * @param array      $optionsTranslate
     *
     * @return ApiMessage
     */
    public function __invoke(
        ApiMessage $apiMessage,
        array $optionsTranslate = []
    ): ApiMessage {
        $optionsTranslate[OptionsTranslate::OPTIONS_PARAMS] = $this->buildStringParams(
            $apiMessage->getParams()
        );

        $apiMessage->setValue(
            $this->translate->__invoke(
                $apiMessage->getValue(),
                $optionsTranslate
            )
        );

        return $apiMessage;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function buildStringParams(array $params = [])
    {
        $stringParams = [];

        foreach ($params as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                $stringParams[$key] = $value;
                continue;
            }
            $stringParams[$key] = json_encode($value);
        }

        return $stringParams;
    }
}
