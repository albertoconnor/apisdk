<?php
include_once "client.php";

abstract class RIQObject
{
  private static $class = 'RIQBase';
  private static $cache = [];
  private static $cache_index = 0;
  private static $page_index = 0;
  protected static $page_length = 200;
  private static $fetch_options = array();
  private static $last_modified_date = null;
  private static $parent = null;


  abstract public function id();
  abstract public function payload();
  abstract public function parse($data);

  public function __toString()
  {
    $dataAux = $this->payload();
    ksort($dataAux);
    return json_encode($dataAux);
  }

  public function save($options=[])
  {
    if ($this->exists()) {
      return $this->update($options);
    } else {
      return $this->create($options);
    }
  }

  public function create($options=[])
  {
    $data = Client::post(static::endpoint(), $this->payload(), $options);
    if (count($data)>0) {
      $data = json_decode($data, true);
    }
    return $this->parse($data);
  }

  public function get($options=[])
  {
    $data = Client::fetch(static::endpoint().'/'.$this->id(), $options);
    if (count($data) > 0) {
      $data = json_decode($data, true);
    }
    return $this->parse($data);
  }

  public function update($options=[])
  {
    $data = Client::put(static::endpoint().'/'.$this->id(), $this->payload(), $options);
    if (count($data)>0) {
      $data = json_decode($data, true);
    }
    return $this->parse($data);
  }

  public function delete($options=[])
  {
    $data = Client::delete(static::endpoint().'/'.$this->id(), $options);
    if (count($data)>0) {
      $data = json_decode($data, true);
    }
    return $data;
  }

  public function exists()
  {
    if ($this->id() == null) {
      return false;
    }
    return Client::fetch(static::endpoint().'/'.$this->id()) != [];
  }

  public static function find_key($key, $array, $default=null)
  {
    return array_key_exists($key, $array) ? $array[$key] : $default;
  }
}
?>