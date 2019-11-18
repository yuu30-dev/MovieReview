<?php

/**
 * 映画_ジャンル中間テーブル用エンティティクラス
 */
class MoviesGenres
{
    /** @var int 映画_ジャンルID */
    public $id;

    /** @var int  映画ID */
    public $movieId;

    /** @var int  ジャンルID */
    public $genreId;
}
