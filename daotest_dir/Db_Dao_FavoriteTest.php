<?php
require_once dirname(__FILE__) . '/../../../../../lib/php/Db/Dao/Favorite.php';

class Db_Dao_FavoriteTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $dbh;
    protected $obj;

    public function __construct()
    {
        $dao = new Db_Dao_Favorite();
        $this->dbh = $dao->getDbHandler();
    }

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->dbh, 'demo');
    }

    protected function getDataSet()
    {
        $xml_path = dirname(__FILE__) . '/../../../../data';
        return $this->createFlatXMLDataSet($xml_path . '/favorite-seed.xml');
    }

    public function setUp()
    {
        $this->obj = new Db_Dao_Favorite();
        parent::setUp();
    }

    public function test_dummy()
    {
        echo "ダミーテスト";
    }

    /*
    public function test_favoriteテーブルに意図したデータを入れることができる()
    {
      $user_id = "1";
      $content_id = "1";
      
      $this->assertTrue($this->obj->insertFavorite($user_id, $content_id));

    }

    public function test_user_idを入力したらcontent_idのリストが返ってくる()
    {
      $user_id = "1";
      $expected = array("content_id"=>"1","content_id"=>"2", "content_id"=>"3");

      
      $this->assertEquals($expected,$this->obj->getFavorite($user_id));

    }
    */
    
}

