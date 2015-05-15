<?php
include_once "client.php";
include_once "riq_obj.php";

abstract class RIQBase extends RIQObject
{
  private static $class = 'RIQBase';
  private static $cache = [];
  private static $cache_index = 0;
  private static $page_index = 0;
  protected static $page_length = 200;
  private static $fetch_options = array();
  private static $last_modified_date = null;
  private static $parent = null;

  abstract public static function node();

  public static function endpoint(){
    return static::node();
  }

  public static function fetchBatch($param,$values,$maxSize){
  	$chunks = self::matrix($values,$maxSize);
  	$objects = array();
  	foreach ($chunks as $i => $chunk) {
      try {
      	self::setFetchOptions(array($param => join(',', $chunk)));
	      $objects += self::fetchPage();
  	  } catch (Exception $e) {
  	    $error = error_get_last();
  	    if ( ($error['type'] == 414 || $error['type'] == 413) && $maxSize > 1) { 
  	   	  $objects += self::fetchBatch($param, $chunk, intval(ceil($maxSize/2)));
  	    }
  	    else
  	    	print_r($e->getMessage());
  	  }
	  }
	  return $objects;
  }

  public static function fetchPage($index=0,$limit=null)
  {
    if ($limit == null) {
      $limit = self::$page_length;
    }
    self::$fetch_options['_start'] = strval($index);
    self::$fetch_options['_limit'] = strval($limit);
    $data = json_decode(Client::get(self::endpoint(), self::$fetch_options), true);
    $objects = array();
    $datas = [];
    if (array_key_exists('objects', $data)) {
      $datas = (array)$data['objects'];
    } else {
      $datas = [];
    }

    foreach ($datas as $key => $value) {
      $value = (array)$value;
      $objects[] = static::factory(['data'=>$value]);
    }

    return $objects;
  }

  public abstract function factory($data);

  public static function next()
  {
    if (self::$cache_index == 0) {
      self::$cache = [];
      self::$cache_index = 0;
      self::$page_index = 0;
    }

    if (self::$cache_index == count(self::$cache)) {
      $size = count(self::$cache);
      if ($size != 0 && $size != self::$page_length) {
        return null;
      }
      self::$cache = [];
      self::$cache = array_merge(self::$cache, self::fetchPage(self::$page_index, self::page_length));
      self::$page_index += count(self::$cache);
      self::$cache_index = 0;
    }
    if (self::$cache_index < count(self::$cache)) {
      $obj = self::$cache[self::$cache_index];
      self::$cache_index += 1;
      return $obj;
    }
    else{
      return null;
    }
  }
  
  public static function matrix($values,$maxSize){
    if ($maxSize < 1) {
      $maxSize = count($values);
    }
    if ($maxSize > 0) {
      $res = array();
      $pos = 0;
      $elem = 0;
      for ($i=0; $i < count($values); $i=$i+$maxSize) { 
        for ($j=0; $j < $maxSize; $j++) { 
          if ($elem < count($values)) {
            $res[$pos][$j] = $values[$elem];
            $elem ++; 
          }
        }
          $pos++;
      }
      return $res;
    } else {
      return [];
    }
  }

  public static function resetCache()
  {
    self::$fetch_options = array();
    self::$cache = [];
    self::$cache_index = 0;
    self::$page_index = 0;
    self::$last_modified_date = None;
  }
  
  public static function setPageSize($limit) {
    RIQBase::$page_length = $limit;
  }

  public static function getPageSize() {
    return RIQBase::$page_length;
  }

  public static function setFetchOptions($options=[])
  {
    RIQBase::resetCache();
    self::$fetch_options = $options;
  }
  
  public static function getVarsClass()
  {
    return var_export(get_class_vars('RIQBase'), true);
  }
  public static function dummyClassMethod() {
    print_r(RIQBase::getVarsClass());
  }
}
?>