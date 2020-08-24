<?php

declare(strict_types=1);

namespace Src\Search;

use Src\Response;
use Src\Singleton\Logger;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class BookSearchAmazon
{
  const URL = 'https://www.amazon.com.br/s?k=[REPLACE]&i=stripbooks&__mk_pt_BR=ÅMÅŽÕÑ&ref=nb_sb_noss_1';

  const BOOK_RESULTS_FILTER   = '.s-main-slot.s-result-list.s-search-results';

  const BOOK_IMAGE_SRC_FILTER = '.s-image';
  const BOOK_ID_FILTER        = '.s-result-item.s-asin';
  const BOOK_NAME_FILTER      = '.a-section h2';
  const BOOK_AUTHOR_FILTER    = '.a-section .a-row span.a-size-base:nth-child(2)';
  const BOOK_AVAL_FILTER      = '.sg-row:first-child .sg-col-inner .a-section.a-spacing-top-micro > .a-row > span';

  private $url;
  private $bookName;
  private $response;

  function __construct(string $bookName = null)
  {
    Logger::get()->info('Book search requested.', ['search' => $bookName]);

    $this->bookName = str_replace(' ', '+', $bookName); // Replace spaces to plus sign.
    $this->url = str_replace('[REPLACE]', $this->bookName, self::URL); // Insert the book name into URL.
  }

  /**
   * Start the crawler.
   *
   * @return bool True if executed successfully. Else false.
   */
  function execute(): bool
  {
    // Check if has a book name search.
    if ($this->bookName == null || strlen($this->bookName) == 0) {
      $this->response = new Response(204);
      return true;
    }

    $client = new Client();
    $crawler = $client->request('GET', $this->url);

    $response = null;
    $result_array = array();
    $result_json  = null;

    try {

      $searchItems = $crawler->filter(self::BOOK_RESULTS_FILTER)->children();
      $searchItems->each(function (Crawler $bookResult) use (&$result_array) {

        // Check for actual results.
        if ($bookResult->attr('data-uuid') != null) {
          $id     = $bookResult->filter(self::BOOK_ID_FILTER)->attr('data-asin');
          $image  = $bookResult->filter(self::BOOK_IMAGE_SRC_FILTER)->attr('src');
          $name   = $bookResult->filter(self::BOOK_NAME_FILTER)->text();
          $author = $bookResult->filter(self::BOOK_AUTHOR_FILTER)->text();

          // Get the avaliation. On Amazon, it may not contain
          // avaliation. So, only set when it has.
          $aval   = null;

          try {
            $avalRawString = $bookResult->filter(self::BOOK_AVAL_FILTER)->text();
            $avalArray = explode(' ', $avalRawString, 2);
            $aval = $avalArray[0];
          } catch (\InvalidArgumentException $e) {
            // Do something when this book has no avaliation.
            $aval = null;
          }

          $result_array[] = [
            'id'        => $id,
            'image_src' => $image,
            'name'      => $name,
            'author'    => $author,
            'aval'      => $aval
          ];
        }
      });
    } catch (\InvalidArgumentException $e) {
      Logger::get()->error('Error while searching.', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

      // Error while parsing.
      $result_array = [
        'info'          => 'Error while searching',
        'error_message' => $e->getMessage()
      ];
      $response = new Response(404, json_encode($result_array));
    } finally {

      if ($response != null) {
        $this->response = $response;
        return false;
      }

      // If contains empty result, return with response code 204.
      if (count($result_array) == 0) {
        $response = new Response(204);
      }
      // Else, with 200 and content.
      else {
        $result_json = json_encode($result_array);
        $response = new Response(200, $result_json);
      }
    }

    $this->response = $response;
    return true;
  }

  function getResponse(): ?Response
  {
    return $this->response;
  }
}
