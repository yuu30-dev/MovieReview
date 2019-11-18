$(function() {
  /**
   * 「お気に入りアイコン」クリック時
   */
  $("[id^=js-favorite]").on("click", function() {
    // 未ログインの場合は処理しない
    if (!isLogin()) return;

    var $this = $(this);

    // データ属性から映画ID、お気に入り状態を取得
    var movieId = $this.data("movie-id");
    var isFavorite = $this.attr("data-favorite") === "true";

    // お気に入り状態切り替え
    toggleFavorite(movieId, isFavorite)
      .done(function() {
        $this.attr(
          "src",
          isFavorite ? "images/favorite_off.png" : "images/favorite_on.png"
        );
        $this.attr("data-favorite", isFavorite ? "false" : "true");
      })
      .fail(function() {
        // TODO: エラー処理
      });
  });

  /**
   * 「お気に入りアイコン」にマウスが入った時
   */
  $("[id^=js-favorite]").on("mouseenter", function() {
    // ログインしている場合は処理しない
    if (isLogin()) return;

    // ログインしていない場合、お気に入り追加できない旨の吹き出しを表示
    // 吹き出し用親要素を追加
    $(this).wrap("<div class=balloon></div>");

    // 吹き出しのテキストを追加
    $(this)
      .parent()
      .append(
        "<p id=js-balloon-text class=balloon__text>お気に入りに追加するにはログインが必要です</p>"
      );
  });

  /**
   * 「お気に入りアイコン」からマウスが離れた時
   */
  $("[id^=js-favorite]").on("mouseleave", function() {
    // ログインしている場合は処理しない
    if (isLogin()) return;

    // 吹き出しのテキストを削除
    $(this)
      .parent()
      .find("#js-balloon-text")
      .remove();

    // 吹き出し用親要素を削除
    $(this).unwrap();
  });
});
