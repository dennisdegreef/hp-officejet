# dennisdegreef/hp-officejet

This library automates the HP OfficeJet J6400-series Web Scanning.

# Usage
```
<?php

require_once 'vendor/autoload.php';

$guzzle = new GuzzleHttp\Client([
    'base_uri' => 'http://192.168.0.2/', // IP or hostname of the OfficeJet
]);
$httpClient = new \Link0\Hp\OfficeJet\HttpClient\GuzzleHttpClient($guzzle);
$scanner = new \Link0\Hp\OfficeJet\Scanner($httpClient);

$bytesWritten = file_put_contents('preview.jpg', $scanner->scanPreview());
var_dump($bytesWritten);

$bytesWritten = file_put_contents('scan.jpg', $scanner->scan());
var_dump($bytesWritten);
```

# License
[MIT](https://github.com/dennisdegreef/hp-officejet/master/LICENSE)
