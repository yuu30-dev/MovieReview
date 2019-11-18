$(function() {
  /**
   * 「お気に入りアイコン」クリック時
   */
  $("#js-main-content").on("click", "[id^=js-favorite-icon-]", function() {
    // 未ログインの場合は処理しない
    if (!isLogin()) return;

    var $this = $(this);

    // データ属性から映画IDを取得
    var movieId = $this.data("movie-id");

    // お気に入り状態切り替え
    toggleFavorite(movieId, true)
      .done(function() {
        // お気に入り一覧を更新
        $("#js-main-content").load(location.href + " #js-favorite-area");
      })
      .fail(function() {
        // TODO: エラー処理
      });
  });
});
