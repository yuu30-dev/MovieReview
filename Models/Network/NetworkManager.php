<?php

class NetworkManager
{
    // cURL
    private $curl;

    /**
     * コンストラクタ
     *
     * @param string $url
     */
    function __construct()
    {
        $this->curl = curl_init();
    }

    /**
     * GETリクエストを送信します。
     *
     * @param string $url
     * @param string $params
     * @return string
     */
    function get($url, $params = null)
    {
        return $this->request($url, 'GET', $params);
    }

    /**
     * POSTリクエストを送信します。
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    function post($url, $params = null)
    {
        return $this->request($url, 'POST', $params);
    }

    /**
     * リクエストを送信します。
     *
     * @param string $url
     * @param string $method
     * @param string|array $params
     * @return string
     */
    private function request($url, $method, $params = null)
    {
        //オプション
        $this->addOption(CURLOPT_URL, $url);
        $this->addOption(CURLOPT_RETURNTRANSFER, true);
        if ($method === 'GET') {
            $this->addOption(CURLOPT_HTTPGET, true);
        } else {
            $this->addOption(CURLOPT_POST, true);
        }

        // 実行
        $response = curl_exec($this->curl);

        return $response;
    }

    /**
     * オプションを追加します。
     *
     * @param int $option
     * @param mixed $value
     * @return void
     */
    private function addOption($option, $value)
    {
        curl_setopt($this->curl, $option, $value);
    }
}
