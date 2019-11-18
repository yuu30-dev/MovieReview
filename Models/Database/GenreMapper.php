<?php

require_once __DIR__ . '/Database.php';

/**
 * ジャンルテーブル管理クラス
 */
class GenreMapper
{
    /**
     * ジャンルを追加します。
     *
     * @param array $genres
     * @return void
     */
    public static function add($genres)
    {
        $db = Database::getInstance();
        $sql = 'INSERT INTO genres (id, name) VALUES(:id, :name)';
        $db->prepare($sql);

        foreach ($genres as $genre) {
            $db->bind(':id', $genre->id)
                ->bind(':name', $genre->name)
                ->excute();
        }
    }

    /**
     * ジャンル一覧を取得します。
     *
     * @return array
     */
    public static function getAll()
    {
        $sql = 'SELECT * FROM genres';
        $genres = Database::getInstance()
            ->prepare($sql)
            ->setClassFetchMode('Genre')
            ->resultAll();

        // Logger::debug('genres : ' . print_r($genres, true));

        return $genres;
    }
}
