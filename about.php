<?php

require_once __DIR__ . '/Utils/function.php';
require_once __DIR__ . '/Utils/Constants.php';

$title = 'このサイトについて';

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

            <div class="transition">
                <p>
                    「Movie Review」は、シンプルなデザインをコンセプトにした映画レビューサービスです。<br>
                    映画レビューの投稿・確認、お気に入り登録など様々なサービスをご利用になれます。
                </p>
                <?php if (isLogin()) { ?>
                    <a href="index" class="transition__link color--link">> トップページへ</a>

                <?php } else { ?>
                    <a href="userRegister" class="transition__link color--link">> いますぐ始める</a>
                <?php } ?>

            </div>
        </article>
    </main>
    <!-- フッターファイル読み込み -->
    <?php include('footer.php') ?>
    <!-- Script -->
    <script src="js/movieFavorite.js"></script>

</body>

</html>