<?php

/**
 * use this to print link location
 *
 * @param string $uri
 * @return print url
 */
if (!function_exists('echo_uri')) {

    function echo_uri($uri = "") {
        echo get_uri($uri);
    }

}

/**
 * prepare uri
 * 
 * @param string $uri
 * @return full url 
 */
if (!function_exists('get_uri')) {

    function get_uri($uri = "") {
        $ci = get_instance();
        $index_page = $ci->config->item('index_page');
        return base_url($index_page . '/' . $uri);
    }

}

/**
 * use this to print file path
 * 
 * @param string $uri
 * @return full url of the given file path
 */
if (!function_exists('get_file_uri')) {

    function get_file_uri($uri = "") {
        return base_url($uri);
    }

}

/**
 * get the url of user avatar
 * 
 * @param string $image_name
 * @return url of the avatar of given image reference
 */
if (!function_exists('get_avatar')) {

    function get_avatar($request = "", $isId = false) {
        if ($request === "system_bot") {
            return base_url("assets/images/avatar-bot.jpg");
        } else if ($request === "bitbucket") {
            return base_url("assets/images/bitbucket_logo.png");
        } else if ($request === "github") {
            return base_url("assets/images/github_logo.png");
        } else if ($request) {
            if($isId) {
                $ci = get_instance();
                $usr_instance = $ci->Users_model->get_baseinfo((int)$request);

                if(!isset($usr_instance->image))
                    return base_url("assets/images/avatar.jpg");

                $request = $usr_instance->image;
            }
            $file = @unserialize($request);
            if (is_array($file)) {
                return get_source_url_of_file($file, get_setting("profile_image_path") . "/", "thumbnail");
            } else {
                return base_url(get_setting("profile_image_path")) . "/" . $request;
            }
        } else {
            return base_url("assets/images/avatar.jpg");
        }
    }

}

/**
 * link the css files 
 * 
 * @param array $array
 * @return print css links
 */
if (!function_exists('load_css')) {

    function load_css(array $array) {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<link rel='stylesheet' type='text/css' href='" . base_url($uri) . "?v=$version' />";
        }
    }

}


/**
 * link the javascript files 
 * 
 * @param array $array
 * @return print js links
 */
if (!function_exists('load_js')) {

    function load_js(array $array) {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<script type='text/javascript'  src='" . base_url($uri) . "?v=$version'></script>";
        }
    }

}

/**
 * check the array key and return the value 
 * 
 * @param array $array
 * @return extract array value safely
 */
if (!function_exists('get_array_value')) {

    function get_array_value($array, $key) {
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }
    }

}

/**
 * prepare a anchor tag for any js request
 * 
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('js_anchor')) {

    function js_anchor($title = '', $attributes = '') {
        $title = (string) $title;
        $html_attributes = "";

        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $html_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }

        return '<a href="#"' . $html_attributes . '>' . $title . '</a>';
    }

}


/**
 * prepare a anchor tag for modal 
 * 
 * @param string $url
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('modal_anchor')) {

    function modal_anchor($url, $title = '', $attributes = '') {
        $attributes["data-act"] = "ajax-modal";
        if (get_array_value($attributes, "data-modal-title")) {
            $attributes["data-title"] = get_array_value($attributes, "data-modal-title");
        } else {
            $attributes["data-title"] = get_array_value($attributes, "title");
        }
        $attributes["data-action-url"] = $url;

        return js_anchor($title, $attributes);
    }

}

/**
 * prepare a anchor tag for ajax request
 * 
 * @param string $url
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('ajax_anchor')) {

    function ajax_anchor($url, $title = '', $attributes = '') {
        $attributes["data-act"] = "ajax-request";
        $attributes["data-action-url"] = $url;
        return js_anchor($title, $attributes);
    }

}

/**
 * get the selected menu 
 * 
 * @param array $sidebar_menu
 * @return the array containing an active class key
 */
if (!function_exists('active_menu')) {

    function get_active_menu($sidebar_menu = array()) {
        $ci = & get_instance();
        $controller_name = strtolower(get_class($ci));
        $uri_string = uri_string();
        $current_url = get_uri($uri_string);

        $found_url_active_key = null;
        $found_controller_active_key = null;
        $found_special_active_key = null;

        foreach ($sidebar_menu as $key => $menu) {
            if (isset($menu["name"])) {
                $menu_name = $menu["name"];
                $menu_url = $menu["url"];

                //compare with current url
                if ($menu_url === $current_url || get_uri($menu_url) === $current_url) {
                    $found_url_active_key = $key;
                }

                //compare with controller name
                if ($menu_name === $controller_name) {
                    $found_controller_active_key = $key;
                }

                //compare with some special links
                if ($uri_string == "projects/all_tasks_kanban" && $menu_url == "projects/all_tasks") {
                    $found_special_active_key = $key;
                }

                //check in submenu values
                $submenu = get_array_value($menu, "submenu");
                if ($submenu && count($submenu)) {
                    foreach ($submenu as $sub_menu) {
                        if (isset($sub_menu['name'])) {
                            $sub_menu_url = $sub_menu["url"];

                            //compare with current url
                            if ($sub_menu_url === $current_url || get_uri($sub_menu_url) === $current_url) {
                                $found_url_active_key = $key;
                            }

                            //compare with controller name
                            if (get_array_value($sub_menu, "name") === $controller_name) {
                                $found_controller_active_key = $key;
                            } else if (get_array_value($sub_menu, "category") === $controller_name) {
                                $found_controller_active_key = $key;
                            }

                            //compare with some special links
                            if ($uri_string == "projects/all_tasks_kanban" && $sub_menu_url == "projects/all_tasks") {
                                $found_special_active_key = $key;
                            }
                        }
                    }
                }
            }
        }

        if (!is_null($found_url_active_key)) {
            $sidebar_menu[$found_url_active_key]["is_active_menu"] = 1;
            $sidebar_menu["active_module"] = $found_url_active_key;
        } else if (!is_null($found_special_active_key)) {
            $sidebar_menu[$found_special_active_key]["is_active_menu"] = 1;
            $sidebar_menu["active_module"] = $found_special_active_key;
        } else if (!is_null($found_controller_active_key)) {
            $sidebar_menu[$found_controller_active_key]["is_active_menu"] = 1;
            $sidebar_menu["active_module"] = $found_controller_active_key;
        }

        return $sidebar_menu;
    }

}

/**
 * get the selected submenu
 * 
 * @param string $submenu
 * @param boolean $is_controller
 * @return string "active" indecating the active sub page
 */
if (!function_exists('active_submenu')) {

    function active_submenu($submenu = "", $is_controller = false) {
        $ci = & get_instance();
        //if submenu is a controller then compare with controller name, otherwise compare with method name
        if ($is_controller && $submenu === strtolower(get_class($ci))) {
            return "active";
        } else if ($submenu === strtolower($ci->router->method)) {
            return "active";
        }
    }

}

/**
 * get the defined config value by a key
 * @param string $key
 * @return config value
 */
if (!function_exists('get_setting')) {

    function get_setting($key = "", $default = "") {
        $ci = get_instance();
        $value = $ci->config->item($key);
        if(!isset($value)) {
            return $default;
        }
        return $value;
    }

}



/**
 * check if a string starts with a specified sting
 * 
 * @param string $string
 * @param string $needle
 * @return true/false
 */
