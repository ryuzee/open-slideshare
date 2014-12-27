<?php

App::uses('AppHelper', 'View/Helper');

class CommonHelper extends AppHelper
{
    public $helpers = array('Html');

    public function base_url($ssl = true)
    {
        if ($ssl) {
            $protocol =  empty($_SERVER["HTTPS"]) ? "http://" : "https://";

            return $protocol . $_SERVER["HTTP_HOST"];
        } else {
            return "http://" . $_SERVER["HTTP_HOST"];
        }
    }

    public function display($comment)
    {
        $comment = nl2br(h($comment));
        // @[Jeff Sutherland](contact:3) thanks!
        $pattern = '/@\[(.+)\]\(contact\:(\d+)\)/';
        $replace = '<a href="/users/view/$2">$1</a>';

        $comment = preg_replace($pattern, $replace, $comment);

        return $comment;
    }

    public function endpoint_s3($bucket_name) {
        if (Configure::read('region') == "us-east-1") {
            $url = "https://". $bucket_name . ".s3.amazonaws.com";
        } else {
            $url = "https://". $bucket_name . ".s3-" . Configure::read('region') .".amazonaws.com";
        }
        return $url;
    }
}
