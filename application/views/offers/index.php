<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('offers'); ?></h1>
            <div class="title-button-group">
                <?php //echo modal_anchor(get_uri("labels/modal_form"), "<i class='fa fa-tags'></i> " . lang('manage_labels'), array("class" => "btn btn-default", "title" => lang('manage_labels'), "data-post-type" => "offers")); ?>
                <?php echo modal_anchor(get_uri("offers/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_offers'), array("class" => "btn btn-default", "title" => lang('add_offers'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="offers-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#offers-table").appTable({
            source: '<?php echo_uri("offers/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                //{targets: [1], visible: false},
                {title: '<?php echo lang("uuid"); ?>', "class": "w10p"},
                {title: '<?php echo lang("title"); ?>', "class": "w15p"},
                {title: '<?php echo lang("description"); ?>', "class": "w100"},
                {title: '<?php echo lang("type"); ?>', "class": "w5p"},
                {title: '<?php echo lang("off"); ?>', "class": "w5p"},
                {title: '<?php echo lang("min"); ?>', "class": "w5p"},
                {title: '<?php echo lang("upto"); ?>', "class": "w5p"},
                {title: '<?php echo lang("files") ?>', "class": "w10p"},
                {title: '<?php echo lang("created_by"); ?>', "class": "w8p"},
                {title: '<?php echo lang("created_at"); ?>', "class": "w8p"},
                {title: '<?php echo lang("updated_at"); ?>', "class": "w8p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w10p"}
            ]
        });
    });
</script>