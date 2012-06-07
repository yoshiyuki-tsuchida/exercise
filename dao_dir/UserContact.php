<?php
require_once dirname(__FILE__) . '/Abstract.php';

class Db_Dao_UserContact extends Db_Dao_Abstract
{
	/*
		private function getTable(){
			return "content";
		}
	 */		

	/**
	 * 全レコード数を取得する
	 *
	 * @return int レコード数
	 */
	public function insertForm($param)
	{

		$dbh = $this->getDbHandler();
		$query = 'INSERT INTO user_contact 
				( user_id, contact_title, contact_body, contact_type, contact_content_url, contact_purchase_num, created_at, updated_at)
				values
			   	( :USER_ID, :CONTACT_TITLE, :CONTACT_BODY, :CONTACT_TYPE, :CONTACT_CONTENT_URL, :CONTACT_PURCHASE_NUM, now(), now())';
		$statement = $dbh->prepare($query);
		$statement->bindValue(':USER_ID', $param['user_id'], PDO::PARAM_STR);
		$statement->bindValue(':CONTACT_TITLE', $param['contact_title'], PDO::PARAM_STR);
		$statement->bindValue(':CONTACT_BODY', $param['contact_body'], PDO::PARAM_STR);
		$statement->bindValue(':CONTACT_TYPE', $param['contact_type'], PDO::PARAM_STR);
		$statement->bindValue(':CONTACT_CONTENT_URL', $param['contact_content_url'], PDO::PARAM_STR);
		$statement->bindValue(':CONTACT_PURCHASE_NUM', $param['contact_purchase_num'], PDO::PARAM_STR);
		$statement->execute();

		if($statement->rowCount() !== 1){
			return false;
		}
		return $dbh->lastInsertId();
	}

	public function findById($contact_id)
	{
		$dbh = $this->getDbHandler();
		$query = 'SELECT uc.*, cs.name status, u.name user_name, u.email 
				  FROM user_contact uc 
				  INNER JOIN contact_status cs on uc.contact_status_id = cs.id 
				  INNER JOIN user u on u.id = uc.user_id 
				  WHERE uc.id=:CONTACT_ID';

		$statement = $dbh->prepare($query);
		$statement->bindValue(':CONTACT_ID', $contact_id, PDO::PARAM_INT);

		$statement->execute();
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function findByStatusId($status_id = null)
	{
		$dbh = $this->getDbHandler();
        $query = "SELECT uc.*, 
                         cs.name status, 
                         u.name user_name, 
                         u.email 
				  FROM user_contact uc 
				  INNER JOIN contact_status cs on uc.contact_status_id = cs.id 
				  INNER JOIN user u on u.id = uc.user_id";
		if(!is_null($status_id)){
			$query = $query . ' WHERE contact_status_id=:STATUS_ID';
		}

		$statement = $dbh->prepare($query);

		if(!is_null($status_id)){
			$statement->bindvalue(':STATUS_ID', $status_id, PDO::PARAM_INT);
		}

		$statement->execute();
		
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function update($id, $params)
	{
		$dbh = $this->getDbHandler();
		$query = 'UPDATE user_contact SET ';

		foreach($params as $key => $val){
			$tmp_query[] = " {$key} = ? "; 
			$bind[] = $val;
		}
		$tmp_query = implode(", ", $tmp_query);

		$query .= $tmp_query;
		$query .= " WHERE id = ?";
		$bind[] = $id;

		$statement = $dbh->prepare($query);
		$statement->execute($bind);

        return $statement->rowCount() > 0;
	}

	/*
	 * 全レコード数を取得する
	 *
	 */
	public function countAll()
	{
	  $dbh = $this->getDbHandler();

	  $query = "SELECT count(id) cnt FROM user_contact";
	  $statement = $dbh->prepare($query);                  
	  $statement->execute();                               
	                                                       
	  $result = $statement->fetch(PDO::FETCH_ASSOC);

	  return intval($result['cnt']);
	}

	public function findLatestListByStatusId($offset = 0, $limit = 5, $status_id = null)
	{
	  $dbh = $this->getDbHandler();
	  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE); //DBハンドルの属性

      $query = "SELECT uc.created_at, 
                       uc.id, 
                       uc.user_id, 
                       uc.contact_title, 
                       uc.contact_status_id,
                       cs.name 
                FROM user_contact uc 
                INNER JOIN contact_status cs 
                ON uc.contact_status_id = cs.id 
                ORDER BY created_at DESC 
                LIMIT :OFFSET, :LIMIT";

	  $statement = $dbh->prepare($query);
	  $statement->bindValue(':OFFSET', $offset, PDO::PARAM_INT);
	  $statement->bindValue(':LIMIT', $limit, PDO::PARAM_INT);
	  $statement->execute();

	  return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
}
