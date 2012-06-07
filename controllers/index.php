<?php
/**
 * トップ画面
 */
$app->get('/(:page)', function ($page = 1) use ($app) {
    require_once LIB_DIR . '/Session.php';
    require_once MODELS_DIR . '/Content.php';

    $session = $app->factory->getSession();
    $session->remove('params');

    $user_info = array();
    if ($session->get('user_id')) {
        $user_info['id'] = $session->get('user_id');
        $user_info['name'] = $session->get('user_name');
        $user_info['privilege'] = $session->get('privilege');
    }

    $content = $app->factory->getContent();
    $paginate = $content->paginate($page, CONTENTS_LIMIT);
    $app->render('index.twig', array('user' => $user_info, 'contents' => $paginate));

    

    
})->conditions(array('page' => '\d.*'));

/*
 * エラー画面
 */
$app->error(function ($msg='') use ($app) {
    $app->render('error.twig', array('message' => $msg), 500);
});
