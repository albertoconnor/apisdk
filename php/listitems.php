<?php
include_once "riq_obj.php";

class ListItems extends RIQObject
{
	private $id = null;
  private $createdDate = null;
  private $modifiedDate = null;
  private $name = null;
  private $listId = null;
  private $accountId = null;
  private $contactIds = null;
  private $fieldValues = null;
  private $lists = null;
  private $linkedItemIds = null;
  protected static $page_length = 200;
  private static $fetch_options = array();

	/*
  Array contents  $id=null, $name=null, $modifiedDate=null, $createdDate=null, $listId=null, $accountId=null,
                  $fieldValues=null, $linkedItemIds=null, $contactIds=null, $data=null, $parent=null
  */
  function __construct(array $prop) { 
   	$parent = self::find_key('parent', $prop);
   	if ($parent == null) {
   		throw new Exception('List Item Parent must be set.');
   	} else{
			$this->lists($parent);
		}

    $this->id = self::find_key('id', $prop);
    $data = self::find_key('data', $prop);
    if ($data != null) {
      $auxTmp = self::parse($data, $parent);
    } elseif ($this->id() != null) {
      $this->get();
    }

    $this->name(self::find_key('name', $prop));
    $this->createdDate(self::find_key('createdDate', $prop));
    $this->modifiedDate(self::find_key('modifiedDate', $prop));
    $this->accountId(self::find_key('accountId', $prop));
    $this->contactIds(self::find_key('contactIds', $prop));
    $this->fieldValues(self::find_key('fieldValues', $prop));
    $this->linkedItemIds(self::find_key('linkedItemIds', $prop));
    $this->listId(self::find_key('listId', $prop));
  }

  public function node()
  {
    return '/'.$this->listId().'/listitems';
  }

  public function endpoint()
  {
    return $this->lists()->endpoint().$this->node();
  }

  public function parse($data, $parent=null)
  {
    $fieldValues = [];
    foreach (self::find_key('fieldValues', $data, []) as $field => $valueList) {
      $fieldValue = [];
      if (count($valueList) == 1) {
        $fieldValue = self::find_key('raw', $valueList[0], []);
      } else {
        foreach ($valueList as $val) {
          $fieldValue[] = self::find_key('raw', $val, []);
        }
      }
      $fieldValues[$field] = $fieldValue;      
    }
    $this->id(self::find_key('id', $data));
    $this->modifiedDate(self::find_key('modifiedDate', $data));
    $this->createdDate(self::find_key('createdDate', $data));
    $this->name(self::find_key('name', $data));
    $this->accountId(self::find_key('accountId', $data));
    $this->listId(self::find_key('listId', $data));
    $this->fieldValues($fieldValues);
    $this->lists($parent);
    $this->contactIds(self::find_key('contactIds', $data));
    $this->linkedItemIds(self::find_key('linkedItemIds', $data));

    return $this;
  }

  # Data Payload
  public function payload()
  {
    $fieldValues = [];
    foreach ($this->fieldValues() as $field => $value) {
      $valueList = [];
      if (is_string($value)) {
        $value = [$value];
      }
      foreach ($value as $val) {
        $valueList[] = ['raw'=>$val];
      }
      $fieldValues[$field] = $valueList;
    }
    $fieldValues = (object)$fieldValues;

    $payload = [
        'name' => $this->name(),
        'accountId' => $this->accountId(),
        'contactIds' => $this->contactIds(),
        'listId' => $this->listId(),
        'fieldValues' => $fieldValues,
        'linkedItemIds' => count($this->linkedItemIds()) > 0 ? (object)$this->linkedItemIds() : null
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

  public function createdDate($value=null)
  {
    $this->createdDate = $value ?: $this->createdDate;
    return $this->createdDate;
  }

  public function modifiedDate($value=null)
  {
    $this->modifiedDate = $value ?: $this->modifiedDate;
    return $this->modifiedDate;
  }

  public function name($value=null)
  {
    $this->name = $value ?: $this->name;
    return $this->name;
  }

  public function accountId($value=null)
  {
    $this->accountId = $value ?: $this->accountId;
    return $this->accountId;
  }

  public function contactIds($value=null)
  {
    if ($value) {
      if (is_string($value)) {
        $this->contactIds = [$value];
      }
      else{
        $this->contactIds = $value;
      }
    }
    return $this->contactIds ?: [];
  }

  public function listId($value=null)
  {
    if ($value) {
      $this->listId = $value;
    }
    if ( !$this->listId and $this->lists()) {
      $this->listId = $this->lists()->id();
    }
    return $this->listId;
  }

  public function lists($value=null)
  {
    $this->lists = $value ?: $this->lists;
    return $this->lists;
  }

  public function fieldValues($value=null)
  {
    if ($value) {
      foreach ($value as $key => $val) {
        $this->fieldValue($key, $val);
      }
    }
    return $this->fieldValues ?: [];
  }

  public function fieldValue($key,$value=null)
  {
    $key = $this->lists()->fieldKey($key);
    if ($this->fieldValues == null) {
      $this->fieldValues = [];
    }
    if ($value) {
      $this->fieldValues[$key] = $value;
    }
    return $this->lists()->fieldValue($key, self::find_key($key, $this->fieldValues));
  }

  public function linkedItemIds($value=null)
  {
    $this->linkedItemIds = $value ?: $this->linkedItemIds;
    return $this->linkedItemIds ?: [];
  }

  public function linkItem($item=null)
  {
    if ($this->linkedItemIds == null) {
      $this->linkedItemIds = [];
    }
    $links = $this->linkedItemIds[$item->itemTypeId()];
    if($links == null)
    {
      $links = [];
    }
    foreach ($links as $entry) {
      $res = self::find_key('itemId', $entry);
      if (!array_key_exists($item->id(), $res)) {
        $links[] = ['itemId'=> $item->id()];
      }
    }
    $this->linkedItemIds[$item->itemTypeId()] = $links;
    return $this->linkedItemIds;
  }

  public function factory($data)
  {
    return clone new self($data);
  }

  public function fetchPage($index=0,$limit=null)
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
      $item = static::factory(['data'=>$value, 'parent'=>$this->lists()]);
      unset($item->lists);
      $objects[] = $item;
    }

    return $objects;
  }
}
?>