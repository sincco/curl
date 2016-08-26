# curl
Simple class for CURL implementation and DOM crawler

##Installation

To add this package as a local, per-project dependency to your project, simply add a dependency on your project's composer.json file. Here is a minimal example of a composer.json file that just defines a dependency on Debug:

```json
{
    "require": {
        "sincco/curl": "dev-master"
    }
}
```


##Use

###Get string for a returned content
```php
use Sincco\Tools\Curl;
$url = 'https://www.amazon.com.mx';
$curl = new \Sincco\Tools\Curl;
$curl->addOption(CURLOPT_CONNECTTIMEOUT, 100);
var_dump($curl->get($url));

```

###Navigate through DOM 
```php
use Sincco\Tools\Curl;
$url = 'https://www.amazon.com.mx';
$curl = new \Sincco\Tools\Curl;
$curl->addOption(CURLOPT_CONNECTTIMEOUT, 100);
$domPage = $curl->getDom($url);
foreach ($domPage->find('div[class=s-item-container] a[class=a-link-normal s-access-detail-page  a-text-normal]') as $item) {
	var_dump($item->href);
}
```

###Consume API
```php
$urlBase = 'http://itron.mx/api/v1/'
$curl = new \Sincco\Tools\Curl;
$curl->addOption(CURLOPT_CONNECTTIMEOUT, 100);
$curl->setMethod('POST');
$params = ['email'=>'user@email.com', 'password'=>'password'];
$token = $curl->getJson($url . 'token', $params);
$curl->setAuthorization($token->token);
$curl->setMethod('GET');
var_dump($curl->getJson($url . 'contratos?filters[contrato]=999999'));
var_dump($curl->getJson($url . 'imagenes/contrato/999999'));
```

#### NOTICE OF LICENSE
This source file is subject to the Open Software License (OSL 3.0) that is available through the world-wide-web at this URL:
http://opensource.org/licenses/osl-3.0.php

**Happy coding!**
- [ivan miranda](http://ivanmiranda.me)
