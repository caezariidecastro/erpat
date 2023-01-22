<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("Epass_Seat/modal_form"), "<i class='fa fa-plus'></i> " . lang('add_seat'), array("class" => "btn btn-default", "title" => lang('add_seat'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="seat-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<style>
    .search-margin {
        margin-right: 15px; 
    }
</style>

<script type="text/javascript">
    $(document).ready(function () {
        $("#seat-table").appTable({
            source: '<?php echo_uri("Epass_Seat/list_data") ?>',
            order: [[1, 'desc']],
            filterDropdown: [
                {id: "status", name: "status", class: "w100", options: [
                    { id: '', text: 'All Status' },
                    { id: 'vacant', text: 'Vacant' },
                    { id: 'assigned', text: 'Assigned' },
                ]},
                {id: "limits", name: "limits", class: "w100", options: [
                    { id: '100', text: '100 items' },
                    { id: '500', text: '500 items' },
                    { id: '1000', text: '1k items' },
                    { id: '5000', text: '5k items' },
                    { id: '1000000', text: 'All Items' },
                ]},
                {id: "area", name: "area", class: "w150", options: [
                    { id: '', text: 'All Area' },
                    { id: '1', text: 'Patrons Only' },
                    { id: '2', text: 'Lower Box Only' },
                    { id: '3', text: 'Upper Box A Only' },
                    { id: '4', text: 'Upper Box B Only' },
                    { id: '4', text: 'Gen. Admin Only' },
                ]}
            ],
            search: {
                show: true,
                name: "search"
            },
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("event_name"); ?>'},
                {title: '<?php echo lang("area_name"); ?>'},
                {title: '<?php echo lang("block_name"); ?>'},
                {title: '<?php echo lang("seat_name"); ?>'},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<?php echo lang("sort"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'},
                {title: '<?php echo lang("date"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            tableRefreshButton: true,
            printColumns: [1,2,3,4,5,6,7,8],
            xlsColumns: [1,2,3,4,5,6,7,8],
            onRelaodCallback: function() {
                appLoader.hide();
            }
        });
        $('#seat-table_filter').remove();
        $('.custom-filter-search').addClass('search-margin');
    });
</script>