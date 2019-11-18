<?php

require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';
require_once __DIR__ . '/Models/Date.php';
require_once __DIR__ . '/Models/Session.php';
require_once __DIR__ . '/Models/Database/AccountMapper.php';

Session::start();

$title = 'プロフィール設定';

$accountId = Session::get(SESSION_KEY_ACCOUNT_ID);
if (empty($accountId)) {
  // ログインされていない場合、ログイン画面へ
  header('Location:login');
  return;
}

// アカウント情報取得
$account = AccountMapper::get('id', $accountId);

// アカウント情報を更新するか
$isUpdate = false;

// ファイル送信されている場合
if (!empty($_FILES['icon'])) {
  // 画像アップロード
  $uploadedFileName = uploadImage($_FILES['icon']);
  if ($uploadedFileName !== false) {
    $account->icon = $uploadedFileName;
    $isUpdate = true;
  }
}

// POST送信されている場合
if (!empty($_POST)) {
  Logger::debug('post : ' . print_r($_POST, true));
  $nickname   = (string) filter_input(INPUT_POST, 'nickname');
  $year       = (string) filter_input(INPUT_POST, 'birthdayYear');
  $month      = (string) filter_input(INPUT_POST, 'birthdayMonth');
  $day        = (string) filter_input(INPUT_POST, 'birthdayDay');
  $gender     = (int) filter_input(INPUT_POST, 'gender');

  if (!empty($nickname)) {
    $account->nickname = $nickname;
  }
  if (!empty($year) && !empty($month) && !empty($day)) {
    $account->birthday = $year . '/' . $month . '/' . $day;
  }
  $account->gender = $gender;

  $isUpdate = true;
}

if ($isUpdate) {
  if (AccountMapper::updateProfile($account)) {
    $updateMsg = '設定が変更されました。';
  } else {
    $updateMsg = '設定の変更に失敗しました。';
  }
}

// 表示用データの生成
$icon = 'images/' . $account->icon;
$nickname = $account->nickname;
// 誕生日
if (!empty($account->birthday)) {
  $date = new Date($account->birthday);
  $year = $date->format('Y');
  $month = $date->format('m');
  $day = $date->format('d');
}
$gender = empty($account->gender) ? 0 : (int) $account->gender;

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

      <!-- プロフィール設定 -->
      <form action="" method="post" enctype="multipart/form-data">
        <div class="profile">
          <div class="profile-icon">
            <img id="js-icon-preview" class="profile-icon__img" src="<?= h($icon) ?>" alt="">
            <label class="profile-icon__edit btn-opacity--hover">アイコンの編集<input type="file" id="js-icon-edit" name="icon" accept="image/*"></label>
          </div>
          <ul class="profile-info">
            <li class="profile-info-nickname">
              <label class="profile-info-item">ニックネーム</label>
              <input type="text" name="nickname" placeholder=" 例）◯◯◯" value="<?= h($nickname) ?>">
            </li>
            <li class="profile-info-birthday">
              <label class="profile-info-item">生年月日</label>
              <input class="profile-info-birthday__year" type="text" name="birthdayYear" placeholder=" 年" value="<?= h($year ?? '') ?>">
              <input class="profile-info-birthday__month" type="text" name="birthdayMonth" placeholder=" 月" value="<?= h($month ?? '') ?>">
              <input class="profile-info-birthday__day" type="text" name="birthdayDay" placeholder=" 日" value="<?= h($day ?? '') ?>">
            </li>
            <li class="profile-info-gender">
              <label class="profile-info-item">性別</label>
              <label class="profile-info-gender__label" for="men"><input type="radio" name="gender" value="0" <?= ($gender === 0) ? 'checked' : '' ?> id="men"> 男性</label>
              <label class="profile-info-gender__label" for="women"><input type="radio" name="gender" value="1" <?= ($gender === 1) ? 'checked' : '' ?> id="women"> 女性</label>
              <label class="profile-info-gender__label" for="secret"><input type="radio" name="gender" value="
2" <?= ($gender === 2) ? 'checked' : '' ?> id="secret"> 秘密</label>
            </li>
            <li class="profile-info__btn">
              <a class="profile-info__cancel btn-opacity--hover" href="index">キャンセル</a>
              <input class="profile-info__save btn-opacity--hover" type="submit" value="保存">
            </li>
          </ul>
        </div>
      </form>

    </article>
  </main>
  <!-- フッターファイル読み込み -->
  <?php include('footer.php') ?>
  <!-- script -->
  <script src="js/imagePreview.js"></script>
</body>

</html>