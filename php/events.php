<?php
include_once "riq_base.php";

class Events extends RIQBase
{
	private $id = null;
  private $modifiedDate = null;
  private $participantIds = null;
  private $subject = null;
  private $body = null;

	/*
  Array contents $id=null, $subject=null, $body=null, $participantIds=null, $data=null
  */
  function __construct(array $prop) { 
  	
   	$this->id = self::find_key('id', $prop);
   	$data = self::find_key('data', $prop);
   	if ($data != null) {
   		$auxTmp = self::parse($data);
   	} elseif ($this->id($id) != null) {
			$this->get();
		}

		$this->participantIds(self::find_key('participantIds', $prop));
		$this->subject(self::find_key('subject', $prop));
		$this->body(self::find_key('body', $prop));
  }

  public function parse($data)
  {
  	$this->id(self::find_key('id', $data));
  	$this->modifiedDate(self::find_key('modifiedDate', $data));
    return $this;
  }

	public static function node()
  {
  	return 'events';
  }

  public function factory($data)
  {
    return clone new self($data);
  }

  public function update($options=[])
  {
    $data = (array)Client::put(self::endpoint(), static::payload(), $options);
    return $this;
  }
  
  # Data Payload
  public function payload()
  {
  	$payload = ['participantIds'=> $this->participantIds, 
  							'subject' => $this->subject(),
  							'body' => $this->body()];
  	if ($this->modifiedDate()) {
  		$payload['modifiedDate'] = $this->modifiedDate();
  	}
  	elseif ($this->id()) {
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

  public function participantIds($value=null)
  {
		$this->participantIds = $value ?: $this->participantIds;
		return $this->participantIds;
  }

  public function subject($value=null)
  {
  	$this->subject = $value ?: $this->subject;
		return $this->subject;
  }

  public function body($value=null)
  {
  	$this->body = $value ?: $this->body;
  	return $this->body;
  }
}
?>