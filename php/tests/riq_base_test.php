<?php
include_once "../riq_base.php";
class RIQBaseTest extends PHPUnit_Framework_TestCase
{
    
    public function testNodeShouldBeSet() {
        $stub = $this->getMockBuilder('RIQBase')
                     ->getMock();
        $this->assertEquals(True, method_exists($stub, 'node'));
    }

    public function testMatrixShouldBeSet() {
        
        $tam = 15;//Def
        $maxSize = 4;//Def
        $parame = array();
        for ($z=0; $z < $tam; $z++) { 
            $parame[$z] = 'Texto '.$z;
        }

        $res = array();
        $pos = 0;
        $elem = 0;
        for ($i=0; $i < count($parame); $i=$i+$maxSize) { 
          for ($j=0; $j < $maxSize; $j++) { 
            if ($elem < count($parame)) {
              $res[$pos][$j] = $parame[$elem];
              $elem ++; 
            }
          }
            $pos++;
        }
        $this->assertEquals($res, RIQBase::matrix($parame, $maxSize));
        
        $maxSize = 2;
        $parame = array( 0 => 'Texto 0', 1 => 'Texto 1',
        '2' => 'Texto 2', '3' => 'Texto 3', 
        '4' => 'Texto 4', '5' => 'Texto 5', 
        '6' => 'Texto 6', '7'=> 'Texto 7', 
        '8' => 'Texto 8', '9' => 'Texto 9', 
        '10' => 'Texto 10', '11' => 'Texto 11');

        $res = array( 0 => Array ( 0 => 'Texto 0', 1 => 'Texto 1' ), 
        1 => array( 0 => 'Texto 2', 1 => 'Texto 3' ),
        2 => array( 0 => 'Texto 4', 1 => 'Texto 5' ),
        3 => array( 0 => 'Texto 6', 1 => 'Texto 7' ),
        4 => array( 0 => 'Texto 8', 1 => 'Texto 9' ),
        5 => array( 0 => 'Texto 10', 1 => 'Texto 11'));

        $this->assertEquals($res, RIQBase::matrix($parame, $maxSize));

        $maxSize = 3;
        $parame = array( 0 => 'Texto 0', 1 => 'Texto 1', 2 => 'Texto 2', 
        3 => 'Texto 3', 4 => 'Texto 4', 5 => 'Texto 5', 
        6 => 'Texto 6', 7 => 'Texto 7', 8 => 'Texto 8', 
        9 => 'Texto 9', 10 => 'Texto 10', 11 => 'Texto 11', 
        12 => 'Texto 12');

        $res = array( 0 => array( 0 => 'Texto 0', 1 => 'Texto 1', 2 => 'Texto 2' ), 
        1 => array( 0 => 'Texto 3', 1 => 'Texto 4', 2 => 'Texto 5' ), 
        2 => array( 0 => 'Texto 6', 1 => 'Texto 7', 2 => 'Texto 8' ),
        3 => array( 0 => 'Texto 9', 1 => 'Texto 10', 2 => 'Texto 11' ), 
        4 => array( 0 => 'Texto 12'));
        $this->assertEquals($res, RIQBase::matrix($parame, $maxSize));
    }

    public function testResetCacheShouldBeSet() {
        $res = array('class' => 'RIQBase',
                'cache' => array(),
                'cache_index' => 0,
                'page_index' => 0,
                'page_length' => 200,
                'fetch_options' => array(),
                'last_modified_date' =>   None,
                'parent' => null);
        RIQBase::resetCache();
        $expe = var_export($res, true);
        $resu = RIQBase::getVarsClass();
        $this->assertEquals($expe, $resu);
    }

    public function testSetPageSizeShouldBeSet() {
        $limit = 50;
        RIQBase::setPageSize($limit);
        $this->assertEquals($limit, RIQBase::getPageSize());
    }

    public function testDummyClassMethodShouldBeSet() {
        $limit = 200;
        RIQBase::setPageSize($limit);

        $this->expectOutputString(RIQBase::getVarsClass(), true);
        RIQBase::dummyClassMethod();
    }

}
?>
