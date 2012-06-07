<?php
require_once dirname(__FILE__) . '/Model.php';
require_once dirname(__FILE__) . '/../lib/php/Db/Dao/UserPurchase.php';

class UserPurchase extends Model
{

  public $id;
  public $user_id;
  public $content_id;
  public $purchase_price;
  public $purchase_type;
  public $created_at;
  public $updated_at;
  
  public function findById($id){
    $purchase_dao = $this->getFactory()->getDb_Dao_UserPurchase();
    $purchase = $purchase_dao->findById($id);
    if ( $purchase === false){
      return null;
    }
    
    $this->id = $purchase['id'];
    $this->user_id = $purchase['user_id'];
    $this->content_id = $purchase['content_id'];
    $this->purchase_price = $purchase['purchase_price'];
    $this->purchase_type = $purchase['purchase_type'];
    $this->created_at= $purchase['created_at'];
    $this->updated_at = $purchase['updated_at'];

    return $this;
  }




  public function insertPurchase($user_id, $content_id, $purchase_price, $purchase_type){
    $purchase_dao = $this->getFactory()->getDb_Dao_UserPurchase();
    return $purchase_dao->insert($user_id, $content_id, $purchase_price, $purchase_type);
  }





}
