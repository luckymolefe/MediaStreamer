<?php
  if(file_exists('controller.php') && is_file('controller.php')) {
    require_once('controller.php');
  } else {
    echo "Failed to include neccessary file!.";
  }
  $model->page_protected();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Streamer | Notification</title>
  <!-- <link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css"> -->
  <!-- <link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css"> -->
  <!-- // <script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script> -->
  <!-- // <script type="text/javascript" src="../boostrap3/js/bootstrap.min.js"></script> -->
  <style type="text/css">
    .container-item {
      font-family: helvetica, arial;
      width: 600px;
      max-width: 100%;
      max-height: 500px;
      margin: 0 auto;
      margin-top: 50px;
      background: rgba(255, 255, 255, 0.6);
      padding: 15px;
      border: thin solid #bce8f1;
      border-radius: 5px;
      box-shadow: 1px 2px 6px rgba(0, 0, 0, 0.4);
      overflow: hidden;
      overflow-y: visible;
    }
    .heading {
      text-align: center;
      margin-top: -10px;
      font-size: 20px;
      /*background-color: #146eb4;*/
      color: #fff;
      padding: 10px;
      border-radius: 5px 5px 0 0;
      margin: -15px -15px 10px -15px;
    }
    .form-control {
      width: 100%;
      margin-bottom: 12px;
    }
    .input-control:focus {
      border-color: #66afe9;
      outline: 0;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
    }
    .input-control {
      max-width: 100%;
      width: 100%;
      margin: 0 auto;
      padding: 15px 0 10px 0;
      border: thin solid #bbb;
      border-radius: 3px;
      text-indent: 10px;
      color: #555;
      font-size: 0.98em;
      font-weight: bold;
      resize: none;
    }
    .input-label {
      font-weight: bold;
      color: #777;
    }
    .btn-submit {
      width: 150px;
      /*background: #146eb4; linear-gradient( #b1cbbb, #d9ecd0, #b1cbbb); /*#c5d5cd*/
      /*color: #fff;  #77a8a8;*/
      padding: 15px;
      border: thin solid #46b8da;
      border-radius: 3px;
      font-size: 0.98em;
      font-weight: bold;
      color: #fff;
      background-color: #5bc0de;
      border-color: #46b8da;
    }
    .btn-submit:hover {
      background: #4285f4; /*#ded;
      color: #fff; /*#77a8a8;*/
      cursor: pointer;
    }
    .btn-submit:active {
      background: #146eb4; /*#d9ecd0;*/
      color: #fff;
      border: thin solid #c5d5cd;
    }
    .btn-submit.btn-block {
      width: 100%;
    }

    .btn-reset {
      width: 150px;
      background: #146eb4; /*linear-gradient( #b1cbbb, #d9ecd0, #b1cbbb);*/ /*#d9ecd0;*/
      color: #fff; /*#77a8a8;*/
      padding: 12px;
      border: thin solid #d7d7d7;
      border-radius: 5px;
      font-weight: bold;
    }
    .btn-reset:hover {
      background: #4285f4;/*#ded;*/
      color: #fff; /*#77a8a8;*/
      cursor: pointer;
    }
    .btn-reset:active {
      background: transparent; /*#d9ecd0;*/
      color: #77a8a8;
      border: thin solid #c5d5cd;
    }
    hr {
      margin-top: 20px;
      margin-bottom: 20px;
      border: 0;
      border-top: 1px solid #eee;
    }
    .close {
      position: relative;
      left: 590px;
      top: -10px;
      color: #ccc;
      cursor: pointer;
      font-size: 1em;
      font-weight: bold;
      /*float: right;*/
    }
    .close:hover {
      color: #777;
    }
    #progress, #invalid, #failed, #success {
      color: #777;
      padding: 10px;
    }
    .message-item {
      max-width: 580px;
      /*max-width: 100%;*/
      max-height: 100px;
      /*max-width: 580px;*/
      margin: 0 auto;
      background: -webkit-linear-gradient(#f5f5f5, #e0e0e0);
      padding: 10px;
      border-bottom: thin solid #bbb;
      border-radius: 5px;
      margin-top: 5px;
      box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      word-wrap: break-word;
    }
    .message-item:hover {
      background: #e8e8e8;
      cursor: pointer;
    }
    .message-photo {
      max-width: 100%;
      border-radius: 5px;
      width: 35px;
      float: left;
      margin-right: 10px;
    }
    .senderNames {
      font-weight: bolder;
      color: #777;
    }
    .senderEmail {
      color: #777;
      font-size: .85em;
    }
    .messageBody {
      width: 100%;
      margin-top: 10px;
      /*margin-left: 45px;*/
      color: #777;
    }
    .received_date {
      float: right;
    }
    .close-item {
      background-color: #ea4335;
      border-radius: 5px;
      padding: 2px 8px;
      float: right;
      margin-top: -10px;
      margin-right: -9px;
      cursor: pointer;
      font-size: 18px;
      font-weight: bold;
      color: #fff;
    }
    .close-item:hover {
      background-color: #fff;
      color: #ea4335;
    }
  </style>
  <script type="text/javascript">
    $(function() {
      /*$('#progress, #invalid, #failed, #success').hide();
      $('#change').click(function() {
        if($('input[type="password"]').val() == "") {
          return alert("Please enter password!");
        }
        if($('#password').val() != $('#passwordConfirm').val()) {
          return alert("Your password do not match!");
        }
        var uid, password, passwordConfirm;
        uid = $('#sess_uid').val().trim();
        password = $('#password').val().trim();
        passwordConfirm = $('#passwordConfirm').val().trim();
            $.ajax({
              type: "POST",
              url: "controller.php",
              data: {"changepassword":"true", "sess_uid":uid, "password":password, "password_confirm":passwordConfirm},
              cache: false,
              beforeSend: function() {
                $('#progress').addClass('alert-info').html("<center><span class='fa fa-spinner fa-pulse'></span><strong> Please wait...</strong></center>").show();
              },
              success: function(data) {
                alert(data);
                $('#progress').removeClass('alert-info').hide();
                var jason = JSON.parse(data);
                if(jason.message == "invalid") {
                  $('#invalid').show();
                }
                if(jason.message == "failed") {
                  $('#failed').show();
                }
                if(jason.message == "success") {
                  $('#success').show();
                  setTimeout(function(){
                    $('.layer').hide();
                  }, 5000);
              }
              },
              error: function() {
                $('#progress').addClass('alert-danger').removeClass('alert-info').html("<center><span class='fa fa-warning'></span><strong>Error: 404 url not found.</strong></center>").show();
              }
            });
        });
      $('.close').click(function() {
        $('.layer').html('').fadeOut('slow');
      });*/
        $('.close').click(function() {
          $('.layer').html('').fadeOut('slow');
        });
    });

    function viewMessage(action, urlEvent) { //handles opening to see full message
      $('.message-item, .message-item-unread').each(function(e) { //loop over message-items for read and unread messages
        $(this).on('click', function() { //on mouse btn up
          var boxUrl = ""; //set variable to null, prepare it to hold values.
          if(urlEvent=="unreadMsg") {
            boxUrl = " Unread Messages"; //if equal to unread then assing value unread to, prepare to display.
          } 
          else if(urlEvent=="readMsg") {
            boxUrl = " Read Messages";
          }
          else if(urlEvent=="allMsg") {
            boxUrl = " All Messages";
          }
          else if(urlEvent=="archives") {
            boxUrl = " Archived Messages";
          }
          var topNav = '<p id="navigate"><span onclick="getMsgBox(\''+urlEvent+'\')" class=fa fa-arrow-left backToInbox" title="Go back to'+boxUrl+'"></span>'+boxUrl+'</p>';
          tempHtmlData = '';
          var tempHtmlData = $(this).html(); //get all from message to hold temporarily
          $(this).parent().addClass('openedreadMessage'); //add this class to prepare a view for full email display
          $(this).parent().html(topNav+tempHtmlData); //then write all the data to
          // $(this).parent().parent().css('background-color','#fff');
          action.parentNode.style.color = "#fff";
          tempHtmlData = ''; //then empty the temporary variable

          /*setTimeout(function() {
            //delay process 500miliseconds before updating database.
            //The function openedMessage() will only be invoked to update DB, that's for unread Inbox messages, else don't call the function.
            (urlEvent=='unreadMsg' || urlEvent == 'allMsg') 
            ? openedMessage(dated) 
            : _('progress').style.visibility = 'visible'; _('progress').innerHTML = "<center>Viewing Message...</center>";
          }, 500);*/
          return false;
        });
      });
    }
  </script>
</head>
  <body>
    <div class="container-item popIn">
      <div type="button" class="close" title="Close"><span class="fa fa-close"></span></div>
      <p class="heading">Notifications</p>
      <!-- <hr> -->
      <form action="contactdetails.php" method="POST" enctype="application/forms-url-encoded">
        <!-- message item -->
        <div class="box-item">
          <div class="message-item" onclick="viewMessage('unreadMsg');">
            <i class="close-item" title="remove message" onclick="archiveItem();">&times;</i>
            <img src="backgrounds/img_avatar_male.png" class="message-photo"/>
            <div class="senderNames">
              Terry Forde <span style="float: right;font-weight: lighter;" class="received_date">2017-11-25</span>
            </div>
            <div class="senderEmail">tforde@email.com</div>
            <div class="messageBody">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat
            </div>
          </div>
        </div>
      </form>
    </div>
    <div>
      <div id="progress" class="alert"></div>
    </div>
  </body>
</html>