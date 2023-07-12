
<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <div class="table-responsive">
            <table id="earning-table" class="display" cellspacing="0" width="100%"></table>
        </div>
    </div>
</div>

<style>
    .cell-class{
        max-width: 100px;
    }
    .cell-style {
        padding: 3px; 
        border-radius: 50%; 
        background-color: #eaeaea; 
        border: 1px solid;
    }
</style>

<script type="text/javascript">
    $(document).ready(function () {

        $("#earning-table").appTable({
            source: '<?php echo_uri("payrolls/earning_lists") ?>',
            filterDropdown: [
                {id: "category_select2_filter_earnings", name: "category_select2_filter", class: "w200 select2_filter", options: <?php echo json_encode($category_select2); ?>},
                {name: "department_id", class: "w200", options: <?= json_encode($department_select2) ?>},
            ],
            columns: [
                {visible: false},
                {title: "<?php echo lang('fullname') ?>", "class": "text-left w200"},
                {title: "<?php echo lang('allowance') ?>", "class": "text-right w100"},
                {title: "<?php echo lang('incentives') ?>", "class": "text-right w100"},
                {title: "<?php echo lang('bonus') ?>", "class": "text-right w100"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center dropdown-option w100"}
            ],
            rowCallback(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                const dataId = aData[0];
            }, onInitComplete() {

                $("#category_select2_filter_earnings").on('change', function() {
                    //filter by selected value on second column
                    //table.column(1).search($(this).val()).draw();
                    console.log($(this).val());
                }); 

                var table = $('#earning-table').DataTable();
                
                let refresh = function() {
                    $('.cell-edit').on( 'click', function () {
                        var id = $( this ).attr('name');
                        $('.cell-class-'+id).removeAttr('disabled');
                        $('#cell-edit-'+id).addClass('hide');
                        $('#cell-save-'+id).removeClass('hide');
                    } );

                    $('.cell-save').on( 'click', function () {
                        var id = $( this ).attr('name');
                        var filter = $( this ).attr('data-filter');

                        appLoader.show();

                        //Get the data
                        const data = {
                            'user_id': id,
                            'filter': filter,
                            'allowances': $('#allowances_'+id).val(),
                            'incentives': $('#incentives_'+id).val(),
                            'others': $('#others_'+id).val()
                        };
                        
                        //TODO: Ajax request!
                        $.ajax({
                            url: "<?= base_url() ?>/payrolls/save_earning",
                            method: "POST",
                            data: data,
                            dataType: "json",
                            success: function(response){
                                if(response.success){
                                    appAlert.success(response.message);
                                } else {
                                    appAlert.error(response.message);
                                }

                                //Done saving.
                                $('.cell-class-'+id).attr('disabled', "true");
                                $('#cell-edit-'+id).removeClass('hide');
                                $('#cell-save-'+id).addClass('hide');

                                appLoader.hide();
                            }
                        })
                    } );
                }
                table.on( 'init.dt', refresh );
                table.on( 'draw', refresh );
            },
            tableRefreshButton: true,
        }); 
    });
</script>