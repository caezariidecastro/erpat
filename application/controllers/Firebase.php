<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Firebase extends MY_Controller {

    function __construct()
    {
        parent::__construct();
    }

    function trigger($event = null) 
    {
        $server_key = get_setting('firebase_server_key');
        $title = $this->input->post('title');
        $body = $this->input->post('body');
        $icon = $this->input->post('icon');
        $url = $this->input->post('url');
        $extra = $this->input->post('extra');

        $uid = $this->input->post('uid');

        if($event == 'cloud-messaging') {

            $data = [
                //'topic' => 'highscore',
                'to' => 'cIQCRIv7SZTO7CcqxfH4v_:APA91bGZDmYc4kxHSreCXwEZJVeaDdvwBrTDvWe3IMBAOhTw6KUR4sd18IcHV9TJDlMIXWJbV3ILNjRpru7nElnEGlreElSlKzvzfjJdneTUzNbs1ksiCeohl7MN6VHogCn3HKeYC1eE',
                'data' => [
                    'title' => $title, 
                    'body' => $body, 
                    'icon' => $icon, 
                    'url' => $url,
                    'extra' => $extra
                ]
            ];

            // use key 'http' even if you send the request to https://...
            $options = array(
                'http' => array(
                    'header'  => 
                        "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n" . 
                        "Authorization:key=".$server_key,
                    'method'  => 'POST',
                    'content' => json_encode( $data )
                )
            );
            $context  = stream_context_create($options);
            $content = file_get_contents('https://fcm.googleapis.com/fcm/send', false, $context);
            if($content !== FALSE) { 
                echo json_encode( array('success' => true ) ); 
            } else {
                echo json_encode( array('success' => false ) );
            }
            
        }
    }
}