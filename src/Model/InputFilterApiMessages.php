<?php

namespace Reliv\RcmApiLib\Model;

use Reliv\RcmApiLib\InputFilter\MessageParamInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputInterface;

/**
 * Class InputFilterApiMessages
 *
 * InputFilterApiMessages
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class InputFilterApiMessages extends ApiMessages
{
    /**
     * @var string
     */
    protected $primaryType = 'inputFilter';

    /**
     * @var string
     */
    protected $primaryMessage = 'An Error Occurred';

    /**
     * @var string
     */
    protected $primarySource = 'validation';

    /**
     * @var string
     */
    protected $primaryCode = 'error';

    /**
     * @param InputFilterInterface $inputFilter
     * @param string               $primaryMessage
     * @param array                $params
     */
    public function __construct(
        InputFilterInterface $inputFilter,
        $primaryMessage = 'An Error Occurred',
        $params = []
    ) {
        $this->primaryMessage = $primaryMessage;
        $this->build($inputFilter, $params);
    }

    /**
     * build
     *
     * @param InputFilterInterface $inputFilter
     * @param array                $params
     *
     * @return void
     */
    public function build(InputFilterInterface $inputFilter, $params = [])
    {

        if ($inputFilter instanceof MessageParamInterface) {
            $params = array_merge($inputFilter->getMessageParams(), $params);
        }

        $primaryApiMessage = new ApiMessage(
            $this->primaryType,
            $this->primaryMessage,
            $this->primarySource,
            $this->primaryCode,
            true,
            $params
        );

        $this->add($primaryApiMessage);

        $this->parseInputs($inputFilter);
    }

    /**
     * parseInputs
     *
     * @param        $input
     * @param string $name
     *
     * @return void
     */
    protected function parseInputs($input, $name = '')
    {
        if ($input instanceof InputFilterInterface) {
            $inputs = $input->getInvalidInput();

            foreach ($inputs as $key => $subinput) {
                $fieldName = $this->getParseName($name, $key, $subinput);
                $this->parseInputs($subinput, $fieldName);
            }

            return;
        }

        $this->buildValidatorMessages($name, $input);
    }

    /**
     * getParseName
     *
     * @param $name
     * @param $key
     * @param $subinput
     *
     * @return string
     */
    protected function getParseName($name, $key, $subinput)
    {
        $fieldName = $key;
        if (method_exists($subinput, 'getName')) {
            $fieldName = $subinput->getName();
        }
        if (!empty($name)) {
            $fieldName = $name . '-' . $fieldName;
        }

        return $fieldName;
    }

    /**
     * buildValidatorMessages
     *
     * @param                $fieldName
     * @param InputInterface $input
     *
     * @return void
     */
    protected function buildValidatorMessages(
        $fieldName,
        InputInterface $input
    ) {
        $validatorChain = $input->getValidatorChain();
        $validators = $validatorChain->getValidators();

        // We get the input messages because input does validations outside of the validators
        $allMessages = $input->getMessages();

        foreach ($validators as $fkey => $validatorData) {
            /** @var \Zend\Validator\AbstractValidator $validator */
            $validator = $validatorData['instance'];

            $params = [];

            if ($validator instanceof MessageParamInterface) {
                $params = $validator->getMessageParams();
            }

            try {
                $messagesParams = $validator->getOption('messageParams');
                $params = array_merge(
                    $params,
                    $messagesParams
                );
            } catch (\Exception $exception) {
                // Do nothing
            }
            $inputMessages = $validator->getMessages();

            // Remove the messages from $allMessages as we get them from the validators
            $allMessages = array_diff($allMessages, $inputMessages);

            $this->buildApiMessages($fieldName, $inputMessages, $params);
        }

        $params = [];

        if ($input instanceof MessageParamInterface) {
            $params = $input->getMessageParams();
        }

        // get any remaining messages that did not come from validators
        $this->buildApiMessages($fieldName, $allMessages, $params);
    }

    /**
     * buildApiMessages
     *
     * @param string $fieldName
     * @param array  $inputMessages
     * @param array  $params
     *
     * @return ApiMessages
     */
    protected function buildApiMessages(
        $fieldName,
        $inputMessages,
        $params = []
    ) {
        foreach ($inputMessages as $errorKey => $message) {
            ///
            $apiMessage = new ValidatorMessageApiMessage(
                $message,
                $fieldName,
                $errorKey,
                $params,
                null
            );

            $this->add($apiMessage);
        }
    }
}
