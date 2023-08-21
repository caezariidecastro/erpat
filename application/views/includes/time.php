<a style="padding-left: 5px; padding-right: 5px;">
    <!-- <span class='fa fa-clock-o' style="font-size: 16px; padding-right: 5px;"></span> -->
    <span id="nav_time_display" style="font-weight: bold;">
        <?php
            $local_time = get_my_local_time();
            echo date("g:i:s A", strtotime($local_time));
        ?>
    </span>
</a>

<script>
    $(document).ready(function() {
        const dateTime = new Date('<?= $local_time ?>');

        setInterval(function(){
            displayTime();
        }, 1000)
    
        function displayTime() {
            dateTime.setSeconds(dateTime.getSeconds() + 1);
            document.getElementById("nav_time_display").innerHTML = dateTime.toLocaleTimeString();
        }
    });
</script>