<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <div class="title-button-group">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default btn-sm active mr-1"  title="<?php echo lang('list_view'); ?>"><i class="fa fa-bars"></i></button>
                    <?php echo anchor(get_uri("hrs/employee/view"), "<i class='fa fa-th-large'></i>", array("class" => "btn btn-default btn-sm")); ?>
                </div> 
                <?php
                if ($permit_edit) {
                    echo modal_anchor(get_uri("labels/modal_form"), "<i class='fa fa-tags'></i> " . lang('manage_labels'), array("class" => "btn btn-default", "title" => lang('manage_labels'), "data-post-type" => "users"));
                }
                if ($permit_invite) {
                    echo modal_anchor(get_uri("hrs/team_members/invitation_modal"), "<i class='fa fa-envelope-o'></i> " . lang('send_invitation'), array("class" => "btn btn-default", "title" => lang('send_invitation')));
                }
                if ($permit_add) {
                    echo modal_anchor(get_uri("hrs/team_members/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_employee'), array("class" => "btn btn-default", "title" => lang('add_team_member')));
                }
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="team_member-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var visibleContact = false;
        if ("<?php echo $show_contact_info; ?>") {
            visibleContact = true;
        }

        var visibleDelete = false;
        if ("<?php echo $this->login_user->is_admin; ?>") {
            visibleDelete = true;
        }

        $("#team_member-table").appTable({
            source: '<?php echo_uri("hrs/team_members/list_data/staff") ?>',
            order: [[1, "asc"]],
            filterDropdown: [
                {name: "status", class: "text-center w100", options: <?php echo $usertype_dropdown; ?>},
                {name: "label_id", class: "text-center w100", options: <?php echo $users_labels_dropdown; ?>},
                {id: "schedule_select2_filter", name: "schedule_select2_filter", class: "w150", options: <?php echo json_encode($schedule_select2); ?>},
                {id: "department_select2_filter", name: "department_select2_filter", class: "w150", options: <?php echo json_encode($department_select2); ?>}
            ],
            columns: [
                {title: '', "class": "w25 text-center"},
                {title: "<?php echo lang("id") ?>", "class": "w10p"},
                {title: "<?php echo lang("name") ?>", "class": "w15p"},
                {title: "<?php echo lang("rfid_num") ?>", "class": "w10p"},
                {visible: visibleContact, title: "<?php echo lang("email") ?>", "class": "w20p"},
                {visible: visibleContact, title: "<?php echo lang("phone") ?>", "class": "w10p"},
                {title: "<?php echo lang("labels") ?>", "class": "w10p"},
                {title: "<?php echo lang("job_title") ?>", "class": "w10p"},
                {title: "<?php echo lang("department") ?>"},
                {title: "<?php echo lang("schedule") ?>"},
                {title: "<?php echo lang("last_online") ?>", "class": "w10p"}
                <?php echo $custom_field_headers; ?>,
                {visible: visibleDelete, title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4], '<?php echo $custom_field_headers; ?>'),
            tableRefreshButton: true,
        });
    });
</script>    
