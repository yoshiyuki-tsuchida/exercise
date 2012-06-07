<?php
require_once dirname(__FILE__) . '/Abstract.php';

class Db_Dao_FavoriteTag extends Db_Dao_Abstract
{
	private function getTable(){
		return "content";
	}
		

    /**
     * お気に入りボタンが押されたらDBに格納する
     *
     */
    public function insertFavoriteTag($favorite_id, $tags)
    {
     
      $dbh = $this->getDbHandler();

      foreach($tags as $t){
	$query = 'insert into favorite_tags ( favorite_id, tags, created_at, updated_at) values ( :FAVORITE_ID, :TAGS, now(), now())';
	$statement = $dbh->prepare($query);
	$statement->bindValue(':FAVORITE_ID', $favorite_id, PDO::PARAM_INT);
	$statement->bindValue(':TAGS', $t, PDO::PARAM_STR);
	$statement->execute();
      }

	if( $statement->rowCount() === 1){
	  return true;
	}
	return false;


    }
    
    
}



