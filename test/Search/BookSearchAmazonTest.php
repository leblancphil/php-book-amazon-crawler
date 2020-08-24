<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\Search\BookSearchAmazon;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\equalTo;
use function PHPUnit\Framework\isFalse;
use function PHPUnit\Framework\isJson;
use function PHPUnit\Framework\isNull;
use function PHPUnit\Framework\isTrue;

class BookSearchAmazonTest extends TestCase
{

  /** @test */
  public function isSuccessfull_searchWithResult()
  {
    $bookName = 'Dominando o Android';
    $bookSearchAmazon = new BookSearchAmazon($bookName);

    // Confirm that executed successfully.
    assertThat($bookSearchAmazon->execute(), isTrue());

    // Get the response object.
    $response = $bookSearchAmazon->getResponse();

    // Confirm the response code is 200.
    assertThat($response->getCode(), equalTo(200));

    // Confirm that has body.
    assertThat($response->hasBody(), isTrue());

    // Confirm that the body is JSON.
    assertThat($response->getBody(), isJson());
  }

  /** @test */
  public function isSuccessfull_searchWithEmptyResult()
  {
    $bookName = 'ansuabsiaub uiab ug eg78etg 4gt784wg 8eg 78w';
    $bookSearchAmazon = new BookSearchAmazon($bookName);

    // Confirm that executed successfully.
    assertTrue($bookSearchAmazon->execute());

    // Get the response.
    $response = $bookSearchAmazon->getResponse();

    // Confirm there is a response.
    assertNotNull($response);

    // Confirm the response code is 204 and has empty body.
    assertThat($response->getCode(), equalTo(204));
    assertThat($response->hasBody(), isFalse());
  }

  /** @test */
  public function isSuccessfull_searchEmpty()
  {
    $bookName = null;
    $bookSearchAmazon = new BookSearchAmazon($bookName);

    // Confirm that executed successfully.
    assertTrue($bookSearchAmazon->execute());

    // Get the response.
    $response = $bookSearchAmazon->getResponse();

    // Confirm there is a response.
    assertNotNull($response);

    // Confirm the response code is 204 and has empty body.
    assertThat($response->getCode(), equalTo(204));
    assertThat($response->hasBody(), isFalse());
  }

  /** @test */
  public function isFailure_search()
  {
    $bookName = '#$a?@d&*';
    $bookSearchAmazon = new BookSearchAmazon($bookName);

    // Confirm that failed to execute.
    assertThat($bookSearchAmazon->execute(), isFalse());

    // Get the response object.
    $response = $bookSearchAmazon->getResponse();

    // Confirm response code is 404.
    assertThat($response->getCode(), equalTo(404));

    // Confirm there is a body with error info.
    assertTrue($response->hasBody());

    // Confirm the response body is a JSON.
    assertThat($response->getBody(), isJson());
  }
}
