<?php
/**
 * \brief   
 * \details     
 * @author  Mario Pastuović
 * @version 1.0
 * \date 30.11.16.
 * \copyright
 *     This code and information is provided "as is" without warranty of
 *     any kind, either expressed or implied, including but not limited to
 *     the implied warranties of merchantability and/or fitness for a
 *     particular purpose.
 *     \par
 *     Copyright (c) Gauss d.o.o. All rights reserved
 * Created by PhpStorm.
 */


namespace TwitteratorBundle\Services;

class TweeterProxy {

    private $oauth_access_token;
    private $oauth_access_token_secret;
    private $consumer_key;
    private $consumer_secret;

    private $config = [
        'base_url' => 'https://api.twitter.com/1.1/',
        'return_format' => '.json'
    ];

    private function buildBaseString($baseURI, $method, $params)
    {
        $r = array();
        ksort($params);
        foreach($params as $key=>$value){
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    private function buildAuthorizationHeader($oauth) {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key=>$value)
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);
        return $r;
    }

    public function __construct($params)
    {
        $this->oauth_access_token = $params['oauth_access_token'];
        $this->oauth_access_token_secret = $params['oauth_access_token_secret'];
        $this->consumer_key = $params['consumer_key'];
        $this->consumer_secret = $params['consumer_secret'];
    }


    private function resolveRouteParameters($params_array)
    {
        $params_str = '';
        $chk = false;

        foreach($params_array as $key => $value){
            if(!$chk){
                $params_str .= '?'.$key.'='.rawurlencode($value);
                $chk = true;
            }else{
                $params_str .= '&'.$key.'='.rawurlencode($value);
            }
        }

        return $params_str;
    }

    public function executeRoute($route, $params = null)
    {
       if($params != null){
           $url = $route.$this->config['return_format'].$this->resolveRouteParameters($params);
           return $this->get($url);
       }else{
           return $this->get($route.$this->config['return_format']);
       }
    }

    private function get($url)
    {

        // Figure out the URL parameters
        $url_parts = parse_url($url);
        parse_str($url_parts['query'], $url_arguments);

        $full_url = $this->config['base_url'] . $url; // URL with the query on it
        $base_url = $this->config['base_url'] . $url_parts['path']; // URL without the query

        // Set up the OAuth Authorization array
        $oauth = [
            'oauth_consumer_key' => $this->consumer_key,
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $this->oauth_access_token,
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0'
        ];
        $base_info = $this->buildBaseString($base_url, 'GET', array_merge($oauth, $url_arguments));

        $composite_key = rawurlencode($this->consumer_secret) . '&' . rawurlencode($this->oauth_access_token_secret);
        $oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));

        // Make Requests
        $header = [
            $this->buildAuthorizationHeader($oauth),
            'Expect:'
        ];
        $options = [
            CURLOPT_HTTPHEADER => $header,
            //CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HEADER => false,
            CURLOPT_URL => $full_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ];

        $feed = curl_init();
        curl_setopt_array($feed, $options);
        $result = curl_exec($feed);
        $info = curl_getinfo($feed);
        curl_close($feed);

        // Send suitable headers to the end user.
        if (isset($info['content_type']) && isset($info['size_download'])) {
            header('Content-Type: ' . $info['content_type']);
            header('Content-Length: ' . $info['size_download']);
        }
        return json_decode($result);
    }
}