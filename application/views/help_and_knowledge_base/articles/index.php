<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <ul id="leaves-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white inner" role="tablist">
            <li class="title-tab">
                <h4 class="pl15 pt10 pr15">
                    <?php
                        if ($type === "knowledge_base") {
                            echo lang("knowledge_base");
                        } else {
                            echo lang("help_page_title");
                        }
                    ?>
                </h4>
            </li>
            <li><a  role="presentation" class="active" href="<?php echo_uri("$type/view_preview"); ?>" data-target="#preview-panel"><?php echo lang("preview"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("$type/view_articles"); ?>" data-target="#articles-panel"><?php echo lang("articles"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("$type/view_categories"); ?>" data-target="#category-panel"><?php echo lang("categories"); ?></a></li>
        </ul>
        <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade active" id="preview-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="articles-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="category-panel"></div>
        </div>
    </div>
</div>