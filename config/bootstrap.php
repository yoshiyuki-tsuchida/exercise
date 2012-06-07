<?php
require 'const.php';
require_once LIB_DIR . '/Factory.php';

$app->factory = new Factory();


/**
 * ログインしている場合はトップにリダイレクトする
 */
function noauthorized()
{
    require_once LIB_DIR . '/Session.php';

    $app = Slim::getInstance();
    $session = $app->factory->getSession();
    if ($session->get('user_id')) {
        $app->redirect('/');
    }
}

/**
 * ログインしていない場合はトップにリダイレクトする
 */
function authorized()
{
    require_once LIB_DIR . '/Session.php';

    $app = Slim::getInstance();
    $session = $app->factory->getSession();

    if (!$session->get('user_id')) {
        $app->redirect('/');
    }
}




/**
 * セッション関係のメソッド
 */
function getUserInfo($session)
{
  $user_info = array();
  $user_info['id'] = $session->get('user_id');
  $user_info['name'] = $session->get('user_name');
  return $user_info;
}


/**
 * ログインしていない場合はログインページにリダイレクトする
 */
function userAuthorized()
{
    require_once LIB_DIR . '/Session.php';

    $app = Slim::getInstance();
    $session = $app->factory->getSession();

    if (!$session->get('user_id')) {
        $app->redirect('/user/login');
    }
    $app->user_info = getUserInfo($session);    
}

/**
 * 非管理者の場合はログインページにリダイレクトする。
 */
function isAdmin(){
    require_once LIB_DIR . '/Session.php';

    $app = Slim::getInstance();
    $session = $app->factory->getSession();

    if (!$session->get('admin_id')) {
        $app->redirect('/admin/login');
    }

}


/**
 * メール送信機能
 */

function _sendmail($params)
{
  $to      = 'yoshiyuki.tsuchida.k@gmail.com';
  $subject = 'the subject';
  $message = 'タイトル：' . $params['contact_title'] . "\r\n" .
    '内容：' . $params['contact_body'] . "\r\n" .
    '区分：' . $params['contact_type'] . "\r\n" .
    '商品URL：' . $params['contact_content_url'] . "\r\n" .
    '購入番号：' . $params['contact_purchase_num'] . "\r\n" ;
  $headers = 'From: web@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    "Content-Type: text/plain; charset=ISO-2022-JP". "\r\n" .
    "Content-Transfer-Encoding: 7bit". "\r\n" .
    'X-Mailer: PHP/' . phpversion();

  mb_language("ja");
  mb_internal_encoding("UTF-8");
  
  mb_send_mail($to, $subject, $message, $headers);
}
