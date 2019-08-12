<?php

namespace GuzzleHelper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\BadResponseException;

/**
 * Makes Requests using PHP Guzzle library
 */
class Request
{

  /**
   * Sends a Custom Guzzle Request.
   * 
   * Sends a custom Guzzle request removing empty body params and standardizing thown Exceptions
   * 
   * @param string $url Request's URL
   * @param string $method Request's Method (GET, POST, etc)
   * @param array $body Request's Body
   * @param array $headers Request's Header
   * @param array $guzzleClientConfig Custom Guzzle Client configuration
   * 
   * @return array Guzzle Reponse and Http Code
   */
  private static function send($url, $method, $body, $headers = [], $guzzleClientConfig = [])
  {
    try {
      $client = new Client($guzzleClientConfig);
      $guzzleResponse = $client->request($method, $url, [
        'http_errors' => false,
        'headers' => $headers,
        'json' => RequestHelper::filterBodyArray($body)
      ]);

      return [
        'html_status' => $guzzleResponse->getStatusCode(),
        'response' => json_decode($guzzleResponse->getBody(), true)
      ];
    } catch (ConnectException | BadResponseException $ex) {
      throw new RequestException($ex);
    }
  }

  /**
   * Sends a Custom Guzzle GET Request.
   * 
   * @param string $url Request's URL
   * @param array $headers Request's Header
   * @param array $guzzleClientConfig Custom Guzzle Client configuration
   * 
   * @return array Guzzle Reponse and Http Code
   */
  public static function get($url, $headers = [], $guzzleClientConfig = [])
  {
    return self::send($url, 'GET', [], $headers, $guzzleClientConfig);
  }

  /**
   * Sends a Custom Guzzle POST Request.
   * 
   * @param string $url Request's URL
   * @param array $body Request's Body
   * @param array $headers Request's Header
   * @param array $guzzleClientConfig Custom Guzzle Client configuration
   * 
   * @return array Guzzle Reponse and Http Code
   */
  public static function post($url, $body, $headers = [], $guzzleClientConfig = [])
  {
    return self::send($url, 'POST', $body, $headers, $guzzleClientConfig);
  }

  /**
   * Sends a custom Guzzle PUT request
   * 
   * @param string $url Request's URL
   * @param array $body Request's Body
   * @param array $headers Request's Header
   * @param array $guzzleClientConfig Custom Guzzle Client configuration
   * 
   * @return array Guzzle Reponse and Http Code
   */
  public static function put($url, $body, $headers = [], $guzzleClientConfig = [])
  {
    return self::send($url, 'PUT', $body, $headers, $guzzleClientConfig);
  }

  /**
   * Sends a custom Guzzle DELETE request
   * 
   * @param string $url Request's URL
   * @param array $body Request's Body
   * @param array $headers Request's Header
   * @param array $guzzleClientConfig Custom Guzzle Client configuration
   * 
   * @return array Guzzle Reponse and Http Code
   */
  public static function delete($url, $body, $headers = [], $guzzleClientConfig = [])
  {
    return self::send($url, 'DELETE', $body, $headers, $guzzleClientConfig);
  }
}
