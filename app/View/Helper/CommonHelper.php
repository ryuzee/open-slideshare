<?php

App::uses('AppHelper', 'View/Helper');

class CommonHelper extends AppHelper
{
    public $helpers = array('Html');

    /**
     * Display comment.
     *
     * @param string $comment
     *
     * @return string
     */
    public function display($comment)
    {
        $comment = nl2br(h($comment));
        // @[Jeff Sutherland](contact:3) thanks!
        $pattern = '/@\[(.+)\]\(contact\:(\d+)\)/';
        $replace = '<a href="/users/view/$2">$1</a>';

        $comment = preg_replace($pattern, $replace, $comment);

        return $comment;
    }

    /**
     * Generate S3 endpoint.
     *
     * @param string $bucket_name
     *
     * @return string
     */
    public function endpoint_s3($bucket_name)
    {
        if (Configure::read('use_s3_static_hosting') == true) {
            $url = 'http://' . $bucket_name;
        } else {
            if (Configure::read('region') == 'us-east-1') {
                $url = 'https://' . $bucket_name . '.s3.amazonaws.com';
            } else {
                $url = 'https://' . $bucket_name . '.s3-' . Configure::read('region') . '.amazonaws.com';
            }
        }

        return $url;
    }

    /**
     * upload_endpoint.
     */
    public function upload_endpoint()
    {
        if (Configure::read('region') == 'us-east-1') {
            $url = 'https://' . Configure::read('bucket_name') . '.s3.amazonaws.com';
        } else {
            $url = 'https://' . Configure::read('bucket_name') . '.s3-' . Configure::read('region') . '.amazonaws.com';
        }

        return $url;
    }

    /**
     * Create url to thumbnail image.
     *
     * @param string $key
     *
     * @return url
     */
    public function thumbnail_url($key)
    {
        if (Configure::read('cdn_base_url')) {
            $url = Configure::read('cdn_base_url') . '/' . $key . '/thumbnail.jpg';
        } else {
            $url = $this->endpoint_s3(Configure::read('image_bucket_name')) . '/' . $key . '/thumbnail.jpg';
        }

        return $url;
    }

    /**
     * slide_page_url.
     *
     * @param mixed $object_path
     */
    public function slide_page_url($object_path)
    {
        if (Configure::read('cdn_base_url')) {
            $url = Configure::read('cdn_base_url') . '/' . $object_path;
        } else {
            $url = $this->endpoint_s3(Configure::read('image_bucket_name')) . '/' . $object_path;
        }

        return $url;
    }

    /**
     * json_url.
     *
     * @param mixed $key
     */
    public function json_url($key)
    {
        if (Configure::read('cdn_base_url')) {
            $url = Configure::read('cdn_base_url') . '/' . $key . '/list.json';
        } else {
            $url = $this->endpoint_s3(Configure::read('image_bucket_name')) . '/' . $key . '/list.json';
        }

        return $url;
    }

    /**
     * transcript_url.
     *
     * @param mixed $key
     */
    public function transcript_url($key)
    {
        if (Configure::read('cdn_base_url')) {
            $url = Configure::read('cdn_base_url') . '/' . $key . '/transcript.txt';
        } else {
            $url = $this->endpoint_s3(Configure::read('image_bucket_name')) . '/' . $key . '/transcript.txt';
        }

        return $url;
    }

    /**
     * truncate
     *
     * @param mixed $text
     * @param mixed $length
     * @param string $ending
     * @param mixed $exact
     */
    public function truncate($text, $length, $ending = '...', $exact = true)
    {
        $len = strlen($text);
        $mblen = mb_strlen($text, mb_internal_encoding());

        // including multibyte??
        if ($len !== $mblen) {
            $length = floor($length / 2);
        }

        if (strlen($text) <= $length) {
            return $text;
        } else {
            mb_internal_encoding("UTF-8");
            if (mb_strlen($text) > $length) {
                $length -= mb_strlen($ending);
                if (!$exact) {
                    $text = preg_replace('/\s+?(\S+)?$/', '', mb_substr($text, 0, $length + 1));
                }
                return mb_substr($text, 0, $length) . $ending;
            } else {
                return $text;
            }
        }
    }
}
