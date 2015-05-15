<?php
include_once "riq_base.php";
include_once "listitems.php";

class Lists extends RIQBase
{
  # Object Attributes
  private $id = null;
  private $modifiedDate = null;
  private $title = null;
  private $listType = null;
  private $fields = null;


  /*
  Array contents $id=null, $title=null, $modifiedDate=null, $fields=null, $data=null
  */
  function __construct(array $prop) { 

    $this->id = self::find_key('id', $prop);
    $data = self::find_key('data', $prop);
    if ($data != null) {
      $auxTmp = self::parse($data, $parent);
    } elseif ($this->id() != null) {
      $this->get();
    }

    $this->title(self::find_key('title', $prop));
    $this->modifiedDate(self::find_key('modifiedDate', $prop));
    $this->fields(self::find_key('fields', $prop));
  }

  public static function node()
  {
    return 'lists';
  }

  public function parse($data)
  {
    $this->id(self::find_key('id', $data));
    $this->modifiedDate(self::find_key('modifiedDate', $data));
    $this->title(self::find_key('title', $data));
    $this->listType(self::find_key('listType', $data));
    $this->fields(self::find_key('fields', $data));

    return $this;
  }

  # Data Payload
  public function payload()
  {

    $payload = [
        'title' => $this->title(),
        'fields' => $this->fields()
    ];

    if ($this->id()) {
      $payload['id'] = $this->id();
    }

    return $payload;
  }

  # Hybrid
  public function id($value=null)
  {
    $this->id = $value ?: $this->id;
    return $this->id;
  }

  public function modifiedDate($value=null)
  {
    $this->modifiedDate = $value ?: $this->modifiedDate;
    return $this->modifiedDate;
  }

  public function title($value=null)
  {
    $this->title = $value ?: $this->title;
    return $this->title;
  }

  public function listType($value=null)
  {
    $this->listType = $value ?: $this->listType;
    return $this->listType;
  }

  public function fields($value=null)
  {
    $this->fields = $value ?: $this->fields;
    return $this->fields ?: [];
  }

  # Sub Endpoints
  public function ListItem(
    $id=null,
    $name=null,
    $modifiedDate=null,
    $createdDate=null,
    $listId = null,
    $accountId = null,
    $fieldValues = null,
    $linkedItemIds = null,
    $contactIds = null,
    $data = null,
    $parent=null)
  {
    $dataSend = [
      'id'=>$id,
      'name'=>$name,
      'modifiedDate'=>$modifiedDate,
      'createdDate'=>$createdDate,
      'listId'=>$listId,
      'accountId'=>$accountId,
      'fieldValues'=>$fieldValues,
      'linkedItemIds'=>$linkedItemIds,
      'contactIds'=>$contactIds,
      'data'=>$data,
      'parent'=>$this
    ];
    return new ListItems($dataSend);
  }

  public function fieldKey($name)
  {
    #if the "name" is already a key, just return it
    foreach ($this->fields() as $field) {
      if ( self::find_key('id', $field) == $name) {
        return $name;
      }
    }

    #otherwise, find the field whose "name" is name, and return that field's id
    foreach ($this->fields() as $field) {
      if ( self::find_key('name', $field) == $name) {
        return self::find_key('id', $field, $name);
      }
    }
    return $name;
  }
  
  public function fieldValue($key,$value=null)
  {
    foreach ($this->fields() as $field) {
      if ( self::find_key('id', $field) == $key) {
        return $key;
      }
    }

    foreach ($this->fields() as $field) {
      if ( self::find_key('display', $field) == $key) {
        return self::find_key('id', $field, $key);
      }
    }
    return $key;
  }
  
  public function fieldOption($key,$value=null)
  {
    foreach ($this->fields() as $field) {
      if ( self::find_key('id', $field) == $key) {
        return $key;
      }
    }

    foreach ($this->fields() as $field) {
      if ( self::find_key('display', $field) == $key) {
        return self::find_key('id', $field, $key);
      }
    }
    return $key;
  }

  public function factory($data)
  {
    return clone new self($data);
  }
}
?>