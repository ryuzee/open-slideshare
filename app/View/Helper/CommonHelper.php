<?php

App::uses('AppHelper', 'View/Helper');

class CommonHelper extends AppHelper
{
    public $helpers = array('Html');

    /**
     * Generate base url.
     *
     * @param bool $ssl
     *
     * @return string
     */
    public function base_url($ssl = true)
    {
        if ($ssl) {
            $protocol = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';

            return $protocol.$_SERVER['HTTP_HOST'];
        } else {
            return 'http://'.$_SERVER['HTTP_HOST'];
        }
    }

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
            $url = 'http://'.$bucket_name;
        } else {
            if (Configure::read('region') == 'us-east-1') {
                $url = 'https://'.$bucket_name.'.s3.amazonaws.com';
            } else {
                $url = 'https://'.$bucket_name.'.s3-'.Configure::read('region').'.amazonaws.com';
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
            $url = 'https://'.Configure::read('bucket_name').'.s3.amazonaws.com';
        } else {
            $url = 'https://'.Configure::read('bucket_name').'.s3-'.Configure::read('region').'.amazonaws.com';
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
        $url = $this->endpoint_s3(Configure::read('image_bucket_name')).'/'.$key.'/thumbnail.jpg';

        return $url;
    }

    /**
     * slide_page_url.
     *
     * @param mixed $object_path
     */
    public function slide_page_url($object_path)
    {
        $url = $this->endpoint_s3(Configure::read('image_bucket_name')).'/'.$object_path;

        return $url;
    }

    /**
     * json_url.
     *
     * @param mixed $key
     */
    public function json_url($key)
    {
        $url = $this->endpoint_s3(Configure::read('image_bucket_name')).'/'.$key.'/list.json';

        return $url;
    }
}
