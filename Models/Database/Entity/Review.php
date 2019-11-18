<?php

/**
 * レビューテーブル用エンティティクラス
 */
class Review
{
    /** @var int レビューID */
    public $id;

    /** @var int 映画ID */
    public $movie_id;

    /** @var int アカウントID */
    public $account_id;

    /** @var string タイトル */
    public $title;

    /** @var string レビュー内容 */
    public $body;

    /** @var string レビュー日時 */
    public $review_date;

    /** @var float 評価 */
    public $rate;
}
