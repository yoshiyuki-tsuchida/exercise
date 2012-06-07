<?php

/**
 * ログイン画面
 */
$app->get('/user/login', 'noauthorized', function () use ($app) {
    $app->render('user/login.twig');
});

/**
 * ログイン画面（ログイン処理）
 */
$app->post('/user/login', 'noauthorized', function () use ($app) {
    require_once LIB_DIR . '/FormValidator/UserFormValidator.php';
    require_once LIB_DIR . '/Session.php';
    require_once LIB_DIR . '/PasswordUtil.php';
    require_once MODELS_DIR . '/User.php';

    $errors = array();
    $params = $app->request()->post();
    $form_validator = $app->factory->getFormValidator_UserFormValidator();

    if ($form_validator->run($params)) {
      $user = $app->factory->getUser();
      if (!is_null($user->findByName($params['user_name']))
	  && $user->password === PasswordUtil::hashPassword($params['password'], $user->salt)) {
	$session = $app->factory->getSession();
	$session->regenerate();
	$session->set('user_id', $user->id);
	$session->set('user_name', $user->name);
	$app->redirect('/');
      } else {
	$errors['user_name'] = 'ユーザー名とパスワードの組み合わせが間違っています';
      }
    } else {
      $errors = $form_validator->getErrors();
    }
    $app->render('user/login.twig', array('errors' => $errors, 'params' => $params));
    
    
  });

/**
 * ログアウト画面
 */
$app->get('/user/logout/', 'authorized', function () use ($app) {
    require_once LIB_DIR . '/Session.php';

    $session = $app->factory->getSession();
    if ($session->get('user_id')) {
        $session->destroy();
        $app->render('user/logout.twig');
    }
});

/**
 * ユーザー新規登録画面
 */
$app->get('/user/register', 'noauthorized', function () use ($app) {
    $app->render('user/register.twig');
});

/**
 * ユーザー新規登録画面（登録処理）
 */
$app->post('/user/register', 'noauthorized', function () use ($app) {
    require_once LIB_DIR . '/FormValidator/RegisterMemberFormValidator.php';
    require_once LIB_DIR . '/PasswordUtil.php';
    require_once MODELS_DIR . '/User.php';

    $params = $app->request()->post();
    $errors = array();
    $form_validator = $app->factory->getFormValidator_RegisterMemberFormValidator();

    if ($form_validator->run($params)) {
        $user = $app->factory->getUser();
        try {
            if ($user->isMember($params['user_name'])) {
                $errors['user_name'] = '既に登録されているユーザー名です';
            } else {
                $salt = PasswordUtil::generateSalt();
                $user->register(
                    $params['user_name'],
                    PasswordUtil::hashPassword($params['password'], $salt),
                    $salt,
                    $params['email'],
                    $params['birthday']
                );
                $app->redirect('/user/login');
            }
        } catch (PDOException $e) {
            $app->error('登録に失敗しました。');
        }
    } else {
        $errors = $form_validator->getErrors();
    }
    $app->render('user/register.twig', array('errors' => $errors, 'params' => $params));
});

