<?php
require_once dirname(__FILE__) . '/Model.php';
require_once dirname(__FILE__) . '/../lib/php/Db/Dao/Content.php';
require_once dirname(__FILE__) . '/../lib/php/DateUtil.php';

class Content extends Model
{
    public $id;
    public $title;
    public $author;
    public $price;
    public $image_path;
    public $category;
    public $description;
    public $discounted_price;

    public function paginate($page = 1, $limit = 1)
    {
        $content_dao = $this->getFactory()->getDb_Dao_Content();
        $num = $content_dao->countAll();
        $total = intval(ceil($num / $limit));
        if ($total < $page) {
            $page = $total;
        }
        $offset = $limit * ($page - 1);
        $list = $content_dao->findLatestList($offset, $limit);

        $list = $this->convertToContentObj($list);

        $prev = $page - 1;
        if ($prev < 1) {
            $prev = null;
        }
        $next = $page + 1;
        if ($total < $next) {
            $next = null;
        }

        return array(
            'prev' => $prev,
            'page' => $page,
            'next' => $next,
            'total' => $total,
            'list' => $list
        );
    }

// ============================================================================
// 以下 追加実装部分
// ============================================================================
    
    /**
     * 配列の各要素をオブジェクトに変換する。
     *
     * @param array Contentの連想配列
     * @return Contentオブジェクトの配列
     *
     */
    private function convertToContentObj($contents)
    {
        $content_objs = array();
        foreach($contents as $content)
        {
            $obj = new Content();

            $obj->id            = $content['id'];
            $obj->title         = $content['title'];
            $obj->author        = $content['author'];
            $obj->price         = $content['price'];
            $obj->image_path    = $content['image_path'];
            $obj->category      = $content['category'];
            $obj->description   = $content['description'];

            $discounted_price   = NULL;

            $content_objs[] = $obj;
        }

        return $content_objs;
    }

    /**
     *
     * コンテンツの割引価格を計算しdiscounted_priceにセットする
     *
     *  a) 発売1ヶ月以内
     *    -> 5%引き( price * 0.95 )
     *  b) 誕生日の前後1週間
     *    -> 10%引き( price * 0.90 )
     *
     *  a,b ともに該当する場合はbを適用
     *
     *  @param $birthday DATETIME. ユーザーの誕生日
     *  @return void
     */
    private function setDiscountedPrice($birthday)
    {
        
    }

	public function findById($content_id)
	{
		$content_dao = $this->getFactory()->getDb_Dao_Content();
		$content = $content_dao->findById($content_id);
		if ($content === false){
		  return null;
		}

		$this->id = $content['id'];
		$this->title = $content['title'];
		$this->author = $content['author'];
		$this->price = $content['price'];
		$this->image_path = $content['image_path'];
		$this->category = $content['category'];
		$this->description = $content['description'];

        $discounted_price   = NULL;

		return $this;
    }

	/*
     
    public function priceCheck($content_id)
    {

        $content_dao = $this->getFactory()->getDb_Dao_Content();
        $content     = $content_dao->findById($content_id);
        $price       = $content['price'];
        $sale_at     = $content['created_at'];

        //check 誕生日の前後1週間
        $session = $app->factory->getSession();
        if($session->get('user_id')) {
            $user_id = $session->get('user_id');
            $user_dao = $this->getFactory()->getDb_Dao_User();
            $user     = $user_dao->findByUserId($user_id);
            $birthday = $user['birthday'];

            if(DateUtil::dateCompare($sale_at, $birthday) <= 7){
                $price = $price * 0.9;
            }
        }

        //check 発売から1ヶ月以内の場合
        $now = date( "Y-m-d h:i:s");
        if(DateUtil::dateCompare($now, $sale_at) <= 30){
            $price = $price * 0.95;
        }

        return $price;

   }
	*/
}
