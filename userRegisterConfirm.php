<?php

require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';
require_once __DIR__ . '/Log/Logger.php';
require_once __DIR__ . '/Models/Session.php';
require_once __DIR__ . '/Models/Mail.php';
require_once __DIR__ . '/Models/Database/AccountMapper.php';

Session::start();

$title = 'ユーザー登録確認';

if (!empty($_POST)) {
  foreach (['email', 'password', 'nickname']  as $v) {
    $$v = filter_input(INPUT_POST, $v);
  }

  // アカウント追加
  $accountId = AccountMapper::add($email, $password, $nickname);
  Logger::debug('Added account : ' . $accountId);

  // セッションにアカウントIDを設定
  Session::set(SESSION_KEY_ACCOUNT_ID, $accountId);

  // 新しいパスワードを登録メールアドレスに送信
  $to = $email;
  $subject = '[Movie Review]ユーザー登録完了のお知らせ';
  $body = 'ユーザー登録が完了しました。' . "\n\n" . 'メールアドレス：' . $email . "\n" . 'パスワード：' . $password . "\n" . 'ニックネーム：' . $nickname . "\n\n" . '※登録内容は大切に保管してください。';
  Mail::send($to, $subject, $body);

  // ユーザー登録完了画面へ
  header('Location:userRegisterComplete');
  return;
} else {
  // セッションから登録内容を取得
  foreach (['email', 'password', 'passwordConfirm', 'nickname']  as $v) {
    if (empty($_SESSION[$v])) {
      // 取得できない値があった場合、ユーザー登録画面へ戻る
      header('Location:userRegister');
      return;
    }

    $$v = Session::get($v);
    Session::remove($v);
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
      $enabledInputItem = false;
      $submitText = 'はじめる';
      include('userRegisterInputItems.php')
      ?>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
</body>

</html>