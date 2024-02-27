<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Music playback timer - PHP</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/jplayer.blue.monday.min.css">
</head>
<body>
    
    <?php
        // Set time and format
        function setTimeAndFormat($date, $hour, $minute, $second) {
            $date->setTime($hour, $minute, $second);
            return $date->format('Y-m-d H:i:s');
        }

        // Scan directory
        function scanDirectory($dir) {
            $files = scandir($dir);
            $result = array();

            foreach($files as $file) {
                if ($file === '.' or $file === '..') continue;
                $result[] = $file;
            }

            return $result;
        }

        $day = date('Y-m-d H:i:s');
        $date = new DateTime(date('Y-m-d'));

        // Set time
        $time1 = setTimeAndFormat($date, 07, 15, 00);
        $time2 = setTimeAndFormat($date, 10, 00, 00);
        $time3 = setTimeAndFormat($date, 11, 30, 00);
        $time4 = setTimeAndFormat($date, 12, 15, 30);
        $time5 = setTimeAndFormat($date, 15, 00, 00);
        $time6 = setTimeAndFormat($date, 16, 20, 00);

        if ($day >= $time1 && $day < $time2) {
            $time = setTimeAndFormat($date, 10, 00, 00);
            $isRelax = false;
        } elseif ($day >= $time2 && $day < $time3) {
            $time = setTimeAndFormat($date, 11, 30, 00);
            $isRelax = true;
        } elseif ($day >= $time3 && $day < $time4) {
            $time = setTimeAndFormat($date, 12, 15, 30);
            $isRelax = false;
        } elseif ($day >= $time4 && $day < $time5) {
            $time = setTimeAndFormat($date, 15, 00, 00);
            $isRelax = false;
        } elseif ($day >= $time5 && $day < $time6) {
            $time = setTimeAndFormat($date, 16, 20, 00);
            $isRelax = true;
        } elseif ($day >= $time6) {
            $date = new DateTime(date('Y-m-d', strtotime($Date. ' + 1 days')));
            $time = setTimeAndFormat($date, 07, 15, 00);
            $isRelax = false;
        } else {
            $time = setTimeAndFormat($date, 07, 15, 00);
            $isRelax = false;
        }

        echo $isRelax;
        echo $time;

        $hot = scanDirectory('./lib/hot/');
        $random = array_rand($hot);
        $mp3 = '/lib/hot/' . ($random + 1);

        $relax = scanDirectory('./lib/relax/');
    ?>

    <p id="timer"></p>
    <div id="jplayer" class="jp-jplayer"></div>
    <div id="jp_container" class="jp-audio" role="application" aria-label="media player">
        <div class="jp-type-playlist">
            <div class="jp-gui jp-interface">
                <div class="jp-controls">
                    <button class="jp-previous" role="button" tabindex="0">previous</button>
                    <button class="jp-play" role="button" tabindex="1">play</button>
                    <button class="jp-next" role="button" tabindex="2">next</button>
                    <button class="jp-stop" role="button" tabindex="3">stop</button>
                </div>
                <div class="jp-progress">
                    <div class="jp-seek-bar">
                    <div class="jp-play-bar"></div>
                    </div>
                </div>
                <div class="jp-volume-controls">
                    <button class="jp-mute" role="button" tabindex="0">mute</button>
                    <button class="jp-volume-max" role="button" tabindex="0">max volume</button>
                    <div class="jp-volume-bar">
                    <div class="jp-volume-bar-value"></div>
                    </div>
                </div>
                <div class="jp-time-holder">
                    <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                    <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                </div>
                <div class="jp-toggles">
                    <button class="jp-repeat" role="button" tabindex="0">repeat</button>
                    <button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
                </div>
            </div>
            <div class="jp-playlist">
                <ul>
                    <li>&nbsp;</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.jplayer.min.js"></script>
    <script src="assets/js/jplayer.playlist.min.js"></script>

    <script type="text/javascript">

// Set the date we're counting down to
let countDownDate = new Date("<?php echo $time; ?>").getTime();

// Time calculations for days, hours, minutes and seconds
function calculateTime(distance) {
    let days = Math.floor(distance / (1000 * 60 * 60 * 24));
    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

    return days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
}

// Update the count down every 1 second
let x = setInterval(function() {
    // Get today's date and time
    let now = new Date().getTime();

    // Find the distance between now and the count down date
    let distance = countDownDate - now;

    // Output the result in an element with id="demo"
    document.getElementById("timer").innerHTML = calculateTime(distance);

    // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(x);
        location = 'http://localhost:8080/';
    }
}, 1000);
</script>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    let arr =[];
    class Song {
        constructor(title, mp3) {
            this.title = title;
            this.mp3 = mp3;
        }
    }

    function addSongToArr(json, index, path) {
        let song = new Song(`${json[index]}`, `http://localhost:8080/${path}/${json[index]}`);
        arr.push(song);
    }

    let isRelax1 = `<?php echo $isRelax ?>`;
    let relaxJson = <?php echo json_encode($relax); ?>;
    let volume = 0.7;

    let hotJson = <?php echo json_encode($hot); ?>;

    if(isRelax1 != '') {
        let ranRelax = Math.floor(Math.random() * relaxJson.length);
        for (let i=0; i < 15; i++) {
            let ranRelax = Math.floor(Math.random() * relaxJson.length);
            addSongToArr(relaxJson, ranRelax, 'relax');
        }
        // addSongToArr(relaxJson, ranRelax, 'relax');
        volume = 0.15;
    } else {
        let ranHot = Math.floor(Math.random() * hotJson.length);
        addSongToArr(hotJson, ranHot, 'hot');
    }

    console.log("volume = ", volume);

    new jPlayerPlaylist({
        jPlayer: "#jplayer",
        cssSelectorAncestor: "#jp_container"
    }, arr, {
        swfPath: "../../assets/js",
        supplied: "mp3, oga",
        wmode: "window",
        useStateClassSkin: true,
        autoBlur: false,
        smoothPlayBar: true,
        keyEnabled: true,
        shuffled: true,
        playlistOptions: {
            //autoPlay: true,
            shuffleOnLoop: true
        }
    });

    setTimeout(function() {
        document.querySelector('#jp_audio_0').volume = volume;
    }, 2500);
});
//]]>

    </script>
</body>
</html>