<html>

<head>
    <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
    <style>
        .display-cues {
            width: 400px;
            float: left;
            margin-left: 50px;
            height: 300px;
            overflow-y: scroll;
			border: solid 1px;
			margin-top: 30px;
        }
        .cue-active {
            background-color: #84c9b6;
        }
    </style>
</head>

<body>
	<?php for($i = 11; $i <=12; $i++){?>
	<div style="display: table;width: 100%;">
		<div style="width: 400px;float: left;">
			<video class="bigschool-video" style="width: 400px;margin-top: 30px;" id="video-<?=$i?>" controls>
				<source src="developerStories-en.webm">
				<track onload="onTrackLoad(<?=$i?>);" id="entrack-<?=$i?>" label="English subtitles" kind="captions" src="entrack.vtt" srclang="en" default>
			</video>
	   
		</div>
		<div id="display-cues-<?=$i?>" class="display-cues">
			<p id="start-point-<?=$i?>"><p>
		</div>
	</div>
	<?php }?>

    <script>
        $(document).ready(function() {
			var cuesTime = [];
			var onHover = [];
			jumpToTime = function (video_id, t){
				document.getElementById('video-' + video_id).currentTime = t + 0.01;
			}
            onTrackLoad = function(video_id) {
                var track = document.getElementById("entrack-"+video_id).track; // get text track from track element          
                var cues = track.cues;
                //console.log(cues);
                // get list of cues                    
                for (var i = 0; i < cues.length; i++) {		
					if(i == 0){
						cuesTime[video_id] = [];
						cuesTime[video_id][i] = 0;
						$('#display-cues-'+video_id).append('<p onclick="jumpToTime(' + video_id + ', ' + cues[i].startTime + ')" data-start-time-video-' + video_id + '="0" class="cue-active">' + cues[i].text + '</p>');				
					}else{
						cuesTime[video_id][i] = cues[i].startTime;
						$('#display-cues-'+video_id).append('<p onclick="jumpToTime(' + video_id + ', ' + cues[i].startTime + ')" data-start-time-video-' + video_id + '="' + cues[i].startTime + '">' + cues[i].text + '</p>');
					}
                }

				onHover[video_id] = false;
				$('#display-cues-'+video_id).scrollTop(0);
				//track.mode = "hidden"; 
            }

            $(".bigschool-video").on(
                "timeupdate",
                function(event) {
					var video_id = (this.id).substring(6);
					//console.log(video_id);
                    var currentTime = this.currentTime;
                   
					
					var cueTimeCurrent = 0;
					for (var i = 0; i < cuesTime[video_id].length; i++) {
						if((currentTime - cuesTime[video_id][i]) <= 0){
							cueTimeCurrent = cuesTime[video_id][i-1];
							break;
						}
						cueTimeCurrent = cuesTime[video_id][i];
					}
					if(currentTime == 0){
						cueTimeCurrent = 0;
					}
					
					$('#display-cues-' + video_id + ' .cue-active').removeClass('cue-active');
					$('[data-start-time-video-' + video_id + '="' + cueTimeCurrent + '"]').addClass('cue-active');
					
					var offsetTop = $('[data-start-time-video-' + video_id + '="' + cueTimeCurrent + '"]').offset().top - $('#start-point-'+video_id).offset().top;
					if(!onHover[video_id]){
						$('#display-cues-' + video_id).scrollTop(offsetTop - 150);
					}
					//console.log(offsetTop);
				});
			
			$('.display-cues').hover(function(event){
				var video_id = (this.id).substring(13);
				onHover[video_id] = true;
			}, function (){
				var video_id = (this.id).substring(13);
				onHover[video_id] = false;
			})
        })
    </script>
</body>

</html>