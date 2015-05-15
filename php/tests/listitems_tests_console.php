<?php
include_once "globalVar.php";
include_once "../lists.php";
include_once "../accounts.php";
include_once "../contacts.php";

class RiqListItemsTestConsole extends PHPUnit_Framework_TestCase
{
  public function testListsGetone()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);

    $data = ["id"=>"53ae0c09e4b0f0eb6bc57ecd"];
    $listObj = new Lists($data);
    $listItem = $listObj->ListItem("53b4b4cce4b0e6c80c5fca0e");
    $this->assertInstanceOf('ListItems', $listItem);
    
  }

  public function testListitemPost()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);

    $data = ["id"=>"53ae0c09e4b0f0eb6bc57ecd"];
    $listObj = new Lists($data);
    $this->assertNotNull($listObj->id());

    $account = new Account([]);
    $contact = new Contact(["id" => "53b4b4cce4b0e6c80c5fca0c"]);
    $listItem = new ListItems(["parent"=> $listObj]);
    $listItem->accountId($account->id());
    $listItem->contactIds($contact->id());
    $listItem->name("MASH Realtors");
    $listItem->fieldValues(["0"=>"5","2"=>"0"]);
    $res = $listItem->create();
    $this->assertInstanceOf('ListItems', $res);
  }
  
  public function testListitemPut()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);

    $data = ["id"=>"53ae0c09e4b0f0eb6bc57ecd"];
    $listObj = new Lists($data);
    $this->assertNotNull($listObj->id());

    $listItem = $listObj->ListItem("552d2170e4b062ffd9c6c10f"); 
    $listItem->name($listItem->name()." updated");
    $res = $listItem->update();
    
    $newList = $listObj->ListItem("552d2170e4b062ffd9c6c10f");
    $this->assertNotNull($newList->id());
    $this->assertNotNull($newList->name());
    $this->assertEquals("552d2170e4b062ffd9c6c10f", $newList->id());
  }

  public function testListitemDelete()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);

    $data = ["id"=>"53ae0c09e4b0f0eb6bc57ecd"];
    $listObj = new Lists($data);
    $this->assertNotNull($listObj->id());

    //Creating ListItems...
    $account = new Account([]);
    $contact = new Contact(["id" => "53b4b4cce4b0e6c80c5fca0c"]);
    $listItem = new ListItems(["parent"=> $listObj]);
    $listItem->accountId($account->id());
    $listItem->contactIds($contact->id());
    $listItem->name("MASH Realtors");
    $listItem->fieldValues(["0"=>"5","2"=>"0"]);
    $res = $listItem->create();
    $this->assertNotNull($res->id());
    $idList = $res->id();

    //Deleting ListItems...
    $listItem = $listObj->ListItem($idList);
    $res = $listItem->delete();
    $this->assertEquals(true, $res);
  }
  
}
?>
