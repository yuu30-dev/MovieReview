<?php

require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';
require_once __DIR__ . '/Models/Session.php';

$title = 'パスワード変更完了';

Session::start();
$sendEmail = Session::get(SESSION_KEY_SEND_EMAIL);
if (empty($sendEmail)) {
  header('Location:passwordChange');
  return;
}

Session::remove(SESSION_KEY_SEND_EMAIL);

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

      <!-- パスワード変更完了 -->
      <div class="transition">
        <p>
          パスワードを変更しました。<br>
          <?= h($sendEmail) ?> に新しいパスワードをお送りしました。
        </p>
        <a class="transition__link color--link" href="index">> トップページへ</a>
      </div>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
</body>

</html>