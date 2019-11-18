<?php

require_once __DIR__ . '/Log/Logger.php';
require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Models/Database/AccountMapper.php';
require_once __DIR__ . '/Models/Session.php';

Session::start();

$title = 'ユーザー登録';

// エラー表示用
$errorInfo = [];

if (!empty($_POST)) {
  // 可変変数を利用して値を取得
  foreach (['email', 'password', 'passwordConfirm', 'nickname'] as $v) {
    $$v = (string) filter_input(INPUT_POST, $v);
  }

  // Eメールアドレスの入力チェック
  if (empty($email)) {
    $errorInfo['email'] = '※Eメールアドレスを入力してください';
  } else if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorInfo['email'] = '※Eメールアドレスの形式が不正です';
  } else if (false !== AccountMapper::get('email', $email)) {
    $errorInfo['email'] = '※登録済みのEメールアドレスです';
  }
  // パスワードの入力チェック
  if (empty($password) || strlen($password) < 6) {
    $errorInfo['password'] = '※パスワードは6文字以上で入力してください';
  }
  // パスワード確認の入力チェック
  if ($password !== $passwordConfirm) {
    $errorInfo['passwordConfirm'] = '※パスワードが一致していません';
  }
  // ニックネームの入力チェック
  if (empty($nickname)) {
    $errorInfo['nickname'] = '※ニックネームを入力してください';
  }

  // 入力内容にエラーがなかった場合
  if (empty($errorInfo)) {
    // セッションに入力内容を設定
    foreach (['email', 'password', 'passwordConfirm', 'nickname'] as $v) {
      Session::set($v, $$v);
    }

    // ユーザー登録確認画面へ
    header('Location:userRegisterConfirm');
    return;
  }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title><?= h($title) ?></title>
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans|Spectral&display=swap" rel="stylesheet">
</head>

<body class="body" class="body" class="body">
  <!-- ヘッダーファイル読み込み -->
  <?php include('header.php') ?>

  <main class="main-content">
    <article>
      <!-- メイン見出し -->
      <div class="main-heading">
        <h2 class="main-heading__title"><?= h($title) ?></h2>
      </div>
      <!-- ユーザー登録入力項目ファイル読み込み -->
      <?php
      // ユーザー登録入力項目ファイルの設定
      $enabledInputItem = true;
      $submitText = '次へ';
      include('userRegisterInputItems.php')
      ?>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
</body>

</html>