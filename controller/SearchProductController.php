<?php

require_once "./app/endpoint.php";

class SearchProductController
{
    public function handleSearchByImage($requestFile)
    {
        $endpoint_url = URL_PYTHON_MODULE . '/get-data-image';
        $post_data = array(
            'search_image' => new CURLFile($requestFile['tmp_name'], $requestFile['type'], $requestFile['name'])
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);

        if (!empty($response['code']) && $response['code'] == 200) {
            return !empty($response['data']) ? $response['data'] : [];
        }

        return [];
    }

    public function prepareParam($string)
    {
        list($sick, $percent) = explode(" (", $string);

        return [
            'sick' => $sick,
            'percent' => $percent
        ];
    }
}
