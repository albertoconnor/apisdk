<?php
include_once "globalVar.php";
include_once "../users.php";
class UserTest extends PHPUnit_Framework_TestCase
{
  public function testUsersGetOne() {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $user = new User(['id' => '538530d2e4b00530d85ae1bf']);
    $this->assertInstanceOf('User', $user);
  }

  public function testUsersFactory() {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $data = ['id' => '538530d2e4b00530d85ae1bf'];
    $other = User::factory($data);
    $this->assertInstanceOf('User', $other);
    $this->assertEquals($data['id'], $other->id());
  }

  public function testUsers() {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $data = ['id' => '538530d2e4b00530d85ae1bf', 'name' => 'Juan Perez', 'email' => 'juan@gmail.com'];
    $other = new User(['data'=>$data]);
    $this->assertInstanceOf('User', $other);
    $this->assertEquals($data['id'], $other->id());
    $this->assertEquals($data['name'], $other->name());
    $this->assertEquals($data['email'], $other->email());
  }
}
?>
