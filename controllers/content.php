<?php

/**
 *  コンテンツ詳細画面
 */

$app->get('/content/(:content_id)', function ($content_id = 0) use ($app) {
    require_once MODELS_DIR . '/Content.php';


    //todo　共通のSession処理、まとめられそう
    require_once LIB_DIR . '/Session.php';
    $session = $app->factory->getSession();
    $user_info = array();
    if ($session->get('user_id')) {
        $user_info['id'] = $session->get('user_id');
        $user_info['name'] = $session->get('user_name');
    }
    /////////////////////////////////////////

    $content = $app->factory->getContent();
    $content->findById($content_id);

    $assign["content"] = $content;
    $assign["user"] = $user_info;   //todo 共通のSession処理、まとめられそう
    $app->render('/content/index.twig', $assign);
});


/**
 * コンテンツ内容画面
 */

$app->get('/content/read/:content_id', function ($content_id = 0) use ($app) {

    require_once LIB_DIR . '/Session.php';
    require_once MODELS_DIR . '/Content.php';
    require_once MODELS_DIR . '/User.php';
    require_once MODELS_DIR . '/UserPurchase.php';

    
    $content = $app->factory->getContent();
    $content->findById($content_id);
    $assign = array();

    if (intval($content->price) > 0 ){

      $session = $app->factory->getSession();
      
      if(!$session->get('user_id')){
	$app->redirect('/user/login');
      }

      $user_info['id'] = $session->get('user_id');
      $user_info['name'] = $session->get('user_name');
      $assign["user"] = $user_info;            

      $user = $app->factory->getUser();
      $user->findById($user_info['id']);
      if ( !$user->didPurchased( intval($content->id) ) ){
	$app->redirect("/content/purchase/" . $content->id);
      }
    }

    $assign["content"] = $content;
    $app->render('/content/read.twig', $assign);

});




/**
 * コンテンツ購入画面
 */

$app->get('/content/purchase/(:content_id)', function ($content_id = 0) use ($app){

    require_once LIB_DIR . '/Session.php';
    require_once MODELS_DIR . '/Content.php';
    require_once MODELS_DIR . '/User.php';
    require_once MODELS_DIR . '/UserPurchase.php';

    var_dump($content_id);
    
    $content = $app->factory->getContent();
    $content->findById(intval($content_id));

    $session = $app->factory->getSession();
    $assign['token'] = htmlspecialchars(session_id(), ENT_COMPAT, 'UTF-8');
    $session->set('token', $assign['token']);
    $assign['content'] = $content;
    $app->render('/content/buy.twig', $assign);
  });


/**
 * コンテンツ購入決済処理
 */

$app->post('/content/purchase/(:content_id)' , function ($content_id = 0) use ($app){
    
    require_once LIB_DIR . '/Session.php';
    require_once MODELS_DIR . '/Content.php';
    require_once MODELS_DIR . '/User.php';
    require_once MODELS_DIR . '/UserPurchase.php';
    require_once MODELS_DIR . '/outsideAPI.php';
    
    
    $param = $app->request()->post();
    $session = $app->factory->getSession();
    if ( $param['token'] !== session_id() ){
      $app->error("正しいページから来てください。");
    }
        
    $api = $app->factory->getoutsideAPI();
    if( $api->outsideClearanceAPI($param['content_id'], $param['purchase_type']) ){
      $purchase = $app->factory->getUserPurchase();
      $content = $app->factory->getContent();
      $content->findById($content_id);
      $purchase->insertPurchase($session->get('user_id'), $content_id, $content->price, $param['purchase_type']);
      $app->redirect("/content/purchased/complete");
    }
    
  });


// 完了画面

$app->get('/content/purchased/complete', function () use ($app){
    $app->render('/content/complete.twig');
  });
    
/*
 * エラー処理 
 */ 

$app->error(function ($msg='') use ($app) {
    $app->render('error.twig', array('message' => $msg), 500);
});


