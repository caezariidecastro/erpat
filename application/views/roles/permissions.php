<div class="tab-content">
    <style>
        .select2-container-multi .select2-choices .select2-search-choice {
            display: flex;
            width: -webkit-fill-available;
            justify-content: space-between;
        }

        .select2-container-multi .select2-search-choice-close {
            //** SHOW THE X ON DELETE */
        }
        
        .select-permission {
            width: -webkit-fill-available;
            max-width: 150px;
            padding: 5px;
            margin: 5px 0 10px;
            border: 1px solid #cecece;
        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="page-title clearfix">
                    <h4><?php echo lang("role_permissions"); ?></h4>
                    <div class="title-button-group">
                        <a href="#" id="save-permit" class="btn btn-default">
                            <i class="fa fa-plus-circle"></i> 
                            <?= lang('save_permission') ?>
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="permission-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#permission-table").appTable({
            source: '<?php echo_uri("roles/permission_lists/$model_info->id") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: '<?php echo lang("modules"); ?>'},
                {title: '<?php echo lang("permissions"); ?>'},
                {title: '<?php echo lang("actions"); ?>'},
                {title: '<?php echo lang("changes"); ?>'},
            ], onInitComplete: function () {

                var table = $('#permission-table').DataTable();

                let refresh = function() {
                    $("#leave_specific_dropdown, #attendance_specific_dropdown, #timesheet_manage_permission_specific_dropdown,  #team_member_update_permission_specific_dropdown, #message_permission_specific_dropdown").select2({
                        multiple: true,
                        formatResult: teamAndMemberSelect2Format,
                        formatSelection: teamAndMemberSelect2Format,
                        data: <?php echo ($members_and_teams_dropdown); ?>
                    });

                    $('[data-toggle="tooltip"]').tooltip();

                    var selectors = ["leave", "attendance", "timesheet", "team_member_update", "ticket", "message_permission", "team_member_update_permission", "timesheet_manage_permission"];
                    $.each(selectors, function (index, element) {
                        var id = element+"_specific_dropdown"; 
                        var val = $('#'+element).val();

                        if( val != 'specific') {
                            $('#'+id).addClass('hide');
                        }
                    });

                    $('.toggle_specific').change(function() {
                        var id = $( this ).attr('id');
                        if( $( this ).val() == 'specific' ) {
                            if( $('#'+id+'_specific_dropdown').hasClass('hide') ) {
                                $('#'+id+'_specific_dropdown').removeClass('hide');
                            }
                        } else {
                            $('#'+id+'_specific_dropdown').addClass('hide');
                        }
                    });

                    $("#ticket_specific_dropdown").select2({
                        multiple: true,
                        data: <?php echo ($ticket_types_dropdown); ?>
                    });

                    $(".select2").select2();
                }
                table.on( 'init.dt', refresh );
                table.on( 'draw', refresh );
            },
            tableRefreshButton: true,
        });

        $('#permission-table tbody').on('click', 'tr', function () {
            appLoader.hide();
            return false;
        });

        $('#save-permit').click(function () {
            var table = $('#permission-table').DataTable();
            var dataRaw = table.$('input, select').serializeArray();

            var dataJson = { id: '<?= $model_info->id ?>' };
            $.each(dataRaw, function( index, item ) {
                dataJson[item.name] = item.value;
            });
            
            appLoader.show();
            $.ajax({
                url: '<?php echo_uri("roles/save_permissions") ?>',
                method: 'POST',
                data: dataJson,
                dataType: "json",
                success: function(response){
                    if (response.success) {
                        //TODO: Reload data tables
                        appAlert.success(response.message, {duration: 10000});
                    } else {
                        appAlert.error(response.message);
                    }
                    appLoader.hide();
                }
            });

            return false;
        });
    });
</script>    