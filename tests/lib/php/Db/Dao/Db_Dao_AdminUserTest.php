<?php
require_once dirname(__FILE__) . '/../../../../../lib/php/Db/Dao/AdminUser.php';

class Db_Dao_AdminUserTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $dbh;
    protected $obj;

    public function __construct()
    {
        $dao = new Db_Dao_AdminUser();
        $this->dbh = $dao->getDbHandler();
    }

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->dbh, 'demo');
    }

        
    protected function getDataSet()
    {
        $xml_path = dirname(__FILE__) . '/../../../../data';
        return $this->createFlatXMLDataSet($xml_path . '/admin_user-seed.xml');
    }
    
    public function setUp()
    {
        $this->obj = new Db_Dao_AdminUser();
	parent::setUp();
    }

    public function findData($name, $password, $salt, $email, $role_id)
    {
        $dbh = $this->obj->getDbHandler();

        $query  = 'select * from admin_user where name = :NAME and password = :PASSWORD and salt = :SALT and email = :EMAIL and role_id = :ROLE_ID';
        $statement = $dbh->prepare($query);
        $statement->bindValue(':NAME', $name, PDO::PARAM_STR);
        $statement->bindValue(':PASSWORD', $password, PDO::PARAM_STR);
        $statement->bindValue(':SALT', $salt, PDO::PARAM_STR);
        $statement->bindValue(':EMAIL', $email, PDO::PARAM_STR);
        $statement->bindValue(':ROLE_ID', $role_id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    /*
    public function test_指定したAdminIDのユーザー情報が取得できること()
    {
      $this->assertTrue($this->obj->findBUserId(1));
    }
    */
    public function test_AdminIDのユーザーを追加と削除できること()
    {
      $name = 'TEST-USER';
      $password = 'TEST-USER-PASS';
      $salt = 'TEST-USER-SALT';
      $email = 'TEST-USER';
      $role_id = '2';
      $this->assertTrue($this->obj->insertAdmin($name, $password, $salt, $email, $role_id));
      
      $result = $this->findData($name, $password, $salt, $email, $role_id);
      $this->assertEquals($name, $result['name']);
      $this->assertEquals($email, $result['email']);

	  
      $this->assertTrue($this->obj->deleteAdmin($name));
      $result = $this->findData($name, $password, $salt, $email, $role_id);
      $this->assertFalse($result);
	}

    public function test_存在する名前の個数を返す()
    {
      $name = 'test1';
      $this->assertEquals(1,$this->obj->countByName($name));
    }



    public function test_id指定で対応するAdminユーザーの情報を一件返す()
    {
      $id = 1;
      $expected = array(
			'id'=>$id,
			'name'=>'test1',
			'password'=>'+8BKlIVe2FP8Rg57h6e6lamdzxly9zBQOYYtBzW4PGQ=',
			'salt'=>'test1',
			'email'=>'test1',
			'role_id'=>'1',
			'created_at'=>'2012-01-01 00:00:00',
			'updated_at'=>'2012-02-02 00:00:00',
			);
      $this->assertEquals($expected, $this->obj->findById($id));

    }


    public function test_名前指定で対応するAdminユーザーの情報を一件返す()
    {
      $name = 'test1';
      $expected = array(
			'id'=>1,
			'name'=>$name,
			'password'=>'+8BKlIVe2FP8Rg57h6e6lamdzxly9zBQOYYtBzW4PGQ=',
			'salt'=>'test1',
			'email'=>'test1',
			'role_id'=>'1',
			'created_at'=>'2012-01-01 00:00:00',
			'updated_at'=>'2012-02-02 00:00:00',
			);
      $this->assertEquals($expected, $this->obj->findByName($name));

    }

}
