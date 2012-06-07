<?php


// 管理画面
$app->get('/admin', 'isAdmin', function () use ($app) {
    require_once LIB_DIR . '/Session.php';
    $session = $app->factory->getSession();
    $admin_info = array();
    if ($session->get('admin_id')) {
        $admin_info['id'] = $session->get('admin_id');
        $admin_info['name'] = $session->get('admin_name');
    }
    $app->render('admin/index.twig',array( "admin" => $admin_info));
});

// 売上集計画面（日別集計）
$app->get('/admin/summary/day','isAdmin', function () use ($app) {
    require_once LIB_DIR . '/Session.php';
    require_once MODELS_DIR . '/Summary.php';
    $session = $app->factory->getSession();
    $admin_info = array();
    if ($session->get('admin_id')) {
        $admin_info['id'] = $session->get('admin_id');
        $admin_info['name'] = $session->get('admin_name');
    }

    $p = $app->request()->get();
    $from_date = null;
    $to_date = null;
    if(isset($p["from_year"])){
        $from_date = $p["from_year"]. "-" . $p["from_month"]."-".$p["from_date"];
        $to_date = $p["to_year"]. "-" . $p["to_month"]."-".$p["to_date"];
    }
    $summary = $app->factory->getSummary();

    $sales = $summary->getSalesSummaryByDate($from_date,$to_date);
    $sales_sum = $summary->getSalesSummary($from_date,$to_date);
    $app->render('admin/summary/day.twig',array( "admin" => $admin_info, "sales" => $sales, "sales_sum" => $sales_sum ));


});


// 売上集計画面（コンテンツ別集計）
$app->get('/admin/summary/content','isAdmin', function () use ($app) {
    require_once LIB_DIR . '/Session.php';
    $session = $app->factory->getSession();
    $admin_info = array();
    if ($session->get('admin_id')) {
        $admin_info['id'] = $session->get('admin_id');
        $admin_info['name'] = $session->get('admin_name');
    }
    $app->render('admin/summary/content.twig',array( "admin" => $admin_info));
});


// お問い合わせ管理画面

$app->get('/admin/contactlist', 'isAdmin', function () use ($app){
    require_once LIB_DIR . '/Session.php';
    require_once MODELS_DIR . '/UserContact.php';
    $session = $app->factory->getSession();
    $admin_info = array();
    if ($session->get('admin_id')) {
        $admin_info['id'] = $session->get('admin_id');
        $admin_info['name'] = $session->get('admin_name');
    }

    $status_id = $app->request()->get('status_id');
    if($status_id === "0"){
        $status_id = null;
    }
    $contact = $app->factory->getUserContact();
    $contacts = $contact->findByStatusId($status_id);

    /* ここにページャーの記述をしようとした */
    //$paginate = $contact->paginate($page, CONTACTS_LIMIT);

	$assign['admin'] = $admin_info;
	$assign['contacts'] = $contacts;
	$assign['status_id'] = $status_id;
	
    $app->render('admin/contact/index.twig', $assign);
});

// お問い合わせ管理画面詳細
$app->map('/admin/contact/detail/:contact_id','userAuthorized', 'isAdmin', function ($contact_id) use ($app) {
    require_once LIB_DIR . '/Session.php';
    require_once MODELS_DIR . '/UserContact.php';
    $session = $app->factory->getSession();
    $admin_info = array();
    if ($session->get('admin_id')) {
        $admin_info['id'] = $session->get('admin_id');
        $admin_info['name'] = $session->get('admin_name');
    }

    $contact = $app->factory->getUserContact();

    $action = $app->request()->post('action');
    if($action === "update_memo"){
        $contact_memo = $app->request()->post('contact_memo');
        $result = $contact->update($contact_id, array("contact_memo" => $contact_memo));
    }

    if($action === "update_status"){
        $status_id = $app->request()->post('status_id');
        $result = $contact->update($contact_id, array("contact_status_id" => $status_id));
    }

    $contact_detail = $contact->findById($contact_id);

    $assign['admin'] = $admin_info;
    $assign['contact_detail'] = $contact_detail;
    $app->render('admin/contact/detail.twig', $assign);
})->via('GET', 'POST');



// 管理者のログイン画面

$app->get('/admin/login', function () use ($app) {
    var_dump("aaaaaaaaaaaaaaaa");
    $app->render('admin/login.twig');
  });


//管理者ログイン画面（処理用）

$app->post('/admin/login', function () use ($app) {
    require_once LIB_DIR . '/FormValidator/AdminFormValidator.php';
    require_once LIB_DIR . '/Session.php';
    require_once LIB_DIR . '/PasswordUtil.php';
    require_once MODELS_DIR . '/AdminUser.php';

    $errors = array();
    $params = $app->request()->post();
    $form_validator = $app->factory->getFormValidator_LoginFormValidator();

    // まだ途中 2012/05/30 15:30
    
    
    
    if ($form_validator->run($params)) {
      $admin = $app->factory->getAdminUser();
      if (!is_null($admin->findByName($params['admin_name']))
	  && $admin->password === PasswordUtil::hashPassword($params['password'], $admin->salt)) {
	$session = $app->factory->getSession();
	$session->regenerate();
	$session->set('admin_id', $admin->id);
	$session->set('admin_name', $admin->name);
	$app->redirect('/admin');
      } else {
	$errors['admin_name'] = 'ユーザー名とパスワードの組み合わせが間違っています';
      }
    } else {
      $errors = $form_validator->getErrors();
    }
    $app->render('admin/login.twig', array('errors' => $errors, 'params' => $params));

    
  });

