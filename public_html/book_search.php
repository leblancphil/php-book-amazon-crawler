<?php

require '../vendor/autoload.php';

use Src\Search\BookSearchAmazon;
use Src\Singleton\Logger;

// -------------------------------------------------------------------

$bookName = filter_input(INPUT_GET, 'bookName', FILTER_SANITIZE_STRING);

$bookSearchAmazon = new BookSearchAmazon($bookName);
$bookSearchAmazon->execute();
$bookSearchResponse = $bookSearchAmazon->getResponse();

Logger::get()->info('Result.', ['search' => $bookName, 'result' => $bookSearchResponse->__toString()]);

// -------------------------------------------------------------------

header('Content-type: application/json');
http_response_code($bookSearchResponse->getCode());

if ($bookSearchResponse->hasBody()) {
  echo ($bookSearchResponse->getBody());
}
