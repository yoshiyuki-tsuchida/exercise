<?php

require_once dirname(__FILE__) . '/Model.php';
require_once dirname(__FILE__) . '/../lib/php/Db/Dao/AdminUser.php';

class AdminUser extends Model
{
    public $id;
    public $name;
    public $password;
    public $salt;
    public $email;
    public $role_id;

    /**
     * 管理者名からAdminUserオブジェクトを返す
     *
     * @param $name 管理者ユーザーの名前
     * @return Object AdminUser
     */
    public function findByName($name)
    {
        $admin_dao = $this->getFactory()->getDb_Dao_AdminUser();
        $admin_info = $admin_dao->findByName($name);
        if ($admin_info === false) {
            return null;
        }

        $this->id = $admin_info['id'];
        $this->name = $admin_info['name'];
        $this->password = $admin_info['password'];
        $this->salt = $admin_info['salt'];
        $this->email = $admin_info['email'];
        $this->role_id = $admin_info['role_id'];

        return $this;
    }

    /**
     * 管理者IDからAdminUserオブジェクトを返す
     *
     * @param $id 管理者ユーザーのID
     * @return Object AdminUser
     */
	public function findById($id)
	{
		$admin_dao = $this->getFactory()->getDb_Dao_AdminUser();
		$admin_info = $admin_dao->findById($id);
		if ($admin_info === false){
		  return null;
		}
		$this->id = $admin_info['id'];
		$this->name = $admin_info['name'];
		$this->password = $admin_info['password'];
		$this->salt = $admin_info['salt'];
		$this->email = $admin_info['email'];
		$this->role_id = $admin_info['role_id'];

		return $this;
	}


    /**
     * ユーザー登録する
     *
     * @param string $name ユーザー名
     * @param string $password パスワード
     * @param string $salt サルト
     * @param string $email Email
     * @param string $role_idロール
     * @return boolean 処理が成功した場合true, 失敗した場合false
     */
    public function register($name, $password, $salt, $email, $role_id)
    {
        $admin = $this->getFactory()->getDb_Dao_AdminUser();
        return $admin->insertAdmin($name, $password, $salt, $email, $role_id);
    }

    /**
     * ユーザーを削除する
     */
    public function delete($name)
    {
      $admin = $this->getFactory()->getDb_Dao_AdminUser();
      return $admin->deleteAdmin($name);
    }
}


