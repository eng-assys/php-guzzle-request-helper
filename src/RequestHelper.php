<?php

namespace GuzzleHelper;

class RequestHelper
{
  const successArrayKey = "success";
  const errorArrayKey = "error";

  const informationalResponses = "1xx";
  const success = "2xx";
  const redirection = "3xx";
  const clientErrors = "4xx";
  const serverErrors = "5xx";

  /**
   * Returns a successful response
   * 
   * @param array $content Response's content
   * @param int $httpCode Reponse's Http Code
   * 
   * @return json successful response
   */
  public static function responseSuccess($content, $httpCode = 200)
  {
    return response()->json([self::successArrayKey => $content], $httpCode);
  }

  /**
   * Returns an error response
   * 
   * @param array $content Response's content
   * @param int $httpCode Reponse's Http Code
   * 
   * @return json error response
   */
  public static function responseError($content, $httpCode = 400)
  {
    return response()->json([self::errorArrayKey => $content], $httpCode);
  }

  /**
   * Identifies Http status code family
   * 
   * @param string $statusCode Http code to identify belonged family
   * 
   * @return string Code of belonged http family (1xx, 2xx, 3xx, 4xx, 5xx)
   */
  public static function identifyHttpStatusCodeFamily($statusCode)
  {
    if ((intval($statusCode) - 100) < 100) {
      return self::informationalResponses;
    } elseif ((intval($statusCode) - 200) < 100) {
      return self::success;
    } elseif ((intval($statusCode) - 300) < 100) {
      return self::redirection;
    } elseif ((intval($statusCode) - 400) < 100) {
      return self::clientErrors;
    } elseif ((intval($statusCode) - 500) < 100) {
      return self::serverErrors;
    } else {
      return null;
    }
  }

  /**
   * Generates url params string
   * 
   * @param array $params Url params
   * 
   * @return string
   * 
   */
  public static function generateUrlParams($params)
  {
    $query = '';
    foreach ($params as $key => $param) {
      if (!empty($param)) {
        if (empty($query)) {
          $query = "?";
        } else {
          $query .= "&";
        }
        if (is_array($param)) {
          $query .= http_build_query(["$key" => $param]);
        } else {
          $query .= "$key=$param";
        }
      }
    }
    return $query;
  }

  /**
   * Removes null contend from body array
   * 
   * @param array $data Body content array
   * 
   * @return array
   */
  public static function filterBodyArray($data)
  {
    if (!$data) return [];
    array_filter($data);
    foreach (array_keys($data) as $key) {
      if (is_array($data[$key]))
        $data[$key] = self::filterBodyArray($data[$key]);
    }
    return $data;
  }
}
