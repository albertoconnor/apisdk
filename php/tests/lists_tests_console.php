<?php
include_once "globalVar.php";
include_once "../lists.php";
class RiqContactsTestConsole extends PHPUnit_Framework_TestCase
{
  public function testListsGetone()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);

    $data = ["id"=>"53ae0c09e4b0f0eb6bc57ecd"];
    $res = new Lists($data);
    $this->assertInstanceOf('Lists', $res);
  }

  public function testListsGetAll()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    Lists::setFetchOptions(["_ids" => "53ae0c09e4b0f0eb6bc57ecd"]);
    $lists = Lists::fetchPage();
    $this->assertNotNull($lists);
  }
}
?>
