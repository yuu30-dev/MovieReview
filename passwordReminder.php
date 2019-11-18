<?php

require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';
require_once __DIR__ . '/Models/Session.php';
require_once __DIR__ . '/Models/Mail.php';
require_once __DIR__ . '/Models/Database/AccountMapper.php';

$title = 'パスワードを忘れた方';

if (!empty($_POST)) {
  $email = filter_input(INPUT_POST, "email");

  if (empty($email)) {
    $errorInfo['email'] = '※Eメールアドレスを入力してください';
  } else if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorInfo['email'] = '※Eメールアドレスの形式が不正です';
  } else {
    $account = AccountMapper::get('email', $email);
    if (false === $account) {
      $errorInfo['email'] = '※登録されていないEメールアドレスです';
    } else {
      // 新しいランダムなパスワードを生成
      $newPassword = substr(bin2hex(random_bytes(8)), 0, 8);
      // DBのパスワードを更新
      AccountMapper::updatePassword($account->id, $newPassword);
      // 新しいパスワードを登録メールアドレスに送信
      $to = $account->email;
      $subject = '[Movie Review]新しいパスワードのお知らせ';
      $body = '新しいパスワードを設定しました。' . "\n\n" . '新しいパスワード：' . $newPassword . "\n\n" . '下記URLにアクセスしてログインしてください。' . "\n" . 'https://yuu30.com/movie/login ' . "\n\n" . '※パスワードは忘れないよう大切に保管してください。';
      Mail::send($to, $subject, $body);

      Session::start();
      Session::set(SESSION_KEY_SEND_EMAIL, $email);
      header('Location:passwordRemindered');
      return;
    }
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

      <!-- パスワードを忘れた方 -->
      <div class="password-reminder">
        <p class="password-reminder__text">
          登録しているメールアドレスをご入力ください。<br>
          ご登録のメールアドレスに、パスワードをお送りします。
        </p>

        <div class="password-reminder-input">
          <form action="passwordReminder" method="post">
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
                <input class="input-list-item__submit input-list-item__submit--small btn-opacity--hover" type="submit" value="送信">
              </li>
            </ul>
          </form>
        </div>
      </div>
    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
</body>

</html>