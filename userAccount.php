<?php

require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';
require_once __DIR__ . '/Models/Session.php';
require_once __DIR__ . '/Models/Database/AccountMapper.php';
require_once __DIR__ . '/Models/Date.php';

Session::start();

$title = 'アカウント';

$accountId = Session::get(SESSION_KEY_ACCOUNT_ID);
if (empty($accountId)) {
  // ログインされていない場合、ログイン画面へ
  header('Location:login');
  return;
}

// アカウント情報を取得
$account = AccountMapper::get('id', $accountId);
$email = $account->email;
$date = new Date($account->register_date);
$registerDate = $date->__toString() . ' (' . $date->week() . ')';

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
        <div class="main-heading-inner">
          <h2 class="main-heading__title"><?= h($title) ?></h2>
          <a class="main-heading--right main-heading__password btn-opacity--hover" href="passwordChange">パスワード変更</a>
        </div>
      </div>

      <!-- アカウント -->
      <div class="account">
        <ul class="account-info">
          <li class="account-info-item account__mail">
            メールアドレス：<?= h($email) ?>
          </li>
          <li class="account-info-item account__password">
            パスワード：・・・・・・・・
          </li>
          <li class="account-info-item account__register">
            登録年月日：<?= h($registerDate) ?>
          </li>
        </ul>
        <a class="account__unsubscribe btn-opacity--hover" href="unsubscribe">退会</a>
      </div>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
</body>

</html>