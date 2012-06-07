<?php
require_once dirname(__FILE__) . '/../../models/AdminUser.php';
require_once dirname(__FILE__) . '/../../lib/php/Factory.php';

class AdminUserTest extends PHPUnit_Framework_TestCase
{
    protected $obj;

    public function setUp()
    {
        $this->obj = new AdminUser();
    }

    public function tearDown()
    {
    }

    public function test_存在する管理者名にはAdminUserオブジェクトが返って来ること()
    {
        $name = 'test1';
        $expected = array(
            'id'        => 1,
            'name'      => 'test1',
            'password'  => 'test1',
            'salt'      => 'test',
            'email'     => 'test1',
            'role_id'   => 1
        );
        $admin = $this->getMock('Db_Dao_AdminUser');
        $admin->expects($this->once())
            ->method('findByName')
            ->with($name)
            ->will($this->returnValue($expected));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($admin));

        $this->obj->setFactory($factory);

        $this->obj->findByName($name);

        foreach($expected as $k => $v){
            $this->assertEquals($v, $this->obj->$k);
        }
    }

    public function test_存在しない管理者名にはNULLが返って来ること()
    {
        $name = 'test33';
        $admin = $this->getMock('Db_Dao_AdminUser');
        $admin->expects($this->any())
            ->method('findByName')
            ->with($name)
            ->will($this->returnValue(false));

        $factory = $this->getMock('Factory');
        $factory->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($admin));

        $this->obj->setFactory($factory);

        $this->assertNull($this->obj->findByName($name));

    }

    public function test_存在する管理者IDにAdminUserオブジェクトが返ってくること()
    {
        $id = 1;;
        $expected = array(
            'id'=>1,
            'name'=>'test1',
            'password'=>'test1',
            'salt'=>'test',
            'email'=>'test1',
            'role_id'=>1
        );
        $admin = $this->getMock('Db_Dao_AdminUser');
        $admin->expects($this->once())
            ->method('findById')
            ->with($id)
            ->will($this->returnValue($expected));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($admin));

        $this->obj->setFactory($factory);

        $this->obj->findById($id);

        foreach ($expected as $k => $v){
            $this->assertEquals($v, $this->obj->$k);
        }
    }

    public function test_存在しない管理者IDにはNULLが返ってくること()
    {
        $id = 3;
        $admin = $this->getMock('Db_Dao_AdminUser');
        $admin->expects($this->any())
            ->method('findById')
            ->with($id)
            ->will($this->returnValue(false));

        $factory = $this->getMock('Factory');
        $factory->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($admin));

        $this->obj->setFactory($factory);

        $this->assertNull($this->obj->findById($id));
    }

    public function test_管理者の追加が可能であること()
    {
        $name = 'NAME';
        $pass = 'PASS';
        $salt = 'SALT';
        $email = 'EMAIL';
        $role_id = '1';

        $admin = $this->getMock('Db_Dao_AdminUser');
        $admin->expects($this->once())
            ->method('insertAdmin')
            ->with($name, $pass, $salt, $email, $role_id)
            ->will($this->returnValue(true));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($admin));

        $this->obj->setFactory($factory);

        $this->assertTrue($this->obj->register($name, $pass, $salt, $email, $role_id));
    }


    public function test_管理者の削除が可能であること()
    {
        $name = 'NAME';

        $admin = $this->getMock('Db_Dao_AdminUser');
        $admin->expects($this->any())
            ->method('deleteAdmin')
            ->with($name)
            ->will($this->returnValue(true));

        $factory = $this->getMock('Factory');
        $factory->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($admin));

        $this->obj->setFactory($factory);

        $this->assertTrue($this->obj->delete($name));

    }
}
