<?php
if(!isset($_GET['messagebox'])) { //if request was not sent to server then exit;
	return false;
	exit();
}
$boxOption = (isset($_GET['selectBox'])) ? $_GET['selectBox'] : 'allMsg';
	/*require_once('model/user_model.php');
	require_once('model/inbox_model.php');
	$loggedon = $user->isLoggedOn();
	if($loggedon==true) {
		$userEmail = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null;
		$inbox = $msg->getunreadMessages($userEmail);
		$inboxCount = (!empty($inbox)) ? count($inbox) : 0;
	}*/
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Inbox</title>
		<style type="text/css">
			/*body {
				background-color: #eee;
				font-family: 'Roboto', sans-serif;
			}*/
			.container-item {
				background-color: rgba(150, 206, 180, 0.5);
				width: 100%;
				max-width: 550px;
				margin: 15px auto;
				margin-top: 50px;
				padding: 18px;
				border-radius: 10px;
				box-shadow: inset 5px 5px 6px rgba(255, 255, 255, 0.4), 
							inset -4px -4px 6px rgba(255, 255, 255, 0.4), 
							3px 3px 8px rgba(0, 0, 0, 0.4);
			}
			#btn {
				background-color: #3BB9FF; /*98AFC7*/
				color: #fff;
				border-radius: 3px;
				padding: 10px 20px 10px 20px;
				border: thin solid #82CAFF; /*3BB9FF*/
				font-family: sans-serif, helvetica;
				font-weight: bold;
				vertical-align: right;
				cursor: pointer; 
				/*left: 225px;*/
				/*position: relative;*/
			}
			#btn:hover {
				background-color: #96ceb4; /*#82CAFF;*/
				cursor: pointer;
			}
			#btn:active {
				background-color: #588c7e; /*#C2DFFF;*/
				color: #ccc; /*#777;*/
				outline: 0;
				cursor: pointer;
			}
			#btn:disabled {
				background-color: #C2DFFF;
				color: #ccc;
			}
			#progress {
				visibility: hidden;
				color: #fff;
				font-family: sans-serif;
				margin-top: 15px;
			}
			.errorMsg {
				color: #ea4335;
				font-weight: bolder;
			}
			.pop-upload-hide {
		      animation: popoff 1.8s ease forwards;
		    }
		    @keyframes popoff {
		      0%  { transform: translateY(50px) }
		      100%{ transform: translateY(-300px) }
		    }
		    .top-navigation {
				max-width: 100%;
				margin: 0 auto;
				margin-left: -10px;
		    }
		    .top-navigation > ul > li {
		    	/*max-width: 100%;*/
		    	/*margin: 0 auto;*/
		    	list-style-type: none;
		    	display: inline-block;
		    	padding: 10px 10px;
		    	background-color: #77a8a8;
		    	color: #fff;
		    	border: 0.1px solid #bbb;
		    	border-radius: 4px;
		    	margin-bottom: 5px;
		    }
		    .navigation-menu > li:hover {
		    	background-color: #96c3b4;
		    	color: #fff;
		    	border: 0.1px solid #96c3b4;
		    	cursor: pointer;
		    }
		    .navigation-menu > li:active {
		    	background-color: #96c3b4;
		    	color: #0F7DC2;
		    }
		    #manageMsgBox { /*The Message-box*/
		    	max-height: 350px; /*400px;*/
		    	overflow-y: auto;
		    }
		    .message-item {
		    	max-width: 100%;
		    	max-height: 100px;
		    	/*max-width: 500px;*/
		    	margin: 0 auto;
		    	background-color: #ddd;
		    	padding: 10px;
		    	border-bottom: thin solid #bbb;
		    	margin-top: 5px;
		    	box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
		    	overflow: hidden;
				text-overflow: ellipsis;
				white-space: nowrap;
				word-wrap: break-word;
		    }
		    .message-item-unread { /*for unread messages*/
		    	max-width: 100%;
		    	max-height: 100px;
		    	/*max-width: 500px;*/
		    	margin: 0 auto;
		    	background-color: #bbb;
		    	padding: 10px;
		    	border-bottom: thin solid #bbb;
		    	margin-top: 5px;
		    	box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
		    	overflow: hidden;
		    }
		    .message-item-unread:hover {
		    	background-color: #eee; /*#eee;*/
		    	cursor: pointer;
		    }
		    .message-item:hover {
		    	background-color: #ccc;
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
		    	margin-top: 10px;
		    	color: #777;
		    }
		    .received_date {
		    	float: right;
		    }
		    .delete-item {
		    	position: relative;
		    	top: -10px;/*-74px;*/
		    	left: 10px;/*368px;*/
		    	max-width: 100%;
		    	font-size: 1.2em;
		    	font-weight: bolder;
		    	background-color: #96ceb4;
		    	border-radius: 1px;
		    	padding: 1px 10px;
		    	color: #fff;
		    	cursor: pointer;
		    	float: right;
		    }
		    .delete-item:hover {
		    	background-color: #B0B0B0;
		    }
		    .delete-item:active {
		    	background-color: #82CAFF;
		    	color: #eee;
		    }
		    .read-item {
		    	position: relative;
		    	top: -10px;
		    	left: 5px;
		    	max-width: 100%;
		    	font-size: 1.2em;
		    	font-weight: bolder;
		    	background-color: #96ceb4;
		    	border-radius: 1px;
		    	padding: 1px 10px;
		    	color: #fff; /*#034f84;*/
		    	cursor: pointer;
		    	float: right;
		    }
		    .openedreadMessage { /*set css class for opening inbox message for reading*/
		    	max-width: 100%;
		    	max-height: 270px;
		    	height: 270px;
				margin: 0 auto;
		    	background-color: #f5f5f5;
		    	overflow-y: auto;
		    	padding: 10px;
		    }
		    .backToInbox {
		    	background-color: #96ceb4;/* #bbb;*/
		    	color: #fff;
		    	padding: 4px 10px;
		    	cursor: pointer;
		    }
		    .backToInbox:hover {
		    	background-color: #ccc; /*#96ceb4;*/
		    }
		    #navigate {
		    	position: relative;
		    	top: -9.8px;
		    	/*left: -6px;*/
		    	width: 100%;
		    	background-color: #77a8a8;/*#ccc;*/
		    	color: #fff;
		    	font-weight: bolder;
		    }
		    .close-item {
		    	position: absolute;
		    	top: 5px;
		    	right: 10px;
		    	cursor: pointer;
		    	font-size: 18px;
		    	color: #fff;
		    }
		    .close-item:hover {
		    	color: #eea29a;
		    }
		    .close-item:active {
		    	color: #c94c4c;
		    }
		</style>

		<script type="text/javascript">

				function _(id) {
					return document.getElementById(id);
				}
				function retrieveMessages(thisEvent) { //this function to retrieve messages for Inboxes unread, read, all and archived
					$('#manageMsgBox').removeClass('openedreadMessage');
					/*_('progress').style.visibility = 'visible';
					_("progress").innerHTML = "<center><span class='fa fa-refresh fa-pulse'></span> loading...</center>";*/
					//reset navigation colors before changing them
					$('.navigation-menu li').css('background-color','#77a8a8');
					$('.navigation-menu li').css('color','#fff');
					//now set new colors for active navigation button
					$('#'+thisEvent).css('background-color','#96c3b4');
					$('#'+thisEvent).css('color','#0F7DC2');
					var type = "POST"; //method to send data
					var url = "message_controller.php"; //URL PHP file that will receive data
					var formdata = thisEvent+"=true"; //data string that will be passed as URL parameters
					var xhr; //prepare variable to assign object data
					//create ajax XMLHttpRequest object,to be able to use its methods/functions
					xhr = new XMLHttpRequest();
					//open to prepapre connection to a webserver for syncronizing data
					xhr.open(type, url, true);
					//Set content-type header information for sending url encoded variable in the request
					xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					//Access the onreadystatechange event for the XMLHttpRequest object
					xhr.onreadystatechange = function() {
						if(xhr.readyState == 4 && xhr.status == 200) {
							if(xhr.responseText != "") {
								_("manageMsgBox").innerHTML = xhr.responseText; //write retrieved data to this Div
								_('progress').style.visibility = 'hidden';
							} else {
								_("progress").innerHTML = "<center><span class='errorMsg animated flash'>Failed to get messages.</span></center>";
							}
						}
						if(xhr.statu == 404) {
							_("progress").innerHTML = "<center><span class='errorMsg animated flash'>Error: 404 url not found.</span></center>";
						}
					}
					//now send HTTP request data to PHP and wait for server response
					xhr.send(formdata);
				} /* onClick Event Function END */

				setTimeout(function() { //delay loading function that retrieve messages
					retrieveMessages('<?php echo $boxOption; ?>'); //get messages and parameters passed may vary according to event that triggered/invoked it.
				}, 1000);

				function archiveItem(dated, tiggeredEvent) { //handles for setting message item to arhive
					var remove = confirm('Remove this message?');
			    	if(remove==false) {
			    		return false; //if cancel is clicked then don't process, and confirmation
			    	}
			    	else { //else then run event
						var message_dated = dated;
						var dataString = {"archiveMsg":"true", "message_date":message_dated};
						$.ajax({
							type: "POST",
							url: "message_controller.php",
							data: dataString,
							beforeSend: function() {
								_('progress').style.visibility = 'visible';
								_('progress').innerHTML = "<center>Removing message...</center>";
							},
							success: function(data) {
								_('progress').innerHTML = "<center>Message has been archived!...</center>";
								setTimeout(function() {
									retrieveMessages(tiggeredEvent);
								}, 1000);
							},
							error: function() {
								_('progress').style.visibility = 'hidden';
								alert("Error: 404 url not found!.");
							}
						});
					} /*else END*/
				}

				function restoreItem(dated) { //handles the restoring of message from archive message box handler
					var remove = confirm('Restore this message?');
			    	if(remove==false) {
			    		return false;
			    	}
					var message_dated = dated;
					var dataString = {"restoreMsg":"true", "message_date":message_dated};
					$.ajax({
						type: "POST",
						url: "message_controller.php",
						data: dataString,
						beforeSend: function() {
							_('progress').style.visibility = 'visible';
							_('progress').innerHTML="<center>Restoring message...</center>";
						},
						success: function(data) {
							_('progress').innerHTML="<center>Message has been restored!...</center>";
							setTimeout(function() {
								retrieveMessages('archives');
							}, 1000);
						},
						error: function() {
							_('progress').style.visibility = 'hidden';
							alert("Error: 404 url not found!.");
						}
					});
				}

				function viewMessage(dated, urlEvent) { //handles opening to see full message
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
							var topNav = '<p id="navigate"><span onclick="getMsgBox(\''+urlEvent+'\')" class="glyphicon glyphicon-arrow-left backToInbox" title="Go back to'+boxUrl+'"></span>'+boxUrl+'</p>';
							tempHtmlData = '';
							var tempHtmlData = $(this).html(); //get all from message to hold temporarily
							$(this).parent().addClass('openedreadMessage'); //add this class to prepare a view for full email display
							$(this).parent().html(topNav+tempHtmlData); //then write all the data to 
							tempHtmlData = ''; //then empty the temporary variable
							//openedMessage(dated);
							setTimeout(function() {
								//delay process 500miliseconds before updating database.
								//The function openedMessage() will only be invoked to update DB, that's for unread Inbox messages, else don't call the function.
								(urlEvent=='unreadMsg' || urlEvent == 'allMsg') 
								? openedMessage(dated) 
								: _('progress').style.visibility = 'visible'; _('progress').innerHTML = "<center>Viewing Message...</center>";
							}, 500);
							return false;
						});
					});
				}

				function getMsgBox(urldata) { //return to the same message box handler
					return retrieveMessages(urldata); //then call the message retrieval handler
				}

				function openedMessage(msgDate) { //opening and updating message as read
					_('progress').style.visibility = 'visible';
					_('progress').innerHTML = "<center>Loading Message...</center>";
					$.ajax({
						type: "POST",
						url: "message_controller.php",
						data: {"markRead":"true", "message_date":msgDate},
						beforeSend: function() {
							_('progress').innerHTML = "<center><span class='fa fa-refresh fa-pulse'></span> Opening message...</center>";
						},
						success: function(data) {
							if(data=="true") {
								_('progress').innerHTML = "<center>Reading Message...</center>";
								updateInboxCount(); //call this function to update number of unread messages, after opening a single smessage.
							}
							else {
								_('progress').innerHTML = "<center>Some error occured!...</center>";
							}
						}
					});
				}
				//updateInboxCount();
				/*function updateInboxCount() { //get number of inbox Msgs count them
					$.get("message_controller.php", {"countInbox":"true"}, function(data) {
						$('#countInbox').html(data);
					});
				}
				updateInboxCount(); //call function, on first load of window to show number of inbox Msgs*/

				$('.cancel, .close-item').click(function(event) { //close message-box window
			      $('#container').addClass('pop-upload-hide'); //remove
			      $('.layer').delay(500).fadeOut('slow'); 
			    });

		</script>
	</head>
	<body>
        <!-- <nav class="navbar navbar-dark bg-inverse" style="background-color: #e3f2fd;">
          <li><a href="newwelcome.php">&larr;Back</a></li>
        </nav> -->
		<div class="container-item animated slideInDown" id="container">
			<!-- <div class="top-navigation">
				<span class="fa fa-times close-item" title="Close"></span>
				<ul class="navigation-menu">
					<li onclick="retrieveMessages('unreadMsg');" id="unreadMsg"><span class="fa fa-envelope"></span> Inbox (<span class="countInbox"></span>)</li>
					<li onclick="retrieveMessages('readMsg');" id="readMsg"><span class="fa fa-envelope-open-o"></span> Read Messages</li>
					<li onclick="retrieveMessages('allMsg');" id="allMsg"><span class="fa fa-envelope-o"></span> All Messages</li>
					<li onclick="retrieveMessages('archives');" id="archives"><span class="fa fa-archive"></span> Archives</li>
				</ul>
			</div> -->
			<div id="manageMsgBox">
				<!--  -->
				<div class="message-item-unread" onclick="viewMessage('<?php echo $unreadMsg->created; ?>', 'unreadMsg');">
					<i class="delete-item" title="remove message" onclick="archiveItem('<?php echo $unreadMsg->created; ?>','unreadMsg');">&times;</i>
					<!-- <img src="<?php #echo $profileImage; ?>" class="message-photo"/> -->
					<div class="senderNames">
						<?php echo $names="Lucky Molefe"; ?>
						<span style="float: right;font-weight: lighter;" class="received_date"><?php echo  $created="2017"; ?></span>
						<!-- <span style="float: right;font-weight: lighter;" class="received_date"><?php echo date('D, d M \a\t H:i A', strtotime($unreadMsg->created)); ?></span> -->
					</div>
					<div class="senderEmail">
						<?php echo $sender_email="test@email.com"; ?>
					</div>
					<div class="messageBody"><?php echo $message="some message test"; ?></div>
					<!-- <span class="read-item" title="open message"><i class="fa fa-envelope"></i></span> -->
				</div>

			</div>
			<div id="progress"></div>
			<div align="right">
				<button type="reset" id="btn" class="cancel" title="Close"><span class="fa fa-times"></span> Close</button>
			</div>
		</div>
	</body>
</html>
