<?php

require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';
require_once __DIR__ . '/Models/Date.php';
require_once __DIR__ . '/Models/Rate.php';
require_once __DIR__ . '/Models/Session.php';
require_once __DIR__ . '/Models/Database/MovieMapper.php';
require_once __DIR__ . '/Models/Database/MoviesGenresMapper.php';
require_once __DIR__ . '/Models/Database/ReviewMapper.php';

Session::start();

$title = '映画詳細';

if (empty($_GET['m_id'])) {
  Logger::warning('movie id is not specified.');

  // 映画IDが指定されていない場合、トップページへ
  header('Location:index');
  return;
}

$movieId = $_GET['m_id'];
$movie = MovieMapper::get($movieId);
if (empty($movie)) {
  Logger::warning('can not find the movie.');

  // 映画情報が取得できない場合、トップページへ
  header('Location:index');
  return;
}

// ログインしている場合
if (isLogin()) {
  // レビュー情報がPOSTされている場合
  if (!empty($_POST['rate']) && !empty($_POST['title']) && !empty($_POST['body'])) {
    // 新規追加か、更新か判定
    if (!empty($_POST['isUpdate']) && $_POST['isUpdate']) {
      // レビュー情報を更新
      ReviewMapper::update(
        $movieId,
        Session::get(SESSION_KEY_ACCOUNT_ID),
        $_POST['title'],
        $_POST['body'],
        $_POST['rate']
      );
    } else {
      // レビュー情報をDBに追加
      ReviewMapper::add(
        $movieId,
        Session::get(SESSION_KEY_ACCOUNT_ID),
        $_POST['title'],
        $_POST['body'],
        $_POST['rate']
      );
    }

    // 二重登録防止でリダイレクトする
    header('Location: movieDetail?m_id=' . $movieId);
  }

  // ログイン中アカウントのレビュー情報を取得
  $reviewFromLoginAccount = ReviewMapper::get($movieId, Session::get(SESSION_KEY_ACCOUNT_ID));
}

// 公開日のフォーマットを変更
$date = new Date($movie->release_date);
$releaseDate = $date->__toString();

// ジャンル情報を取得
$genres = MoviesGenresMapper::getGenres($movie->id);
$genreText = '';
foreach ($genres as $genre) {
  $genreText = $genreText . '[ ' . $genre['name'] . ' ]&emsp;';
}

// レビュー情報一覧を取得
$reviews = ReviewMapper::getAllWithMovieId($movieId);
// 平均評価を取得(小数第１までで四捨五入)
$averateRate = round(ReviewMapper::getAverageRate($movieId), 1);

// 評価のチェック判定
$rateFromLoginAccount = $reviewFromLoginAccount->rate ?? 0;
$checkedRate = function ($rate) use ($rateFromLoginAccount) {
  return ((int) $rateFromLoginAccount === $rate) ? 'checked' : '';
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

  <main class="main-content main-content--border">
    <article>
      <!-- 映画情報 -->
      <section class="movie-info-wrap padding--movie-detail" style="background-image: url('<?= h($movie->backdrop_path) ?>');">
        <div class="movie-info">
          <h2 class="movie-info__title"><?= h($movie->title) ?></h2>
          <p class="movie-info__genre"><?= html_entity_decode(h($genreText)) ?></p>
          <p class="movie-info__release-date">公開年：<?= h($releaseDate) ?></p>
          <p class="movie-info__overview"><?= h($movie->overview) ?></p>
        </div>
      </section>

      <!-- 映画レビュー -->
      <section class="movie-review padding--movie-detail">
        <h3 class="movie-review__title"><?= h(count($reviews)) ?>件のレビュー（平均評価：<span class="color--star"><?= Rate::convertRateToStar($averateRate) ?></span> <?= h(sprintf('%.1f', $averateRate)) ?>）</h3>
        <button id="js-review-show" class="review-show__btn">レビューを書く</button>

        <!-- レビュー投稿ダイアログ -->
        <dialog id="js-review-dialog" class="review-dialog">
          <h2 class="review-dialog__heading">レビューを投稿する</h2>

          <form id="js-review-dialog" action="" method="post">
            <input type="hidden" name="isUpdate" value="<?= !empty($reviewFromLoginAccount) ?>">

            <div class="review-dialog-star-wrap">
              <div>
                <h3 class="review-dialog__heading--sub">評価<span class="font-size--small"> [必須]</span></h3>
                <p id="js-review-star-error" class="review-dialog__error review-dialog__error--hidden font--error">&emsp;※星の数を選択してください</p>
              </div>
              <div class="review-dialog-star-area">
                <?php for ($i = 1; $i < 6; $i++) { ?>
                  <label class="review-dialog-star__label">
                    <input type="radio" class="review-dialog-star__radio" name="rate" value="<?= h($i) ?>" <?= h($checkedRate($i)) ?> />
                    <p class="review-dialog-star__icon"><?= h(str_repeat('★', $i)) ?></p>
                  </label>
                <?php } ?>
              </div>
            </div>

            <div class="review-dialog-title-wrap">
              <div>
                <h3 class="review-dialog__heading--sub">タイトル<span class="font-size--small"> [必須]</span></h3>
                <p id="js-review-title-error" class="review-dialog__error review-dialog__error--hidden font--error">&emsp;※タイトルを入力してください</p>
              </div>
              <input type="text" class="review-dialog__title" name="title" value="<?= h($reviewFromLoginAccount->title ?? '') ?>">
            </div>

            <div class="review-dialog-body-wrap">
              <div>
                <h3 class="review-dialog__heading--sub">レビュー内容<span class="font-size--small"> [必須]</span></h3>
                <p id="js-review-body-error" class="review-dialog__error review-dialog__error--hidden font--error">&emsp;※レビュー内容を入力してください</p>
              </div>
              <textarea class="review-dialog__body" rows="7" name="body"><?= h($reviewFromLoginAccount->body ?? '') ?></textarea>
            </div>

            <div class="review-dialog-btn-wrap">
              <input id="js-review-cancel" type="button" class="review-cancel__btn btn-opacity--hover" value="キャンセル">
              <input type="submit" class="review-post__btn btn-opacity--hover" value="投稿">
            </div>
          </form>
        </dialog>

        <!-- レビュー一覧 -->
        <div class="movie-review-list">
          <?php foreach ($reviews as $review) {
            $reviewDate = (new DateTime($review['review_date']))->format('Y年m月d日');
            $star = Rate::convertRateToStar($review['rate']);
            ?>
            <div class="movie-review-item">
              <div class="movie-review-item__user">
                <img class="movie-review-item__icon" src="<?= h('images/' . $review['icon']) ?>">
                <p class="movie-review-item__name"><?= h($review['nickname']) ?></p>
              </div>
              <p class="movie-review-item__rating color--star"><?= h($star) ?></p>
              <p class="movie-review-item__date"><?= h($reviewDate) ?></p>
              <p class="movie-review-item__title"><?= h($review['title']) ?></p>
              <p class="movie-review-item__body"><?= nl2br(h($review['body'])) ?></p>
            </div>
          <?php } ?>
        </div>
      </section>

    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
  <!-- Script -->
  <script src="js/movieDetail.js"></script>

</body>

</html>