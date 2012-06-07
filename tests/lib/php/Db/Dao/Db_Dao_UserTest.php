<?php
require_once dirname(__FILE__) . '/../../../../../lib/php/Db/Dao/User.php';

class Db_Dao_UserTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $dbh;
    protected $obj;

    public function __construct()
    {
        $dao = new Db_Dao_User();
        $this->dbh = $dao->getDbHandler();
    }

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->dbh, 'demo');
    }

    protected function getDataSet()
    {
        $xml_path = dirname(__FILE__) . '/../../../../data';
        return $this->createFlatXMLDataSet($xml_path . '/user-seed.xml');
    }

    public function setUp()
    {
        $this->obj = new Db_Dao_User();
        parent::setUp();
    }

    public function test_指定したIDのユーザー情報が取得できること()
    {
        $expected = array(
            'id' => '1',
            'name' => 'test1',
            'email' => 'test1',
            'password' => 'test1',
            'salt' => 'test',
            'birthday' => '2000-01-01',
	    'privilege' => '0',
            'created_at' => '2012-01-01 00:00:00',
            'updated_at' => '2012-02-02 00:00:00'
        );

        $result = $this->obj->findByUserId(1);

        $this->assertEquals($expected, $result);
    }

    public function test_指定したIDのユーザー情報がない場合falseが返ること()
    {
        $result = $this->obj->findByUserId(9999);
        $this->assertFalse($result);
    }

    public function test_指定した名前のユーザー情報が取得できること()
    {
        $expected = array(
            'id' => '1',
            'name' => 'test1',
            'email' => 'test1',
            'password' => 'test1',
            'salt' => 'test',
            'birthday' => '2000-01-01',
        );

        $result = $this->obj->findByName('test1');

        $this->assertEquals($expected, $result);
    }

    public function test_指定した名前のユーザ情報がない場合falseが返ること()
    {
        $result = $this->obj->findByName('a');
        $this->assertFalse($result);
    }

    public function test_指定した名前のユーザーがいる場合ユーザー数を取得できること()
    {
        $result = $this->obj->countByName('test1');
        $this->assertSame(1, $result);
    }

    public function test_指定した名前のユーザーがいない場合0が取得できること()
    {
        $result = $this->obj->countByName('test9999999');
        $this->assertSame(0, $result);
    }

    public function findData($name, $password, $salt, $email, $birthday)
    {
        $dbh = $this->obj->getDbHandler();

        $query  = 'select * from user where name = :NAME and password = :PASSWORD and salt = :SALT and email = :EMAIL and birthday = :BIRTHDAY';
        $statement = $dbh->prepare($query);
        $statement->bindValue(':NAME', $name, PDO::PARAM_STR);
        $statement->bindValue(':PASSWORD', $password, PDO::PARAM_STR);
        $statement->bindValue(':SALT', $salt, PDO::PARAM_STR);
        $statement->bindValue(':EMAIL', $email, PDO::PARAM_STR);
        $statement->bindValue(':BIRTHDAY', $birthday, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function test_ユーザー情報が追加できること()
    {
        $name = 'TEST-USER';
        $password = 'TEST-USER-PASS';
        $salt = 'TEST-USER-SALT';
        $email = 'TEST-USER';
        $birthday = '2000-01-01';
        $this->assertTrue($this->obj->insert($name, $password, $salt, $email, $birthday));

        $result = $this->findData($name, $password, $salt, $email, $birthday);
        $this->assertNotNull($result['id']);
        $this->assertNotNull($result['created_at']);
    }
}