if (!function_exists('starts_with')) {

    function starts_with($string, $needle) {
        $string = $string;
        return $needle === "" || strrpos($string, $needle, -strlen($string)) !== false;
    }

}

/**
 * check if a string ends with a specified sting
 * 
 * @param string $string
 * @param string $needle
 * @return true/false
 */
if (!function_exists('ends_with')) {

    function ends_with($string, $needle) {
        return $needle === "" || (($temp = strlen($string) - strlen($string)) >= 0 && strpos($string, $needle, $temp) !== false);
    }

}

/**
 * create a encoded id for sequrity pupose 
 * 
 * @param string $id
 * @param string $salt
 * @return endoded value
 */
if (!function_exists('encode_id')) {

    function encode_id($id, $salt) {
        $ci = get_instance();
        $id = $ci->encryption->encrypt($id . $salt);
        $id = str_replace("=", "~", $id);
        $id = str_replace("+", "_", $id);
        $id = str_replace("/", "-", $id);
        return $id;
    }

}


/**
 * decode the id which made by encode_id()
 * 
 * @param string $id
 * @param string $salt
 * @return decoded value
 */
if (!function_exists('decode_id')) {

    function decode_id($id, $salt) {
        $ci = get_instance();
        $id = str_replace("_", "+", $id);
        $id = str_replace("~", "=", $id);
        $id = str_replace("-", "/", $id);
        $id = $ci->encryption->decrypt($id);

        if ($id && strpos($id, $salt) != false) {
            return str_replace($salt, "", $id);
        } else {
            return "";
        }
    }

}

/**
 * decode html data which submited using a encode method of encodeAjaxPostData() function
 * 
 * @param string $html
 * @return htmle
 */
if (!function_exists('decode_ajax_post_data')) {

    function decode_ajax_post_data($html) {
        $html = str_replace("~", "=", $html);
        $html = str_replace("^", "&", $html);
        return $html;
    }

}

/**
 * check if fields has any value or not. and generate a error message for null value
 * 
 * @param array $fields
 * @return throw error for bad value
 */
if (!function_exists('check_required_hidden_fields')) {

    function check_required_hidden_fields($fields = array()) {
        $has_error = false;
        foreach ($fields as $field) {
            if (!$field) {
                $has_error = true;
            }
        }
        if ($has_error) {
            echo json_encode(array("success" => false, 'message' => lang('something_went_wrong')));
            exit();
        }
    }

}

/**
 * convert simple link text to clickable link
 * @param string $text
 * @return html link
 */
if (!function_exists('link_it')) {

    function link_it($text) {
        if ($text != strip_tags($text)) {
            //contains HTML, return the actual text
            return $text;
        } else {
            return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s]?[^\s]+)?)?)@', '<a href="$1" target="_blank">$1</a>', $text);
        }
    }

}

/**
 * convert mentions to link or link text
 * @param string $text containing text with mentioned brace
 * @param string $return_type indicates what to return (link or text)
 * @return text with link or link text
 */
if (!function_exists('convert_mentions')) {

    function convert_mentions($text, $convert_links = true) {

        preg_match_all('#\@\[(.*?)\]#', $text, $matches);

        $members = array();

        $mentions = get_array_value($matches, 1);
        if ($mentions && count($mentions)) {
            foreach ($mentions as $mention) {
                $user = explode(":", $mention);
                if ($convert_links) {
                    $user_id = get_array_value($user, 1);
                    $members[] = get_team_member_profile_link($user_id, trim($user[0]));
                } else {
                    $members[] = $user[0];
                }
            }
        }

        if ($convert_links) {
            $text = nl2br(link_it($text));
        } else {
            $text = nl2br($text);
        }

        $text = preg_replace_callback('/\[[^]]+\]/', function ($matches) use (&$members) {
            return array_shift($members);
        }, $text);

        return $text;
    }

}

/**
 * get all the use_ids from comment mentions
 * @param string $text
 * @return array of user_ids
 */
if (!function_exists('get_members_from_mention')) {

    function get_members_from_mention($text) {

        preg_match_all('#\@\[(.*?)\]#', $text, $matchs);

        //find the user ids.
        $user_ids = array();
        $mentions = get_array_value($matchs, 1);

        if ($mentions && count($mentions)) {
            foreach ($mentions as $mention) {
                $user = explode(":", $mention);
                $user_id = get_array_value($user, 1);
                if ($user_id) {
                    array_push($user_ids, $user_id);
                }
            }
        }

        return $user_ids;
    }

}

/**
 * send mail
 * 
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param array $optoins
 * @return true/false
 */
if (!function_exists('send_app_mail')) {

    function send_app_mail($to, $subject, $message, $optoins = array()) {
        $email_config = Array(
            'charset' => 'utf-8',
            'mailtype' => 'html'
        );

        //check mail sending method from settings
        if (get_setting("email_protocol") === "smtp") {
            $email_config["protocol"] = "smtp";
            $email_config["smtp_host"] = get_setting("email_smtp_host");
            $email_config["smtp_port"] = get_setting("email_smtp_port");
            $email_config["smtp_user"] = get_setting("email_smtp_user");
            $email_config["smtp_pass"] = decode_password(get_setting('email_smtp_pass'), "email_smtp_pass");
            $email_config["smtp_crypto"] = get_setting("email_smtp_security_type");

            if (!$email_config["smtp_crypto"]) {
                $email_config["smtp_crypto"] = "tls"; //for old clients, we have to set this by defaultsssssssss
            }

            if ($email_config["smtp_crypto"] === "none") {
                $email_config["smtp_crypto"] = "";
            }
        }

        $ci = get_instance();
        $ci->load->library('email', $email_config);
        $ci->email->clear(true); //clear previous message and attachment
        $ci->email->set_newline("\r\n");
        $ci->email->set_crlf("\r\n");
        $ci->email->from(get_setting("email_sent_from_address"), get_setting("email_sent_from_name"));
        $ci->email->to($to);
        $ci->email->subject($subject);
        $ci->email->message($message);

        //add attachment
        $attachments = get_array_value($optoins, "attachments");
        if (is_array($attachments)) {
            foreach ($attachments as $value) {
                $file_path = get_array_value($value, "file_path");
                $file_name = get_array_value($value, "file_name");
                $ci->email->attach(trim($file_path), "attachment", $file_name);
            }
        }

        //check reply-to
        $reply_to = get_array_value($optoins, "reply_to");
        if ($reply_to) {
            $ci->email->reply_to($reply_to);
        }

        //check cc
        $cc = get_array_value($optoins, "cc");
        if ($cc) {
            $ci->email->cc($cc);
        }

        //check bcc
        $bcc = get_array_value($optoins, "bcc");
        if ($bcc) {
            $ci->email->bcc($bcc);
        }

        //send email
        if ($ci->email->send()) {
            return true;
        } else {
            //show error message in none production version
            if (ENVIRONMENT !== 'production') {
                show_error($ci->email->print_debugger());
            }
            return false;
        }
    }

}


/**
 * get users ip address
 * 
 * @return ip
 */
if (!function_exists('get_real_ip')) {

    function get_real_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}

/**
 * check if it's localhost
 * 
 * @return boolean
 */
