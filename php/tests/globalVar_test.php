<?php
include_once "globalVar.php";
class GlobalVarTest extends PHPUnit_Framework_TestCase
{
	public function testKeyShouldBeSet() {
        $this->assertNotNull(GlobalVar::KEY);
    }

    public function testSecretShouldBeSet() {
    	$this->assertNotNull(GlobalVar::SECRET);
    }
}