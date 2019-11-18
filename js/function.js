/**
 * ログイン済みか判定します。
 */
function isLogin() {
  return $("#js-mypage").length;
}

/**
 * お気に入り状態を切り替えます。
 * @param {int} movieId
 * @param {boolean} isFavorite
 */
function toggleFavorite(movieId, isFavorite) {
  // お気に入りの通信を実行
  return $.ajax({
    type: "post",
    url: "Controllers/Favorite.php",
    dataType: "json",
    data: {
      movieId: movieId,
      favorite: isFavorite
    }
  });
}
