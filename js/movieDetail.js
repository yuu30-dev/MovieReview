$(function() {
  /** ダイアログオブジェクト */
  var $dialog = $("#js-review-dialog")[0];

  /**
   * 「レビューを書く」ボタンクリック時
   */
  $("#js-review-show").on("click", function() {
    // 未ログインの場合は処理しない
    if (!isLogin()) return;

    $dialog.showModal();
  });

  /**
   * 「レビューを書く」にマウスが入った時
   */
  $("#js-review-show").on("mouseenter", function() {
    // ログインしている場合は処理しない
    if (isLogin()) return;

    // ログインしていない場合、お気に入り追加できない旨の吹き出しを表示
    // 吹き出し用親要素を追加
    $(this).wrap("<div class=balloon></div>");

    // 吹き出しのテキストを追加
    $(this)
      .parent()
      .append(
        "<p id=js-balloon-text class=balloon__text>レビューを書くにはログインが必要です</p>"
      );
  });

  /**
   * 「レビューを書く」からマウスが離れた時
   */
  $("#js-review-show").on("mouseleave", function() {
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

  /**
   * 「投稿」ボタンクリック時(Submit時)
   */
  $("#js-review-dialog").on("submit", function() {
    var hasError = false;

    // 「評価」の選択チェック
    if ($("input[name=rate]:checked").length) {
      $("#js-review-star-error").addClass("review-dialog__error--hidden");
    } else {
      hasError = true;
      $("#js-review-star-error").removeClass("review-dialog__error--hidden");
    }

    // 「タイトル」の入力チェック
    if ($("input[name=title").val().length) {
      $("#js-review-title-error").addClass("review-dialog__error--hidden");
    } else {
      hasError = true;
      $("#js-review-title-error").removeClass("review-dialog__error--hidden");
    }

    // 「レビュー内容」の入力チェック
    if ($("textarea[name=body").val().length) {
      $("#js-review-body-error").addClass("review-dialog__error--hidden");
    } else {
      hasError = true;
      $("#js-review-body-error").removeClass("review-dialog__error--hidden");
    }

    // エラーが有ればSubmitしない
    if (hasError) {
      return false;
    }

    $dialog.close();
  });

  /**
   * 「キャンセル」ボタンクリック時
   */
  $("#js-review-cancel").on("click", function() {
    $dialog.close();
  });
});
