<?php
include_once "globalVar.php";
include_once "../contacts.php";
class ContactTest extends PHPUnit_Framework_TestCase
{
  public function testContactProperty() {
    $prope = [ "name" => [[ "value" => "James McSales" ]],
                        "email" => [[ "value" => "james.mcsales@relateiq.com" ],
                                    [ "value" => "jimmy@personal.com"]],
                        "phone" => [ ["value" => "(888) 555-1234"],
                                    [ "value" => "(888) 555-0000"]],
                        "address" => [ [ "value" => "123 Main St, USA"]],
                        "liurl" => [ [ "value" => "https://www.linkedin.com/in/jamesmcsales"]],
                        "twhan" => [ ["value" => "@jamesmcsales" ]],
                        "company" => [ ["value" => "RelateIQ"]],
                        "title" => [ ["value" => "Noob" ]]
                        ];
    $data = ["properties"=>$prope];
    $contact = new Contact($data);
    $this->assertEquals($prope, $contact->properties());

    $email = [ "james.mcsales@relateiq.com", "jimmy@personal.com"];
    $this->assertEquals($email, $contact->property('email'));
    $this->assertEquals(2, count($contact->property('email')));
    
    $phone = [ "(888) 555-1234", "(888) 555-0000"];
    $cont = count($phone);
    $this->assertEquals($phone, $contact->property('phone'));
    $this->assertEquals($cont, count($contact->property('phone')));

    $company = "RelateIQ";
    $this->assertEquals($company, $contact->property('company'));

    $this->assertEquals(null, $contact->property('fax'));

    $addPhone = "(888) 555-5555";
    $this->assertEquals($addPhone, $contact->property('phone', $addPhone));

    $addPhone = "(888) 555-0000";
    $this->assertEquals($addPhone, $contact->property('phone', $addPhone));

    $addPhone = ["(888) 555-5555", "(888) 555-0000"];
    $this->assertEquals($addPhone, $contact->property('phone', $addPhone));
    $this->assertContains("(888) 555-5555", $contact->property('phone', $addPhone));
    $this->assertContains("(888) 555-0000", $contact->property('phone', $addPhone));

    $addPhone = [ "(888) 555-1234", "(888) 555-0000", "(888) 555-5555"];
    $this->assertContains("(888) 555-1234", $contact->property('phone', $addPhone));
    $this->assertContains("(888) 555-0000", $contact->property('phone', $addPhone));
    $this->assertContains("(888) 555-5555", $contact->property('phone', $addPhone));

  }

  public function testPropertyWithMetadata() {
    $prope = [ "name" => [[ "value" => "James McSales" ]],
                        "email" => [[ "value" => "james.mcsales@relateiq.com" ],
                                    [ "value" => "jimmy@personal.com"]],
                        "phone" => [ ["value" => "(888) 555-1234"],
                                    [ "value" => "(888) 555-0000"]],
                        "address" => [ [ "value" => "123 Main St, USA"]],
                        "liurl" => [ [ "value" => "https://www.linkedin.com/in/jamesmcsales"]],
                        "twhan" => [ ["value" => "@jamesmcsales" ]],
                        "company" => [ ["value" => "RelateIQ"]],
                        "title" => [ ["value" => "Noob" ]]
                        ];
    $data = ["properties"=>$prope];
    $contact = new Contact($data);
    $this->assertEquals($prope, $contact->properties());

    $email = [ ["value"=>"james.mcsales@relateiq.com"], ["value"=>"jimmy@personal.com"]];
    $cont = count($email);
    $this->assertEquals($email, $contact->propertyWithMetadata('email'));
    $this->assertEquals(2, count($contact->propertyWithMetadata('email')));
    
    $phone = [ ["value"=>"(888) 555-1234"], ["value"=>"(888) 555-0000"]];
    $cont = count($phone);
    $this->assertEquals($phone, $contact->propertyWithMetadata('phone'));
    $this->assertEquals($cont, count($contact->propertyWithMetadata('phone')));

    $company = ["value"=>"RelateIQ"];
    $this->assertEquals($company, $contact->propertyWithMetadata('company'));

    $this->assertEquals(null, $contact->propertyWithMetadata('fax'));

    $addPhone = [ ["value" => "(888) 555-1234"], 
                  ["value" => "(888) 555-0000"]];
    $this->assertEquals($addPhone, $contact->propertyWithMetadata('phone', $addPhone));

    $addPhone = [[ "value" => "(888) 555-0000"]];
    $this->assertEquals([ "value" => "(888) 555-0000"], $contact->propertyWithMetadata('phone', $addPhone));

    $addPhone = [ ["value" => "(888) 555-1234"], 
                  ["value" => "(888) 555-0000"]];
    $this->assertEquals($addPhone, $contact->property('phone', $addPhone));
    $this->assertContains(["value" => "(888) 555-1234"], $contact->property('phone', $addPhone));
    $this->assertContains(["value" => "(888) 555-0000"], $contact->property('phone', $addPhone));
  }

