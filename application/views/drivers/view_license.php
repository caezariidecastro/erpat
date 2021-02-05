<div class="modal-body clearfix">
    <div class="row">
        <div class="col-md-12">
            <img class="img-responsive" src="<?= $file_path?>" alt="">
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".modal-dialog").addClass("modal-lg");
    });
</script>