<?php
include_once "globalVar.php";
include_once "../client.php";
class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testKeyShouldBeSet() {
        Client::key("foo");
        $this->assertEquals("foo", Client::key());
    }

    public function testSecretShouldBeSet() {
        Client::secret("foo");
        $this->assertEquals("foo", Client::secret());
    }

    public function testEndpointShouldBeSet() {
        Client::endpoint("foo");
        $this->assertEquals("foo", Client::endpoint());
    }

    public function testHeadersShouldBeSet() {
        Client::headers("foo");
        $this->assertEquals("foo", Client::headers());
    }

    public function testRelateIQShouldSetEndpointKeyAndSecret() {
        Client::relateIQ("key", "secret", "http://localhost/api");
        $this->assertEquals("http://localhost/api", Client::endpoint());
        $this->assertEquals("key", Client::key());
        $this->assertEquals("secret", Client::secret());
    }

    public function testRelateIQShouldSetDefaultEndpoint() {
        Client::relateIQ("key", "secret");
        $this->assertEquals("https://api.relateiq.com/v2/", Client::endpoint());
        $this->assertEquals("key", Client::key());
        $this->assertEquals("secret", Client::secret());
    }

    public function testCacheShouldSetCacheForAnEndpoint() {
        Client::cache("http://localhost", "foo");
        $this->assertEquals("foo", Client::cache("http://localhost"));
    }

    public function testCacheShouldreturnEmptyStringIfEndpointDoesNotExist() {
        $this->assertEquals("", Client::cache("http://somethingNonExistent"));
    }

    public function testGetUriOptionsShould() {
        $options = ['id'=>'123456', 'usr'=>'team', 'pwd'=>'secret'];
        $expected = '?id=123456&usr=team&pwd=secret';
        $this->assertEquals($expected, Client::getUriOptions($options));

        $options = ['id'=>'123456'];
        $expected = '123456';
        $this->assertEquals($expected, Client::getUriOptions($options));

        $options = ['team'];
        $expected = 'team';
        $this->assertEquals($expected, Client::getUriOptions($options));

        $options = [];
        $expected = '';
        $this->assertEquals($expected, Client::getUriOptions($options));
    }

    public function testGetClient() {
        Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
        Client::endpoint('https://api.relateiq.com/v2/accounts/');
        $this->assertEquals("https://api.relateiq.com/v2/accounts/", Client::endpoint());

        Client::headers(["Content-type" => "application/json", "Accept" => "application/json"]);
        $this->assertEquals(["Content-type" => "application/json", "Accept" => "application/json"], Client::headers());

        $this->assertEquals(200, Client::get('', [])->code);
    }

    public function testGetAccountClient() {
        Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
        Client::endpoint('https://api.relateiq.com/v2/accounts?_ids=537a8283e4b03f2401a8f7ae&_start=0&_limit=200');
        Client::headers(["Content-type" => "application/json", "Accept" => "application/json"]);

        $this->assertEquals(200, Client::get('', [])->code);
    }

    public function testGetOneAccountClient() {
        Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
        Client::endpoint('https://api.relateiq.com/v2/accounts/538530d2e4b00530d85ae1bf');

        Client::headers(["Content-type" => "application/json", "Accept" => "application/json"]);

        $this->assertEquals(200, Client::get('', [])->code);
    }

    public function testGetNotFoundAccountClient() {
        Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
        Client::endpoint('https://api.relateiq.com/v2/accounts/000000000000000000000000');

        Client::headers(["Content-type" => "application/json", "Accept" => "application/json"]);

        $this->assertEquals(404, Client::get('', [])->code);
    }

    public function testPostClient() {
        Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
        Client::endpoint('https://api.relateiq.com/v2/accounts/');
        $this->assertEquals("https://api.relateiq.com/v2/accounts/", Client::endpoint());

        Client::headers(["Content-type" => "application/json", "Accept" => "application/json"]);
        $this->assertEquals(["Content-type" => "application/json", "Accept" => "application/json"], Client::headers());

        $data = ['name' => 'Account1'];
        $this->assertEquals(200, Client::post('',$data, [])->code);
    }

    public function testPutClient()
    {
        Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
        Client::endpoint('https://api.relateiq.com/v2/accounts/551433f8e4b016fae539b210');

        Client::headers(["Content-type" => "application/json", "Accept" => "application/json"]);

        $data = ['id'=>'551433f8e4b016fae539b210', 'name' => 'Account 11'];
        $this->assertEquals(200, Client::put('',$data, [])->code);
    }

    public function testPutEvent()
    {
        Client::relateIQ(GlobalVar::KEY, GlobalVar::SECRET);
        Client::endpoint('https://api.relateiq.com/v2/events');

        Client::headers(["Content-type" => "application/json", "Accept" => "application/json"]);

        $data = ["subject" => "Support Ticket #12345: How do I create an event?",
        "body" => "Just called Tim and walked him through how to create an event with the new API.\n-James.",
        "participantIds" => [["type" => "email", "value" => "james.mcsales@relateiq.com"],
                            ["type" => "email", "value" => "tim.archer@avocado.com"],
                            ["type" => "phone", "value" => "8001235555"]]
        ];
        $res = Client::put('',$data, []);
        $this->assertEquals(204, $res->code);
    }
}
?>
