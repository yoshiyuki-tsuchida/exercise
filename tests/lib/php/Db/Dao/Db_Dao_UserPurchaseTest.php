<?php
require_once dirname(__FILE__) . '/../../../../../lib/php/Db/Dao/UserPurchase.php';

class Db_Dao_UserPurchaseTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $dbh;
    protected $obj;

    public function __construct()
    {
        $dao = new Db_Dao_UserPurchase();
        $this->dbh = $dao->getDbHandler();
    }

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->dbh, 'demo');
    }

    protected function getDataSet()
    {
        $xml_path = dirname(__FILE__) . '/../../../../data';
        return $this->createFlatXMLDataSet($xml_path . '/user_purchase-seed.xml');
    }

    public function setUp()
    {
        $this->obj = new Db_Dao_UserPurchase();
        parent::setUp();
    }

    public function test_idから1件のレコードを取得する()
    {
        $id = 1;
        $expected = array(
            'id'             => $id,
            'user_id'        =>1,
            'content_id'     =>1,
            'purchase_price' =>100,
            'purchase_type'  =>2,
            'created_at'     =>'2012-05-01 00:00:00',
            'updated_at'     =>'2012-05-05 00:00:00',
        );

        $this->assertEquals($expected, $this->obj->findById($id));
    }

    public function test_ユーザーIDからそのユーザーのコンテンツ情報が返ってくる()
    {
        $expected = array(
            array(
                'id'            => 1,
                'user_id'       => 1,
                'content_id'    => 1,
                'title'         => 'Content1',
                'description'   => 'DescDesc',
                'purchase_price'=> 100,
                'purchase_type' => 2,
                'created_at'    => '2012-05-01 00:00:00',
                'released_at'    => '2012-01-01 00:00:00'
            ),
            array(
                'id'            => 2,
                'user_id'       => 1,
                'content_id'    => 3,
                'title'         => 'Content3',
                'description'   => 'DescDesc',
                'purchase_price'=> 1000,
                'purchase_type' => 2,
                'created_at'    => '2012-05-02 00:00:00',
                'released_at'   => '2012-02-01 00:00:00'
            )
        );

        $this->assertEquals($expected, $this->obj->findByUserId(1));
    }

    public function test_ユーザーIDとコンテンツIDから1件の該当レコードが返ってくる()
    {
        $user_id = 2;
        $content_id = 5;
        $expected = array(
            'id'             =>3,
            'user_id'        => $user_id,
            'content_id'     => $content_id,
            'purchase_price' =>200,
            'purchase_type'  =>2,
            'created_at'     =>'2012-05-03 00:00:00',
            'updated_at'     =>'2012-05-05 00:00:00',
        );

        $this->assertEquals($expected, $this->obj->findByUserIdAndContentId($user_id, $content_id));
    }


    public function test_購入処理でデータがinsertする()
    {
      $user_id = 1;
      $content_id = 3;
      $purchase_price = 2000;
      $purchase_type = 2;

      $this->assertTrue($this->obj->insert($user_id, $content_id, $purchase_price, $purchase_type));
    }
}


