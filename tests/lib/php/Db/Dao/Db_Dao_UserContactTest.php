<?php
require_once dirname(__FILE__) . '/../../../../../lib/php/Db/Dao/UserContact.php';

class Db_Dao_UserContactTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $dbh;
    protected $obj;

    public function __construct()
    {
        $dao = new Db_Dao_UserContact();
        $this->dbh = $dao->getDbHandler();
    }

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->dbh, 'demo');
    }

    protected function getDataSet()
    {
        $xml_path = dirname(__FILE__) . '/../../../../data';
        return $this->createFlatXMLDataSet($xml_path . '/user_contact-seed.xml');
    }

    public function setUp()
    {
        $this->obj = new Db_Dao_UserContact();
        parent::setUp();
    }

    public function test_DBに意図したデータを入れることができる()
    {
        $insert = array(
            'user_id' => '1',
            'contact_title' => 'title1',
            'contact_body' => 'body1',
            'contact_type' => '1',
            'contact_content_url' => 'hoge@hoge.com',
            'contact_purchase_num' => '1111',
            'created_at' => '2012-01-01 00:00:00',
            'updated_at' => '2012-02-02 00:00:00',
        );

        $this->assertEquals(5,$this->obj->insertForm($insert));
    }

    /**
     * @exceptedException InvalidArgumentException
     */
    public function test_DBに失敗した場合Exception()
    {

        $insert = array(
            'user_id' => '',
            'contact_title' => 'title1',
            'contact_body' => 'body1',
            'contact_type' => '',
            'contact_content_url' => 'hoge@hoge.com',
            'contact_purchase_num' => '1111',
            'created_at' => '2012-01-01 00:00:00',
            'updated_at' => '2012-02-02 00:00:00',
        );

        $this->obj->insertForm($insert);
    }

    public function test_idから一つの問い合わせ情報を取得する()
    {
        $id = 1;
        $expected = array(
            'id'=>1,
            'user_id'=>1,
            'contact_title'=>'test1',
            'contact_body'=>'test1 body',
            'contact_type'=>1,
            'contact_content_url'=>'http://sample1.com',
            'contact_purchase_num'=>1,
            'contact_status_id'=>1,
            'contact_memo'=>'test1memo',
            'created_at'=>'2012-01-01 00:00:00',
            'updated_at'=>'2012-01-01 00:00:00',
            'status'=>'未処理',
            'user_name'=>'test1',
            'email'=>'test1',
        );

        $this->assertEquals($expected, $this->obj->findById($id));
    }

    public function test_ステータスIDから該当する複数レコードの情報を配列で返す()
    {
        $status_id = 3;
        $expected = array(
            array(
                'id'=>3,
                'user_id'=>3,
                'contact_title'=>'test3',
                'contact_body'=>'test3 body',
                'contact_type'=>3,
                'contact_content_url'=>'http://sample1.com',
                'contact_purchase_num'=>3,
                'contact_status_id'=>$status_id,
                'contact_memo'=>'test1memo',
                'created_at'=>'2012-01-01 00:00:00',
                'updated_at'=>'2012-01-01 00:00:00',
                'status'=>'処理済',
                'user_name'=>'testuser',
                'email'=>'testuser',
            ),
            array(
                'id'=>4,
                'user_id'=>4,
                'contact_title'=>'test4',
                'contact_body'=>'test4 body',
                'contact_type'=>3,
                'contact_content_url'=>'http://sample1.com',
                'contact_purchase_num'=>3,
                'contact_status_id'=>$status_id,
                'contact_memo'=>'test1memo',
                'created_at'=>'2012-01-01 00:00:00',
                'updated_at'=>'2012-01-01 00:00:00',
                'status'=>'処理済',
                'user_name'=>'admin',
                'email'=>'admin@admin.com',
            )
        );

        $this->assertEquals($expected, $this->obj->findByStatusId($status_id));
    }

    public function test_ステータスを指定せずに取得すると全件返ってくる()
    {
        $status_id = null;
        $expected = array(
            array(
                'id'=>1,
                'user_id'=>1,
                'contact_title'=>'test1',
                'contact_body'=>'test1 body',
                'contact_type'=>1,
                'contact_content_url'=>'http://sample1.com',
                'contact_purchase_num'=>1,
                'contact_status_id'=>1,
                'contact_memo'=>'test1memo',
                'created_at'=>'2012-01-01 00:00:00',
                'updated_at'=>'2012-01-01 00:00:00',
                'status'=>'未処理',
                'user_name'=>'test1',
                'email'=>'test1',
            ),
            array(
                'id'=>2,
                'user_id'=>2,
                'contact_title'=>'test2',
                'contact_body'=>'test2 body',
                'contact_type'=>2,
                'contact_content_url'=>'http://sample1.com',
                'contact_purchase_num'=>2,
                'contact_status_id'=>2,
                'contact_memo'=>'test1memo',
                'created_at'=>'2012-01-01 00:00:00',
                'updated_at'=>'2012-01-01 00:00:00',
                'status'=>'対応中',
                'user_name'=>'test2',
                'email'=>'test2',
            ),
            array(
                'id'=>3,
                'user_id'=>3,
                'contact_title'=>'test3',
                'contact_body'=>'test3 body',
                'contact_type'=>3,
                'contact_content_url'=>'http://sample1.com',
                'contact_purchase_num'=>3,
                'contact_status_id'=>3,
                'contact_memo'=>'test1memo',
                'created_at'=>'2012-01-01 00:00:00',
                'updated_at'=>'2012-01-01 00:00:00',
                'status'=>'処理済',
                'user_name'=>'testuser',
                'email'=>'testuser',
            ),
            array(
                'id'=>4,
                'user_id'=>4,
                'contact_title'=>'test4',
                'contact_body'=>'test4 body',
                'contact_type'=>3,
                'contact_content_url'=>'http://sample1.com',
                'contact_purchase_num'=>3,
                'contact_status_id'=>3,
                'contact_memo'=>'test1memo',
                'created_at'=>'2012-01-01 00:00:00',
                'updated_at'=>'2012-01-01 00:00:00',
                'status'=>'処理済',
                'user_name'=>'admin',
                'email'=>'admin@admin.com',
            )
        );

        $this->assertEquals($expected, $this->obj->findByStatusId(NULL));
        $this->assertEquals($expected, $this->obj->findByStatusId());
    }

    public function test_更新に成功した場合はTRUEが返って来ること()
    {
        $contact_id = 3;
        $params = array(
            'contact_status_id' => 2,
            'contact_memo' => 'memo update',
            'updated_at' => '2012-12-12 00:00:00'
        );

        $this->assertTrue($this->obj->update($contact_id, $params));

        $contact = $this->obj->findById($contact_id);
        foreach( $params as $k => $v)
        {
            $this->assertEquals($v, $contact[$k]);
        }
    }

    public function test_更新に失敗した場合はFALSEが返って来ること()
    {
        $contact_id = 10;
        $params = array(
            'contact_status_id' => 2,
            'contact_memo' => 'memo update',
            'updated_at' => '2012-12-12 00:00:00'
        );

        $this->assertFalse($this->obj->update($contact_id, $params));
    }

    public function test_全レコード数を返すこと()
    {
        $xml_path = dirname(__FILE__) . '/../../../../data';
        $xmldoc = simplexml_load_file($xml_path . '/user_contact-seed.xml');

        $expected = count($xmldoc->user_contact);

        $this->assertEquals($expected, $this->obj->countAll());
    }

    public function test_指定したLIMITの数だけ返ってくること()
    {
        $offset = 0;
        $expected = 2;
        $this->assertEquals($expected, count($this->obj->findLatestListByStatusId($offset, $expected)));
    }

    public function test_指定したID以降のレコードが返って来ること()
    {
       $offset = 2;
       $limit = 3;     
       
       $contents = $this->obj->findLatestListByStatusId($offset, $limit);

       foreach( $contents as $content ){
           $this->assertTrue($content['id'] > $offset); 
       }
    }

    public function test_指定ステータスIDのレコードのみが返ってくること()
    {
       $offset = 2;
       $limit = 3;     
       $status_id = 3;

       $contents = $this->obj->findLatestListByStatusId($offset, $limit);

       foreach( $contents as $content ){
           $this->assertTrue($content['id'] > $offset); 
           $this->assertTrue(intval($content['contact_status_id']) === $status_id);
       }
    }
}
