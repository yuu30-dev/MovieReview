<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Entity/Movie.php';

/**
 * 映画テーブル管理クラス
 */
class MovieMapper
{
    /**
     * 映画を追加します。
     * 既に追加されている場合は、更新します。
     *
     * @param array $movies
     * @return void
     */
    public static function addOrUpdate(array $movies)
    {
        $sql = 'INSERT 
        INTO movies (id, title, release_date, overview, thumbnail_path, backdrop_path)
        VALUES(:id, :title, :releaseDate, :overview, :thumbnailPath, :backdropPath)
        ON DUPLICATE KEY UPDATE
        id = :id';

        $db = Database::getInstance();
        $db->prepare($sql);

        foreach ($movies as $movie) {
            $db->bind(':id', $movie->id)
                ->bind(':title', $movie->title)
                ->bind(':releaseDate', $movie->release_date)
                ->bind(':overview', $movie->overview)
                ->bind(':thumbnailPath', $movie->thumbnail_path)
                ->bind(':backdropPath', $movie->backdrop_path)
                ->excute();
        }
    }

    /**
     * 指定されたIDの映画を取得します。
     *
     * @param int $id
     * @return Movie
     */
    public static function get($id): Movie
    {
        $sql = 'SELECT * FROM movies WHERE id=:id';

        $movies = Database::getInstance()
            ->prepare($sql)
            ->setClassFetchMode('Movie')
            ->bind(':id', $id)
            ->resultAll();

        // Logger::debug('movie : ' . print_r($movies, true));

        return current($movies);
    }
}
