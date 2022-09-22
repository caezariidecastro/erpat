
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
                var table = $('#contribution-table').DataTable();
                $('#contribution-table tbody').on( 'click', 'tr', function () {
                    //console.log( table.row( this ).index() );
                } );

                $('.cell-edit').on( 'click', function () {
                    var id = $( this ).attr('name');
                    $('.cell-class-'+id).removeAttr('disabled');
                    $('#edit-'+id).addClass('hide');
                    $('#save-'+id).removeClass('hide');
                } );

                $('.cell-save').on( 'click', function () {
                    var id = $( this ).attr('name');

                    appLoader.show();

                    //Get the data
                    const data = {
                        'filter': $('#category_select2_filter').val(),
                        'user_id': id,
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
                        url: "<?= base_url() ?>/payrolls/save_weekly",
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
                            $('#save-'+id).addClass('hide');
                            $('#edit-'+id).removeClass('hide');
                            appLoader.hide();
                        }
                    })
                } );
            }
            //printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            //xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            //summation: [{column: 6, dataType: 'number'}],
            //tableRefreshButton: true,
        }); 
                
        //$(".dataTable:visible").appTable({newData: result.data, dataId: result.id});
        // setInterval(function(){
        //     $("#payrolls-tabs").find("li.active").text() == "Contributions" ? $('#add_payrolls_button').show() : $('#add_payrolls_button').hide()
        // }, 200)
    });
</script>