<?php

require_once __DIR__ . '/../Log/Logger.php';
require_once __DIR__ . '/../Models/Database/FavoriteMapper.php';
require_once __DIR__ . '/../Models/Session.php';
require_once __DIR__ . '/../Utils/Constants.php';

Session::start();

// Content-TypeをJSONに指定する
header('Content-Type: application/json');

// アカウントIDを取得
$accountId = Session::get(SESSION_KEY_ACCOUNT_ID);

// 映画ID, お気に入りの状態を取得
$movieId = filter_input(INPUT_POST, 'movieId');
$isFavorite = filter_input(INPUT_POST, 'favorite');

Logger::debug('accountId : ' . $accountId . ', movieId : ' . $movieId . ', isFavorite : ' . $isFavorite);

// 整合性チェック
if (empty($accountId) || empty($movieId) || empty($isFavorite)) {
    $error = 'お気に入り登録に失敗しました。もう一度やり直してください';
}

// エラー時
if (isset($error)) {
    // 「400 Bad Request」で {"error": "..."} のように返す
    http_response_code(400);
    echo json_encode(compact('error'));
    return;
}

if ($isFavorite === 'true') {
    // お気に入り登録されている場合、削除する
    Logger::debug('remove favorite');
    $isSuccess = FavoriteMapper::remove($movieId, $accountId);
} else {
    // お気に入り登録されていない場合、登録する
    Logger::debug('add favorite');
    $isSuccess = FavoriteMapper::add($movieId, $accountId);
}

if ($isSuccess) {
    // 「200 OK」で返す
    echo json_encode(null);
} else {
    // 「400 Bad Request」で {"error": "..."} のように返す
    http_response_code(400);
    echo json_encode(compact('error'));
}
