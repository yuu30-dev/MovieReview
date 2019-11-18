<?php

require_once __DIR__ . '/Models/Database/FavoriteMapper.php';
require_once __DIR__ . '/Models/Session.php';
require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';

$title = 'お気に入り';

Session::start();

// ログイン済み
if (isLogin()) {
  $accountId = Session::get(SESSION_KEY_ACCOUNT_ID);
  $movies = FavoriteMapper::getAll($accountId);

  // 表示用の映画情報を生成
  $moviesForDisplay = [];
  foreach ($movies as $movie) {
    $displayData = createDisplayData($movie, true);
    array_push($moviesForDisplay, $displayData);
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

  <main class="main-content" id="js-main-content">
    <article id="js-favorite-area">
      <!-- メイン見出し -->
      <div class="main-heading">
        <h2 class="main-heading__title"><?= h($title) ?></h2>
      </div>

      <!-- 未ログイン時 -->
      <?php if (!isLogin()) { ?>
        <div class="transition">
          <p>
            お気に入りのご利用には、ログインが必要です。<br>
            ログインページからログインしてください。
          </p>
          <a href="login?prev=favorite" class="transition__link color--link">> ログインページへ</a>
        </div>
      <?php } else { ?>
        <?php if (empty($moviesForDisplay)) { ?>
          <!-- お気に入り登録されていない場合 -->
          <div class="transition">
            <p>
              お気に入りに登録されている映画がありません。<br>
              お気に入りの映画を登録して自分だけのリストを作りましょう！
            </p>
            <a href="index" class="transition__link color--link">> トップページへ</a>
          </div>
        <?php } else { ?>
          <!-- お気に入り一覧 -->
          <div class="grid-container">
            <?php foreach ($moviesForDisplay as $movie) { ?>
              <div class="grid-item">
                <a class="grid-item__link" href="movieDetail?m_id=<?= h($movie['id']) ?>">
                  <div>
                    <img class="grid-item__thumbnail" src=<?= h($movie['thumbnail']) ?> alt="">
                  </div>
                </a>
                <div class="grid-item-footer">
                  <div class="grid-item-rating-wrap">
                    <?php if (!empty($movie['star'])) { ?>
                      <p class="grid-item__rating">
                        <span class="color--star"><?= h($movie['star']) ?></span> <?= h(sprintf('%.1f', $movie['averageRate'])) ?>
                      </p>
                    <?php } ?>
                    <img class="grid-item-rating-count__icon" src="images/review_count.png" alt="">
                    <p class="grid-item-rating-count__text"><?= h($movie['reviewCount']) ?>件</p>
                  </div>
                  <img class="grid-item__favorite" src="<?= h($movie['favoriteIcon']) ?>" alt="" id="js-favorite-icon-<?= h($movie['id']) ?>" data-movie-id="<?= h($movie['id']) ?>">
                </div>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
      <?php } ?>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
  <!-- Script -->
  <script src="js/movieFavorite.js"></script>

</body>

</html>