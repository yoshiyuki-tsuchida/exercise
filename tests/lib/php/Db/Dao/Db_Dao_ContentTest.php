<?php
require_once dirname(__FILE__) . '/../../../../../lib/php/Db/Dao/Content.php';

class Db_Dao_ContentTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $dbh;
    protected $obj;

    public function __construct()
    {
        $dao = new Db_Dao_Content();
        $this->dbh = $dao->getDbHandler();
    }

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->dbh, 'demo');
    }

    protected function getDataSet()
    {
        $xml_path = dirname(__FILE__) . '/../../../../data';
        return $this->createFlatXMLDataSet($xml_path . '/content-seed.xml');
    }

    public function setUp()
    {
        $this->obj = new Db_Dao_Content();
        parent::setUp();
    }

    public function test_����ƥ��ID������Υ���ƥ�ľ������������()
    {
      $id = 3;
      $expected = array(
                        'id'          => '3',
                        'title'       => 'Content3',
                        'author'      => 'Author3',
                        'price'       => '2000',
                        'image_path'  => 'http://placehold.it/260x180',
                        'description' => 'DescDesc',
                        'category'    => '1',
                        'created_at'  => '2012-02-01 00:00:00',
                        'updated_at'  => null,
                        );
      $this->assertEquals($expected, $this->obj->findById($id));
    }
     
    public function test_����ƥ�ľ���η��������Ǥ��뤳��()
    {
        $this->assertEquals(9, $this->obj->countAll());
    }

    public function test_���ꤷ���������֤���������ʬ�Υ���ƥ�ľ��󤬼����Ǥ��뤳��()
    {
        $expected = array(
                          array(
                                'id'          => '5',
                                'title'       => 'Content5',
                                'author'      => 'Author1',
                                'price'       => '100',
                                'image_path'  => 'http://placehold.it/260x180',
                                'description' => 'DescDesc',
                                'category'    => '1',
                                '5',
                                'Content5',
                                'Author1',
                                '100',
                                'http://placehold.it/260x180',
                                'DescDesc',
                                '1'
                                ),
                          array(
                                'id'          => '4',
                                'title'       => 'Content4',
                                'author'      => 'Author4',
                                'price'       => '0',
                                'image_path'  => 'http://placehold.it/260x180',
                                'description' => 'DescDesc',
                                'category'    => '2',
                                '4',
                                'Content4',
                                'Author4',
                                '0',
                                'http://placehold.it/260x180',
                                'DescDesc',
                                '2'
                                ),
                          array(
                                'id'          => '3',
                                'title'       => 'Content3',
                                'author'      => 'Author3',
                                'price'       => '2000',
                                'image_path'  => 'http://placehold.it/260x180',
                                'description' => 'DescDesc',
                                'category'    => '1',
                                '3',
                                'Content3',
                                'Author3',
                                '2000',
                                'http://placehold.it/260x180',
                                'DescDesc',
                                '1'
                                )
                          );

        $this->assertEquals($expected, $this->obj->findLatestList(2, 3));
    }

    public function test_�ǡ������ʾ�μ������֤���ꤷ�������������֤뤳��()
    {
        $this->assertEquals(array(), $this->obj->findLatestList(100, 3));
    }
}

