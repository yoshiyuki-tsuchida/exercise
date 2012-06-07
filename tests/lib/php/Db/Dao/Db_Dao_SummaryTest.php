<?php
require_once dirname(__FILE__) . '/../../../../../lib/php/Db/Dao/Summary.php';

class Db_Dao_SummaryTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $dbh;
    protected $obj;

    public function __construct()
    {
        $dao = new Db_Dao_Summary();
        $this->dbh = $dao->getDbHandler();
    }

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->dbh, 'demo');
    }

    protected function getDataSet()
    {
        $xml_path = dirname(__FILE__) . '/../../../../data';
        return $this->createFlatXMLDataSet($xml_path . '/summary-seed.xml');
    }

    public function setUp()
    {
        $this->obj = new Db_Dao_Summary();
        parent::setUp();
    }

    public function test_dummy()
    {
        echo "ダミーテスト";
    }

    /*
    public function test_select_summary_by_date()
    {
        $expected = array(
            array('date' => '2012-01-01',
                  'sales' => 1000),
            array('date' => '2012-01-02',
                  'sales' => 1000),
            array('date' => '2012-01-03',
                  'sales' => 1000),
            array('date' => '2012-01-04',
                  'sales' => 1000)
        );
        $this->assertEquals($expected, 
            $this->obj->select_summary_by_date('2012-01-01', '2012-01-04') );
        $this->assertEquals($expected, 
            $this->obj->select_summary_by_date('2012-01-01') );
        $this->assertEquals(array(), 
            $this->obj->select_summary_by_date(null, '2011-01-01') );
    }

    public function test_select_summary_by_content()
    {
        $expected = array(
			'sales_summary' => 4000,
			'summary_by_content' => array (
				array (
					'title' => 'Content7',
	                 'author' => 'Author3',
					 'price' => 1000,
					 'category' => '1',
					 'description' => 'DescDesc',
	                      'image_path' => 'http://placehold.it/260x180',
	                      'created_at' => '2012-01-01 00:00:00',
	                      'updated_at' => '',
	                      'sales' => 4000,
	                  )
	  
	 
			)
        );
        $this->assertEquals($expected, 
            $this->obj->select_summary_by_content() );
    }

    public function test_select_sales_summary()
    {
        $this->assertEquals(4000, $this->obj->select_sales_summary('2012-01-01', '2012-01-04'));
        $this->assertEquals(3000, $this->obj->select_sales_summary('2012-01-02'));
        $this->assertEquals(3000, $this->obj->select_sales_summary(null, '2012-01-03'));
    }
     */
}
