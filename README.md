Rcm Api Lib
====================

API basic response library making use of 
Middleware (like Zend Expressive) OR ZF2's MVC framework

Makes it easy to quickly create a common API data format.

Include a JavaScript (Angular.JS) library for the client.

##### Several types of common error message types are supported: #####

- Array
- Exception
- HttpStatusCode
- InputFilter (Zend)
- String
- Custom types can also be injected


### EXAMPLE: Middleware (like Zend Expressive) ###
        
```php
// From a Middleware that extends Reliv\RcmApiLib\Middleware\AbstractJsonController

    /** EXAMPLE: InputFilter (Zend)  **/
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) {
        $inputFilter = new RcmGoogleAnalyticsFilter();

        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return $this->getApiResponse(
                [],
                400,
                $inputFilter
            );
        }
    }
```

##### Returns something like: #####
    
```json
{
  "data": [],
  "messages": [
    {
      "type": "inputFilter",
      "source": "validation",
      "code": "error",
      "value": "Some information was missing or invalid on the form. Please check the form and try again.",
      "primary": true,
      "params": [],
      "key": "inputFilter.validation.error"
    },
    {
      "type": "validatorMessage",
      "source": "my-value",
      "code": "isEmpty",
      "value": "Value is required and can't be empty",
      "primary": null,
      "params": [],
      "key": "validatorMessage.my-value.isEmpty"
    }
  ]
} 
```
    
    
    
```php    
    /** EXAMPLE: General **/
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) {
        return $this->getApiResponse(
            ['my' => 'response'],
            400,
            new ApiMessage(
                'my-type',
                'my-message-value {my-param}',
                'my-source',
                'my-code',
                true,
                ['my-param' => 'my-value']
            )
        );
    }

```

### EXAMPLE: Zend Framework ###

```php
// From a ZF2 Controller that extends \Reliv\RcmApiLib\Controller\AbstractRestfulJsonController
// @see \Reliv\RcmApiLib\Controller\ExampleRestfulJsonController

    // Add exception message
    $this->addApiMessage(
        new \Exception('Some exception')
    );

    // Add generic message as array
    $this->addApiMessage(
        [
            'key' => 'ArrayError',
            'value' => 'Some {param} Message',
            'primary' => true,
            'type' => 'Array',
            'code' => 'mycode',
            'params' => ['param' => 'array message']
        ]
    );

    // Add generic message as object
    $this->addApiMessage(
        new ApiMessage('MYKEY', 'Some Message')
    );
    
    // Add HTTP sttus message
    $this->addApiMessage(
        new HttpStatusCodeApiMessage(403)
    );

    // Add inputFilter message
    $inputFilter = new \Zend\InputFilter\InputFilter(); // Use you own inputFilter here
    $this->addApiMessage(
        $inputFilter
    );
    
    // Return the response
    return $this->getApiResponse(
        null,
        $statusCode = 200,
        $inputFilter,
        true
    );
    
    // Return the response with your data and no messages
    return $this->getApiResponse(
        ['myThing' => 'someThing'],
    );
    
```

### Author: ###
James Jervis
jjervis@relivinc.com
Copyright (c) 2015, Reliv' International, Inc.
https://github.com/reliv/rcm-api-lib
