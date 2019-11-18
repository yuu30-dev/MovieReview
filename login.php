<?php

require_once __DIR__ . '/Log/Logger.php';
require_once __DIR__ . '/Models/Database/AccountMapper.php';
require_once __DIR__ . '/Models/Session.php';
require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';

Session::start();

$title = 'ログイン';

// エラー表示用
$errorInfo = [];

if (!empty($_POST)) {
  // 可変変数を利用して値を取得
  foreach (['email', 'password'] as $v) {
    $$v = (string) filter_input(INPUT_POST, $v);
  }

  // 入力チェック
  if (empty($email)) {
    $errorInfo['email'] = '※Eメールアドレスを入力してください。';
  }
  if (empty($password)) {
    $errorInfo['password'] = '※パスワードを入力してください。';
  }

  // 入力内容にエラーがなかった場合
  if (empty($errorInfo)) {
    $account = AccountMapper::get('email', $_POST['email']);

    // 指定されたメールアドレスのアカウントが存在していた場合
    if (false !== $account) {
      if (password_verify($password, $account->password)) {
        Logger::debug('Login success');

        // セッションにアカウントIDを設定
        Session::set(SESSION_KEY_ACCOUNT_ID, $account->id);

        // TODO: ログイン維持の処理
        // 「ログインしたままにする」がチェックされているか判定
        if (isset($_POST['keepLogin'])) {
          Logger::debug('keepLogin: on');
        } else {
          Logger::debug('keepLogin: off');
        }

        // 前のページを取得
        $prevPage = (string) filter_input(INPUT_GET, 'prev');

        // 前のページが指定されている場合、そのページに戻る
        // 不正な値を考慮し、お気に入り画面のみとしておく
        if (!empty($prevPage) && $prevPage === 'favorite') {
          // お気に入り画面へ
          header('Location:movieFavorite');
        } else {
          // トップページへ
          header('Location:index');
        }

        return;
      } else {
        $errorInfo['login_faile'] = 'Eメールアドレスまたは、パスワードが間違っています。';
      }
    } else {
      $errorInfo['login_faile'] = 'Eメールアドレスまたは、パスワードが間違っています。';
    }
  }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title><?= h($title) ?>
  </title>
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans|Spectral&display=swap" rel="stylesheet">
</head>

<body class="body" class="body" class="body">
  <!-- ヘッダーファイル読み込み -->
  <?php include 'header.php'; ?>

  <main class="main-content">
    <article>
      <!-- メイン見出し -->
      <div class="main-heading">
        <h2 class="main-heading__title"><?= h($title) ?></h2>
      </div>

      <!-- 入力エリア -->
      <div class="main-content-input">
        <!-- エラー表示 -->
        <?php if (!empty($errorInfo['login_faile'])) { ?>
          <div class="login-error-wrap">
            <p class="font--error"><?= h($errorInfo['login_faile'] ?? '') ?></p>
          </div>
        <?php } ?>

        <form action="" method="post">
          <ul class="input-list">
            <li class="input-list-item">
              <label for="email">
                <span class="input-list-item__label">Eメールアドレス</span>
                <?php if (!empty($errorInfo['email'])) { ?>
                  <span class="font--error"><?= h($errorInfo['email']) ?></span>
                <?php } ?>
              </label>
              <input class="input-list-item__text" type="email" name="email" value="<?= h($email ?? '') ?>">
            </li>
            <li class="input-list-item">
              <label for="password">
                <span class="input-list-item__label">パスワード</span>
                <?php if (!empty($errorInfo['password'])) { ?>
                  <span class="font--error"><?= h($errorInfo['password']) ?></span>
                <?php } ?>
              </label>
              <input class="input-list-item__text" type="password" name="password" value="<?= h($password ?? '') ?>">
            </li>
            <li class="input-list-item input-list-item--left">
              <input type="checkbox" name="keepLogin" id="keepLogin">
              <label for="keepLogin">ログインしたままにする</label>
            </li>
            <li class="input-list-item">
              <input class="input-list-item__submit btn-opacity--hover" type="submit" value="ログイン">
            </li>
          </ul>
        </form>

        <div class="align--right">
          <a class="login-password-reminder__link color--link" href="passwordReminder">パスワードを忘れた場合はこちら</a>
        </div>
      </div>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
</body>

</html>