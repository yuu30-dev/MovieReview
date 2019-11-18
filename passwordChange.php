<?php

require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';
require_once __DIR__ . '/Models/Session.php';
require_once __DIR__ . '/Models/Mail.php';
require_once __DIR__ . '/Models/Database/AccountMapper.php';

$title = 'パスワード変更';

if (!empty($_POST)) {
  Session::start();

  foreach (['oldPassword', 'newPassword', 'newPasswordConfirm'] as $key) {
    $$key = filter_input(INPUT_POST, $key);
  }

  if (empty($oldPassword)) {
    $errorInfo['oldPassword'] = '※現在のパスワードを入力してください';
  } else {
    $account = AccountMapper::get('id', Session::get(SESSION_KEY_ACCOUNT_ID));
    if (false === password_verify($oldPassword, $account->password)) {
      $errorInfo['oldPassword'] = '※パスワードが間違っています';
    }
  }
  if (empty($newPassword)) {
    $errorInfo['newPassword'] = '※新しいパスワードを入力してください';
  } else if (strlen($newPassword) < 6) {
    $errorInfo['newPassword'] = '※パスワードは6文字以上で入力してください';
  } else if ($newPassword !== $newPasswordConfirm) {
    $errorInfo['newPasswordConfirm'] = '※パスワードが一致していません';
  }

  if (empty($errorInfo)) {
    // DBのパスワードを更新
    AccountMapper::updatePassword($account->id, $newPassword);
    // 新しいパスワードを登録メールアドレスに送信
    $to = $account->email;
    $subject = '[Movie Review]新しいパスワードのお知らせ';
    $body = 'パスワード変更が完了しました。' . "\n\n" . '新しいパスワード：' . $newPassword . "\n\n" . '※パスワードは忘れないよう大切に保管してください。';
    Mail::send($to, $subject, $body);

    Session::set(SESSION_KEY_SEND_EMAIL, $to);
    header('Location:passwordChanged');
    return;
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

  <main class="main-content">
    <article>
      <!-- メイン見出し -->
      <div class="main-heading">
        <h2 class="main-heading__title"><?= h($title) ?></h2>
      </div>

      <div class="main-content-input">
        <form action="passwordChange" method="post">
          <ul class="input-list">
            <li class="input-list-item">
              <label for="oldPassword">
                <span class="input-list-item__label">現在のパスワード</span>
                <?php if (!empty($errorInfo['oldPassword'])) { ?>
                  <span class="font--error"><?= h($errorInfo['oldPassword']) ?></span>
                <?php } ?>
              </label>
              <input class="input-list-item__text" type="password" name="oldPassword" value="<?= h($oldPassword ?? '') ?>">
            </li>
            <li class="input-list-item">
              <label for="newPassword">
                <span class="input-list-item__label">新しいパスワード</span>
                <?php if (!empty($errorInfo['newPassword'])) { ?>
                  <span class="font--error"><?= h($errorInfo['newPassword']) ?></span>
                <?php } ?>
              </label>
              <input class="input-list-item__text" type="password" name="newPassword" placeholder="6文字以上で入力してください" value="<?= h($newPassword ?? '') ?>">
            </li>
            <li class="input-list-item">
              <label for="newPasswordConfirm">
                <span class="input-list-item__label">新しいパスワード確認</span>
                <?php if (!empty($errorInfo['newPasswordConfirm'])) { ?>
                  <span class="font--error"><?= h($errorInfo['newPasswordConfirm']) ?></span>
                <?php } ?>
              </label>
              <input class="input-list-item__text" type="password" name="newPasswordConfirm" value="<?= h($newPasswordConfirm ?? '') ?>">
            </li>
            <li class="input-list-item">
              <input class="input-list-item__submit btn-opacity--hover" type="submit" value="変更">
            </li>
          </ul>
        </form>
      </div>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
</body>

</html>