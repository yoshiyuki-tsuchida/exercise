<?php
require_once dirname(__FILE__) . '/Abstract.php';

class Db_Dao_Favorite extends Db_Dao_Abstract
{
	private function getTable(){
		return "content";
	}
		

    /**
     * お気に入りボタンが押されたらDBに格納する
     *
     */
    public function insertFavorite($user_id, $content_id)
    {
     

      $dbh = $this->getDbHandler();
      
      $query = 'insert into favorite ( user_id, content_id, created_at, updated_at) values ( :USER_ID, :CONTENT_ID, now(), now())';
      $statement = $dbh->prepare($query);
      $statement->bindValue(':USER_ID', $user_id, PDO::PARAM_INT);
      $statement->bindValue(':CONTENT_ID', $content_id, PDO::PARAM_INT);
      $statement->execute();

	if( $statement->rowCount() === 1){
	  return true;
	}
	return false;

    }


    /**
     * ユーザーIDからお気に入りのコンテンツIDのリストを取ってくる
     *
     */
    public function getFavorite($user_id)
    {
     

      $dbh = $this->getDbHandler();
      
      $query = 'select content_id from favorite where user_id = :USER_ID oderby content_id';
      $statement = $dbh->prepare($query);
      $statement->bindValue(':USER_ID', $user_id, PDO::PARAM_INT);
      $statement->execute();

      return $statement->fetch(PDO::FETCH_ASSOC);

    }

    
}