  public function testContactGetOne()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $data = ["id"=>"54ee0ed6e4b08099b9917451"];
    $contact = new Contact($data);
    $this->assertNotNull($contact->name());
  }

  public function testContactGetAll()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $contacts = Contact::fetchByIds(["54ee0ed6e4b08099b9917451"]);
    $this->assertNotNull($contacts["54ee0ed6e4b08099b9917451"]->id());
    $this->assertNotNull($contacts["54ee0ed6e4b08099b9917451"]->name());
  }

  public function testContactGetNotFound()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $contacts = new Contact(["id"=>"000000000000000000000000"]);
    $this->assertNull($contacts->name());
  }

  public function testContactPost()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $contact = new Contact([]);
    $contact->name("Juan Torrez");
    $contact->email(["juan.torrez@relateiq.com","jtorrez@personal.com"]);
    $contact->phone(["(888) 555-6666","(888) 555-7777"]);
    $contact->address("456 Main St, USA");
    $contact->company("RelateIQ");
    $contact->title("Naab");
    $contact->twhan("@juantorrez");
    $res = $contact->create();
    $this->assertInstanceOf('Contact', $res);

    $contact2 = new Contact(["id"=>$contact->id()]);
    $this->assertEquals($contact->name(), $contact2->name());

  }

  public function testContactPut()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $contact = new Contact(["id"=>"551c7102e4b0e9a403dfb098"]);
    $contact->name("Juan TorrezUpdated3");
    $res = $contact->update();
    $this->assertInstanceOf('Contact', $res);
    
    $contact2 = new Contact(["id"=>$res->id()]);
    $this->assertEquals($contact->name(), $contact2->name());
    $this->assertEquals("Juan TorrezUpdated3", $contact2->name());
  }

  public function testContactExists()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $contact = new Contact(["id"=>"551c7102e4b0e9a403dfb098"]);
    $res = $contact->exists();
    $this->assertEquals(true, $res);

    $contact = new Contact(["id"=>"000000000000000000000000"]);
    $res = $contact->exists();
    $this->assertEquals(false, $res);
    
    $contact = new Contact([]);
    $res = $contact->exists();
    $this->assertEquals(false, $res);
  }

  public function testContactSaveUpdate()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $contact = new Contact(["id"=>"551c7102e4b0e9a403dfb098"]);
    $contact->name("Juan TorrezUpdated2Time");
    $res = $contact->save();
    $this->assertInstanceOf('Contact', $res);
    $this->assertEquals('Juan TorrezUpdated2Time', $res->name());

    $contact2 = new Contact(['id'=>'551c7102e4b0e9a403dfb098']);
    $this->assertEquals('Juan TorrezUpdated2Time', $contact2->name());
    $this->assertEquals($res->name(), $contact2->name());
  }

  public function testContactSaveCreate()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $contact = new Contact([]);
    $contact->name("Maria Perez");
    $res = $contact->save();
    $this->assertInstanceOf('Contact', $res);
    $this->assertEquals('Maria Perez', $res->name());
    $this->assertNotNull($res->id());
  }

  public function testContactDelete()
  {
    //Creating contact...
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $contact = new Contact([]);
    $contact->name("James McSales");
    $contact->email(["james.mcsales3@relateiq.com","jimmy3@personal.com"]);
    $contact->phone(["(888) 555-1234","(888) 555-0000"]);
    $contact->address("123 Main St, USA");
    $contact->company("RelateIQ");
    $contact->title("Noob");
    $contact->twhan("@jamesmcsales");
    $res = $contact->create();
    $id = $res->id();
    $this->assertNotNull($id);
    
    // "Deleting contact..."
    $res2 = $contact->delete();
    $this->assertEquals(true, $res2);
    
    // "Verifying deletion..."
    $contact2 = new Contact(["id"=>$id]);
    $res3 = $contact2->exists();
    $this->assertEquals(false, $res3);
  }
  
  public function testContactMetada()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $contact = new Contact([]);
    $data = array ('name' => array ( 0 => array ('value' => 'Juan TorrezUpdated','metadata' => array ())),
                  'email' => array ( 0 => array ('value' => 'tim.archer@avocado.com','metadata' => array ()),
                                    1 => array ('value' => 'tarcher@webmail.org','metadata' => array ())));
    $dataRes = array ('name' => array ( 0 => array ('value' => 'Juan TorrezUpdated')),
                  'email' => array ( 0 => array ('value' => 'tim.archer@avocado.com'),
                                    1 => array ('value' => 'tarcher@webmail.org')));
    $res = $contact->metadata($data);
    $this->assertEquals($dataRes, $res);
  }

  public function testContactLimit()
  {
    Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
    $testLimit = 1;
    Contact::setPageSize($testLimit);
    $this->assertEquals($testLimit, Contact::getPageSize());
    $contacts = Contact::fetchByIds(["54ee0ed6e4b08099b9917451"]);
    $this->assertNotNull($contacts["54ee0ed6e4b08099b9917451"]->id());
    $this->assertNotNull($contacts["54ee0ed6e4b08099b9917451"]->name());


    $contacts2 = Contact::fetchByIds([]);
    $this->assertEquals([], $contacts2);
  }
}
?>
