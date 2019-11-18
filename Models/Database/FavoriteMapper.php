<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Entity/Movie.php';

/**
 * お気に入りテーブル管理クラス
 */
class FavoriteMapper
{
    /**
     * お気に入りに追加します。
     *
     * @param 映画ID $movieId
     * @param アカウントID $accountId
     * @return boolean
     */
    public static function add($movieId, $accountId): bool
    {
        $sql = 'INSERT INTO favorites (movie_id, account_id) VALUES(:movieId, :accountId)';

        return Database::getInstance()
            ->prepare($sql)
            ->bind(':movieId', $movieId)
            ->bind(':accountId', $accountId)
            ->excute();
    }

    /**
     * お気に入りから削除します。
     *
     * @param 映画ID $movieId
     * @param アカウントID $accountId
     * @return boolean
     */
    public static function remove($movieId, $accountId): bool
    {
        $sql = 'DELETE FROM favorites WHERE movie_id=:movieId AND account_id=:accountId';

        return Database::getInstance()
            ->prepare($sql)
            ->bind(':movieId', $movieId)
            ->bind(':accountId', $accountId)
            ->excute();
    }

    /**
     * お気に入りに登録されているか判定します。
     *
     * @param 映画ID $movieId
     * @param アカウントID $accountId
     * @return boolean
     */
    public static function has($movieId, $accountId): bool
    {
        $sql = 'SELECT * FROM favorites 
        WHERE movie_id = :movieId AND account_id = :accountId';

        return Database::getInstance()
            ->prepare($sql)
            ->bind(':movieId', $movieId)
            ->bind(':accountId', $accountId)
            ->rowCount() > 0;
    }

    /**
     * 指定されたアカウントのお気に入り一覧を返します。
     *
     * @param string $accountId
     * @return array
     */
    public static function getAll($accountId): array
    {
        $sql = 'SELECT movies.* FROM favorites
        INNER JOIN movies
        ON favorites.movie_id = movies.id
        WHERE account_id=:accountId';

        $movies = Database::getInstance()
            ->prepare($sql)
            ->setClassFetchMode('Movie')
            ->bind(':accountId', $accountId)
            ->resultAll();

        // Logger::debug('favorite movies : ' . print_r($movies, true));

        return $movies;
    }
}
