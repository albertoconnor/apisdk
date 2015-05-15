<?php
include_once "riq_base.php";

class Account extends RIQBase
{
	private $id = null;
  private $name = null;
  private $modifiedDate = null;

  /*
  Array contents $id=null, $name=null, $modifiedDate=null, $data=null
  */
  function __construct(array $prop) { 
    $this->id = self::find_key('id', $prop);
    $data = self::find_key('data', $prop);
   	if ($data != null) {
   		$auxTmp = self::parse($data);
   	} elseif ($this->id($id) != null) {
			$this->get();
		}

    $this->name(self::find_key('name', $prop));
    $this->modifiedDate(self::find_key('modifiedDate', $prop));
  }

  public static function node()
  {
  	return 'accounts';
  }

  public function parse($data)
  {
  	$this->id(self::find_key('id', $data));
  	$this->name(self::find_key('name', $data));
  	$this->modifiedDate(self::find_key('modifiedDate', $data));
    return $this;
  }

  public function payload()
  {
  	$payload = ['name'=> $this->name()];
  	if ($this->id()) {
  		$payload['id'] = $this->id();
  	}
  	return $payload;
  }

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

  public function name($value=null)
  {
  	$this->name = $value ?: $this->name;
  	return $this->name;
  }

  public function factory($data)
  {
    return clone new self($data);
  }
}
?>