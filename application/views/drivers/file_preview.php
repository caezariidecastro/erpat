<?php

if (isset($file_name) && $file_name) {
    echo "<div class='box saved-file-item-container'><div class='box-content pt5 pb5'>
            <img src='".base_url(get_setting("profile_image_path").$file_name)."' style='width: 100%; height: auto;'/>
            <a href='#' style='position: absolute; right: 20px;' class='delete-saved-file p5 dark' data-file_name='$file_name'><span class='fa fa-close image-close' style='font-size: 20px;'></span></a>
        </div></div>";
}
?>

<script>
    $(".image-close").click(function(){
        $("#license_image").removeClass("hide");
    });
</script>