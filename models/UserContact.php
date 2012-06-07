<?php
require_once dirname(__FILE__) . '/Model.php';
require_once dirname(__FILE__) . '/../lib/php/Db/Dao/UserContact.php';

class UserContact extends Model
{
    public $id;
    public $user_id;
    public $contact_title;
    public $contact_body;
    public $contact_type;
    public $contact_content_url;
    public $contact_purchase_num;
    public $contact_status_id;
    public $contact_memo;
    public $created_at;
    public $updated_at;
    

    public function insert($param)
    {
      $contact_dao = $this->getFactory()->getDb_Dao_UserContact();
      return $contact_dao->insertForm($param);
    }

	public function findById($contact_id = null)
	{
	  $contact_dao = $this->getFactory()->getDb_Dao_UserContact();
	  $contact = $contact_dao->findById($contact_id);
	  if($contact === false){
	    return null;
	  }
	  
	  $this->id = $contact['id'];
	  $this->user_id = $contact['user_id'];
	  $this->contact_title = $contact['contact_title'];
	  $this->contact_body = $contact['contact_body'];
	  $this->contact_type = $contact['contact_type'];
	  $this->contact_content_url = $contact['contact_content_url'];
	  $this->contact_purchase_num = $contact['contact_purchase_num'];
	  $this->contact_status_id = $contact['contact_status_id'];
	  $this->contact_memo = $contact['contact_memo'];
	  $this->created_at = $contact['created_at'];
	  $this->updated_at = $contact['updated_at'];      
	  
	  return $this;
	}

	public function findAll()
	{
	  $contact_dao = $this->getFactory()->getDb_Dao_UserContact();
	  $contacts = $contact_dao->findByStatusId(null);

	  
	  $result = array();
	  foreach ($contacts as $contact){
	    foreach ($contact as $k => $v){
	      $obj = new UserContact();
	      $obj->$k = $v;
	    }
	    $result[] = $obj;
	  }
	  return $result;
	}

	public function findByStatusId($status_id = null)
	{
	  $contact_dao = $this->getFactory()->getDb_Dao_UserContact();
	  $contacts = $contact_dao->findByStatusId($status_id);
	  
		
	  
	  $result = array();
	  foreach ($contacts as $contact){
	    $obj = new UserContact();
	    foreach ($contact as $k => $v){
	      $obj->$k = $v;
	    }
	    $result[] = $obj;
	  }
	  return $result;
	}

	public function update($id, $params)
	{
      $contact_dao = $this->getFactory()->getDb_Dao_UserContact();
	  return $contact_dao->update($id, $params);
	}

    public function paginate($page = 1, $limit = 1)
    {
		$contact_dao = $this->getFactory()->getDb_Dao_UserContact();
		$num = $contact_dao->countAll(); 
		$total = intval(ceil($num/$limit));
		if ($total <$page){
		  $page= $total;
		}
		$offset = $limit * ($page -1);
		$list =$contact_dao->findLatestList($offset,$limit);

		$prev = $page-1;
		if($prev <1){
			$prev = null;
		}
		$next =  $page+1;
		if($total <$next){
			$next = null;
		}

        return array(
            'prev'=>$prev, 
            'page'=>$page, 
            'next'=>$next, 
            'total'=>$total
        );
    }
}
