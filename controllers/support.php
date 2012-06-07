<?php
require_once LIB_DIR . '/Session.php';

/**
 *  お問い合わせ画面
 */

$app->get('/support/contact', 'userAuthorized', function () use ($app) {
    $session = $app->factory->getSession();
    $assign['contact'] = $session->get('params');
    $assign["user"] = $app->user_info;
    $app->render('/support/contact.twig', $assign);
});

/**
 * お問い合わせ画面（Validation処理）
 */

$app->post('/support/contact', 'userAuthorized', function () use ($app) {
    require_once LIB_DIR . '/FormValidator/ContactFormValidator.php';
    $errors = array();
    $params = $app->request()->post();

    if(!array_key_exists('contact_privacy', $params)){
        $params['contact_privacy'] = '';
    }

    $form_validator = $app->factory->getFormValidator_ContactFormValidator();

    if($params["contact_type"] === "1"){
        $form_validator->isContentUrl();
    }else if($params["contact_type"] === "2"){
        $form_validator->isPurchaseNum();
    }


    if ($form_validator->run($params)) {
      $session = $app->factory->getSession();
      $session->set('params', $params);
      if($params["prev_page"] === "contact"){
	$app->redirect('/support/contact/confirm');
      }
    } else {
      $errors = $form_validator->getErrors();
    }
    
    $assign['errors'] = $errors;
    $assign['contact'] = $params;
    $assign["user"] = $app->user_info;
    
    $app->render('support/contact.twig', $assign);

});




/**
 *  お問い合わせ内容確認画面
 */

$app->get('/support/contact/confirm', 'userAuthorized', function () use ($app) {

    require_once MODELS_DIR . '/User.php';
    $session = $app->factory->getSession();
    $params = $session->get('params');

    if(is_null($params)){
        $app->error('正しいページから来てください');
    }
    $user = $app->factory->getUser();
    $user->findById($session->get('user_id'));

    $params['email'] = $user->email;
    $assign["user"] = $app->user_info;
    $assign["contact"] = $params;

    $assign["token"] = session_id();
    $session->set('token', session_id());
    $app->render('/support/contacts/confirm.twig',$assign);
});


/**
 *  お問い合わせ完了画面（処理）
 */

$app->post('/support/contact/complete', 'userAuthorized', function () use ($app) {

    require_once MODELS_DIR . '/UserContact.php';
    $session = $app->factory->getSession();
    $params = $session->get('params');
    $params['user_id'] = $session->get('user_id');
    if ( is_null($params)){
      $app->error('正しいページから来てください');
    }
    
    $post = $app->request()->post();
    if($post['token'] !== session_id()){
        $app->error('正しいページから来てください');
    }
        
    $contact = $app->factory->getUserContact();
    $session->set('contact_id',$contact->insert($params));

    _sendmail($params);
    $session->remove('params');
    
    $app->redirect('/support/contact/complete');
});


/**
 * お問い合わせ完了画面（表示）
 */

$app->get('/support/contact/complete', 'userAuthorized', function () use ($app) {
    $session = $app->factory->getSession();
    $contact_id = $session->get('contact_id');

    $assign["user"] = $app->user_info;
    $assign["contact_id"] = $contact_id;
    $app->render('/support/contacts/complete.twig',$assign);

});



/*
 * エラー処理 
 */ 

$app->error(function ($msg='') use ($app) {
    $app->render('error.twig', array('message' => $msg), 500);
});


