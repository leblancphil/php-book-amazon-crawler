<?php

declare(strict_types=1);

namespace Src;

/**
 * A class containing the response body and code.
 */
class Response
{

  /**
   * @var string
   */
  private $body;

  /**
   * @var int
   */
  private $code;

  function __construct(int $code = null, string $body = null)
  {
    $this->code = $code;
    $this->body = $body;
  }

  public function getBody(): ?string
  {
    return $this->body;
  }

  public function getCode(): int
  {
    return $this->code;
  }

  public function hasBody(): bool
  {
    return $this->body != null;
  }

  public function isBodyNull(): bool
  {
    return $this->body == null;
  }

  public function __toString(): String
  {
    $containsBody = $this->hasBody() ? 'true' : 'false';

    return "code = $this->code, containsBody = $containsBody";
  }
}
