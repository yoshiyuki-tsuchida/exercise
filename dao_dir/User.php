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
class Db_Dao_User extends Db_Dao_Abstract
{
    /**
     * ユーザIDを指定して任意のユーザ情報を返す
     *
     * @param int $userId 
     * @return array 
     * @throws PDOExeption
     *
     */
    public function findByUserId($userId)
    {
        $dbh = $this->getDbHandler();

        $query  = 'select * from user where id = :USER_ID';
        $statement = $dbh->prepare($query);
        $statement->bindValue(':USER_ID', $userId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * ユーザー名を指定してユーザー情報を返す
     *
     * @param string $name ユーザー名
     * @return array ユーザー情報
     * @throws PDOException
     */
    public function findByName($name)
    {
        $dbh = $this->getDbHandler();

        $query = 'select id, name, email, password, salt, birthday from user where name = :NAME';
        $statement = $dbh->prepare($query);
        $statement->bindValue(':NAME', $name, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * ユーザー名を指定してユーザー数を返す
     *
     * @param string $name ユーザー名
     * @return int ユーザー数
     * @throws PDOException
     */
    public function countByName($name)
    {
        $dbh = $this->getDbHandler();
        $query = 'select count(id) cnt from user where name = :NAME';
        $statement = $dbh->prepare($query);
        $statement->bindValue(':NAME', $name, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return intval($result['cnt']);
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
    public function insert($name, $pass, $salt, $email, $birthday)
    {
        $dbh = $this->getDbHandler();
        $query = 'insert into user (name, password, salt, email, birthday, created_at) values (:NAME, :PASSWORD, :SALT, :EMAIL, :BIRTHDAY, now())';
        $statement = $dbh->prepare($query);
        $statement->bindValue(':NAME', $name, PDO::PARAM_STR);
        $statement->bindValue(':PASSWORD', $pass, PDO::PARAM_STR);
        $statement->bindValue(':SALT', $salt, PDO::PARAM_STR);
        $statement->bindValue(':EMAIL', $email, PDO::PARAM_STR);
        $statement->bindValue(':BIRTHDAY', $birthday, PDO::PARAM_STR);

        $statement->execute();

        return ($statement->rowCount() === 1);
    }



    /**
     * adminユーザーならTRUEを返す
     *
     * @param string $name ユーザー名
     * @param string $pass パスワード
     * @param string $salt サルト
     * @param string $email E-Mail
     * @return boolean 追加が成功して場合true, 失敗した場合false
     */
    public function isAdmin($user_id)
    {
        $dbh = $this->getDbHandler();
        $query = 'select privilege from user where id = :USER_ID';
        $statement = $dbh->prepare($query);
        $statement->bindValue(':USER_ID', $user_id, PDO::PARAM_INT);
        $statement->execute();

	$privilege = $statement->fetch(PDO::FETCH_ASSOC);

	//	var_dump($privilege);

	
        return ($privilege['privilege'] > "0");
    }
}

