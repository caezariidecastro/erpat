<?php

    if (!function_exists('get_team_all_unique')) {
        function get_team_all_unique($heads, $members) {
            $users = [];
            $lists = explode(",", $heads.",".$members);
            for ($i = 0; $i < count($lists); $i++) {
                if (isset($lists[$i]) && !in_array($lists[$i], $users)) {
                    $users[] = $lists[$i];
                }
            }   
            return $users;
        }
    }
    
    if (!function_exists('get_qrcode_image')) {
        function get_qrcode_image($string, $module, $action, $is_html = false, $size = 75) {
            
            require_once APPPATH."third_party/tcpdf/tcpdf_barcodes_2d.php";
            if( $module || $action) {
                $string = json_encode([$module, $action, $string]);
            }
            $qrcodeobj = new TCPDF2DBarcode($string, 'QRCODE,H');
            $qr_png_data = $qrcodeobj->getBarcodePngData();
            $qrcode = base64_encode($qr_png_data);
            $qr_base64 = "data:image/png;base64,".$qrcode;

            if($is_html) {
                $qrcode = '<a href="'.$qr_base64.'" download="qrcode-'.$string.'.png"><img src="' . $qr_base64 . '" width="'.$size.'" height="'.$size.'"/></a>';
            }
            return $qrcode;
        }
    }

    if (!function_exists('get_barcode_image')) {
        function get_barcode_image($string, $is_html = false, $width = 1.7, $height = 90) {
            
            require_once APPPATH."third_party/tcpdf/tcpdf_barcodes_1d.php";
            $barcodeobj = new TCPDFBarcode($string, 'C39E+');
            $bar_png_data = $barcodeobj->getBarcodePngData($width,$height);
            $barcode = base64_encode($bar_png_data);
            $bar_base64 = "data:image/png;base64,".$barcode;
            
            if($is_html) {
                $barcode = '<a href="'.$bar_base64.'" download="barcode-'.$string.'.png"><img src="' . $bar_base64 . '" /></a>';
            }
            return $barcode;
        }
    }

    if (!function_exists('num_limit')) {
        function num_limit($number, $max = NULL, $min = 0) {
            if($number < $min) {
                return $min;
            }
            
            if($max !== NULL && $number > $max) {
                return $max;
            }

            return $number;
        }
    }

    if (!function_exists('get_loan_stage')) {
        function get_loan_stage($stage_name) {
            $list = explode(" - ", $stage_name);
            if( count($list) > 0 ) {
                return strtolower($list[0]);
            }

            return "draft";
        }
    }