<?php
require_once dirname(__FILE__) . '/Abstract.php';

class Db_Dao_UserPurchase extends Db_Dao_Abstract
{
	private function getTable(){
		return "user_purchase";
	}

    /**
     * 商品購入処理を実行する
     *
     * @param string $user_id ユーザーid
     * @param string $content_id コンテンツid
     * @param string $purchase_type 購入方法
     * @param string $price 価格
     */
	public function buyContent($user_id, $content_id, $purchase_type, $price)
	{
		$dbh = $this->getDbHandler();
		$table = self::getTable();

        $query = "INSERT INTO {$table} (
                      user_id, 
                      content_id, 
                      purchase_price, 
                      purchase_type, 
                      created_at, updated_at
                  ) values (
                      :UID, 
                      :CID, 
                      :PRICE, 
                      :TYPE, 
                      now(), 
                      now()
                  )";
		$statement = $dbh->prepare($query);
                $statement->bindValue(':UID', $user_id, PDO::PARAM_INT);
                $statement->bindValue(':CID', $content_id, PDO::PARAM_INT);
                $statement->bindValue(':PRICE', $price, PDO::PARAM_INT);
                $statement->bindValue(':TYPE', $purchase_type, PDO::PARAM_INT);
		$statement->execute();

        return ($statement->rowCount() === 1);
	}

	public function findById($purchase_id)
	{
      $dbh = $this->getDbHandler();
      $query = "SELECT * FROM user_purchase WHERE id = :PURCHASE_ID";
      $statement = $dbh->prepare($query);
      $statement->bindValue(':PURCHASE_ID',$purchase_id, PDO::PARAM_INT);
      $statement->execute();
      return $statement->fetch(PDO::FETCH_ASSOC);
	}
 
    /**
     * ユーザーIDからその人の購入コンテンツの情報を取得する
     *
     * @param int $offset 取得位置
     * @param int $limit 取得件数
     */
    public function findByUserId($user_id)
    {
        $dbh = $this->getDbHandler();
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);

        $query = "SELECT p.id,
                         p.user_id, 
                         p.content_id, 
                         c.title,
                         c.description, 
                         p.purchase_price, 
                         p.purchase_type, 
                         p.created_at,
                         c.created_at as 'released_at'
                  FROM content as c 
                  INNER JOIN user_purchase as p 
                  ON c.id=p.content_id 
                  WHERE p.user_id=:USER_ID 
                  ORDER BY p.created_at";

        $statement = $dbh->prepare($query);
        $statement->bindValue(':USER_ID', $user_id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByUserIdAndContentId($user_id, $content_id)
    {
        $dbh = $this->getDbHandler();
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    
        $query = "SELECT * FROM user_purchase WHERE user_id = :USER_ID AND content_id = :CONTENT_ID";
        $statement = $dbh->prepare($query);
        $statement->bindValue(':USER_ID', $user_id, PDO::PARAM_INT);
        $statement->bindValue(':CONTENT_ID', $content_id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }


    public function insert($user_id, $content_id, $purchase_price, $purchase_type){
      $dbh = $this->getDbHandler();
      $query = "INSERT INTO user_purchase (user_id, content_id, purchase_price, purchase_type, created_at, updated_at) VALUES (:USER_ID, :CONTENT_ID, :PURCHASE_PRICE, :PURCHASE_TYPE, NOW(), NOW())";

      $statement = $dbh->prepare($query);
      $statement->bindValue(':USER_ID', $user_id, PDO::PARAM_INT);
      $statement->bindValue(':CONTENT_ID', $content_id, PDO::PARAM_INT);
      $statement->bindValue(':PURCHASE_PRICE', $purchase_price, PDO::PARAM_INT);
      $statement->bindValue(':PURCHASE_TYPE', $purchase_type, PDO::PARAM_INT);
      $statement->execute();
      return ($statement->rowCount() === 1);
    }
}

