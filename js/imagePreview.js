/**
 * 画像プレビュー処理
 */
$(function() {
  $("#js-icon-edit").on("change", function(item) {
    var file = item.target.files[0];
    var reader = new FileReader();
    var $preview = $("#js-icon-preview");

    // 画像ファイル以外の場合は何もしない
    if (file.type.indexOf("image") < 0) {
      return false;
    }

    // 画像ファイル読み込み完了後の処理
    reader.onload = (function(file) {
      return function(e) {
        // ロードした画像を表示する
        $preview.attr({
          src: e.target.result,
          title: file.name
        });
      };
    })(file);

    // 画像ファイル読み込み
    reader.readAsDataURL(file);
  });
});
