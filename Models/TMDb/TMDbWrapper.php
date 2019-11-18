<?php

require_once __DIR__ . '/../../Log/Logger.php';
require_once __DIR__ . '/../Network/NetworkManager.php';
require_once __DIR__ . '/../Database/Entity/Movie.php';
require_once __DIR__ . '/../Database/Entity/Genre.php';
require_once __DIR__ . '/../Singleton.php';
require_once __DIR__ . '/../TMDb/MovieParser.php';

/**
 * TheMovieDBのラッパークラス
 * @see https://www.themoviedb.org/
 */
class TMDbWrapper
{
    use Singleton;

    /** 画像の元URL */
    public const IMAGE_BASE_URL = "https://image.tmdb.org/t/p/";
    /** APIキー */
    private const API_KEY = "";
    /** 言語 */
    private const LANGUAGE = 'ja';
    /** TMDBのURL */
    private const TMDB_URL = 'https://api.themoviedb.org/3/';

    /** 通信用クラス */
    private $networkManager;

    /**
     * コンストラクタ
     */
    private function __construct()
    {
        $this->networkManager = new NetworkManager();
    }

    /**
     * 映画情報一覧を取得します。
     *
     * @param string    $sortType
     * @param int       $genreId
     * @param int       $page
     * @return array
     */
    public function getMovies($sortType, $genreId, $page = 1): array
    {
        // パラメータ作成
        $params = [];
        array_push($params, 'sort_by=' . $sortType);
        array_push($params, 'page=' . $page);
        if (!empty($genreId)) {
            array_push($params, 'with_genres=' . $genreId);
        }

        $jsonString = $this->request('discover/movie', $params);
        $movies = MovieParser::parse($jsonString);

        // Logger::debug('getMovies() return : ' . print_r($movies, true));

        return $movies;
    }

    /**
     * ジャンル一覧を取得します。
     *
     * @return array
     */
    public function getGenres(): array
    {
        $genres = [];

        $jsonString = $this->request('genre/movie/list');
        $json = json_decode($jsonString, true);

        foreach ($json['genres'] as $genre) {
            $movieGenre = new Genre();
            $movieGenre->id    = $genre['id'];
            $movieGenre->name  = $genre['name'];
            array_push($genres, $movieGenre);
        }

        // Logger::debug('getGenres() return : ' . print_r($genres, true));

        return $genres;
    }

    /**
     * 指定されたテキストの映画情報一覧を取得します。
     *
     * @return array
     */
    public function search($text, $page = 1): array
    {
        // Logger::debug('search() text: ' . $text);

        // パラメータ作成
        $params = [];
        array_push($params, 'query=' . urlencode($text));
        array_push($params, 'page=' . $page);

        $jsonString = $this->request('search/movie', $params);
        $movies = MovieParser::parse($jsonString);

        // Logger::debug('search() return : ' . print_r($movies, true));

        return $movies;
    }

    /**
     * リクエスト処理を実行します。
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    private function request($url, $params = [])
    {
        $apiKey = 'api_key=' . TMDbWrapper::API_KEY;
        $language = 'language=' . TMDbWrapper::LANGUAGE;

        $param = '';
        foreach ($params as $value) {
            $param = $param . '&' . $value;
        }

        $requestUrl = TMDbWrapper::TMDB_URL . $url . '?' . $apiKey . '&' . $language . $param;
        // Logger::debug('request : ' . $requestUrl);

        return $this->networkManager->get($requestUrl);
    }
}
