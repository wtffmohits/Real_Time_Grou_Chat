<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}
$currentUser = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Chatbox</title>
  <!-- Include jQuery for AJAX -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <style>
    /* Dark themed page styling */
    body {
      margin: 0;
      padding: 20px;
      font-family: Arial, sans-serif;
      background: #1f2c3a;
      display: flex;
      flex-direction: column;
      align-items: center;
      color: #fff;
    }
    h2 {
      margin-bottom: 10px;
    }
    /* Chat Container */
    #chatbox {
      width: 100%;
      max-width: 600px;
      height: 400px;
      background: #2b3747;
      border-radius: 10px;
      padding: 10px;
      overflow-y: auto;
      margin-bottom: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.4);
    }
    /* Message Bubble Styling */
    .message-bubble {
      padding: 10px 15px;
      margin: 5px;
      border-radius: 15px;
      max-width: 80%;
      word-wrap: break-word;
      font-size: 14px;
      line-height: 1.4;
      clear: both;
      color: #fff;
    }
    .message-left {
      background-color: #3A4352;
      float: left;
      border-top-left-radius: 0;
    }
    .message-right {
      background-color: #229ED9;  /* Blue color for current user */
      float: right;
      border-top-right-radius: 0;
    }
    .message-username {
      font-weight: bold;
      margin-bottom: 5px;
      color: #cfd8e2;
    }
    /* Message input field */
    #messageInput {
      padding: 10px;
      margin: 5px 0;
      border: 1px solid #444;
      border-radius: 5px;
      width: 400px;
      background: #2b3747;
      color: #fff;
    }
    /* Send button styling */
    #sendButton {
      padding: 10px 20px;
      border: none;
      background: #0088cc;
      color: #fff;
      border-radius: 5px;
      cursor: pointer;
    }
    #sendButton:hover {
      background: #006fa6;
    }
  </style>
  <script>
    // Set current user from PHP session
    const currentUser = "<?php echo $currentUser; ?>";

    // Function to load messages via AJAX; auto-scroll only if user is near the bottom
    function loadMessages() {
      var chatbox = $('#chatbox');
      var isNearBottom = (chatbox[0].scrollHeight - chatbox.scrollTop() - chatbox.outerHeight()) < 50;
      $.ajax({
        url: 'get_messages.php',
        dataType: 'json',
        success: function(data) {
          let html = '';
          data.forEach(function(msg) {
            let bubbleClass = (msg.username === currentUser)
                              ? 'message-bubble message-right'
                              : 'message-bubble message-left';
            html += `
              <div class="${bubbleClass}">
                <div class="message-username">${msg.username}</div>
                <div class="message-text">${msg.message}</div>
              </div>
            `;
          });
          chatbox.html(html);
          if(isNearBottom) {
            chatbox.scrollTop(chatbox[0].scrollHeight);
          }
        }
      });
    }

    $(document).ready(function(){
      loadMessages();
      setInterval(loadMessages, 2000);

      $('#sendButton').click(function(){
        let message = $('#messageInput').val().trim();
        if(message === ""){
          alert("Please enter a message.");
          return;
        }
        $.ajax({
          type: 'POST',
          url: 'send_message.php',
          data: { message: message },
          success: function(response){
            console.log("Response:", response);
            $('#messageInput').val('');
            loadMessages();
          }
        });
      });

      $('#messageInput').keypress(function(e){
        if(e.which === 13){
          $('#sendButton').click();
        }
      });
    });
  </script>
</head>
<body>
  <h2>Welcome, <?php echo $currentUser; ?></h2>
  <div id="chatbox"></div>
  <input type="text" id="messageInput" placeholder="Type your message here..." />
  <button id="sendButton">Send</button>
</body>
</html>
