<?php
require_once dirname(__FILE__) . '/../../models/Content.php';
require_once dirname(__FILE__) . '/../../lib/php/Factory.php';

class ContentTest extends PHPUnit_Framework_TestCase
{
    protected $obj;

    public function setUp()
    {
        $this->obj = new Content();
    }

    public function test_指定したページ数からページャー情報が取得できること()
    {
        $expected = array(
            'prev' => null,
            'page' => 1,
            'next' => 2,
            'total' => 5, 
            'list' => array(new Content(), new Content())
        );

        $content = $this->getMock('Db_Dao_Content', array('countAll', 'findLatestList'));

        $content->expects($this->once())
            ->method('countAll')
            ->will($this->returnValue(10));

        $content->expects($this->once())
            ->method('findLatestList')
            ->with(0, 2)
            ->will($this->returnValue(array(1, 2)));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($content));

        $this->obj->setFactory($factory);

        $this->assertEquals($expected, $this->obj->paginate(1, 2));
    }

    public function test_指定したトータルページ数以上のページ数を指定した場合最終ページの情報が返ること()
    {
        $expected = array(
            'prev' => 4,
            'page' => 5,
            'next' => null,
            'total' => 5, 
            'list' => array(new Content(), new Content())
        );

        $content = $this->getMock('Db_Dao_Content', array('countAll', 'findLatestList'));

        $content->expects($this->once())
            ->method('countAll')
            ->will($this->returnValue(10));

        $content->expects($this->once())
            ->method('findLatestList')
            ->with(8, 2)
            ->will($this->returnValue(array(9, 10)));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($content));

        $this->obj->setFactory($factory);

        $this->assertEquals($expected, $this->obj->paginate(6, 2));
    }

// ============================================================================
// 以下 追加実装部分
// ============================================================================

    public function test_存在するIDから該当するConentオブジェクトが返ってくること()
    {
        $content_id = 1;

        $expected = array(
            'id' => $content_id,
            'title' => 'test',
            'author' => 'taji',
            'price' => 1000,
            'image_path' => 'test',
            'category' => 'sport',
            'description' => 'testtest',
        );
        $content = $this->getMock('Db_Dao_Content');
        $content->expects($this->any())
            ->method('findById')
            ->with($content_id)
            ->will($this->returnValue($expected));

        $factory = $this->getMock('Factory');
        $factory->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($content));

        $this->obj->setFactory($factory);
        $this->obj->findById($content_id);

        foreach ($expected as $k => $v) {
            $this->assertEquals($v, $this->obj->$k);
        }
    }

    public function test_存在しないIDにはNULLを返すこと()
    {
        $content_id = 2;

        $content = $this->getMock('Db_Dao_Content');
        $content->expects($this->any())
            ->method('findById')
            ->with($content_id)
            ->will($this->returnValue(false));

        $factory = $this->getMock('Factory');
        $factory->expects($this->any())
            ->method('__call')
            ->will($this->returnValue($content));

        $this->obj->setFactory($factory);

        $this->assertNull($this->obj->findById($content_id));
    }
}
