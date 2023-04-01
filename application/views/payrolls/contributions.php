
<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <div class="table-responsive">
            <table id="contribution-table" class="display" cellspacing="0" width="100%"></table>
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

        $("#contribution-table").appTable({
            source: '<?php echo_uri("payrolls/contribution_lists") ?>',
            filterDropdown: [
                {id: "category_select2_filter", name: "category_select2_filter", class: "w200", options: <?php echo json_encode($category_select2); ?>}
            ],
            columns: [
                {visible: false},
                {title: "<?php echo lang('fullname') ?>", "class": "text-left w200"},
                {title: "<?php echo lang('sss') ?>", "class": "text-right w100"},
                {title: "<?php echo lang('pagibig') ?>", "class": "text-right w100"},
                {title: "<?php echo lang('phealth') ?>", "class": "text-right w100"},
                {title: "<?php echo lang('hmo') ?>", "class": "text-right w100"},
                {title: "<?= lang('company')." ".lang('loan') ?>", "class": "text-right w100"},
                {title: "<?= lang('sss')." ".lang('loan') ?>", "class": "text-right w100"},
                {title: "<?= lang('hdmf')." ".lang('loan') ?>", "class": "text-right w100"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center dropdown-option w100"}
            ],
            rowCallback(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                const dataId = aData[0];
            }, onInitComplete() {

                $("#category_select2_filter").on('change', function() {
                    //filter by selected value on second column
                    //table.column(1).search($(this).val()).draw();
                    console.log($(this).val());
                }); 

                var table = $('#contribution-table').DataTable();
                
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
                            'sss_contri': $('#sss_contri_'+id).val(),
                            'pagibig_contri': $('#pagibig_contri_'+id).val(),
                            'philhealth_contri': $('#philhealth_contri_'+id).val(),
                            'hmo_contri': $('#hmo_contri_'+id).val(),
                            'company_loan': $('#company_loan_'+id).val(),
                            'sss_loan': $('#sss_loan_'+id).val(),
                            'hdmf_loan': $('#hdmf_loan_'+id).val(),
                        };
                        
                        //TODO: Ajax request!
                        $.ajax({
                            url: "<?= base_url() ?>/payrolls/save_contribution",
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