if (!function_exists('is_localhost')) {

    function is_localhost() {
        $known_localhost_ip = array(
            '127.0.0.1',
            '::1'
        );
        if (in_array(get_real_ip(), $known_localhost_ip)) {
            return true;
        }
    }

}


/**
 * convert string to url
 * 
 * @param string $address
 * @return url
 */
if (!function_exists('to_url')) {

    function to_url($address = "") {
        if (strpos($address, 'http://') === false && strpos($address, 'https://') === false) {
            $address = "http://" . $address;
        }
        return $address;
    }

}

/**
 * validate post data using the codeigniter's form validation method
 * 
 * @param string $address
 * @return throw error if foind any inconsistancy
 */
if (!function_exists('validate_submitted_data')) {

    function validate_submitted_data($fields = array(), $returnAsBool = false) {
        $ci = get_instance();
        foreach ($fields as $field_name => $requirement) {
            $ci->form_validation->set_rules($field_name, $field_name, $requirement);
        }

        if ($ci->form_validation->run() == FALSE && $returnAsBool == FALSE) {
            if (ENVIRONMENT === 'production') {
                $message = lang('something_went_wrong');
            } else {
                $message = validation_errors();
            }
            echo json_encode(array("success" => false, 'message' => $message));
            exit();
        } else {
            
        }
    }

}


/**
 * validate post data using the codeigniter's form validation method
 * 
 * @param string $address
 * @return throw error if foind any inconsistancy
 */
if (!function_exists('validate_numeric_value')) {

    function validate_numeric_value($value = 0) {
        if ($value && !is_numeric($value)) {
            die("Invalid value");
        }
    }

}

/**
 * team members profile anchor. only clickable to team members
 * client's will see a none clickable link
 * 
 * @param string $id
 * @param string $name
 * @param array $attributes
 * @return html link
 */
if (!function_exists('get_team_member_profile_link')) {

    function get_team_member_profile_link($id = 0, $name = "", $attributes = array()) {
        $ci = get_instance();

        if( !$id ) {
            return "None";
        }

        if ($ci->login_user->user_type === "staff") {
            return anchor("hrs/employee/view/" . $id, $name, $attributes);
        } else {
            return js_anchor($name, $attributes);
        }
    }

}

if (!function_exists('get_supplier_contact_link')) {
    function get_supplier_contact_link($id = 0, $name = "", $attributes = array()) {
        if($id == 0) {
            return "Internal";
        } else {
            return anchor("mes/suppliers/contacts/" . $id, $name, $attributes);
        }
    }
}

if (!function_exists('get_vendor_contact_link')) {

    function get_vendor_contact_link($id = 0, $name = "", $attributes = array()) {
        return anchor("mes/vendor/" . $id . "/contacts", $name, $attributes);
    }

}


/**
 * team members profile anchor. only clickable to team members
 * client's will see a none clickable link
 * 
 * @param string $id
 * @param string $name
 * @param array $attributes
 * @return html link
 */
if (!function_exists('get_client_contact_profile_link')) {

    function get_client_contact_profile_link($id = 0, $name = "", $attributes = array()) {
        return anchor("sales/Clients/contact_profile/" . $id, $name, $attributes);
    }

}


/**
 * return a colorful label accroding to invoice status
 * 
 * @param Object $invoice_info
 * @return html
 */
if (!function_exists('get_invoice_status_label')) {

    function get_invoice_status_label($invoice_info, $return_html = true) {
        $invoice_status_class = "label-default";
        $status = "not_paid";
        $now = get_my_local_time("Y-m-d");

        //ignore the hidden value. check only 2 decimal place.
        $invoice_info->invoice_value = floor($invoice_info->invoice_value * 100) / 100;

        if ($invoice_info->status == "cancelled") {
            $invoice_status_class = "label-danger";
            $status = "cancelled";
        } else if ($invoice_info->status != "draft" && $invoice_info->due_date < $now && $invoice_info->payment_received < $invoice_info->invoice_value) {
            $invoice_status_class = "label-danger";
            $status = "overdue";
        } else if ($invoice_info->status !== "draft" && $invoice_info->payment_received <= 0) {
            $invoice_status_class = "label-warning";
            $status = "not_paid";
        } else if ($invoice_info->payment_received * 1 && $invoice_info->payment_received >= $invoice_info->invoice_value) {
            $invoice_status_class = "label-success";
            $status = "fully_paid";
        } else if ($invoice_info->payment_received > 0 && $invoice_info->payment_received < $invoice_info->invoice_value) {
            $invoice_status_class = "label-primary";
            $status = "partially_paid";
        } else if ($invoice_info->status === "draft") {
            $invoice_status_class = "label-default";
            $status = "draft";
        }

        $invoice_status = "<span class='mt0 label $invoice_status_class large'>" . lang($status) . "</span>";
        if ($return_html) {
            return $invoice_status;
        } else {
            return $status;
        }
    }

}

/**
 * return a colorful label accroding to expense status
 * 
 * @param Object $expense_info
 * @return html
 */
if (!function_exists('get_expense_status_label')) {

    function get_expense_status_label($expense_info, $return_html = true) {
        $expense_status_class = "label-default";
        $status = "not_paid";
        $now = get_my_local_time("Y-m-d");

        //ignore the hidden value. check only 2 decimal place.
        $expense_info->amount = floor($expense_info->amount * 100) / 100;

        if ($expense_info->status == "cancelled") {
            $expense_status_class = "label-danger";
            $status = "cancelled";
        } else if ($expense_info->status != "draft" && $expense_info->due_date < $now && $expense_info->payment_received < $expense_info->amount) {
            $expense_status_class = "label-danger";
            $status = "overdue";
        } else if ($expense_info->status !== "draft" && $expense_info->payment_received <= 0) {
            $expense_status_class = "label-warning";
            $status = "not_paid";
        } else if ($expense_info->payment_received * 1 && $expense_info->payment_received >= $expense_info->amount) {
            $expense_status_class = "label-success";
            $status = "fully_paid";
        } else if ($expense_info->payment_received > 0 && $expense_info->payment_received < $expense_info->amount) {
            $expense_status_class = "label-primary";
            $status = "partially_paid";
        } else if ($expense_info->status === "draft") {
            $expense_status_class = "label-default";
            $status = "draft";
        }

        $expense_status = "<span class='mt0 label $expense_status_class large'>" . lang($status) . "</span>";
        if ($return_html) {
            return $expense_status;
        } else {
            return $status;
        }
    }

}



/**
 * get all data to make an invoice
 * 
 * @param Int $invoice_id
 * @return array
 */
