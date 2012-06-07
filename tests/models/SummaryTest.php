<?php
require_once dirname(__FILE__) . '/../../models/Summary.php';
require_once dirname(__FILE__) . '/../../lib/php/Factory.php';

class SummaryTest extends PHPUnit_Framework_TestCase
{
    protected $obj;

    public function setUp()
    {
        $this->obj = new Summary();
    }

    public function test_日付を指定したらその期間のコンテンツのデータを取ってくる()
    {
        $expected = array(
            array('date' => '2012-01-01 00:00:00',
                  'sales' => 1000),
            array('date' => '2012-01-02 00:00:00',
                  'sales' => 1000),
            array('date' => '2012-01-03 00:00:00',
                  'sales' => 1000),
            array('date' => '2012-01-04 00:00:00',
                  'sales' => 1000)
        );


        $dao_mock = $this->getMock('Db_Dao_Summary', array('select_summary_by_date'));

        $dao_mock->expects($this->any())
            ->method('select_summary_by_date')
            ->will($this->returnValue($expected));

        $factory = $this->getMock('Factory');
        $factory->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($dao_mock));

        $this->obj->setFactory($factory);

		$this->assertEquals($expected, $this->obj->getSalesSummaryByDate("2012-01-01","2012-01-04" ));
		$this->assertEquals($expected, $this->obj->getSalesSummaryByDate(NULL,"2012-01-04" ));
		$this->assertEquals($expected, $this->obj->getSalesSummaryByDate("2012-01-01",NULL ));

		
    }




    public function test_存在しないデータの日付を指定したら空の配列を返す()
    {
      $dao_mock = $this->getMock('Db_Dao_Summary', array('select_summary_by_date'));
      
      $dao_mock->expects($this->once())
		->method('select_summary_by_date')
		->will($this->returnValue(array()));
      
      $factory = $this->getMock('Factory');
      $factory->expects($this->once())
		->method('__call')
		->will($this->returnValue($dao_mock));
	 
      $this->obj->setFactory($factory);
      
      $this->assertEquals(array(), $this->obj->getSalesSummaryByDate("2013-01-01","2014-01-04" ));
      
    }



//    public function test_日付を指定したら合計金額を取ってくる()
//    {
//
//        $dao_mock = $this->getMock('Db_Dao_Summary', array('select_sales_summary'));
//
//        $dao_mock->expects($this->once())
//            ->method('select_sales_summary')
//            ->will($this->returnValue(4000));
//
//        $factory = $this->getMock('Factory');
//        $factory->expects($this->once())
//            ->method('__call')
//            ->will($this->returnValue($dao_mock));
//
//        $this->obj->setFactory($factory);
//
//        $this->assertEquals(4000, $this->obj->getSalesSummary("2012-01-01","2012-01-04" ));
//	$this->assertEquals(4000, $this->obj->getSalesSummary(NULL,"2012-01-04" ));
//	$this->assertEquals(4000, $this->obj->getSalesSummary("2012-01-01",NULL ));
//	$this->assertEquals(NULL, $this->obj->getSalesSummary("2013-01-01","2014-01-04" ));
//    }

}
