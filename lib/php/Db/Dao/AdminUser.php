<?php

//require_once PROJECT_DIR . '/lib/php/Db/Abstract.php';
require_once dirname(__FILE__) . '/Abstract.php';

/**
 * Db_Dao_Userクラス
 *
 * @package Db
 * @subpackage Dao
 * @version $Id$
 *
 */
class Db_Dao_AdminUser extends Db_Dao_Abstract
{
    /**
     * ユーザIDを指定して任意のユーザ情報を返す
     *
     * @param int $userId 
     * @return array 
     * @throws PDOExeption
     *
     */
	public function findById($adminId)
    {
		$dbh = $this->getDbHandler();
		$query = "SELECT * FROM admin_user where id = :ADMIN_ID";

		$statement = $dbh->prepare($query);
	
		$statement->bindValue(':ADMIN_ID', $adminId, PDO::PARAM_INT);
		$statement->execute();

		return $statement->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * ユーザー情報を追加する
     *
     * @param string $name ユーザー名
     * @param string $pass パスワード
     * @param string $salt サルト
     * @param string $email E-Mail
     * @return boolean 追加が成功して場合true, 失敗した場合false
     */
    public function insertAdmin($name, $pass, $salt, $email, $role_id)
    {
        $dbh = $this->getDbHandler();
        $query = 'insert into admin_user (name, password, salt, email, role_id, created_at) values (:NAME, :PASSWORD, :SALT, :EMAIL, :ROLE_ID, now())';
        $statement = $dbh->prepare($query);
        $statement->bindValue(':NAME', $name, PDO::PARAM_STR);
        $statement->bindValue(':PASSWORD', $pass, PDO::PARAM_STR);
        $statement->bindValue(':SALT', $salt, PDO::PARAM_STR);
        $statement->bindValue(':EMAIL', $email, PDO::PARAM_STR);
        $statement->bindValue(':ROLE_ID', $role_id, PDO::PARAM_INT);

        $statement->execute();

        return ($statement->rowCount() === 1);
    }

    public function deleteAdmin($name)
    {
      $dbh = $this->getDbHandler();
      $query = 'DELETE FROM admin_user WHERE name = :NAME';

      $statement = $dbh->prepare($query);
      $statement->bindValue(':NAME',$name, PDO::PARAM_STR);
      $statement->execute();

      return ($statement->rowCount() === 1); 
    }

    public function countByName($name)
    {
      $dbh = $this->getDbHandler();
      $query = 'SELECT count(id) cnt FROM admin_user WHERE name = :NAME';
      $statement = $dbh->prepare($query);
      $statement->bindValue(':NAME', $name, PDO::PARAM_STR);
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      
      return intval($result['cnt']);;
    }

    public function findByName($name)
    {
      $dbh = $this->getDbHandler();
      $query = 'SELECT * FROM admin_user WHERE name = :NAME';
      $statement = $dbh->prepare($query);
      $statement->bindValue(':NAME', $name, PDO::PARAM_STR);
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      
      return $result;
    }   


}
