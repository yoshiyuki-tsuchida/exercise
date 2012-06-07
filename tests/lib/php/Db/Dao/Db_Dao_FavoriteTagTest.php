<?php
require_once dirname(__FILE__) . '/../../../../../lib/php/Db/Dao/FavoriteTag.php';

class Db_Dao_FavoriteTagTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $dbh;
    protected $obj;

    public function __construct()
    {
        $dao = new Db_Dao_FavoriteTag();
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
        $this->obj = new Db_Dao_FavoriteTag();
        parent::setUp();
    }

    public function test_dummy()
    {
        echo "ダミーテスト";
    }

    /*
    public function test_favorite_tagテーブルに意図したデータを入れることができる()
    {
      $favarite_id = "1";
      $tags = array("犬","太陽","かわいい");

      
      $this->assertTrue($this->obj->insertFavoriteTag($favarite_id, $tags));

    }
     */
}

