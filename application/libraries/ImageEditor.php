<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

    /**
	*
	* @package uideck-producto
	* @since 1.31.110
    */

    require_once FCPATH.'vendor/autoload.php';
    
    // import the Intervention Image Manager Class
    use Intervention\Image\ImageManager;
    use Intervention\Image\Image;

class ImageEditor {

    private $ci;

    public function __construct() {
        $this->ci = & get_instance();
        //$this->ci->load->model("Tickets_model");
    }

    public function render($epass) {

        // create an image manager instance with favored driver
        $manager = new ImageManager(['driver' => 'gd']);

        // to finally create image instances
        $img = $manager->canvas(648, 1080, '#3d3d3d');

        //Background
        $background = $manager->make( get_setting("system_file_path")."epass.jpg" )->resize(648, 1080);
        $img->insert($background, 'top', 0, 0); 

        // QRCode
        $qrgen = "data:image/png;base64,".get_qrcode_image($epass['uuid'], 'epass', 'verify', false, 400);
        $qrcode = $manager->make( $qrgen )->resize(400, 400);
        $img->insert($qrcode, 'top', 0, 318); 

        // UUID
        $img->text($epass['uuid'], 324, 749, function($font) {
            $font->file( getcwd()."/".get_setting("system_file_path")."/Myriad_Pro_Regular.ttf" );
            $font->size(24);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('top');
        });  

        // Fullname
        $img->text($epass['fname'], 324, 840, function($font) {
            $font->file( getcwd()."/".get_setting("system_file_path")."/Myriad_Pro_Bold.ttf" );
            $font->size(45);
            $font->color('#fa00b9');
            $font->align('center');
            $font->valign('top');
        }); 
        
        // area
        $img->text($epass['area'], 75, 920, function($font) {
            $font->file( getcwd()."/".get_setting("system_file_path")."/Myriad_Pro_Regular.ttf" );
            $font->size(30);
            $font->color('#ffffff');
            $font->align('left');
            $font->valign('top');
        });  

        // seat
        $img->text($epass['seat'], 575, 920, function($font) {
            $font->file( getcwd()."/".get_setting("system_file_path")."/Myriad_Pro_Regular.ttf" );
            $font->size(30);
            $font->color('#ffffff');
            $font->align('right');
            $font->valign('top');
        });  
        
        // Saving
        $dir_path = get_setting("event_epass_ticket_path");
        $file_name = $epass['uuid'];
        $file_mime = "jpg";
        $file_url = get_uri($url_path."/".$dir_path."/".$file_name.".".$file_mime);
        $file_path = getcwd()."/".$dir_path . $file_name . "." . $file_mime;
        
        $filedata = $img->save($file_path, 50, $file_mime)
            ->encode('data-url');

        return array(
            "path" => $dir_path."/".$filedata->basename, 
            "url" => $file_url,
            "base64" => $filedata->encoded
        );
    }

}