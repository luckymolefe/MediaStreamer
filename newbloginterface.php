<?php
  if(file_exists('controller.php') && is_file('controller.php')) {
    require_once('controller.php');
    require_once('post_controller.php');
    require_once('notify_controller.php');
  } else {
    echo "Failed to include neccessary file!.";
  }
  $model->page_protected();
  $row = (isset($_SESSION['auth']['uid'])) ? $model->readProfile($_SESSION['auth']['uid']) : null;
  if(!$model->isUserActive($row['email'])) {
    header("Location: verify");
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Media Streamer</title><!-- Menu with Diagonal canvas -->
  <!-- <link rel="stylesheet" type="text/css" href="boostrap3/css/bootstrap.min.css"> -->
  <link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css">
  <script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script>
  <!-- // <script type="text/javascript" src="boostrap3/js/bootstrap.min.js"></script> -->
  <style type="text/css">
    body {
      background: url('backgrounds/breakdance_cover.jpg') no-repeat;
      background-attachment: fixed;
      background-position: center top;
      /*background-size: 100%; */
      max-width: 100%;
      margin: 0 auto;
    }
    body::-webkit-scrollbar {
      width: .75em;
    }
    body::-webkit-scrollbar-track {
      box-shadow: inset;
      background-color: #ccc;
    }
    body::-webkit-scrollbar-thumb {
      background: -webkit-linear-gradient(360deg, #10527B 30%, #10325E); /*#5bc0de rgba(0, 0, 0, 0.8);*/ /*darkgrey;*/
      outline: 1px solid slategrey;
      border-radius: 10px;
    }
    .menu {
      position: fixed; /*fixed=notMove, absolute=moveAlong(static)*/
      width: 100%;
      height: 76px;
      margin: 0 auto;
      top: 0px;
      left: 0px;
      border: thin solid none;
      background: linear-gradient(360deg, #10527B 30%, #10325E);
      color: #fff;
      box-shadow: 1px 4px 6px rgba(0, 0, 0, 0.5);
      z-index: 3;
      /*opacity: 0.5;*/
      /*padding-top: 15px;*/
    }

    .menu:before {
      content: ' ';
      position: absolute;
      width: 50%;
      margin: 0 auto;
      left: 300px;
      top: 70px;
      border: 65px solid ;
      border-color: #10527B transparent transparent transparent;
      /*filter: drop-shadow(5px 2px 4px red);*/
      border-radius: 0 0 10px 10px;
      /*background: -webkit-linear-gradient(90deg, #10527B 20%, #10527B, #5bc0de);*/
      outline: 0;
      z-index: 3;
    }

    /*this is for temporary logo*/
    .menu:after { 
      position: absolute;
      content: '\f260'; /*put any logo-icon here*/
      font-family: 'FontAwesome';
      max-width: 100%;
      margin: 0 auto;
      top: 5px;
      left: 710px;
      font-size: 8em;
      z-index: 3;
    }
    @media all and (max-width: 690px) {
      .menu:after {
        left: 20px; /*@screen go small resize to position best fit*/
        font-size: 5em;
        top: 0px;
      }
    }
    nav > span > ul > li {
      display: inline-block;
      border-radius: 50%;
      border: thin solid #fff;
      padding: 6px 8px;
      margin-left: 12px;
      font-size: 1.5em;
    }
    nav > span > ul > li:hover {
      cursor: pointer;
      background-color: #fff;
      color: #10527B;
    }
    .pull-right {
      position: relative;
      left: -50px;
      margin-right: 15px;
    }
    .pull-right > i {
      padding: 0 4px;
    }
    .container {
      width: 850px;
      max-width: 100%;
      margin: 0 auto;
      margin-top: 70px;
      /*margin-left: 340px;*/
      /*margin-right: 380px;*/
    }
    .menu-bar {
      position: fixed;
      top: 18px;
      right: 20px;
      margin: 0 auto;
      font-size: 1.5em;
      background-color: #10527B;
      color: #fff;
      z-index: 3;
      border: thin solid #fff;
      border-radius: 5px;
      padding: 5px 10px;
      cursor: pointer;
      display: none;
      visibility: hidden;
    }
    .menu-bar:hover {
      background-color: #eee;
      color: #10527B;
    }
    .menu-bar:active {
      color: #46b8da;
    }
    .drop-menu {
      position: fixed;
      top: 60px;
      width: 95%;
      max-width: 100%;
      /*background-color: #f5f5f5;*/
      background: -webkit-linear-gradient(#10527B, #10325E);
      color: #d7d7d7;
      border-radius: 0 0 5px 5px;
      padding: 10px;
      z-index: 1;
      display: none;
      box-shadow: 1px 4px 5px rgba(0, 0, 0, 0.5);
      font-family: helvetica, arial;
    }
    .drop-menu > li {
      /*width: 100%;*/
      /*margin-top: 5px;*/
      margin-left: -10px;
      margin-right: -10px;
      font-size: 20px;
      padding: 5px;
      text-indent: 5px;
    }
    .drop-menu > li:hover {
      background-color: #46b8da; /*#d7d7d7;*/
      color: #fff;
      cursor: pointer;
    }
    @media all and (max-width: 1200px) {
      .menu:after {
        left: 550px; /*@screen go small resize to position best fit*/
        /*font-size: 5em;
        top: 0px;*/
      }
    }
    @media screen and (max-width: 1152px) {
      .container {
        margin-left: 180px;
        width: 800px;
      }
    }
    @media screen and (max-width: 980px) {
      .container {
        margin-left: 200px;
        margin-top: 20px;
        width: 600px;
      }
      .menu:before {
        display: none;
      }
      nav > span > ul > li {
        display: none;
      }
      .menu-bar {
        display: block;
        visibility: visible;
      }
      .menu:after {
        font-size: 5em;
        top: 0;
        left: 20px;
      }
    }
    @media screen and (max-width: 820px) {
      .container {
        width: 500px;
        margin-left: 140px;
      }
    }
    @media screen and (max-width: 650px) {
      .container {
        /*margin-left: 0px;*/
        width: 450px;
      }
      .user-icon {
        visibility: hidden;
      }
      .content:after {
        display: none;
      }
    }
    @media screen and (max-width: 580px) {
      .container {
        margin-left: 0px;
        width: 500px;
      }
    }
    @media screen and (max-width: 480px) {
      .container {
        margin-left: 0px;
        width: 400px;
      }
    }

    /*.post {
      margin-bottom: 30px;
    }*/
    .post > li {
      display: inline;
    }
    
    .content {
      position: relative;
      width: auto;
      height: auto;
      margin: 0 auto;
      background: rgba(0, 0, 0, 0.5);
      border-radius: 5px;
      padding: 10px 0px;
    }
    .content:after {
      content: ' ';
      position: absolute;
      width: 0;
      top: 25px;
      left: -44px;
      border: 22px solid;
      border-color: transparent rgba(0, 0, 0, 0.5) transparent transparent;
    }
    .content video, img {
      max-width: 100%;
      margin: 0 auto;
    }
    video {
      width: 100%;
      height: 250px;
      /*margin-bottom: 10px;*/
    }
    audio {
      width: 100%;
      /*margin-bottom: 10px;*/
    }
    .user-icon {
      position: relative;
      left: -130px;
      top: 95px;
      color: #5bc0de;
      border: 3px solid #fff;
      border-radius: 50%;
      padding: 10px 20px;
    }
    .user-icon:hover {
      color: #eee;
      border: 3px solid #5bc0de;
    }
   
    .media {
      background-color: #000;
      /*padding: 15px 0;*/
      margin-bottom: 10px;
    }
    .user-name {
      font-family: helvetica, arial;
      font-size: 1.5em;
      color: #5bc0de;
      padding-left: 15px;
    }
    .nick-name {
      font-family: helvetica, arial;
      font-size: 0.99em;
      color: #999;
      margin-bottom: 15px;
      padding-left: 15px;
    }
    .nick-name > .pull-right {
      margin-right: -15px;
    }
    ul {
      list-style-type: none;
    }
    .post-text {
      font-family: helvetica, arial;
      font-size: 1.2em;
      color: #fff;
      margin-bottom: 15px;
      padding-left: 15px;
    }
    .bottom-bar {
      color: #fff;
    }
    .bottom-bar > span {
      margin-right: 15px;
      margin-left: 15px;
      font-size: 1.5em;
    }
    .bottom-bar > span:hover {
      color: #5bc0de;
      cursor: pointer;
    }
    .bottom-bar > .pull-right {
      margin-left: 45px;
      margin-right: -15px;
    }
    sub {
      font-family: helvetica, arial;
      font-size: 15px;
    }
    .mediaFileName {
      font-family: helvetica, arial;
      color: #fff;
      font-weight: bolder;
    }
    .layer {
      position: fixed;
      top: 0;
      left: 0;
      max-width: 100%;
      margin: 0 auto;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      color: #fff;
      font-family: arial, helvetica;
      z-index: 6;
      display: none;
    }
    .loader {
      position: relative;
      font-size: 10em;
      top: 35%;
      left: 45%;
      text-align: center;
    }
    .popup { /*message popup*/
      position: absolute;
      top: 20px;
      margin-left: -13px;
      background-color: #ea4335;
      color: #fff;
      border-radius: 50%;
      font-family: arial, helvetica;
      font-size: 0.55em;
      padding: 2px 6px;
    }
    .message-alert {
      width: 800px;
      max-width: 100%;
      margin: 0 auto;
      background-color: #4285f4;
      color: #fff;
      margin-top: 20%;
      text-align: center;
      padding: 15px 10px;
      border-radius: 5px;
      font-family: helvetica, arial;
      font-size: 20px;
    }
    .side-menu {
      position: fixed;
      top: 50px;
      left: -320px;
      width: 280px;
      height: 1500px;
      overflow: hidden;
      max-width: 100%;
      background-color: rgba(245, 245, 245, 0.6);
      color: #777;
      border-radius: 0 0 5px 5px;
      padding: 10px;
      z-index: 2;
      display: block;
      box-shadow: 4px 1px 5px rgba(0, 0, 0, 0.5);
      font-family: helvetica, arial;
    }
    .side-menu > li {
      /*width: 100%;*/
      /*margin-top: 5px;*/
      margin-left: -10px;
      margin-right: -10px;
      font-size: 20px;
      padding: 15px;
      text-indent: 5px;
      background: linear-gradient(360deg, #10527B 30%, #10325E);
      color: #fff;
      /*background-color: #10325E; /*#d7d7d7;*/*/
    }
    .side-menu > li:hover {
      /*background-color: #777;*/
      background: linear-gradient(360deg, #777 30%, #d7d7d7);
      color: #10325E;
      cursor: pointer;
    }
    .side-menu > li > span {
      float: right;
    }

    /*test*/
    .loading {
      width: 400px; /*set size of loader-bar length*/
      max-width: 85vw;
      height: 4px;
      position: absolute;
      bottom: 55vh;
      left: 50%;
      border-radius: 4px;
      background-color: #ddd; /*rgba(100, 100, 100, 0.5);*/
      transform: translate(-50%, -50%);
      overflow: hidden;
      display: none;
      font-family: arial, helvetica;
    }
    .loading:after {
      content: '';
      display: block;
      width: 1px; /*control the size of a loading bar starting point to end point*/
      height: 4px;
      background: -webkit-linear-gradient(360deg, #46b8da 30%, #10325E) #4285fa; /*#ccc;*/
      animation: load 10s linear;
    }
    @keyframes load {
      0% {
        width: 0;
      }
      10% {
        width: 5%;
      }
      20% {
        width: 15%;
      }
      30% {
        width: 25%;
      }
      40% {
        width: 30%;
      }
      50% {
        width: 44%;
      }
      60% {
        width: 50%;
      }
      70% {
        width: 72%;
      }
      80% {
        width: 84%;
      }
      90% {
        width: 92%;
      }
      100% {
        width: 100%;
      }
    }
    /*logo position ontop of loader progress bar*/
    .logo:before {
      content: '\f260';
      font-family: 'FontAwesome';
      position: absolute;
      max-width: 100%;
      margin: 0 auto;
      top: -130px;
      font-size: 8em;
      /*-webkit-transform: rotateZ(-10deg);*/
      background: -webkit-linear-gradient(#10527B, #46b8da);
      border-radius: 50%;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      /*color: -webkit-radial-gradient(#10527B, #10325E);*/ /*#46b8da;*/
      -webkit-animation: spin 2s ease forwards infinite; /*now call the animation to animate this embedded logo*/
    }
    /*animate logo to spin while progress bar loading*/
    @keyframes spin {
        0% { }
      100% { -webkit-transform: rotateY(360deg); }
    }
    .react {
      -webkit-animation: puff 0.75s ease-in-out;
    }
    /* animate element on click once-off */
    @keyframes puff {
      0% {
        -webkit-transform: scale(1.0);
      }
      100% {
        -webkit-transform: scale(2.2);
        opacity: 0;
      }
    }
    .streamer {
      position: fixed;
      /*top: 690px;*/
      bottom: -349px;
      background-color: #46b8da;
      width: 100%;
      height: 350px;
      z-index: 4;
    }
    .shutter {
      position: relative;
      top: -34px;
      left: 5px;
      background: -webkit-linear-gradient(#10527B, #46b8da);
      color: #fff;
      padding: 2px 10px;
      border-radius: 5px 5px 0 0;
      cursor: pointer;
      font-family: arial, helvetica;
      font-size: 1.8em;
    }
    .shutter:hover {
      background: #46b8da;
      color: #10527B;
    }
    #mediastream {
      width: 400px;
      max-width: 100%;
      margin: 0 auto;
      background-color: #4ce;
    }
    .pageSlider {
      position: fixed;
      right: 0px;
      bottom: 0px;
      z-index: 4;
      background-color: rgba(0, 0, 0, 0.6);
      color: #fff;
      padding: 8px 10px;
      border-radius: 5px;
      font-size: 18px;
      display: none;
    }
    .pageSlider:hover {
      background-color: rgba(255, 255, 255, 0.6);
      color: #4285f4;
      cursor: pointer;
    }
    .circle_loader {
      position: absolute;
      top: 45%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100px;
      height: 100px;
      border-radius: 50%;
      border: 10px solid #46b8da; /* #10527B , #10325E, #46b8da */
      border-top: 10px solid #10527B;
      animation: animate 0.8s infinite linear; 
    }
    @keyframes animate {
      0% {
        transform: translate(-50%, -50%) rotate(0deg);
      }
      100% {
        transform: translate(-50%, -50%) rotate(360deg);
      }
    }
    .notification-bar {
      position: fixed;
      width: 380px;
      max-width: 100%;
      top: 15%;
      left: 35%;
      margin: 0 auto;
      background-color: #96ceb4;
      font-family: arial, helvetica;
      font-weight: bold;
      color: #f5f5f5;
      border-radius: 5px;
      padding: 15px;
      display: none;
      z-index: 5;
    }
    .notify-quit {
      position: absolute;
      top: 0px;
      right: 0px;
      background-color: #b5e7a0;
      color: #588c7e;
      font-size: 25px;
      padding: 12.5px;
      border-radius: 0 5px 5px 0;
    }
    .notify-quit:hover {
      /*background-color: #588c7e;*/
      color: #e42;
      cursor: pointer;
    }
    @media screen and (max-width: 680px) {
      .notification-bar {
        left: 100px;
      }
    }
    @media all and (max-width: 530px) {
      .notification-bar {
        left: 0px;
      }
    }
    @media all and (max-width: 430px) {
      .notification-bar {
        width: 280px;
      }
    }
    .popIn {
      animation: pop .3s ease-in forwards;
    }
    @keyframes pop {
      0%   {transform: scale(0); opacity: 0; }
      90%   {transform: scale(1.1); opacity: 0.55; }
      100% {transform: scale(1); opacity: 1; }
    }
    .popOut {
      animation: zoomOut .3s ease forwards;
    }
    @keyframes zoomOut {
      0%   { transform: scale(1); opacity: 1; }
      100% { transform: scale(0); opacity: 0; }
    }
  </style>
  
  <script type="text/javascript">
    
    $(function() {
      /*$('audio, video').prop("volume", 0.3); //setting audio volume for both Video and Audio

      $('video').hover(function(event) {
        if( event.type === "mouseenter" ) {
          $(this).attr('controls', 'controls');
        }
        else if(event.type === "mouseleave") {
          $(this).removeAttr('controls');
        }
      });*/
      /*$('.mediaFileName').each(function(e) {
        var videoURL = $('.web-video').attr('src'); //get full file path
        videoURL = videoURL.substr(12); //remove directory path containing videos files
        videoName = videoURL.replace('.mp4',''); //remove file extension
        videoName = videoName.replace(new RegExp('_', 'g'), ' '); //remove all underscores
        //then write the name of the video
        $(this).html(videoName);
      });*/
      
      /*progress loading...*/
      $('.layer').css('background','rgba(255, 255, 255, 0.9)');
      $('.layer').html('<div style="position: relative;display: block;top: 40%;left: 45%;color: #46b8da;"><i class="logo"></i> Loading media posts!...</div><div class="loading"></div>').show();
      $('.loading').show();
      $('.streamer').hide();
      setTimeout(function() {
        $('.layer').css('background',' rgba(0, 0, 0, 0.6)');
        $('.layer').hide();
        $('.loading').hide();
        $('.streamer').show();
      }, 10000);

      $('.logout').click(function() {
        if(confirm('Want to logout?') == false) {
          return false;
        }
        var tokendata = $(this).data('token');
        $.ajax({
          type: "POST",
          url: "controller.php",
          data: {"logout":"true", "token":tokendata},
          cache: false,
          beforeSend: function() {
            $('.layer').html("<span class='fa fa-spinner fa-pulse loader'></span>").show();
          },
          success: function(data) {
            var jason = JSON.parse(data);
            $('.layer').hide();
            $('body').css('background','#fff'); //set body-background to white
            $('body').html('').addClass('circle_loader'); //add animation
            window.open(jason.url,'_self'); //then redirect page
          },
          error: function() {
            $('.layer').html("<span class='fa fa-spinner fa-pulse loader'></span>").hide();
          }
        });
      });

      $('.Settings').click(function() {
        $.get("settings.php", {"changepass":"true"}, function(responsedata) {
          $('.layer').html(responsedata).show();
          $('.drop-menu').hide();
          $('.side-menu').animate({'left':'-320px'}, 1000);
          setDefaults();
        });
      });
      $('.Profile').click(function() {
        $.get("profile.php", {"updateprofile":"true"}, function(responsedata) {
          $('.layer').html(responsedata).show();
          $('.drop-menu').hide();
          $('.side-menu').animate({'left':'-320px'}, 1000);
          setDefaults();
        });
      });
      $('.Upload').click(function() {
        $.get("upload.php", {"upload":"true"}, function(responsedata) {
          $('.layer').html(responsedata).show();
          $('.drop-menu').hide();
          $('.side-menu').animate({'left':'-320px'}, 1000);
          setDefaults();
        });
      });
      $('.playlist').click(function() {
        $.get("playlist.php", {"loadplaylist":"true"}, function(responsedata) {
          $('.layer').html(responsedata).show();
          $('.drop-menu').hide();
          $('.side-menu').animate({'left':'-320px'}, 1000);
          setDefaults();
        });
      });
      $('.grouplist').click(function() {
        $.get('followers.php', {"groupadd":"true"}, function(data) {
          $('.layer').html(data).show();
          $('.drop-menu').hide();
          $('.side-menu').animate({'left':'-320px'}, 1000);
          setDefaults();
        });
      });
      $('.openinbox').click(function() {
        $.get("notification.php", {"messagebox":"true"}, function(responsedata) {
          $('.layer').html(responsedata).show();
          $('.drop-menu').hide();
          $('.side-menu').animate({'left':'-320px'}, 1000);
          setDefaults();
        });
      });
      //slide menu from top
      /*$('.menu-bar').click(function() {
        $('.drop-menu').slideToggle('slow');
      });*/
      //slide menu from left
      $('.menu-bar').click(function() {
        if( $('.side-menu').position().left == "-320" ) {
          $('.side-menu').animate({'left':'0px'}, 500);
          $('.menu-bar').css('background-color','#fff');
          $('.menu-bar').css('color','#46b8da');
          $('.comment-panel').html(''); //close comment panel if open
        }
        else {
          $('.side-menu').animate({'left':'-320px'}, 1000);
          $('.menu-bar').css('background-color','#10527B');
          $('.menu-bar').css('color','#fff');
        }
      });
      function setDefaults() { //set css back to defaults 
        $('.menu-bar').css('background-color','#10527B');
        $('.menu-bar').css('color','#fff');
      }

      $('.shutter').click(function() {
        if( $('.streamer').position().top == "690" ) { /*690*/
          $('.streamer').animate({'top':'450px'}, 300); /*450*/
          $('.shutter').html('<i title="Close" class="fa fa-angle-double-down"></i>');
        }
        else {
          $('.streamer').animate({'top':'690px'}, 1000);
          $('.shutter').html('<i title="Open" class="fa fa-angle-double-up"></i>');
          $('#mediastream').html(''); //clean medialoader on exit
        }
      });
      
    });
    function doCronJob() {
      $.get("post_controller.php",{"getposts":"true"}, function(data) {
          $('.post').html(data); //get posts
      });
      checkNotification();
      getFeedNotification();
    }
    setTimeout(function() {
      doCronJob(); //delay callback function before loading, post items
    }, 9500);
    function objectPush() { //push item popup messages
      var i = document.getElementById('counterTimer');
      if(i === null) {
        return false;
      }
      if (parseInt(i.innerHTML) == 0) {
         i.innerHTML = 60;
         doCronJob(); //get and run all objects added to cronJob, populate data
         return 0;
      }
      i.innerHTML = parseInt(i.innerHTML) - 1;
    }
    setInterval(function(){ objectPush(); }, 1500);

    function addToList(action) {
      var add = confirm('Add this to playlist?');
      if(add == false) {
        return false;
      }
      var mediaURL = action.dataset.url; //media_url
      var userID = action.dataset.uid; //user_id
      var mediaID = action.dataset.mid; //media_id
      $.get("post_controller.php",{"playlistAdd":"true","user_id":userID, "media_id":mediaID, "media_url":mediaURL}, function(data) {
        var jason = JSON.parse(data);
          if(jason.message == "success") {
            action.classList.add('react');
            action.style.color = "#5bc0de"; //change color
            setTimeout(function() { //delay 100milisec before showing 1
              action.childNodes[0].innerHTML = '1'; //set value to 1
            }, 1000);
            // alert("Item added to playlist successfully!");
          }
          else {
            alert(jason.message);
          }
      });
    }
    function react(action) {
      var add = confirm('You love this?');
      if(add == false) {
        return false;
      }
      var userID = action.dataset.uid; //user_id
      var mediaID = action.dataset.mid; //media_id
      $.get("post_controller.php", {"like":"true", "user_id":userID, "media_id":mediaID}, function(responsedata) {
        var jason = JSON.parse(responsedata);
          if(jason.message == "success") {
            action.classList.add('react');
            action.style.color = "#5bc0de"; //change color
            setTimeout(function() {
              action.childNodes[0].innerHTML = '1'; //set value to 1
              // alert("Item added to playlist successfully!");
            }, 1000);
          }
          else {
            alert(jason.message);
          }
      });
    }
    function addComment(action) {
      $('.comment-panel').html('');
      var userID = action.dataset.uid;
      var mediaID = action.dataset.mediaid;
      $.get("comment.php", {"comment":"true", "uid":userID, "media_id":mediaID}, function(data) {
        action.parentNode.children[0].innerHTML = ""; //set to empty element first
        action.parentNode.children[0].innerHTML = data; //then add new data to element
      });
    }
    //on post comment button click, run this
    function postComment() {
      var uid = document.getElementById('user_id').value;
      var media_id = document.getElementById('media_id').value;
      var commentText = document.getElementById('commentText').value;
      if(commentText=="") {
        alert("Please type your comment!");
        return false;
      }
      $('#commentText').attr('disabled','true');
      $('#post_comment').attr('disabled','true');
      $('#post_comment').html('<i class="fa fa-spinner fa-pulse"></i> Posting comment...');
      // $('#post_comment').children('i').removeClass('fa-comments').addClass('fa-spinner fa-pulse');
      $('.close-item').attr('onclick','');
      $.post("post_controller.php", {"comment":"true", "uid":uid, "media_id":media_id, "comment_data":commentText}, function(responsedata) {
        var jason = JSON.parse(responsedata);
          if(jason.message == "success") {
            setTimeout(function() {
              $('.comment-panel').html('');
              alert("Comment posted successfully!");
            }, 1000);
          }
          else {
            alert("Failed to post comment!");
          }
      });
    }
    //
    function showComments(action) {      
      var uid = action.dataset.uid;
      var mediaId = action.dataset.mid;
      $.get("viewcomments.php", {"view":"true", "mediaid":mediaId, "uid":uid}, function(data) {
        $('.layer').show();
        $('.layer').html(data);
      });
    }
    function checkNotification() {
      var email = "<?php echo $row['email']; ?>";
      $.get("notify_controller.php", {"notify":"true", "email":email}, function(data) {
        // alert(data);
        var jason = JSON.parse(data);
        if(jason.response == "OK") {
          $('#notification-bar').html('<span class="fa fa-close notify-quit" title="Close" data-unset="unsetMessage" onclick="closeMsgPop(this)"></span>');
          if($('#notification-bar').hasClass('popOut')) {
            $('#notification-bar').removeClass('popOut').addClass('popIn');
          }
          $('#notification-bar').prepend(jason.message).show();
        }
      });
    }

    //run when user made new post, notify all followers
    function setFeedNotify() {
      var email = "<?php echo $row['email']; ?>";
      $.post("notify_controller.php", {"setfeednotify":"true", "email":email}, function(data) {
        //data returns void;
      });
    }
    //run to check for followed users post notification
    function getFeedNotification() {
      var uid = "<?php echo $_SESSION['auth']['uid']; ?>";
      $.get("notify_controller.php", {"getfeednotify":"true", "uid":uid}, function(data) {
        var jason = JSON.parse(data);
        if(jason.response == "OK") {
          $('#notification-bar').html('<span class="fa fa-close notify-quit" title="Close" data-unset="unsetNotify" onclick="closeMsgPop(this)"></span>');
          if($('#notification-bar').hasClass('popOut')) {
            $('#notification-bar').removeClass('popOut').addClass('popIn');
          }
          $('#notification-bar').prepend(jason.message).show();
        }
      });
    }
    //
    function unsetFeedNotify() {
      var uid = "<?php echo $_SESSION['auth']['uid']; ?>";
      $.get("notify_controller.php", {"notifyFeedUnset":"true", "uid":uid}, function(data) {
        //data returns void;
      });
    }
    //run function to unset notification message popup
    function unsetNotification() {
      var email = "<?php echo $row['email']; ?>";
      var followerID = $('#follower').attr('data-followerID');
      $.get("notify_controller.php", {"messageUnset":"true", "email":email, "followerId":followerID}, function(data) {
        //data returns void;
      });
    }
    //close comment popup window
    function closePopup(action) {
      action.parentNode.parentNode.innerHTML = "";
      // alert(action.parentNode.parentNode.className);
    }
    function callMediaPlayer() { //callback function to toggle open mediaplayer
      $('.streamer').animate({'top':'450px'}, 500);
      $('.shutter').html('<i title="Close" class="fa fa-angle-double-down"></i>');
    }
    $(window).scroll(function() {
      if($(window).scrollTop() + $(window).height() >= $('.post').height()) {
        $('.pageSlider').show().addClass('react');
        return false;
      }
      if($(window).scrollTop() + $(window).innerHeight <= (document).height()) {
        $('.pageSlider').hide();
        return false;
      }
    });
    function slidePager() {
      window.scrollBy({top: -650, left: 0, behavior: 'smooth'});
    }
    function closeMsgPop(action) { //close notification message popup
      $('#notification-bar').removeClass('popIn').addClass('popOut').delay(500).queue(function() {
        $('#notification-bar').hide();
      });
      var action = action.dataset.unset;

      switch(action) {
        case "unsetMessage":
          // alert("Message Unset Activated");
          unsetNotification(); //call function onClose message_popup
        break;
        case "unsetNotify":
          // alert("notification Unset Activated");
          unsetFeedNotify();
        break;
      }
      
    }
  </script>
</head>
<body>
  <span id="counterTimer">60</span> <!-- counter timer for autoload -->
  <div id="notification-bar" class="notification-bar popIn"> <span class="fa fa-close notify-quit" title="Close" onclick="closeMsgPop()"></span></div>
  <div class="layer"></div>
  <nav>
    <span class="menu">
      <ul>
        <!-- <li title="Shares"><i class="fa fa-share-alt"></i></li> -->
        <li class="openinbox" title="Messages"><i class="fa fa-envelope-o"></i></li><!-- <span class="popup">1</span> -->
        <li class="grouplist" title="Followers list"><i class="fa fa-users"></i></li>
        <li class="playlist" title="myMusic playlist"><i class="fa fa-music"></i></li>
        <li class="Upload" title="Media Upload"><i class="fa fa-sellsy"></i></li>

        <li data-token="<?php echo $_SESSION['auth']['token']?>" title="Logout" class="logout pull-right"><i class="fa fa-power-off"></i></li> <!-- fa-sign-put -->
        <li title="Settings" class="Settings pull-right"><i class="fa fa-cog"></i></li>
        <li title="Profile" class="Profile pull-right"><i class="fa fa-user-o"></i></li>
      </ul>
    </span>
    <span class="menu-bar fa fa-bars" title="Toggle Menu"></span>
    <ul class="drop-menu">
      <li title="Shares"><i class="fa fa-share-alt"></i> Share</li>
      <li class="openinbox" title="Messages"><i class="fa fa-envelope-o"></i> Messages</li>
      <li class="grouplist" title="Followers list"><i class="fa fa-users"></i> Followers</li>
      <li class="playlist" title="myMusic playlist"><i class="fa fa-music"></i> myMusic Playlist</li>
      <li class="Upload" title="Media Upload"><i class="fa fa-sellsy"></i> Media Upload</li>
      <hr>
      <li class="Profile" title="Profile" ><i class="fa fa-user-o"></i> Profile</li>
      <li class="Settings" title="Settings" ><i class="fa fa-cog"></i> Settings</li>
      <li class="logout" data-token="<?php echo $_SESSION['auth']['token']?>" title="Logout"><i class="fa fa-power-off"></i> Logout</li> <!-- fa-sign-put -->
    </ul>

    <ul class="side-menu">
      <li><i class="fa fa-user-circle fa-3x pull-left"></i> <div><?php echo $row['firstname'].' '.$row['lastname']; ?> <br><span style='font-size:15px;margin-left:7px;'><?php echo $row['email']; ?></span></div></li>
      <!-- <li title="Shares"><i class="fa fa-share-alt"></i> Share <span class="fa fa-chevron-right"></span></li> -->
      <li class="openinbox" title="Messages"><i class="fa fa-envelope-o"></i> Messages <span class="fa fa-chevron-right"></span></li>
      <li class="grouplist" title="Followers list"><i class="fa fa-users"></i> Followers <span class="fa fa-chevron-right"></span></li>
      <li class="playlist" title="myMusic playlist"><i class="fa fa-music"></i> myMusic Playlist <span class="fa fa-chevron-right"></span></li>
      <li class="Upload" title="Media Upload"><i class="fa fa-sellsy"></i> Media Upload <span class="fa fa-chevron-right"></span></li>
      <!-- <hr> -->
      <li class="Profile" title="Profile" ><i class="fa fa-user-o"></i> Profile <span class="fa fa-chevron-right"></span></li>
      <li class="Settings" title="Settings" ><i class="fa fa-cog"></i> Settings <span class="fa fa-chevron-right"></span></li>
      <li class="logout" data-token="<?php echo $_SESSION['auth']['token']?>" title="Logout"><i class="fa fa-power-off"></i> Logout</li> <!-- fa-sign-put -->
    </ul>
  </nav>

  <div class="container">
    <div class="post">
        <div style="margin-top:400px;" class='message-alert'><center><span class='fa fa-refresh fa-pulse'></span> Posts loading!...</center></div>
    </div><!-- END posts -->
  </div><!-- END container -->

  <div id="streamer" class="streamer">
    <span class="shutter"><i title="Open" class="fa fa-angle-double-up "></i></span>
      <h1 style="margin-top: -20px;text-align:center; color:#fff;font-family:arial;">Media Player</h1>
      <div id="mediastream" align="center">
        <?php
          $sourcefile = (isset($_REQUEST['sourceurl'])) ? urldecode($_REQUEST['sourceurl']) : null;
          $sourcefile = str_replace("_", " ", $sourcefile);
          if($sourcefile != null) {
            $info = pathinfo($sourcefile);
            if($info['extension'] == 'mp3') {
        ?>
              <audio controls="true" autoplay="false" volume="0.3" preload="auto"><source src="mediauploads/<?php echo $sourcefile; ?>"/></audio>
        <?php } else if($info['extension'] == 'mp4') { ?>
              <video controls="true" autoplay="false" volume="0.2" preload="auto"><source src="mediauploads/<?php echo $sourcefile; ?>"/></video>
        <?php
            }  
          }
        ?>
      </div>
  </div>
  <span class="fa fa-chevron-up pageSlider" title="To top" onclick="slidePager()"></span>
</body>
</html>