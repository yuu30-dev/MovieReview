<?php

require_once __DIR__ . '/Utils/function.php';

$title = 'ユーザー登録完了';

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

      <!-- ユーザー登録完了メッセージ -->
      <div class="transition">
        <p>
          ユーザー登録が完了しました。<br>
          登録内容の控えをメールで送信致しましたので大切に保管してください。
        </p>
        <a class="transition__link color--link" href="index">> トップページへ</a>
      </div>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
</body>

</html>