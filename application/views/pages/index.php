<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h4> <?php echo lang('pages'); ?></h4>
            <?php if($page_create) { ?>
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("pages/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_page'), array("class" => "btn btn-default", "title" => lang('add_page'))); ?>
                </div>
            <?php } ?>
        </div>
        <div class="table-responsive">
            <table id="pages-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#pages-table").appTable({
            source: '<?php echo_uri("pages/list_data") ?>',
            columns: [
                {title: '<?php echo lang("title"); ?>'},
                {title: '<?php echo lang("url"); ?>'},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>