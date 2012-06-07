<?php
require_once dirname(__FILE__) . '/../../models/UserPurchase.php';
require_once dirname(__FILE__) . '/../../lib/php/Factory.php';

class UserPurchaseTest extends PHPUnit_Framework_TestCase
{
	protected $obj;

	public function setUp()
	{
		$this->obj = new UserPurchase();
	}

	public function tearDown()
	{
	}

	public function test_ID指定で情報がプロパティにセットされたオブジェクトを返す()
	{
	  $id = 1;
	  $expected = array(
			    'id' => 1,
			    'user_id' => 1,
			    'content_id' => 1,
			    'purchase_price' => 100,
			    'purchase_type' => 1,
			    'created_at' => '2012-01-01 00:00:00',
			    'updated_at' => '2012-02-02 00:00:00',
			    );
	  $purchase = $this->getMock('Db_Dao_UserPurchase');
	  $purchase->expects($this->once())
	    ->method('findById')
	    ->with($id)
	    ->will($this->returnValue($expected));

	  $factory = $this->getMock('Factory');
	  $factory->expects($this->once())
	    ->method('__call')
	    ->will($this->returnValue($purchase));

	  $this->obj->setFactory($factory);

	  $this->obj->findById($id);

	  foreach ($expected as $k => $v){
	    $this->assertEquals($v, $this->obj->$k);
	  }
	  
	}

	public function test_IDが無ければnullを返す()
	{
	  $id = 3;
	  $purchase = $this->getMock('Db_Dao_UserPurchase');
	  $purchase->expects($this->once())
	    ->method('findById')
	    ->with($id)
	    ->will($this->returnValue(false));

	  $factory = $this->getMock('Factory');
	  $factory->expects($this->once())
	    ->method('__call')
	    ->will($this->returnValue($purchase));

	  $this->obj->setFactory($factory);
	  
	  $this->assertNull($this->obj->findById($id));
	  
	}


	public function test_購入処理をした商品データをinsertする()
	{
	  $user_id = 2;
	  $content_id = 3;
	  $purchase_price = 2000;
	  $purchase_type = 2;

	  $mock = $this->getMock('Db_Dao_UserPurchase');
	  $mock->expects($this->once())
	    ->method('insert')
	    ->with($user_id, $content_id, $purchase_price, $purchase_type)
	    ->will($this->returnValue(true));
	  
	    $factory = $this->getMock('Factory');
	    $factory->expects($this->once())
	      ->method('__call')
	      ->will($this->returnValue($mock));
	    
	    $this->obj->setFactory($factory);
	    
	    
	  $this->assertTrue($this->obj->insertPurchase($user_id, $content_id, $purchase_price, $purchase_type));





	}
	
}
