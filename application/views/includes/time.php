<a>
    <span class='fa fa-clock-o' style="font-size: 16px; padding-right: 5px;"></span>
    <span id="nav_time_display">
        <?php
            $local_time = get_my_local_time();
            echo date("g:i:s A", strtotime($local_time));
        ?>
    </span>
</a>

<script>
    $(document).ready(function() {
        let dateTime = new Date('<?= $local_time ?>');

        setInterval(function(){
            displayTime();
        }, 1000)
    
        function displayTime() {
            dateTime.setSeconds(dateTime.getSeconds() + 1);

            var hours = dateTime.getHours();
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
            var minutes = dateTime.getMinutes();
                minutes = minutes < 10 ? '0' + minutes : minutes;
            var seconds = dateTime.getSeconds();
                seconds = seconds < 10 ? '0' + seconds : seconds;
            var ampm = hours >= 12 ? 'PM' : 'AM';
            var strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;

            document.getElementById("nav_time_display").innerHTML = strTime;
        }
    });
</script>