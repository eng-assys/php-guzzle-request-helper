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

  public static function responseSuccess($content, $httpCode = 200)
  {
    return response()->json([self::successArrayKey => $content], $httpCode);
  }

  public static function responseError($content, $httpCode = 400)
  {
    return response()->json([self::errorArrayKey => $content], $httpCode);
  }

  public static function identifyHttpStatusCodeFamily($status_code)
  {
    if ((intval($status_code) - 100) < 100) {
      return self::informationalResponses;
    } elseif ((intval($status_code) - 200) < 100) {
      return self::success;
    } elseif ((intval($status_code) - 300) < 100) {
      return self::redirection;
    } elseif ((intval($status_code) - 400) < 100) {
      return self::clientErrors;
    } elseif ((intval($status_code) - 500) < 100) {
      return self::serverErrors;
    } else {
      return null;
    }
  }

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
