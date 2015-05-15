<?php
include_once "globalVar.php";
include_once "../events.php";
class EventTest extends PHPUnit_Framework_TestCase
{
  public function testKeyShouldBeSet() {
    $participantIds = [["type" => "email", "value" => "james.mcsales@relateiq.com"],
                      ["type" => "email", "value" => "tim.archer@avocado.com"],
                      ["type" => "phone","value" => "8001235555"]];
    $data = ['participantIds'=>$participantIds];
    $event = new Events($data);
    $this->assertEquals($participantIds, $event->participantIds());
  }

  public function testEvent()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $event = new Events([]);
    $event->subject("Support Ticket #12345: How do I create an event?");
    $event->body("Just called Tim and walked him through how to create an event with the new API.
    He'll reach out to support@relateiq.com with any questions he might have.
    Resolving.
    - James");
    $participantIds = [["type" => "email", "value" => "james.mcsales@relateiq.com"],
                      ["type" => "email", "value" => "tim.archer@avocado.com"],
                      ["type" => "phone","value" => "8001235555"]];
    $event->participantIds($participantIds);
    $res = $event->update();
    $this->assertInstanceOf('Events', $res);
  }    
}
?>
