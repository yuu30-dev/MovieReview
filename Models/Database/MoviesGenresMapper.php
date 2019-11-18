<?php

require_once __DIR__ . '/Database.php';

/**
 * 映画・ジャンルテーブル管理クラス
 */
class MoviesGenresMapper
{
    /**
     * 映画・ジャンル情報を追加します。
     *
     * @param int $movieId
     * @param int $genreId
     * @return void
     */
    public static function add($movieId, $genreIds)
    {
        $sql = 'INSERT INTO movies_genres (movie_id, genre_id) 
        SELECT * FROM (select :movieId, :genreId) as TMP
        WHERE NOT EXISTS 
        (SELECT * FROM movies_genres WHERE movie_id = :movieId AND genre_id = :genreId)';

        $db = Database::getInstance()
            ->prepare($sql)
            ->bind(':movieId', $movieId);

        foreach ($genreIds as $genreId) {
            $db->bind('genreId', $genreId)
                ->excute();
        }
    }

    /**
     * 指定された映画IDのジャンル一覧を返します。
     *
     * @param int $movieId
     * @return void
     */
    public static function getGenres($movieId)
    {
        $sql = 'SELECT genres.name FROM movies_genres
        INNER JOIN genres
        ON movies_genres.genre_id = genres.id
        WHERE movies_genres.movie_id = :movieId';

        $genres = Database::getInstance()
            ->prepare($sql)
            ->bind(':movieId', $movieId)
            ->resultAll();

        // Logger::debug('getGenres : ' . print_r($genres, true));

        return $genres;
    }
}
