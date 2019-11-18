<?php

require_once __DIR__ . '/Utils/function.php';

$title = '退会';

if (!empty($_POST)) {
  // POSTされていた場合、退会処理を実行する
  // TODO: 退会処理
  header('Location:logout');
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

      <!-- 退会 -->
      <section class="unsubscribe">
        <h1 class="unsubscribe__title">このまま退会しますか？</h1>
        <p class="unsubscribe__description">※退会後、３０日間はアカウント情報が保持されます。<br>30日以内であれば、再度ログインすることでアカウントを復活することができます。</p>
        <form action="" method="post" class="unsubscribe-button">
          <a class="unsubscribe-button__cancel btn-opacity--hover" href="userAccount">キャンセル</a>
          <input type="submit" class="unsubscribe-button__enter btn-opacity--hover" name="enter" value="退会する" />
          </div>
      </section>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
</body>

</html>