if (!function_exists('get_invoice_making_data')) {

    function get_invoice_making_data($invoice_id) {
        $ci = get_instance();
        $ci->load->model("Clients_model");
        $ci->load->model("Invoices_model");
        $ci->load->model("Invoice_items_model");
        $invoice_info = $ci->Invoices_model->get_details(array("id" => $invoice_id))->row();

        $ci->load->model('Deliveries_model');

        if ($invoice_info) {
            $data['invoice_info'] = $invoice_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['invoice_info']->client_id);
            $data['delivery_info'] = $ci->Deliveries_model->get_details(array("invoice_id" => $invoice_id))->row();
            $data['consumer_info'] = $ci->Users_model->get_details(array("id" => $data['invoice_info']->consumer_id))->row();
            $data['invoice_items'] = $ci->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->result();
            $data['invoice_status_label'] = get_invoice_status_label($invoice_info);
            $data["invoice_total_summary"] = $ci->Invoices_model->get_invoice_total_summary($invoice_id);

            $data['invoice_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "invoices", "show_in_invoice" => true, "related_to_id" => $invoice_id))->result();
            $data['client_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "clients", "show_in_invoice" => true, "related_to_id" => $data['invoice_info']->client_id))->result();
            return $data;
        }
    }

}

/**
 * get all data to make an invoice
 * 
 * @param Invoice making data $invoice_data
 * @return array
 */
if (!function_exists('prepare_invoice_pdf')) {

    function prepare_invoice_pdf($invoice_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);

        if ($invoice_data) {

            $invoice_data["mode"] = $mode;

            $html = $ci->load->view("invoices/invoice_pdf", $invoice_data, true);

            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }

            $invoice_info = get_array_value($invoice_data, "invoice_info");
            $pdf_file_name = get_invoice_id($invoice_info->id) . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}

if (!function_exists('prepare_purchase_pdf')) {

    function prepare_purchase_pdf($purchase_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);

        if ($purchase_data) {

            $purchase_data["mode"] = $mode;

            $html = $ci->load->view("purchase_orders/purchase_pdf", $purchase_data, true);

            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }

            $purchase_info = get_array_value($purchase_data, "purchase_order_info");
            $pdf_file_name = lang("purchase") . "-" . $purchase_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}

if (!function_exists('prepare_return_pdf')) {

    function prepare_return_pdf($purchase_return_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);

        if ($purchase_return_data) {

            $purchase_return_data["mode"] = $mode;

            $html = $ci->load->view("purchase_returns/return_pdf", $purchase_return_data, true);

            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }

            $purchase_return_info = get_array_value($purchase_return_data, "purchase_return_info");
            $pdf_file_name = lang("return") . "-" . $purchase_return_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}

if (!function_exists('prepare_payroll_pdf')) {

    function prepare_payroll_pdf($payroll_data) {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1);
        $ci->pdf->setImageScale(2.0);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(9);

        if ($payroll_data) {

            $html = $ci->load->view("payrolls/pdf", $payroll_data, true);
            $ci->pdf->writeHTML($html, true, false, true, false, '');

            $payroll_info = get_array_value($payroll_data, "payroll_info");
            $pdf_file_name =  str_replace(" ", "-", $payroll_info->employee_name) . "-" .lang("payroll") . "-" . $payroll_info->created_on . ".pdf";

            $ci->pdf->Output($pdf_file_name, "I");
        }
    }

}

/**
 * 
 * get payroll number
 * @param Int $payroll_id
 * @return string
 */
if (!function_exists('get_payroll_id')) {

    function get_payroll_id($payroll_id) {
        $prefix = strtoupper(lang("payroll")) . " #";
        $value = str_pad($payroll_id, 4, 0, STR_PAD_LEFT);
        return $prefix . $value ;
    }

}

/**
 * 
 * get payslip number
 * @param Int $payslip_id
 * @return string
 */
if (!function_exists('get_payslip_id')) {

    function get_payslip_id($id, $payroll_id) {
        $prefix = str_pad($payroll_id, 4, 0, STR_PAD_LEFT); 
        $value = str_pad($id, 3, 0, STR_PAD_LEFT);
        return $prefix ."-". $value ;
    }

}

/**
 * get all data to make an estimate
 * 
 * @param emtimate making data $estimate_data
 * @return array
 */
