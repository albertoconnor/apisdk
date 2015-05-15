<?php
include_once "riq_base.php";

class User extends RIQBase
{
	private $id = null;
  private $name = null;
  private $email = null;

	/*
  Array contents $id=null, $name=null, $email=null, $data=null
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
		$this->email(self::find_key('email', $prop));
  }

  public static function node()
  {
    return 'users';
  }

  public function parse($data)
  {
  	$this->id(self::find_key('id', $data));
  	$this->name(self::find_key('name', $data));
    $this->email(self::find_key('email', $data));
    return $this;
  }

  # Data Payload
  public function payload()
  {
    $payload = ['name'=> $this->name(), 
                'email' => $this->email()];
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

  public function email($value=null)
  {
    $this->email = $value ?: $this->email;
    return $this->email;
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