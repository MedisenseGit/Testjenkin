<?php
ob_start();
session_start();
error_reporting(0);

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$_SESSION["localMemberName"] = '';
$_SESSION["remoteMemberName"] = '';

$channel_id = $_GET['ch'];
$type = $_GET['t'];			// 1- doctor link, 2 -patient link

//$channel_id = '3743_8_1632121071';
//$type = 1;
$str_arr = explode ("_", $channel_id); 
$doc_id = $str_arr[0];
$member_id = $str_arr[1];
$txn_id = $str_arr[2];

// echo $doc_id. "<br />".$member_id. "<br />".$txn_id. "<br />";

$checkPatient = $objQuery->mysqlSelect("member_id, member_name","user_family_member","member_id='".$member_id."'");
$checkDoc = $objQuery->mysqlSelect("ref_id,ref_name","referal","ref_id='".$doc_id."'");

$checkInfo = $objQuery->mysqlSelect("patient_id","doc_my_patient","transaction_id='".$txn_id."' AND doc_id='".$doc_id."'");

$localMemberName = '';
$remoteMemberName = '';
if($type == 1) {
	$localMemberName = $checkDoc[0]['ref_name'];
	$remoteMemberName = $checkPatient[0]['member_name'];
}
else if($type == 2) {
	$localMemberName = $checkPatient[0]['member_name'];
	$remoteMemberName = $checkDoc[0]['ref_name'];
}

$_SESSION["localMemberName"] = $localMemberName;
$_SESSION["remoteMemberName"] = $remoteMemberName;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Medisense Video Call</title>
  <link rel="stylesheet" href="../assets/bootstrap.min.css">
  <link rel="stylesheet" href="./index.css">
</head>
<body>
 <!-- <div class="container-fluid banner">
    <p class="banner-text">Basic Mute / Unmute</p>
    <a style="color: rgb(255, 255, 255);fill: rgb(255, 255, 255);fill-rule: evenodd; position: absolute; right: 10px; top: 4px;"
      class="Header-link " href="https://github.com/AgoraIO-Community/AgoraWebSDK-NG/tree/master/Demo">
      <svg class="octicon octicon-mark-github v-align-middle" height="32" viewBox="0 0 16 16" version="1.1" width="32" aria-hidden="true"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg>
    </a>
  </div> -->

  <!--div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Congratulations!</strong><span> You can invite others join this channel by click </span><a href="" target="_blank">here</a>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div-->
  <!--div id="success-alert-with-token" class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Congratulations!</strong><span> Joined room successfully. </span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div id="success-alert-with-token" class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Congratulations!</strong><span> Joined room successfully. </span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div-->
  
  <div class="alert alert-success alert-dismissible fade show">
	<?php if($type == 1) {
		?>
		 <strong>Dear <?php echo $checkDoc[0]['ref_name']; ?></strong><span> You can join the video call with <strong><?php echo $checkPatient[0]['member_name']; ?></strong> by clicking Join button </span><!--a href="" target="_blank">here</a-->
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
		<?php
	} else  if($type == 2) {
		?>
		 <strong>Dear <?php echo $checkPatient[0]['member_name']; ?></strong><span> You can join the video call with Dr. <strong><?php echo $checkDoc[0]['ref_name']; ?></strong> by clicking Join button </span><!--a href="" target="_blank">here</a-->
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
		<?php
	}
	?>
  
  <!--  <strong>Dear <?php echo $checkDoc[0]['ref_name']; ?></strong><span> You can join the video call with <strong><?php echo $checkPatient[0]['member_name']; ?></strong> by clicking Join button </span><!--a href="" target="_blank">here</a>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button> -->
  </div>
  
  <div class="container">
    <form id="join-form">
      <div class="row form-group">
          <div class="col-sm" hidden>
            <p class="join-info-text">AppID</p>
            <input class="form-control" id="appid" type="text" placeholder="enter appid" value="7532fd0938e74e31a654cbd27f952772" required>
            <p class="tips">If you don`t know what is your appid, checkout <a href="https://docs.agora.io/en/Agora%20Platform/terms?platform=All%20Platforms#a-nameappidaapp-id">this</a></p>
          </div>
       <!--   <div class="col-sm">
            <p class="join-info-text">Token(optional)</p>
            <input id="token" type="text" placeholder="enter token">
            <p class="tips">If you don`t know what is your token, checkout <a href="https://docs.agora.io/en/Agora%20Platform/terms?platform=All%20Platforms#a-namekeyadynamic-key">this</a></p>
          </div> -->
          <div class="col-sm" hidden>
            <p class="join-info-text">Channel</p>
            <input class="form-control" id="channel" type="text" placeholder="enter channel name" value="<?php echo $channel_id; ?>" required>
            <p class="tips">If you don`t know what is your channel, checkout <a href="https://docs.agora.io/en/Agora%20Platform/terms?platform=All%20Platforms#channel">this</a></p>
          </div>
      </div>

      <div class="button-group">
        <button id="join" type="submit" class="btn btn-primary">Join call</button>
        <button id="leave" type="button" class="btn btn-primary" disabled>Leave</button>
        <button id="mute-audio" type="button" class="btn btn-primary btn-sm">Mute Audio</button>
        <button id="mute-video" type="button" class="btn btn-primary btn-sm">Mute Video</button>
      </div>
    </form>

    <div class="row video-group">
      <div class="col">
        <p id="local-player-name" class="player-name"></p>
        <div id="local-player" class="player"></div>
      </div>
	   <div class="col">
        <div id="remote-playerlist"></div>
      </div>
      <div class="w-100"></div>
      <!--div class="col">
        <div id="remote-playerlist"></div>
      </div-->
    </div>
  </div>

  <script src="../assets/jquery-3.4.1.min.js"></script>
  <script src="../assets/bootstrap.bundle.min.js"></script>
  <script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>
  <script src="./basicMute.js"></script>
  <script>
		// A $( document ).ready() block.
		$( document ).ready(function() {
			console.log( "ready!" );
			document.getElementById('#local-player-name').innerHTML = "name";

		});
  </script>
</body>
</html>