if (!function_exists('prepare_estimate_pdf')) {

    function prepare_estimate_pdf($estimate_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();

        if ($estimate_data) {

            $estimate_data["mode"] = $mode;

            $html = $ci->load->view("estimates/estimate_pdf", $estimate_data, true);
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }

            $estimate_info = get_array_value($estimate_data, "estimate_info");
            $pdf_file_name = get_estimate_id($estimate_info->id).".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}

/**
 * 
 * get invoice number
 * @param Int $invoice_id
 * @return string
 */
if (!function_exists('get_invoice_id')) {

    function get_invoice_id($invoice_id) {
        $prefix = get_setting("invoice_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("invoice")) . " #";
        return get_id_name($invoice_id, $prefix, 4);
    }

}

/**
 * 
 * get expense number
 * @param Int $expense_id
 * @return string
 */
if (!function_exists('get_expense_id')) {

    function get_expense_id($expense_id) {
        $prefix = get_setting("expense_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("expense")) . " #";
        return get_id_name($expense_id, $prefix, 4);
    }

}

if (!function_exists('get_purchase_order_id')) {

    function get_purchase_order_id($purchase_order_id) {
        return get_id_name($purchase_order_id,  lang("purchase_order")." #", 4);
    }

}

if (!function_exists('get_purchase_return_id')) {

    function get_purchase_return_id($purchase_return_id) {
        return get_id_name($purchase_return_id, lang("return_order")." #", 4);
    }

}

if (!function_exists('get_payroll_id')) {

    function get_payroll_id($id) {
        return get_id_name($id, lang("payroll")." #", 4);
    }

}

/**
 * 
 * get estimate number
 * @param Int $estimate_id
 * @return string
 */
if (!function_exists('get_estimate_id')) {

    function get_estimate_id($estimate_id) {
        $prefix = get_setting("estimate_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("estimate")) . " #";
        return get_id_name($estimate_id, $prefix, 4);
    }

}

/**
 * 
 * get ticket number
 * @param Int $ticket_id
 * @return string
 */
if (!function_exists('get_ticket_id')) {

    function get_ticket_id($ticket_id) {
        $prefix = get_setting("ticket_prefix");
        $prefix = $prefix ? $prefix : lang("ticket") . " #";
        return get_id_name($ticket_id, $prefix, 4);
    }

}


/**
 * get all data to make an estimate
 * 
 * @param Int $estimate_id
 * @return array
 */
if (!function_exists('get_estimate_making_data')) {

    function get_estimate_making_data($estimate_id) {
        $ci = get_instance();
        $ci->load->model("Estimates_model");
        $ci->load->model("Estimate_items_model");
        $estimate_info = $ci->Estimates_model->get_details(array("id" => $estimate_id))->row();
        if ($estimate_info) {
            $data['estimate_info'] = $estimate_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['estimate_info']->client_id);
            $data['consumer_info'] = $ci->Users_model->get_one($data['estimate_info']->consumer_id);
            $data['estimate_items'] = $ci->Estimate_items_model->get_details(array("estimate_id" => $estimate_id))->result();
            $data["estimate_total_summary"] = $ci->Estimates_model->get_estimate_total_summary($estimate_id);

            $data['estimate_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "estimates", "show_in_estimate" => true, "related_to_id" => $estimate_id))->result();
            return $data;
        }
    }

}


/**
 * get team members and teams select2 dropdown data list
 * 
 * @return array
 */
if (!function_exists('get_team_members_and_teams_select2_data_list')) {

    function get_team_members_and_teams_select2_data_list($exclude_teams = false) {
        $ci = get_instance();

        $team_members = $ci->Users_model->get_all_where(array("deleted" => 0, "status" => "active", "user_type" => "staff"))->result();
        $members_and_teams_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_and_teams_dropdown[] = array("type" => "member", "id" => "member:" . $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        if(!$exclude_teams) {
            $team = $ci->Team_model->get_all_where(array("deleted" => 0))->result();
            foreach ($team as $team) {
                $members_and_teams_dropdown[] = array("type" => "team", "id" => "team:" . $team->id, "text" => $team->title);
            }
        }

        return $members_and_teams_dropdown;
    }

}



/**
 * submit data for notification
 * 
 * @return array
 */
if (!function_exists('log_notification')) {

    function log_notification($event, $options = array(), $user_id = 0) {

        $ci = get_instance();

        $url = get_uri("notification_processor/create_notification");

        $req = "event=" . encode_id($event, "notification");

        if ($user_id) {
            $req .= "&user_id=" . $user_id;
        } else if ($user_id === "0") {
            $req .= "&user_id=" . $user_id; //if user id is 0 (string) we'll assume that it's system bot 
        } else if (isset($ci->login_user)) {
            $req .= "&user_id=" . $ci->login_user->id;
        }


        foreach ($options as $key => $value) {
            $value = urlencode($value);
            $req .= "&$key=$value";
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);


        if (get_setting("add_useragent_to_curl")) {
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:19.0) Gecko/20100101 Firefox/19.0");
        }

        curl_exec($ch);
        curl_close($ch);
    }

}


/**
 * save custom fields for any context
 * 
 * @param Int $estimate_id
 * @return array
 */
if (!function_exists('save_custom_fields')) {

    function save_custom_fields($related_to_type, $related_to_id, $is_admin = 0, $user_type = "", $activity_log_id = 0, $save_to_related_type = "", $user_id = 0) {
        $ci = get_instance();

        $custom_fields = $ci->Custom_fields_model->get_combined_details($related_to_type, $related_to_id, $is_admin, $user_type)->result();

        // we have to update the activity logs table according to the changes of custom fields
        $changes = array();

        //for migration, we've to save on new related type
        if ($save_to_related_type) {
            $related_to_type = $save_to_related_type;
        }

        //save custom fields
        foreach ($custom_fields as $field) {
            $field_name = "custom_field_" . $field->id;

            //client can't edit the field value if the option is active
            if ($user_type == "client" && $field->disable_editing_by_clients) {
                continue; //skip to the next loop
            }

            //to get the custom field values for per users from the same page, we've to use the user id
            if ($user_id) {
                $field_name .= "_" . $user_id;
            }

            //save only submitted fields
            if (array_key_exists($field_name, $_POST)) {
                $value = $ci->input->post($field_name);

                $field_value_data = array(
                    "related_to_type" => $related_to_type,
                    "related_to_id" => $related_to_id,
                    "custom_field_id" => $field->id,
                    "value" => $value
                );

                $field_value_data = clean_data($field_value_data);

                $save_data = $ci->Custom_field_values_model->upsert($field_value_data, $save_to_related_type);

                if ($save_data) {
                    $changed_values = get_array_value($save_data, "changes");
                    $field_title = get_array_value($changed_values, "title");
                    $field_type = get_array_value($changed_values, "field_type");
                    $visible_to_admins_only = get_array_value($changed_values, "visible_to_admins_only");
                    $hide_from_clients = get_array_value($changed_values, "hide_from_clients");

                    //add changes of custom fields
                    if (get_array_value($save_data, "operation") == "update") {
                        //update
                        $changes[$field_title . "[:" . $field->id . "," . $field_type . "," . $visible_to_admins_only . "," . $hide_from_clients . ":]"] = array("from" => get_array_value($changed_values, "from"), "to" => get_array_value($changed_values, "to"));
                    } else if (get_array_value($save_data, "operation") == "insert") {
                        //insert
                        $changes[$field_title . "[:" . $field->id . "," . $field_type . "," . $visible_to_admins_only . "," . $hide_from_clients . ":]"] = array("from" => "", "to" => $value);
                    }
                }
            }
        }

        //finally save the changes to activity logs table
        return update_custom_fields_changes($related_to_type, $related_to_id, $changes, $activity_log_id);
    }

}

/**
 * update custom fields changes to activity logs table
 */
if (!function_exists('update_custom_fields_changes')) {

    function update_custom_fields_changes($related_to_type, $related_to_id, $changes, $activity_log_id = 0) {
        if ($changes && count($changes)) {
            $ci = get_instance();
            $ci->load->model("Tasks_model");

            $related_to_data = new stdClass();

            $log_type = "";
            $log_for = "";
            $log_type_title = "";
            $log_for_id = "";

            if ($related_to_type == "tasks") {
                $related_to_data = $ci->Tasks_model->get_one($related_to_id);
                $log_type = "task";
                $log_for = "project";
                $log_type_title = $related_to_data->title;
                $log_for_id = $related_to_data->project_id;
            }

            $log_data = array(
                "action" => "updated",
                "log_type" => $log_type,
                "log_type_title" => $log_type_title,
                "log_type_id" => $related_to_id,
                "log_for" => $log_for,
                "log_for_id" => $log_for_id
            );


            if ($activity_log_id) {
                $before_changes = array();

                //we have to combine with the existing changes of activity logs
                $activity_log = $ci->Activity_logs_model->get_one($activity_log_id);
                $activity_logs_changes = unserialize($activity_log->changes);
                if (is_array($activity_logs_changes)) {
                    foreach ($activity_logs_changes as $key => $value) {
                        $before_changes[$key] = array("from" => get_array_value($value, "from"), "to" => get_array_value($value, "to"));
                    }
                }

                $log_data["changes"] = serialize(array_merge($before_changes, $changes));

                if ($activity_log->action != "created") {
                    $ci->Activity_logs_model->update_where($log_data, array("id" => $activity_log_id));
                }
            } else {
                $log_data["changes"] = serialize($changes);
                return $ci->Activity_logs_model->save($log_data);
            }
        }
    }

}


/**
 * use this to clean xss and html elements
 * the best practice is to use this before rendering 
 * but you can use this before saving for suitable cases
 *
 * @param string or array $data
 * @return clean $data
 */
if (!function_exists("clean_data")) {

    function clean_data($data) {
        $ci = get_instance();

        $data = $ci->security->xss_clean($data);
        $disable_html_input = get_setting("disable_html_input");

        if ($disable_html_input == "1") {
            $data = html_escape($data);
        }

        return $data;
    }

}


//return site logo
if (!function_exists("get_logo_url")) {

    function get_logo_url() {
        return get_file_from_setting("site_logo");
    }

}

//get logo from setting
if (!function_exists("get_file_from_setting")) {

    function get_file_from_setting($setting_name = "", $only_file_path_with_slash = false) {

        if ($setting_name) {
            $setting_value = get_setting($setting_name);
            if ($setting_value) {
                $file = @unserialize($setting_value);
                if (is_array($file)) {

                    //show full size thumbnail for signin page background
                    $show_full_size_thumbnail = false;
                    if ($setting_name == "signin_page_background") {
                        $show_full_size_thumbnail = true;
                    }

                    return get_source_url_of_file($file, get_setting("system_file_path"), "thumbnail", $only_file_path_with_slash, $only_file_path_with_slash, $show_full_size_thumbnail);
                } else {
                    if ($only_file_path_with_slash) {
                        return "/" . (get_setting("system_default_path") . $setting_value);
                    } else {
                        return get_file_uri(get_setting("system_default_path") . $setting_value);
                    }
                }
            }
        }
    }

}

//get site favicon
if (!function_exists("get_favicon_url")) {

    function get_favicon_url() {
        $favicon_from_setting = get_file_from_setting('favicon');
        return $favicon_from_setting ? $favicon_from_setting : get_file_uri("assets/images/favicon.png");
    }

}


//get color plate
if (!function_exists("get_custom_theme_color_list")) {

    function get_custom_theme_color_list() {
        //scan the css files for theme color and show a list
        try {
            $dir = getcwd() . '/assets/css/color/';
            $files = scandir($dir);
            if ($files && is_array($files)) {

                echo "<span class='color-tag clickable mr15 change-theme' data-color='1d2632' style='background:#1d2632'> </span>"; //default color

                foreach ($files as $file) {
                    if ($file != "." && $file != ".." && $file != "index.html") {
                        $color_code = str_replace(".css", "", $file);
                        echo "<span class='color-tag clickable mr15 change-theme' style='background:#$color_code' data-color='$color_code'> </span>";
                    }
                }
            }
        } catch (Exception $exc) {
            
        }
    }

}
//make random string
if (!function_exists("make_random_string")) {

    function make_random_string($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';

        for ($i = 0; $i < $length; $i++) {
            $random_string .= $characters[rand(0, $characters_length - 1)];
        }

        return $random_string;
    }

}

//add custom variable data
if (!function_exists("get_custom_variables_data")) {

    function get_custom_variables_data($related_to_type = "", $related_to_id = 0, $is_admin = 0) {
        if ($related_to_type && $related_to_id) {
            $ci = get_instance();
            $variables_array = array();

            $options = array("related_to_type" => $related_to_type, "related_to_id" => $related_to_id);

            if ($related_to_type == "leads") {
                $options["is_admin"] = $is_admin;
                $options["check_admin_restriction"] = true;
            }

            $values = $ci->Custom_field_values_model->get_details($options)->result();

            foreach ($values as $value) {
                if ($related_to_type == "tickets" && $value->example_variable_name && $value->value) {
                    $variables_array[$value->example_variable_name] = $value->value;
                } else if ($related_to_type == "leads" && $value->show_on_kanban_card && $value->value) {
                    $variables_array[] = array(
                        "custom_field_type" => $value->custom_field_type,
                        "custom_field_title" => $value->custom_field_title,
                        "value" => $value->value
                    );
                }
            }

            return $variables_array;
        }
    }

}

//make labels view data for different contexts
if (!function_exists("make_labels_view_data")) {

    function make_labels_view_data($labels_list = "", $clickable = false, $large = false) {
        $labels = "";

        if ($labels_list) {
            $labels_array = explode(",", $labels_list);

            foreach ($labels_array as $label) {
                $label_parts = explode("--::--", $label);

                $label_id = get_array_value($label_parts, 0);
                $label_title = get_array_value($label_parts, 1);
                $label_color = get_array_value($label_parts, 2);

                $clickable_class = $clickable ? "clickable" : "";
                $large_class = $large ? "large" : "";

                $labels .= "<span class='mt0 label $large_class $clickable_class' style='background-color:$label_color;' title=" . lang("label") . ">" . $label_title . "</span> ";
            }
        }

        return $labels;
    }

}

//get update task info anchor data
if (!function_exists("get_update_task_info_anchor_data")) {

    function get_update_task_info_anchor_data($model_info, $type = "", $can_edit_tasks = false, $extra_data = "", $extra_condition = false) {
        if ($model_info && $type) {

            if ($type == "status") {

                return $can_edit_tasks ? js_anchor($model_info->status_key_name ? lang($model_info->status_key_name) : $model_info->status_title, array('title' => "", "class" => "white-link", "data-id" => $model_info->id, "data-value" => $model_info->status_id, "data-act" => "update-task-info", "data-act-type" => "status_id")) : ($model_info->status_key_name ? lang($model_info->status_key_name) : $model_info->status_title);
            } else if ($type == "milestone") {

                return $can_edit_tasks ? js_anchor($model_info->milestone_title, array('title' => "", "class" => "", "data-id" => $model_info->id, "data-value" => $model_info->milestone_id, "data-act" => "update-task-info", "data-act-type" => "milestone_id")) : $model_info->milestone_title;
            } else if ($type == "user") {

                return ($can_edit_tasks && $extra_condition) ? js_anchor($model_info->assigned_to_user, array('title' => "", "class" => "", "data-id" => $model_info->id, "data-value" => $model_info->assigned_to, "data-act" => "update-task-info", "data-act-type" => "assigned_to")) : $model_info->assigned_to_user;
            } else if ($type == "labels") {

                return $can_edit_tasks ? js_anchor($extra_data, array('title' => "", "class" => "", "data-id" => $model_info->id, "data-value" => $model_info->labels, "data-act" => "update-task-info", "data-act-type" => "labels")) : $extra_data;
            } else if ($type == "points") {

                return $can_edit_tasks ? js_anchor($model_info->points, array('title' => "", "class" => "", "data-id" => $model_info->id, "data-value" => $model_info->points, "data-act" => "update-task-info", "data-act-type" => "points")) : $model_info->points;
            } else if ($type == "collaborators") {

                return $can_edit_tasks ? js_anchor($extra_data, array('title' => "", "class" => "", "data-id" => $model_info->id, "data-value" => $model_info->collaborators, "data-act" => "update-task-info", "data-act-type" => "collaborators")) : $extra_data;
            } else if ($type == "start_date") {

                return $can_edit_tasks ? js_anchor(format_to_date($model_info->start_date, false), array('title' => "", "class" => "", "data-id" => $model_info->id, "data-value" => $model_info->start_date, "data-act" => "update-task-info", "data-act-type" => "start_date")) : format_to_date($model_info->start_date, false);
            } else if ($type == "deadline") {

                return $can_edit_tasks ? js_anchor(format_to_date($model_info->deadline, false), array('title' => "", "class" => "", "data-id" => $model_info->id, "data-value" => $model_info->deadline, "data-act" => "update-task-info", "data-act-type" => "deadline")) : format_to_date($model_info->deadline, false);
            }
        }
    }

}

if (!function_exists('get_lead_contact_profile_link')) {
        
    function get_lead_contact_profile_link($id = 0, $name = "", $attributes = array()) {
        return anchor("mcs/leads/contact_profile/" . $id, $name, $attributes);
    }

}

if (!function_exists('decode_password')) {

    function decode_password($data = "", $salt = "") {
        if ($data && $salt) {
            if (strlen($data) > 100) {
                //encoded data with encode_id
                //return with decode
                return decode_id($data, $salt);
            } else {
                //old data, return as is
                return $data;
            }
        }
    }

}

if (!function_exists('validate_invoice_verification_code')) {

    function validate_invoice_verification_code($code = "", $given_invoice_data = array()) {
        if ($code) {
            $ci = get_instance();
            $ci->load->model("Verification_model");
            $options = array("code" => $code, "type" => "invoice_payment");
            $verification_info = $ci->Verification_model->get_details($options)->row();

            if ($verification_info && $verification_info->id) {
                $existing_invoice_data = unserialize($verification_info->params);

                //existing data
                $existing_invoice_id = get_array_value($existing_invoice_data, "invoice_id");
                $existing_client_id = get_array_value($existing_invoice_data, "client_id");
                $existing_contact_id = get_array_value($existing_invoice_data, "contact_id");

                //given data 
                $given_invoice_id = get_array_value($given_invoice_data, "invoice_id");
                $given_client_id = get_array_value($given_invoice_data, "client_id");
                $given_contact_id = get_array_value($given_invoice_data, "contact_id");

                if ($existing_invoice_id === $given_invoice_id && $existing_client_id === $given_client_id && $existing_contact_id === $given_contact_id) {
                    return true;
                }
            }
        }
    }

}

if (!function_exists('can_edit_this_task_status')) {

    function can_edit_this_task_status($assigned_to = 0) {
        $ci = get_instance();

        if (get_array_value($ci->login_user->permissions, "can_update_only_assigned_tasks_status")) {
            //user can change only assigned tasks
            if ($assigned_to == $ci->login_user->id) {
                return true;
            }
        } else {
            return true;
        }
    }

}

if (!function_exists('send_message_via_pusher')) {

    function send_message_via_pusher($to_user_id, $message_data, $message_id, $message_type = "message") {
        $ci = get_instance();

        $pusher_app_id = get_setting("pusher_app_id");
        $pusher_key = get_setting("pusher_key");
        $pusher_secret = get_setting("pusher_secret");
        $pusher_cluster = get_setting("pusher_cluster");

        if (!$pusher_app_id || !$pusher_key || !$pusher_secret || !$pusher_cluster) {
            return false;
        }

        require_once(APPPATH . "third_party/Pusher/vendor/autoload.php");

        $options = array(
            'cluster' => $pusher_cluster,
            'encrypted' => true
        );

        $pusher = new Pusher\Pusher(
                $pusher_key, $pusher_secret, $pusher_app_id, $options
        );

        if ($message_type == "message") {
            //send message
            $data = array(
                "message" => $message_data
            );

            if ($pusher->trigger('user_' . $to_user_id . '_message_id_' . $message_id . '_channel', 'rise-chat-event', $data)) {
                return true;
            }
        } else {
            //send typing indicator
            $message = lang("typing");
            $message_info = $ci->Messages_model->get_one($message_id);

            $user_info = $ci->Users_model->get_one($ci->login_user->id);
            $avatar = " <img alt='...' src='" . get_avatar($user_info->image) . "' class='dark strong' /> ";

            $message_data = array(
                "<div class='chat-other'>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <div class='avatar-xs avatar mr10'>" . $avatar . "</div>
                                    <div class='chat-msg typing-indicator' data-message_id='$message_info->id'>" . lang("typing") . "<span></span><span></span><span></span></div>
                                </div>
                            </div>
                        </div>"
            );

            if ($pusher->trigger('user_' . $to_user_id . '_message_id_' . $message_id . '_channel', 'rise-chat-typing-event', $message_data)) {
                return true;
            }
        }
    }

}

if (!function_exists('can_access_messages_module')) {

    function can_access_messages_module() {
        $ci = get_instance();

        $can_chat = false;

        $client_message_users = get_setting("client_message_users");
        $client_message_users_array = explode(",", $client_message_users);

        if (($ci->login_user->user_type === "staff" && ($ci->login_user->is_admin || get_array_value($ci->login_user->permissions, "message_permission") !== "" || in_array($ci->login_user->id, $client_message_users_array))) || ($ci->login_user->user_type === "client" && $client_message_users)) {
            $can_chat = true;
        }

        return $can_chat;
    }

}

if (!function_exists('number_with_decimal')) {
    function number_with_decimal($number, $decimal_places = 2) {
        return number_format((float)$number, $decimal_places, '.', ',');
    }
}

if (!function_exists('crypto_rand_secure')) {
    function crypto_rand_secure($min, $max){
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }
}

if (!function_exists('getToken')) {
    function getToken($length){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
        }

        return $token;
    }
}

if (!function_exists('getHierarchalTag')) {
    function getHierarchalTag($count, $symbol){
        $tag = "";

        switch ($count) {
            case 2:
                $tag = "$symbol ";
                break;
            case 3:
                $tag = "$symbol$symbol ";
                break;
            case 4:
                $tag = "$symbol$symbol$symbol ";
                break;
            
            default:
                $tag = "";
                break;
        }

        return $tag;
    }
}

if (!function_exists('get_purchase_order_status_label')) {

    function get_purchase_order_status_label($status) {
        if($status == "draft"){
            $status = '<span class="mt0 label label-default large">Draft</span>';
        }

        if($status == "partially_budgeted"){
            $status = '<span class="mt0 label label-warning large">Partially budgeted</span>';
        }

        if($status == "cancelled"){
            $status = '<span class="mt0 label label-danger large">Cancelled</span>';
        }

        if($status == "completed"){
            $status = '<span class="mt0 label label-success large">Completed</span>';
        }

        return $status;
    }

}

if (!function_exists('get_purchase_return_status_label')) {

    function get_purchase_return_status_label($status) {
        if($status == "draft"){
            $status = '<span class="mt0 label label-default large">Draft</span>';
        }

        if($status == "cancelled"){
            $status = '<span class="mt0 label label-danger large">Cancelled</span>';
        }

        if($status == "completed"){
            $status = '<span class="mt0 label label-success large">Completed</span>';
        }

        return $status;
    }

}

if (!function_exists('get_purchase_order_making_data')) {

    function get_purchase_order_making_data($purchase_order_id) {
        $ci = get_instance();
        $ci->load->database();
        $ci->load->model("Purchase_order_materials_model");
        $ci->load->model("Vendors_model");
        $purchase_order_info = $ci->Purchase_orders_model->get_details(array("id" => $purchase_order_id))->row();

        if ($purchase_order_info) {
            $data['purchase_order_info'] = $purchase_order_info;
            $data['vendor_info'] = $ci->Vendors_model->get_one($data['purchase_order_info']->vendor_id);
            $data['purchase_order_status_label'] = get_purchase_order_status_label($purchase_order_info->status);
            $data["purchase_order_info"]->total = $ci->Purchase_order_materials_model->get_purchase_total_material($purchase_order_id);
            $data['purchase_order_materials'] = $ci->Purchase_order_materials_model->get_details(array("purchase_id" => $purchase_order_id))->result();
            return $data;
        }
    }

}

if (!function_exists('get_purchase_return_making_data')) {

    function get_purchase_return_making_data($purchase_return_id) {
        $ci = get_instance();
        $ci->load->database();
        $ci->load->model("Purchase_order_returns_model");
        $ci->load->model("Vendors_model");
        $purchase_return_info = $ci->Purchase_order_returns_model->get_details(array("id" => $purchase_return_id))->row();

        if ($purchase_return_info) {
            $data['purchase_return_info'] = $purchase_return_info;
            $data['vendor_info'] = $ci->Vendors_model->get_one($purchase_return_info->vendor_id);
            $data['purchase_return_status_label'] = get_purchase_return_status_label($purchase_return_info->status);
            $data["purchase_return_info"]->total = $ci->Purchase_order_return_materials_model->get_return_total_material($purchase_return_id);
            $data['purchase_return_materials'] = $ci->Purchase_order_returns_model->get_purchase_return_materials($purchase_return_id)->result();
            return $data;
        }
    }

}

if (!function_exists('get_current_item_inventory_count')) {

    function get_current_item_inventory_count($data) {
        $result = $data->stock + $data->stock_override + $data->produced - $data->delivered - $data->invoiced - $data->transferred + $data->received;
        return number_format($result, 2, '.', '');
    }

}


if (!function_exists('get_current_material_inventory_count')) {

    function get_current_material_inventory_count($data) {
        $result = $data->stock + $data->stock_override - $data->production_quantity + $data->purchased - $data->returned - $data->transferred + $data->received;
        return number_format($result, 2, '.', '');
    }
}

if (!function_exists('get_total_leave_credit_balance')) {

    function get_total_leave_credit_balance($user_id = 0, $leave_type_id = 0) {
        $ci = get_instance();
        $ci->load->model("Leave_credits_model");
        $options = array("user_id" => $user_id);
        if($leave_type_id) {
            $options = array_merge($options,
                array("leave_type_id"=>$leave_type_id)
            );
        }
        return $ci->Leave_credits_model->get_balance($options);
    }
}

if (!function_exists('is_leave_credit_required')) {

    function is_leave_credit_required($leave_type_id) {
        $ci = get_instance();
        $ci->load->model("Leave_types_model");
        $options = array("id" => $leave_type_id);
        $query = $ci->Leave_types_model->get_details($options);
        if($query->num_rows()) {
            return $query->row()->required_credits?true:false;
        }

        return false;
    }
}

if (!function_exists('sanitize_with_special_char')) {

    function sanitize_with_special_char($cur_string) {
        return trim(preg_replace('/\s\s+/', ' ',$cur_string));
    }
}

if (!function_exists('hash_unique_id')) {

    function hash_unique_id($data, $width=192, $rounds=3, $salt="bytescrafter") {
        return substr(
            implode(
                array_map(
                    function ($h) {
                        return str_pad(bin2hex(strrev($h)), 16, "0");
                    },
                    str_split(hash("tiger192,$rounds", $data, true), 8)
                )
            ), 0, 48-(192-$width)/4
        );
    }
}

if (!function_exists('get_encode_where')) {

    function get_encode_where($object, $encoded = 'true', $isEcho = false) {
        if($isEcho) {
            echo (string)$encoded === 'true' ? json_encode($object) : $object;
        } else {
            return (string)$encoded === 'true' ? json_encode($object) : $object;
        }
    }
}

/** 
 * Get header Authorization
 * */
if (!function_exists('getAuthorizationHeader')) {
    function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
}

/**
* get access token from header
* */
if (!function_exists('getBearerToken')) {
    function getBearerToken() {
        $headers = getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}

//make labels view data for different contexts
if (!function_exists("make_status_view_data")) {

    function make_status_view_data($is_active = false) {
        return "<span class='mt0 label large' style='background-color: ".($is_active?'#46c246':'#ff4f4f').";'>" . ($is_active?'ENABLED':'DISABLED') . "</span> ";;
    }

}

if (!function_exists("serialized_breaktime")) {
    function serialized_breaktime($breaktime_object, $default = '', $convert_to_local = true, $totime = true) {

        $ci = get_instance();
        $ci->load->helper('date_time');

        //Deserialized data else set to default.
        $current = isset($breaktime_object)?unserialize($breaktime_object):[null,null,null,null,null,null];

        if($totime) {
            $current[0] = isset($current[0])?format_to_time($current[0], $convert_to_local):$default;
            $current[1] = isset($current[1])?format_to_time($current[1], $convert_to_local):$default;
            $current[2] = isset($current[2])?format_to_time($current[2], $convert_to_local):$default;
            $current[3] = isset($current[3])?format_to_time($current[3], $convert_to_local):$default;
            $current[4] = isset($current[4])?format_to_time($current[4], $convert_to_local):$default;
            $current[5] = isset($current[5])?format_to_time($current[5], $convert_to_local):$default;
        } else {
            $current[0] = isset($current[0])?$current[0]:$default;
            $current[1] = isset($current[1])?$current[1]:$default;
            $current[2] = isset($current[2])?$current[2]:$default;
            $current[3] = isset($current[3])?$current[3]:$default;
            $current[4] = isset($current[4])?$current[4]:$default;
            $current[5] = isset($current[5])?$current[5]:$default;
        }

        return $current;
    }
}

if (!function_exists("contain_str")) {

    function contain_str($haystack, $needle) {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }

        return false;
    }

}

if (!function_exists('get_id_name')) {
    function get_id_name($id, $prefix = "", $zeros = 2) {
        if($id) {
            return $prefix.str_pad($id, $zeros, '0', STR_PAD_LEFT);
        }
        return "-";
    }
}

if (!function_exists('get_warehouse_link')) {

    function get_warehouse_link($id = 0, $name = "", $attributes = array()) {
        $ci = get_instance();

        if( !$id ) {
            return "None";
        }

        return anchor("inventory/Warehouses/view/" . $id, $name, $attributes);
    }

}

if (!function_exists('set_user_meta')) {
    function set_user_meta($user_id, $meta_key, $meta_val) {
        $ci = get_instance();

        return $ci->Users_model
            ->set_meta($user_id, $meta_key, $meta_val);
    }
}

if (!function_exists('get_user_meta')) {
    function get_user_meta($user_id, $meta_key) {
        $ci = get_instance();

        return $ci->Users_model
            ->get_meta($user_id, $meta_key);
    }
}

if (!function_exists('get_night_differential')) {
    function get_night_differential($start_date = "2023-03-27 20:00:00", $end_date = "2023-03-28 05:00:00") {
        // Overtime start time trigger
        $night_start = get_setting('nightpay_start_trigger', '10:00 PM'); //10pm
        $night_start = convert_time_to_24hours_format($night_start); //22:00:00

        // Overtime end time trigger
        $night_end = get_setting('nightpay_end_trigger', '06:00 AM'); //6am
        $night_end = convert_time_to_24hours_format($night_end); //06:00:00

        // get the date base on the current start date.
        $start_check = convert_date_format($start_date, "Y-m-d")." ".$night_start;
        $end_check = add_day_to_datetime($start_date, 1, "Y-m-d")." ".$night_end;
        $latest_span = get_time_overlap_seconds(
            $start_date, $end_date, $start_check , $end_check 
        );

        // get the date base on the current start date.
        $start_check = sub_day_to_datetime($start_date, 1, "Y-m-d")." ".$night_start;
        $end_check = convert_date_format($start_date, "Y-m-d")." ".$night_end;
        $pre_span = get_time_overlap_seconds(
            $start_date, $end_date, $start_check , $end_check 
        );

        return $latest_span + $pre_span;
    }
}

if (!function_exists('adjustBrightness')) {
    function adjustBrightness($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color   = hexdec($color); // Convert to decimal
            $color   = max(0,min(255,$color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }
}

if (!function_exists('get_custom_link')) {

    function get_custom_link($path = "", $name = "", $attributes = array(), $new_name) {
        $ci = get_instance();

        $cur_name = $name;
        if( isset($new_name) ) {
            $cur_name = $new_name;
        }

        return anchor($path . $name, $cur_name, $attributes);
    }

}

if (!function_exists('exit_response_with_message')) {

    function exit_response_with_message($content, $is_lang = true) {
        echo json_encode(array(
            "success" => false,
            "textColor" => "#ef4646",
            "message" => $is_lang?lang($content):$content   
        ));
        exit;
    }
}