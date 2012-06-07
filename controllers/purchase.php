<?php

/**
 * check buy
 * セッションのチェック
 * コンテンツがあるかのチェック
 * 有料か無料かのチェック
 * 有料ならログインしているかチェック
 * 有料でログインしていなかったらログイン画面へ
 * 以上のチェックを経て、読めるなら内容へ、読めないなら購入へ
 * これは読めるor読めないのチェックなので、購入か購入じゃないかのチェックではない
 * @id  content_id
 */


/**
 * コンテンツ購入画面
 * 購入ボタンを押したときの処理
 * POST内容をもらって、
 */
$app->post('/purchase/buy', function () use ($app) {

    require_once LIB_DIR . '/Session.php';
    require_once MODELS_DIR . '/Purchase.php';

    $session = $app->factory->getSession();
    $user_info = array();


    if ($session->get('user_id')) {
        $user_info['id'] = $session->get('user_id');
        $user_info['name'] = $session->get('user_name');
    }else {
        $app->redirect("/user/login");
        exit;
    }

    $params = $app->request()->post();
    $content_id    = $params['content_id'];
    $price         = $params['price'];
    $purchase_type = $params['purchase_type'];

    $purchase = $app->factory->getPurchase();

    $buyFlag = $purchase->buyContent($session->get('user_id'), $content_id, $purchase_type, $price);

    $app->redirect("/purchase/check/$content_id");
});





$app->post('/purchase/exec', function () use ($app) {
    echo "exec";
});


/*
 * エラー処理 
 */ 

$app->error(function ($msg='') use ($app) {
    $app->render('error.twig', array('message' => $msg), 500);
});

