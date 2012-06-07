<?php
require_once dirname(__FILE__) . '/../../models/UserContact.php';
require_once dirname(__FILE__) . '/../../lib/php/Factory.php';

class UserContactTest extends PHPUnit_Framework_TestCase
{
	protected $obj;

	public function setUp()
	{
		$this->obj = new UserContact();
	}

	public function tearDown()
	{
	}

	public function test_insert処理で最新のIDが返ってくる()
	{
		$insert = array(
				'id' => '1',
				'user_id' => '1',
				'contact_title' => 'title1',
				'contact_body' => 'body1',
				'contact_type' => '1',
				'contact_content_url' => 'hoge@hoge.com',
				'contact_purchase_num' => '1111',
				'created_at' => '2012-01-01 00:00:00',
				'updated_at' => '2012-02-02 00:00:00',
				);
		$user_contact = $this->getMock('Db_Dao_UserContact' , array('insertForm'));
		$user_contact->expects($this->once())
			->method('insertForm')
			->with($insert)
			->will($this->returnValue(4));

		$factory = $this->getMock('Factory');
		$factory->expects($this->once())
			->method('__call')
			->will($this->returnValue($user_contact));

		$this->obj->setFactory($factory);

		$this->assertEquals(4,$this->obj->insert($insert));
	}


	public function test_ID指定で情報がプロパティにセットされたオブジェクトを返す()
	{
	  $expected = array(
			    'id' => '1',
			    'user_id' => '1',
			    'contact_title' => 'title1',
			    'contact_body' => 'body1',
			    'contact_type' => '1',
			    'contact_content_url' => 'hoge@hoge.com',
			    'contact_purchase_num' => '1111',
			    'contact_status_id' => '1',
			    'contact_memo' => 'memo1',
			    'created_at' => '2012-01-01 00:00:00',
			    'updated_at' => '2012-02-02 00:00:00',
			    );

	  $contact = $this->getMock('Db_Dao_UserContact');
	  $contact->expects($this->any())
            ->method('findById')
            ->with('1')
            ->will($this->returnValue($expected));
	  
	  $factory = $this->getMock('Factory');
	  $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($contact));
	  
	  $this->obj->setFactory($factory);
	  
	  $this->obj->findById('1');
	  
	  foreach ($expected as $k => $v) {
            $this->assertEquals($v, $this->obj->$k);
	  }
	  
	}

	public function test_全てのレコードが返って来ること()
	{
	  $contacts = array(
			    array(
				  'id' => '1',
				  'user_id' => '1',
				  'contact_title' => 'title1',
				  'contact_body' => 'body1',
				  'contact_type' => '1',
				  'contact_content_url' => 'hoge@hoge.com',
				  'contact_purchase_num' => '1111',
				  'contact_status_id' => '1',
				  'contact_memo' => 'memo1',
				  'created_at' => '2012-01-01 00:00:00',
				  'updated_at' => '2012-02-02 00:00:00',
				  ),
			    array(
				  'id' => '2',
				  'user_id' => '2',
				  'contact_title' => 'title2',
				  'contact_body' => 'body2',
				  'contact_type' => '2',
				  'contact_content_url' => 'hoge@hoge.com',
				  'contact_purchase_num' => '1111',
				  'contact_status_id' => '1',
				  'contact_memo' => 'memo1',	  
				  'created_at' => '2012-01-01 00:00:00',
				  'updated_at' => '2012-02-02 00:00:00',
				  ),
			    );
			    
	  $expected=array();
	  foreach ($contacts as $contact){
	    foreach ($contact as $k => $v){
	      $obj = new UserContact();
	      $obj->$k = $v;
	    }
	    $expected[] = $obj;
	  }
	  
	  $contact = $this->getMock('Db_Dao_UserContact');
	  $contact->expects($this->any())
            ->method('findByStatusId')
	    ->with(null)
            ->will($this->returnValue($contacts));
	  
	  $factory = $this->getMock('Factory');
	  $factory->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($contact));
	  
	  $this->obj->setFactory($factory);

	  $this->assertEquals($expected,$this->obj->findAll());
	}

	public function test_ステータスID指定で対象のレコードを複数返す()
	{
	  $contacts = array(
			    array(
				  'id' => '1',
				  'user_id' => '1',
				  'contact_title' => 'title1',
				  'contact_body' => 'body1',
				  'contact_type' => '1',
				  'contact_content_url' => 'hoge@hoge.com',
				  'contact_purchase_num' => '1111',
				  'contact_status_id' => '1',
				  'contact_memo' => 'memo1',
				  'created_at' => '2012-01-01 00:00:00',
				  'updated_at' => '2012-02-02 00:00:00',
				  ),
			    array(
				  'id' => '2',
				  'user_id' => '2',
				  'contact_title' => 'title2',
				  'contact_body' => 'body2',
				  'contact_type' => '2',
				  'contact_content_url' => 'hoge@hoge.com',
				  'contact_purchase_num' => '1111',
				  'contact_status_id' => '1',
				  'contact_memo' => 'memo2',
				  'created_at' => '2012-01-01 00:00:00',
				  'updated_at' => '2012-02-02 00:00:00',
				  ),
			    );
			    
	  $expected=array();
	  foreach ($contacts as $contact){
	    foreach ($contact as $k => $v){
	      $obj = new UserContact();
	      $obj->$k = $v;
	    }
	    $expected[] = $obj;
	  }
	  
	  $contact = $this->getMock('Db_Dao_UserContact');
	  $contact->expects($this->any())
            ->method('findByStatusId')
	    ->with('2')
            ->will($this->returnValue($contacts));
	  
	  $factory = $this->getMock('Factory');
	  $factory->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($contact));
	  
	  $this->obj->setFactory($factory);

	  $this->assertEquals($expected, $this->obj->findByStatusId('2'));
	}

    public function test_該当IDを更新するとTRUEが返ってくること()
    {
        $contact_id = 1;
        $params = array(
            'contact_status_id' => 2,
            'contact_memo'      => 'Update Memo',
            'updated_at'        => '2012-05-30 00:00:00'
        );

        $contact = $this->getMock('Db_Dao_UserContact');
        $contact->expects($this->any())
            ->method('update')
            ->with($contact_id, $params)
            ->will($this->returnValue(true));

        $factory = $this->getMock('Factory');
        $factory->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($contact));

        $this->obj->setFactory($factory);

        $this->assertTrue($this->obj->update($contact_id, $params));
    }

    public function test_更新が行われなかった場合はFALSEが返ってくること()
    {
        $contact_id = 1;
        $params = array(
            'contact_status_id' => 2,
            'contact_memo'      => 'Update Memo',
            'updated_at'        => '2012-05-30 00:00:00'
        );

        $contact = $this->getMock('Db_Dao_UserContact');
        $contact->expects($this->any())
            ->method('update')
            ->with($contact_id, $params)
            ->will($this->returnValue(false));

        $factory = $this->getMock('Factory');
        $factory->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($contact));

        $this->obj->setFactory($factory);

        $this->assertFalse($this->obj->update($contact_id, $params));
    }
}
