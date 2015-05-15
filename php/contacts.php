<?php
include_once "riq_base.php";

class Contact extends RIQBase
{
	private $id = null;
  private $modifiedDate = null;
  private $properties = null;

  /*
  Array contents  $id=null, $name=null, $email=null, $phone=null, $address=null, $company=null,
                  $title=null, $properties=null, $modifiedDate=null, $twhan=null, $data=null
  */
  function __construct(array $prop) { 
  	
    $this->id = self::find_key('id', $prop);
    $data = self::find_key('data', $prop);

   	if ($data != null) {
   		$auxTmp = self::parse($data);
   	} elseif ($this->id() != null) {
			$this->get();
		}

    $this->properties(self::find_key('properties', $prop));
    $this->name(self::find_key('name', $prop));
    $this->email(self::find_key('email', $prop));
    $this->phone(self::find_key('phone', $prop));
    $this->address(self::find_key('address', $prop));
    $this->company(self::find_key('company', $prop));
    $this->title(self::find_key('title', $prop));
    $this->modifiedDate(self::find_key('modifiedDate', $prop));
    $this->twhan(self::find_key('twhan', $prop));
  }

  public static function node()
  {
    return 'contacts';
  }

  public static function fetchByIds($contactIds)
  {
    $contactsById = [];
    foreach (self::fetchBatch('_ids',$contactIds,self::$page_length) as $contact) {
      $contactsById[$contact->id()] = $contact;
    }
    self::setFetchOptions();
    return $contactsById;
  }
  
  public function parse($data)
  {
    $this->id(self::find_key('id', $data));
    $this->modifiedDate(self::find_key('modifiedDate', $data));
    $this->properties(self::find_key('properties', $data, []));
    return $this;
  }

  # Data Payload
  public function payload()
  {
    $payload = ['properties'=> $this->metadata($this->properties)];
    if ($this->modifiedDate()) {
      $payload['modifiedDate'] = $this->modifiedDate();
    }
    if ($this->id()) {
      $payload['id'] = $this->id();
    }
    return $payload;
  }

  public function metadata($prop)
  {
    if (array_key_exists('metadata', $prop)) {
      unset($prop['metadata']);
    } else {
      foreach ($prop as &$value) {
        foreach ($value as &$val) {
          unset($val['metadata']);
        }
      }
    }
    return $prop;
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
  
  public function properties($value=null)
  {
    $this->properties = $value ?: $this->properties;
    return $this->properties ?: [];
  }
  
  public function property($key, $value=null)
  {
    if ($this->properties == null) {
      $this->properties = [];
    }
    #set value if passed in
    if ($value != null) {
      $new_values = is_array($value) ? $value : [$value];
      
      $existing_values = [];
      foreach ( self::find_key($key, $this->properties, []) as $k => $val) {
        $existing_values[] = $val['value'];
      }
      $existing_values = array_unique($existing_values);

      # do not update a value if it already exists in order to keep any existing metadata
      $values_to_add = [];
      foreach ($new_values as $val) {
        if (!in_array($val, $existing_values)) {
          $values_to_add[] = ['value'=>$val];
        }
      }

      $values_to_keep = [];
      foreach ( self::find_key($key, $this->properties, []) as $val) {
        if (in_array($val['value'], $new_values)) {
          $values_to_keep[] = $val;
        }
      }

      $this->properties[$key] = array_merge($values_to_add, $values_to_keep);
    }

    # get value to return
    $retval = [];
    foreach ( self::find_key($key, $this->properties, []) as $val) {
      $retval[] = $val['value'];
    }
    # return scalar if only one item, list otherwise (or null if empty)
    if (count($retval) == 0) {
      return null;
    } elseif (count($retval) == 1) {
      return $retval[0];
    }else{
      return $retval;
    }
  }

  # value should be of the form: [{'value':string , 'metadata':{string:string}}]
  # returns a list of objects if multiple values, a single object if one value, or None
  # if there are no values
  public function propertyWithMetadata($key, $value=null)
  {
    if ($this->properties == null) {
      $this->properties = [];
    }

    if ($value != null) {
      $values = is_array($value) ? $value : [$value];
      # update even if key,value pair already exists. If existing metadata should be preserved,
      # it should be included in the passed in object
      $this->properties[$key] = $values;
    }

    $retval = self::find_key($key, $this->properties, []);

    if (count($retval) == 0) {
      return null;
    } elseif (count($retval) == 1) {
      return $retval[0];
    }else {
      return $retval;
    }
  }

  public function name($value=null)
  {
    return $value ? $this->property('name',$value): $this->property('name');
  }

  public function email($value=null)
  {
    return $value ? $this->property('email',$value): $this->property('email');
  }

  public function phone($value=null)
  {
    return $value ? $this->property('phone',$value): $this->property('phone');
  }

  public function address($value=null)
  {
    return $value ? $this->property('address',$value): $this->property('address');
  }

  public function company($value=null)
  {
    return $value ? $this->property('company',$value): $this->property('company');
  }

  public function title($value=null)
  {
    return $value ? $this->property('title',$value): $this->property('title');
  }

  public function twhan($value=null)
  {
    return $value ? $this->property('twhan',$value): $this->property('twhan');
  }
  
  public function factory($data)
  {
    return clone new self($data);
  }
}
?>