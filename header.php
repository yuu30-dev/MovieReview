<?php

require_once __DIR__ . '/Models/TMDb/TMDbWrapper.php';
require_once __DIR__ . '/Models/Database/GenreMapper.php';
require_once __DIR__ . '/Models/Database/AccountMapper.php';
require_once __DIR__ . '/Models/Session.php';
require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';

Session::start();

$isLogin = isLogin();
if ($isLogin) {
  $account = AccountMapper::get('id', Session::get(SESSION_KEY_ACCOUNT_ID));
  $nickname = $account->nickname;
  $icon = 'images/' . $account->icon;
}

// DBからジャンル情報を取得
$genres = GenreMapper::getAll();

if (count($genres) === 0) {
  $tmdb = TMDbWrapper::getInstance();
  $genres = $tmdb->getGenres();
  GenreMapper::add($genres);
}

// 検索が実行されていた場合、文字を取得
$searchText = empty($_GET['q']) ? '' : $_GET['q'];

?>

<header class="header">
  <div class="header-inner">
    <a class="header__title header__link" href="index">Movie Review</a>

    <nav class="header-nav">
      <ul>
        <li class="header-nav__item category"><a href="javascript:void(0)">カテゴリ</a>
          <ul class="category-list">
            <?php foreach ($genres as $genre) { ?>
              <li class="category-item"><a class="category-item__link" href="index?g_id=<?= h($genre->id) ?>"><?= h($genre->name) ?></a></li>
            <?php } ?>
          </ul>
        </li>
        <li class="header-nav__item"><a class="header__link" href="movieFavorite">お気に入り</a></li>
        <li class="header-nav__item"><a class="header__link" href="about">このサイトについて</a></li>
      </ul>
    </nav>

    <div class="header-menu">
      <!-- 映画検索 -->
      <div class="header-menu-search">
        <form action="index" method="get">
          <input class="header-menu-search__input" type="search" name="q" placeholder="作品名を入力してください" value="<?= h($searchText) ?>">
        </form>
      </div>

      <!-- ログインしている場合 -->
      <?php if ($isLogin) { ?>
        <!-- ユーザーアイコン / ニックネーム -->
        <div class="header-menu-account header-menu-account--login">
          <img class="header-menu-account__icon" src="<?= h($icon) ?>" alt="">
          <ul id="js-mypage" class="mypage">
            <li class="mypage-item"><a class="mypage-item__link" href="userAccount">アカウント</a></li>
            <li class="mypage-item"><a class="mypage-item__link" href="profileSetting">プロフィール設定</a></li>
            <li class="mypage-item"><a class="mypage-item__link" href="movieReview">レビュー履歴</a></li>
            <li class="mypage-item"><a class="mypage-item__link" href="logout">ログアウト</a></li>
          </ul>
          <p class="header-menu-account__item--right"><?= h($nickname) ?></p>
        </div>
      <?php } else { ?>
        <!-- ログイン / ユーザー登録 -->
        <div class="header-menu-account">
          <a class="header__link" href="login">ログイン</a>
          <a class="header-menu-account__item--right header__link" href="userRegister">ユーザー登録</a>
        </div>
      <?php } ?>
    </div>
  </div>
</header>