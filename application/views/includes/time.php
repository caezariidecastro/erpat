<li class="hidden-xs">
    <a><strong>Time:</strong> <strong><span id="nav_time_display"></span></strong></a>
</li>

<script>
    
    $(document).ready(function() {
        setInterval(function(){
            displayTime();
        }, 1000)
    });
    
    function displayTime() {
        let dateTime = new Date();
        dateTime.setUTCDate("<?php echo get_my_local_time("d")?>");
        document.getElementById("nav_time_display").innerHTML = dateTime.toLocaleTimeString();
    }
</script>