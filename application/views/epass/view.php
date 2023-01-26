<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("EventPass/modal_form_allocate"), "<i class='fa fa-recycle'></i> " . lang('allocate_seats'), array("class" => "btn btn-danger", "title" => lang('allocate_seats'))); ?>
                <?php echo modal_anchor(get_uri("EventPass/modal_form_add"), "<i class='fa fa-plus'></i> " . lang('add_epass'), array("class" => "btn btn-default", "title" => lang('add_epass'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="epass-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<style>
    .search-margin {
        margin-left: 15px; 
        margin-right: 15px; 
    }
</style>

<script type="text/javascript">
    $(document).ready(function () {
        
        $("#epass-table").appTable({
            source: '<?php echo_uri("EventPass/list_data") ?>',
            order: [[1, 'desc']],
            columns: [
                {visible: false, searchable: false},
                // {visible: false, searchable: false},
                //{title: '', "class": "w25"},
                {title: '<?php echo lang("reference_id"); ?>'},
                {title: '<?php echo lang("event_name"); ?>'},
                {title: '<?php echo lang("full_name"); ?>'},
                {title: '<?php echo lang("group_name"); ?>'},
                {title: '<?php echo lang("virtual_id"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'},
                {title: '<?php echo lang("seats_requested"); ?>'},
                {title: '<?php echo lang("seats_assignment"); ?>', "class": "w200"},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<?php echo lang("date"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            filterDropdown: [
                {id: "status",  name: "status", class: "w150", options: [
                    {text: '<?php echo lang("draft") ?>', id: "draft"},
                    {text: '<?php echo lang("approved") ?>', id: "approved"},
                    {text: '<?php echo lang("sent") ?>', id: "sent"},
                    {text: '<?php echo lang("cancelled") ?>', id: "cancelled"}
                ]},
                {id: "limits", name: "limits", class: "w100", options: [
                    { id: '100', text: '100 items' },
                    { id: '500', text: '500 items' },
                    { id: '1000', text: '1k items' },
                    { id: '5000', text: '5k items' },
                    { id: '1000000', text: 'All Items' },
                ]},
                {id: "groups", name: "groups", class: "w150", options: [
                    { id: '', text: 'All Groups' },
                    { id: 'franchisee', text: 'Franchisee Only' },
                    { id: 'distributor', text: 'Distributor Only' },
                    { id: 'seller', text: 'Seller Only' },
                    { id: 'viewer', text: 'Viewer Only' },
                ]}
            ],
            radioButtons: [
                { value: 'guest', name: "type", text: 'Guest', isChecked: true },
                { value: 'companion', name: "type", text: 'Companion', isChecked: false }
            ],
            search: {
                show: true,
                name: "search"
            },
            onRelaodCallback: function() {
                appLoader.hide();
            },
            onInitComplete: () => {

            },
            rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                console.log('asdsdas');
            },
            tableRefreshButton: true,
            printColumns: [1,2,3,4,5,6,7,8,9],
            xlsColumns: [1,2,3,4,5,6,7,8,9],
        });
        $('#epass-table_filter').remove();
        $('.custom-filter-search').addClass('search-margin');

    });
</script>