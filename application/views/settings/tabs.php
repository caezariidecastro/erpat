<?php
$settings_menu = array(
    "system" => array(
        array("name" => "general", "url" => "settings/general"),
        array("name" => "display", "url" => "settings/display"),
        array("name" => "calendar", "url" => "settings/calendar"),
        array("name" => "finance", "url" => "settings/finance"),
        array("name" => "company", "url" => "settings/company")
    ),
    "components" => array(
        array("name" => "gdpr", "url" => "settings/gdpr"),
        array("name" => "kiosk", "url" => "settings/kiosk"),
        array("name" => "footer", "url" => "settings/footer"),
        array("name" => "tasks", "url" => "task_status")
    ),
    "customization" => array(
        array("name" => "email_templates", "url" => "email_templates"),
        array("name" => "notifications", "url" => "settings/notifications"),
    ),
    "maintainance" => array(
        array("name" => "apis", "url" => "settings/apis"),
        array("name" => "crons", "url" => "settings/crons"),
        array("name" => "modules", "url" => "settings/modules"),
        array("name" => "migrate_database", "url" => "database"),
        array("name" => "check_fix", "url" => "check_fix")
    ),
    "access_permission" => array(
        array("name" => "roles", "url" => "roles"),
    ),
    "client" => array(
        array("name" => "client_permissions", "url" => "settings/client_permissions"),
        array("name" => "client_groups", "url" => "sales/client_groups"),
        array("name" => "dashboard", "url" => "dashboard/client_default_dashboard"),
        array("name" => "client_left_menu", "url" => "left_menus/index/client_default"),
        array("name" => "client_projects", "url" => "settings/client_projects"),
    ),
    "setup" => array(
        array("name" => "custom_fields", "url" => "custom_fields"),
        array("name" => "cron_job", "url" => "settings/cron_job"),
        array("name" => "left_menu", "url" => "left_menus"),
        array("name" => "email_title_smtp", "url" => "settings/email"),
        array("name" => "integration", "url" => "settings/integration"),
        array("name" => "imap_settings", "url" => "settings/imap_settings"),
        array("name" => "system_account", "url" => "settings/system_account"),
        array("name" => "payment_methods", "url" => "payment_methods"),
        array("name" => "shipping_option", "url" => "settings/shipping_option"),
    ),
);

//restricted settings
if (get_setting("module_attendance") == "1") {
    $settings_menu["access_permission"][] = array("name" => "ip_restriction", "url" => "settings/ip_restriction");
}

if (get_setting("module_event") == "1") {
    $settings_menu["components"][] = array("name" => "events", "url" => "settings/events");
}

if (get_setting("module_invoice") == "1") {
    $settings_menu["customization"][] = array("name" => "invoices", "url" => "settings/invoices");
}

if (get_setting("module_estimate") == "1") {
    $settings_menu["customization"][] = array("name" => "estimates", "url" => "settings/estimates");
}

if (get_setting("module_ticket") == "1") {
    $settings_menu["components"][] = array("name" => "tickets", "url" => "settings/tickets");
}

$settings_menu["components"][] = array("name" => "projects", "url" => "settings/projects");

if (get_setting("module_project_timesheet") == "1") {
    $settings_menu["components"][] = array("name" => "timesheets", "url" => "settings/timesheets");
}

?>

<ul class="nav nav-tabs vertical settings" role="tablist">
    <?php
    foreach ($settings_menu as $key => $value) {

        //collapse the selected settings tab panel
        $collapse_in = "";
        $collapsed_class = "collapsed";
        if (in_array($active_tab, array_column($value, "name"))) {
            $collapse_in = "in";
            $collapsed_class = "";
        }
        ?>

        <div class="clearfix settings-anchor <?php echo $collapsed_class; ?>" data-toggle="collapse" data-target="#settings-tab-<?php echo $key; ?>">
            <?php echo lang($key); ?>
            <span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
        </div>

        <?php
        echo "<div id='settings-tab-$key' class='collapse $collapse_in'>";
        echo "<ul class='list-group help-catagory'>";

        foreach ($value as $sub_setting) {
            $active_class = "";
            $setting_name = get_array_value($sub_setting, "name");
            $setting_url = get_array_value($sub_setting, "url");

            if ($active_tab == $setting_name) {
                $active_class = "active";
            }

            echo "<a href='" . get_uri($setting_url) . "' class='list-group-item $active_class'>" . lang($setting_name) . "</a>";
        }

        echo "</ul>";
        echo "</div>";
    }
    ?>
</ul>