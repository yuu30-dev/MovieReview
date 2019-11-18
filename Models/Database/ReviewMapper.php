<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Entity/Review.php';

/**
 * レビューテーブル管理クラス
 */
class ReviewMapper
{
    /**
     * レビューを追加します。
     *
     * @param int $movieId
     * @param int $accountId
     * @param string $title
     * @param string $body
     * @param int $rate
     * @return void
     */
    public static function add($movieId, $accountId, $title, $body, $rate)
    {
        Logger::debug('add review.');

        $sql = 'INSERT 
        INTO reviews (movie_id, account_id, title, body, rate)
        VALUES(:movieId, :accountId, :title, :body, :rate)';

        Database::getInstance()
            ->prepare($sql)
            ->bind(':movieId', $movieId)
            ->bind(':accountId', $accountId)
            ->bind(':title', $title)
            ->bind(':body', $body)
            ->bind(':rate', $rate)
            ->excute();
    }

    /**
     * レビューを更新します。
     *
     * @param int $movieId
     * @param int $accountId
     * @param string $title
     * @param string $body
     * @param int $rate
     * @return void
     */
    public static function update($movieId, $accountId, $title, $body, $rate)
    {
        Logger::debug('update review.');

        $sql = 'UPDATE reviews
        SET title = :title, body = :body, rate = :rate
        WHERE movie_id = :movieId AND account_id = :accountId';

        Database::getInstance()
            ->prepare($sql)
            ->bind(':movieId', $movieId)
            ->bind(':accountId', $accountId)
            ->bind(':title', $title)
            ->bind(':body', $body)
            ->bind(':rate', $rate)
            ->excute();
    }

    /**
     * 指定されたアカウントIDのレビュー情報一覧を取得します。
     *
     * @param int $accountId
     * @return array
     */
    public static function getAllWithAccountId($accountId): array
    {
        Logger::debug('get all reviews with account id: ' . $accountId);

        $sql = 'SELECT reviews.movie_id AS movieId, reviews.title AS reviewTitle, reviews.body, reviews.review_date AS reviewDate, reviews.rate, movies.title AS movieTitle, movies.thumbnail_path AS thumbnailPath
         FROM reviews 
         INNER JOIN movies
         ON reviews.movie_id = movies.id
         WHERE reviews.account_id = :accountId
         ORDER BY reviews.review_date DESC';

        $reviews = Database::getInstance()
            ->prepare($sql)
            ->bind(':accountId', $accountId)
            ->resultAll();

        Logger::debug('reviews : ' . print_r($reviews, true));

        return $reviews;
    }

    /**
     * 指定された映画IDのレビュー情報一覧を取得します。
     *
     * @param int $movieId
     * @return array
     */
    public static function getAllWithMovieId($movieId): array
    {
        Logger::debug('get all reviews with movie id: ' . $movieId);

        $sql = 'SELECT reviews.title, reviews.body, reviews.review_date, reviews.rate, accounts.nickname, accounts.icon
         FROM reviews 
         INNER JOIN accounts
         ON reviews.account_id = accounts.id
         WHERE reviews.movie_id = :movieId
         ORDER BY reviews.review_date DESC';

        $reviews = Database::getInstance()
            ->prepare($sql)
            ->bind(':movieId', $movieId)
            ->resultAll();

        // Logger::debug('reviews : ' . print_r($reviews, true));

        return $reviews;
    }

    /**
     * 指定された映画ID・アカウントIDのレビュー情報一覧を取得します。
     *
     * @param int $movieId
     * @param int $accountId
     * @return Review
     */
    public static function get($movieId, $accountId)
    {
        Logger::debug('get review : ' . $movieId);

        $sql = 'SELECT * FROM reviews 
         WHERE reviews.movie_id = :movieId AND reviews.account_id = :accountId';

        $reviews = Database::getInstance()
            ->prepare($sql)
            ->setClassFetchMode('Review')
            ->bind(':movieId', $movieId)
            ->bind(':accountId', $accountId)
            ->resultAll();

        // Logger::debug('review : ' . print_r(current($reviews), true));

        return current($reviews);
    }

    /**
     * 指定された映画IDの平均評価を取得します。
     *
     * @param int $movieId
     * @return float
     */
    public static function getAverageRate($movieId)
    {
        // Logger::debug('get average rate : ' . $movieId);

        $sql = 'SELECT AVG(rate) FROM reviews
        WHERE movie_id = :movieId';

        $rate = Database::getInstance()
            ->prepare($sql)
            ->bind(':movieId', $movieId)
            ->single();

        // Logger::debug('average rate : ' . print_r($rate['AVG(rate)'], true));

        return $rate['AVG(rate)'];
    }

    /**
     * 指定された映画IDの評価数を返します。
     *
     * @param [type] $movieId
     * @return int
     */
    public static function count($movieId): int
    {
        // Logger::debug('review count movie id : ' . $movieId);

        $sql = 'SELECT COUNT(*) FROM reviews
        INNER JOIN accounts
        ON reviews.account_id = accounts.id
        WHERE reviews.movie_id = :movieId';

        $count = Database::getInstance()
            ->prepare($sql)
            ->bind(':movieId', $movieId)
            ->single();

        // Logger::debug('review count : ' . print_r($count['COUNT(*)'], true));

        return $count['COUNT(*)'];
    }
}
