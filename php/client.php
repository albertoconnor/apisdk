<?php
include_once('httpful.phar');
  // Client.php
  // Utility class to perform http requests
  // For more info visit https://api.relateiq.com/#/php

class Client {
  private static $key;
  private static $secret;
  private static $endpoint;
  private static $headers = array("Content-type" => "application/json", "Accept" => "application/json");
  private static $cache = array ();

  # Hybrids
  public static function key($value=null) {
    self::$key = $value ?: self::$key;
    return self::$key;
  }

  public static function secret($value=null) {
    self::$secret = $value ?: self::$secret;
    return self::$secret;
  }

  public static function endpoint($value=null) {
    self::$endpoint = $value ?: self::$endpoint;
    return self::$endpoint;
  }

  public static function headers($value=null) {
    self::$headers = $value ?: self::$headers;
    return self::$headers;
  }

  public static function relateIQ($key, $secret, $endpoint=null) {
    $endpoint = $endpoint ?: "https://api.relateiq.com/v2/";
    self::key($key);
    self::secret($secret);
    self::endpoint($endpoint);
  }

  public function cache($endpoint, $value = null) {
    if ($value != null) {
      self::$cache[$endpoint] = $value;
    }

    return array_key_exists($endpoint, self::$cache) ? self::$cache[$endpoint] : "";
  }

  public function get($endpoint, $options=[])
  {
    $uri = Client::endpoint().$endpoint.Client::getUriOptions($options);
    $response = \Httpful\Request::get($uri)->authenticateWith(Client::key(), Client::secret())
      ->addHeaders(Client::headers())->send();
    return $response;
  }

  public function post($endpoint, $data, $options=[])
  {
    $uri = Client::endpoint().$endpoint.Client::getUriOptions($options);
    $data = json_encode($data);
    $response = \Httpful\Request::post($uri)->authenticateWith(Client::key(), Client::secret())
      ->addHeaders(Client::headers())->body($data)->send();
      return $response;
  }

  public function put($endpoint, $data, $options=[])
  {
    $uri = Client::endpoint().$endpoint.Client::getUriOptions($options);
    $data = json_encode($data);
    $response = \Httpful\Request::put($uri)->sendsJson()->authenticateWith(Client::key(), Client::secret())
      ->addHeaders(Client::headers())->body($data)->send();
      return $response;
  }

  public function delete($endpoint, $options=[])
  {
    $uri = Client::endpoint().$endpoint.Client::getUriOptions($options);
    $response = \Httpful\Request::delete($uri)->authenticateWith(Client::key(), Client::secret())
      ->addHeaders(Client::headers())->send();
    return $response;
  }

  public function getUriOptions($options=[])
  {
    $qyery = '';
    if(count($options) > 0)
    {
      if (count($options) == 1) {
        $query = $query. current($options);
      } else {
        $query = '?';
        foreach ($options as $key => $value) {
          $query = $query.$key.'='.$value.'&';
        }
        $pos = strrpos($query, '&');
        $query = substr($query, 0, $pos);
      } 
    }
    return $query;
  }

  public function fetch($endpoint,$options=[])
  {
    try {

      $data = Client::get($endpoint);
      if ($data->code == 200) {
        return $data;
      } elseif ($data->code == 404) {
        return [];
      }else{
        throw new Exception ($data->body->errorMessage);
      }
    } catch (Exception $e) {
      if ($e->getCode() == 404) {
        return [];
      }
      throw new Exception ($e);
    }
  }
}

?>
