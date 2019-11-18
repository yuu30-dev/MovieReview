<?php

/**
 * 映画情報のパース用クラス
 */
class MovieParser
{
    /**
     * 指定されたJSON文字列をパースして、Movie一覧を返します。
     *
     * @param string $jsonString
     * @return array
     */
    static function parse($jsonString): array
    {
        $movies = [];

        $json = json_decode($jsonString, true);
        $results = $json['results'];
        foreach ($results as $result) {
            $movie = new Movie();
            $movie->id              = $result['id'];
            $movie->genreIds        = $result['genre_ids'];
            $movie->title           = $result['title'];
            $movie->release_date     = $result['release_date'];
            $movie->overview        = $result['overview'];
            $movie->thumbnail_path   = TMDbWrapper::IMAGE_BASE_URL . 'w300' . $result['poster_path'];
            array_push($movies, $movie);
            $movie->backdrop_path    = TMDbWrapper::IMAGE_BASE_URL . 'w1280' . $result['backdrop_path'];
        }

        return $movies;
    }
}
