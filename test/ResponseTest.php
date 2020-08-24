<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\Response;

use function PHPUnit\Framework\assertIsInt;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\containsEqual;
use function PHPUnit\Framework\containsIdentical;
use function PHPUnit\Framework\containsOnly;
use function PHPUnit\Framework\equalTo;
use function PHPUnit\Framework\isFalse;
use function PHPUnit\Framework\isJson;
use function PHPUnit\Framework\isNull;
use function PHPUnit\Framework\isTrue;
use function PHPUnit\Framework\isType;

class ResponseTest extends TestCase
{

  /** @test */
  public function isCorrect_response200WithBody()
  {
    $data = [
      [
        'id' => 'someId',
        'name' => 'someName',
        'author' => 'someAuthor',
        'imageSrc' => 'someImageSrc'
      ]
    ];

    $response = new Response(200, json_encode($data));

    assertThat($response->getCode(), equalTo(200));
    assertNotNull($response->getBody());
    assertThat($response->getBody(), isJson());
    assertThat($response->hasBody(), isTrue());
    assertThat($response->isBodyNull(), isFalse());
  }

  /** @test */
  public function isCorrect_response204Empty()
  {
    $response = new Response(204);

    assertThat($response->getCode(), equalTo(204));
    assertThat($response->getBody(), isNull());
    assertThat($response->hasBody(), isFalse());
    assertThat($response->isBodyNull(), isTrue());
  }

  /** @test */
  public function isCorrect_response404MayContainBody()
  {
    $data = ['info' => 'Error while searching', 'error_message' => 'Some error message'];

    $response = new Response(404, json_encode($data));

    assertThat($response->getCode(), equalTo(404));
    assertThat($response->getBody(), isJson());
  }

  /** @test */
  public function isCorrect_toString()
  {
    $response = new Response(204);

    assertThat(strpos($response->__toString(), 'code = 204, containsBody = false'), isType('int'));
  }
}
