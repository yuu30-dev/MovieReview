<?php

require_once __DIR__ . '/../Models/Database/ReviewMapper.php';
require_once __DIR__ . '/../Models/Rate.php';
require_once __DIR__ . '/Constants.php';

/**
 * エスケープされた文字列を返します。
 *
 * @param string $str
 * @return string
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * ログインしているか
 *
 * @return boolean
 */
function isLogin(): bool
{
    Session::start();
    return Session::has(SESSION_KEY_ACCOUNT_ID);
}

/**
 * 映画の表示用データを生成して返します。
 *
 * @param Movie $movie
 * @param boolean $isFavorite
 * @return array
 */
function createDisplayData($movie, $isFavorite): array
{
    // レビュー情報取得
    $averageRate = round(ReviewMapper::getAverageRate($movie->id), 1);
    $star = Rate::convertRateToStar($averageRate);
    $reviewCount = ReviewMapper::count($movie->id);
    $reviewCount = $reviewCount === 0 ? '- ' : $reviewCount;

    // 表示用データ生成
    return $displayData = [
        'id' => $movie->id,
        'thumbnail' => $movie->thumbnail_path,
        'favoriteIcon' => $isFavorite ? FAVORITE_IMG_ON : FAVORITE_IMG_OFF,
        'favorite' => $isFavorite ? 'true' : 'false',
        'star' => $star,
        'averageRate' => $averageRate,
        'reviewCount' => $reviewCount
    ];
}

/**
 * 指定されたファイルの画像をアップロードします。
 *
 * @param [type] $file
 * @return string|false
 * アップロードに成功した場合、ファイル名を返します。アップロードに失敗した場合、falseを返します。
 */
function uploadImage($file)
{
    // 一時ファイルがアップロードされているか
    if (!is_uploaded_file($file['tmp_name'])) {
        return false;
    }

    // $files['type']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
    // exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
    $imagetype = exif_imagetype($file['tmp_name']);
    if (!in_array($imagetype, [IMAGETYPE_GIF, IMAGETYPE_PNG, IMAGETYPE_JPEG])) {
        return false;
    }

    // ファイル名が被らないようSHA-1ハッシュを使用してファイル名を設定
    $sha1FileName = sha1_file($file['tmp_name']);
    $extension = image_type_to_extension($imagetype);
    $uploadPath = 'images/' . $sha1FileName . $extension;

    // ファイルを移動する
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return false;
    }

    // ファイルが読出可能になるようにアクセス権限を変更
    chmod($uploadPath, 0644);

    // ファイル名を返す
    return $sha1FileName . $extension;
}
