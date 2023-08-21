<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."third_party/phpqr/qrlib.php";
class Phpqr { 
    public function __construct() { 
        //parent::__construct();
    }

    public static function generate($data, $simple = true) {
        if($simple) {    
            $text = QRcode::text(json_encode($data));
            $raw = join("<br/>", $text);
            
            $raw = strtr($raw, array(
                '0' => '<span style="color:white">&#9608;&#9608;</span>',
                '1' => '&#9608;&#9608;'
            ));
                        
            return '<tt style="font-size:7px">'.$raw.'</tt>';
        } else {
            return QRcode::svg(json_encode($data));
        }
    }
}
