<?php

require_once __DIR__ . '/Models/TMDb/MovieSortType.php';
require_once __DIR__ . '/Models/TMDb/TMDbWrapper.php';
require_once __DIR__ . '/Models/Database/FavoriteMapper.php';
require_once __DIR__ . '/Models/Database/MovieMapper.php';
require_once __DIR__ . '/Models/Database/MoviesGenresMapper.php';
require_once __DIR__ . '/Models/Session.php';
require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';

Session::start();

$title = '映画一覧';

// 映画情報を取得
$tmdb = TMDbWrapper::getInstance();

if (!empty($_GET['q'])) {
  // 検索が実行されていた場合
  $movies = $tmdb->search($_GET['q']);
} else {
  // ジャンルIDが指定されていれば取得
  $genreId = empty($_GET['g_id']) ? '' : $_GET['g_id'];
  $movies = $tmdb->getMovies(MovieSortType::popularity, $genreId);
}

// 映画情報をDBに追加
MovieMapper::addOrUpdate($movies);
// 映画・ジャンル情報をDBに追加
foreach ($movies as $movie) {
  MoviesGenresMapper::add($movie->id, $movie->genreIds);
}

// 表示用の映画情報
$moviesForDisplay = [];

// 表示用の映画情報を生成
if (isLogin()) {
  $accountId = Session::get(SESSION_KEY_ACCOUNT_ID);
  foreach ($movies as $movie) {
    $displayData = createDisplayData($movie, FavoriteMapper::has($movie->id, $accountId));
    array_push($moviesForDisplay, $displayData);
  }
} else {
  foreach ($movies as $movie) {
    $displayData = createDisplayData($movie, false);
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

<body class="body" class="body" class="body" class="body">
  <!-- ヘッダーファイル読み込み -->
  <?php include('header.php') ?>

  <main class="main-content">
    <article>
      <!-- メイン見出し用 -->
      <div class="main-heading">
        <h2 class="main-heading__title"><?= h($title) ?></h2>
      </div>

      <?php if (empty($moviesForDisplay)) { ?>
        <!-- 映画一覧が空の場合 -->
        <div class="transition">
          <p>該当する作品がありません。</p>
          <a href="index" class="transition__link color--link">> トップページへ</a>
        </div>
      <?php } else { ?>
        <!-- 映画一覧 -->
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
                <img class="grid-item__favorite" src="<?= h($movie['favoriteIcon']) ?>" alt="" id="js-favorite<?= h($movie['id']) ?>" data-movie-id="<?= h($movie['id']) ?>" data-favorite="<?= h($movie['favorite']) ?>">
              </div>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
  <!-- Script -->
  <script src="js/index.js"></script>
</body>

</html>