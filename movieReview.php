<?php

require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';
require_once __DIR__ . '/Models/Session.php';
require_once __DIR__ . '/Models/Rate.php';
require_once __DIR__ . '/Models/Database/ReviewMapper.php';

Session::start();

$title = 'レビュー履歴';

if (!isLogin()) {
  header('login.php');
  return;
}

$reviews = ReviewMapper::getAllWithAccountId(Session::get(SESSION_KEY_ACCOUNT_ID));

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
          <p class="main-heading--right main-heading__review">レビュー件数：<?= h(count($reviews)) ?>件</p>
        </div>
      </div>

      <?php if (empty($reviews)) { ?>
        <!-- レビュー投稿されていない場合 -->
        <div class="transition">
          <p>
            まだレビューが投稿されていません。<br>
            お気に入りの映画や思い出の映画などレビューを投稿しましょう！
          </p>
          <a href="index" class="transition__link color--link">> トップページへ</a>
        </div>
      <?php } else { ?>
        <!-- レビュー一覧 -->
        <ul class="review-history">
          <?php foreach ($reviews as $review) { ?>
            <li class="review-item">
              <a class="review-item__link" href="movieDetail?m_id=<?= h($review['movieId']) ?>">
                <img class="review-item__thumbnail" src="<?= h($review['thumbnailPath']) ?>" alt="">
              </a>
              <div class="review-info">
                <p class="review-info__movie-title"><?= h($review['movieTitle']) ?></p>
                <p class="review-info__rating color--star"><?= h(Rate::convertRateToStar($review['rate'])) ?></p>
                <p class="review-info__date"><?= h((new Date($review['reviewDate']))->__toString()) ?></p>
                <p class="review-info__review-title"><?= h($review['reviewTitle']) ?></p>
                <p class="review-info__body"><?= nl2br(h($review['body'])) ?></p>
              </div>
            </li>
          <?php } ?>
        </ul>
      <?php } ?>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
</body>

</html>