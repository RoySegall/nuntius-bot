Now that you know what is a Webhook you know that you need to set an endpoint.

Before we begin let's clear something - the endpoint for the incoming webhooks
does not mean for any thing else rather than getting information from external
services. i.e - setting an endpoint to display a list of entities is a bad
practice.

## Adding a routing
As always, let's go to the `hooks.yml` file:
```yml
webhooks_routing:
  'github': '\Nuntius\WebhooksRounting\GitHub'
```

The key of the item, in this case, `github`, will be the endpoint. i.e: 
`http://address.com/github`

## The router controller

You set the endpoint, let's see how the controller should look:
```php
<?php

/**
 * Handling incoming webhooks from GitHub.
 */
class GitHub implements WebhooksRoutingControllerInterface {

  /**
   * {@inheritdoc}
   */
  public function response(Request $request) {
    
    // Logic here.
    
    return new JsonResponse(['type' => 'success', 'message' => 'The request has been processed.']);
  }

}
```

The controller need to implement the response method and do the logic over 
there: Maybe dispatch events, insert something to DB or just send a PM on slack.

In this, you can see the controller return a `JsonResponse` but you can return 
any `Response` object. As long as you return an object because:
1. The service that sent the webhook might think the request failed if a 2XX or 
a 5XX response was return.
2. Symfony will fail the page of a `Response` type won't returned.
