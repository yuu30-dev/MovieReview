<?php

/**
 * 映画用モデルクラス
 */
class Movie
{

    /** @var int 映画ID */
    public $id;

    /** @var Genre[] カテゴリID一覧 */
    public $genreIds;

    /** @var string タイトル */
    public $title;

    /** @var string 公開日 */
    public $release_date;

    /** @var string 概要 */
    public $overview;

    /** @var string サムネイルパス */
    public $thumbnail_path;

    /** @var string 背景画像パス */
    public $backdrop_path;
}
