<?php
include_once "globalVar.php";
include_once "../accounts.php";
class AccountTest extends PHPUnit_Framework_TestCase
{
  public function testKeyShouldBeSet() {
    $account1 = new Account(['name' => 'Planet Express']);
    $this->assertEquals("accounts", $account1::node());
    $this->assertEquals("Planet Express", $account1->name());
    $par = $account1->parse(['name' => 'Planet Express', 'id' => '123', 'modifiedDate'=>'2015/03/24']);
    $this->assertEquals(123, $account1->id());
    $this->assertEquals('Planet Express', $account1->name());
    $this->assertEquals('2015/03/24', $account1->modifiedDate());
    $this->assertEquals(['name'=>'Planet Express', 'id' => '123'], $account1->payload());

  }

  public function testAccountGetAll()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $accounts = Account::fetchPage();
    foreach ($accounts as $key => $value) {
      print_r($value->__toString());
    }
  }

  public function testAccountGetOne()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $account = new Account(['id' => '538530d2e4b00530d85ae1bf']);
    var_export($account->__toString());
  }

  public function testAccountGetOneOptions()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    Account::setFetchOptions(["_ids" => "53f78029e4b088c4688c4194"]);
    $accounts = Account::fetchPage();
    foreach ($accounts as $key => $value) {
      print_r($value->__toString());
    }
  }

  public function testAccountGetNotFound()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $account = new Account(['id' => '000000000000000000000000']);
    var_export($account->name());
  }

  public function testAccountExist()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $account = new Account(['id' => '000000000000000000000000']);
    $res = $account->exists();
    $this->assertEquals(false, $res);

    $account = new Account(['id' => '53f78029e4b088c4688c4194']);
    $res = $account->exists();
    $this->assertEquals(true, $res);
  }

  public function testCreateAccount()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $account = new Account(['name' => 'Account4']);
    $res = $account->create();
    $this->assertInstanceOf('Account', $res);
    $this->assertEquals('Account4', $res->name());
  }

  public function testUpdateAccount()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $account = new Account(['id'=>'55143b18e4b016fae539bd0b','name' => 'Account updated']);
    $res = $account->update();
    $this->assertInstanceOf('Account', $res);
    $this->assertEquals('Account updated', $res->name());
  }

  public function testAccountSaveUpdate()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $account = new Account(['id'=>'55143b18e4b016fae539bd0b']);
    $account->name('Account updated2Time');
    $res = $account->save();
    $this->assertInstanceOf('Account', $res);
    $this->assertEquals('Account updated2Time', $res->name());

    $account2 = new Account(['id'=>'55143b18e4b016fae539bd0b']);
    $this->assertEquals('Account updated2Time', $account2->name());
  }

  public function testAccountSaveCreate()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $account = new Account(['name' => 'Account Created']);
    $res = $account->save();
    $this->assertInstanceOf('Account', $res);
    $this->assertEquals('Account Created', $res->name());
  }
      
}
?>
