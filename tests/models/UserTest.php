<?php
require_once dirname(__FILE__) . '/../../models/User.php';
require_once dirname(__FILE__) . '/../../lib/php/Factory.php';

class UserTest extends PHPUnit_Framework_TestCase
{
    protected $obj;

    public function setUp()
    {
        $this->obj = new User();
    }

    public function tearDown()
    {
    }

    public function  test_テーブルに指定した名前のデータが存在しないときtrueになること()
    {
        $user = $this->getMock('Db_Dao_User', array('countByName'));
        $user->expects($this->once())
            ->method('countByName')
            ->with('a')
            ->will($this->returnValue(1));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($user));

        $this->obj->setFactory($factory);

        $this->assertTrue($this->obj->isMember('a'));
    }

    public function test_テーブルに指定した名前のデータが存在しないときfalseになること()
    {
        $user = $this->getMock('Db_Dao_User', array('countByName'));
        $user->expects($this->once())
            ->method('countByName')
            ->with('a')
            ->will($this->returnValue(0));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($user));

        $this->obj->setFactory($factory);

        $this->assertFalse($this->obj->isMember('a'));
    }

    public function test_ユーザーの追加が行われること()
    {
        $name = 'NAME';
        $pass = 'PASS';
        $salt = 'SALT';
        $email = 'EMAIL';
        $birthday = 'BIRTHDAY';

        $user = $this->getMock('Db_Dao_User');
        $user->expects($this->once())
            ->method('insert')
            ->with($name, $pass, $salt, $email, $birthday)
            ->will($this->returnValue(true));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($user));

        $this->obj->setFactory($factory);

        $this->assertTrue($this->obj->register($name, $pass, $salt, $email, $birthday));
    }

    public function test_ユーザーIDを指定してDBからデータを取得してプロパティに設定されること()
    {
      $id = 1;
        $expected = array(
            'id' => 1,
            'name' => 'test',
            'password' => 'pass',
            'salt' => 'salt',
            'email' => 'email',
            'birthday' => 'birthday'
        );
        $user = $this->getMock('Db_Dao_User');
        $user->expects($this->once())
            ->method('findByUserId')
            ->with($id)
            ->will($this->returnValue($expected));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($user));

        $this->obj->setFactory($factory);

        $this->obj->findById($id);

        foreach ($expected as $k => $v) {
            $this->assertEquals($v, $this->obj->$k);
        }
    }
    public function test_ユーザー名を指定してDBからデータを取得してプロパティに設定されること()
    {
      $name = 'test';
      $expected = array(
            'id' => 1,
            'name' => 'test',
            'password' => 'pass',
            'salt' => 'salt',
            'email' => 'email',
            'birthday' => 'birthday'
        );
        $user = $this->getMock('Db_Dao_User');
        $user->expects($this->once())
            ->method('findByName')
            ->with($name)
            ->will($this->returnValue($expected));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($user));

        $this->obj->setFactory($factory);

        $this->obj->findByName($name);

        foreach ($expected as $k => $v) {
            $this->assertEquals($v, $this->obj->$k);
        }
    }

// ============================================================================
// 以下 追加実装部分
// ============================================================================

    public function test_指定したidのデータが存在しない場合nullが返ること()
    {
      $id = 3;

      $user = $this->getMock('Db_Dao_User');
        $user->expects($this->once())
            ->method('findByUserId')
            ->with($id)
            ->will($this->returnValue(false));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($user));

        $this->obj->setFactory($factory);

        $this->assertNull($this->obj->findById($id));
    }

    public function test_指定したユーザー名のデータが存在しない場合nullが返ること()
    {
      $name = 'test';

      $user = $this->getMock('Db_Dao_User');
        $user->expects($this->once())
            ->method('findByName')
            ->with($name)
            ->will($this->returnValue(false));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($user));

        $this->obj->setFactory($factory);

        $this->assertNull($this->obj->findByName($name));
    }

    public function test_購入済みコンテンツのIDにはtrueを返すこと()
    {
        $this->obj->id = 1;
        $content_id = 1;

        $expected = array(
            'id'             => 1,
            'user_id'        => $this->obj->id,
            'content_id'     => $content_id,
            'purchase_price' => 100,
            'purchase_type'  => 3,
            'created_at'     => '2012-05-01 00:00:00',
            'updated_at'     => '2012-05-05 00:00:00'
        );

        $purchase = $this->getMock('Db_Dao_UserPurchase', array('findByUserIdAndContentId'));
        $purchase->expects($this->any())
            ->method('findByUserIdAndContentId')
            ->with($this->obj->id, $content_id)
            ->will($this->returnValue($expected));

        $factory = $this->getMock('Factory');
        $factory->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($purchase));

        $this->obj->setFactory($factory);

        $this->assertTrue($this->obj->didPurchased($content_id));
    }

    public function test_未購入コンテンツのIDにはfalseを返すこと()
    {
        $this->obj->id = 1;
        $content_id = 1;
        $expected = false;

        $purchase = $this->getMock('Db_Dao_UserPurchase', array('findByUserIdAndContentId'));
        $purchase->expects($this->any())
            ->method('findByUserIdAndContentId')
            ->with($this->obj->id, $content_id)
            ->will($this->returnValue($expected));

        $factory = $this->getMock('Factory');
        $factory->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($purchase));

        $this->obj->setFactory($factory);

        $this->assertFalse($this->obj->didPurchased($content_id));
    }
}
