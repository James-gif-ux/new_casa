<?php
 include 'nav/admin_sidebar.php'; 
 require_once '../model/server.php';
  $connector = new Connector();
  $sql = "SELECT * FROM messages ORDER BY date_sent DESC";
  $messages = $connector->executeQuery($sql);

  $sql = "SELECT COUNT(*) as unread_count FROM messages WHERE status = 0";
  $result = $connector->executeQuery($sql);
  $row = $result->fetch(PDO::FETCH_ASSOC);
  $unread_count = ($row && isset($row['unread_count'])) ? $row['unread_count'] : 0;

  // Check if there are new messages


  // When a new message is sent
  $unread_count++;

  // When an existing message is read
  $unread_count--;
  if ($unread_count < 0) {
      $unread_count = 0;
  }
  // Update the SQL query to include date comparison
  $sql = "SELECT m.*, r.reply_content, r.date_sent as reply_date,
  CASE 
      WHEN m.date_sent >= NOW() - INTERVAL 1 DAY 
      AND m.viewed_status = 0 THEN 1 
      ELSE 0 
  END as is_new 
  FROM messages m 
  LEFT JOIN replies r ON m.message_id = r.message_id
  WHERE m.status IN ('unread', 'read') 
  ORDER BY m.date_sent DESC";
$messages = $connector->executeQuery($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <style>
        .chat-container {
            margin-top: 100px;
            max-width: 1585px;
            margin-left: 30px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            display: flex;
            height: calc(100vh - 150px);
            overflow: hidden;
        }

        .message-list {
            width: 400px;
            border-right: 1px solid #e0e0e0;
            overflow-y: auto;
            background: #f8f9fa;
            border-top-left-radius: 25px;
            border-bottom-left-radius: 25px;
            height: 100%;
            padding: 20px;
        }

        .message-preview {
            padding: 2px;
            border: 1px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 12px;
            margin-bottom: 15px;
            background: white;
            position: relative;
        }

        .message-preview:hover {
            background: #f5f5f5;
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .message-preview.unread {
            background: #e3f2fd;
            border-left: 4px solid #1976d2;
        }

        .message-preview.read {
            opacity: 0.8;
        }

        .message-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            background: #fff;
            border-top-right-radius: 25px;
            border-bottom-right-radius: 25px;
        }

        .sender-info {
            font-size: 14px;
            color: #1976d2;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            font-weight: 600;
        }

        .message-subject {
            font-weight: 600;
            margin-bottom: 8px;
            color: #263238;
            font-size: 16px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .message-text {
            color: #546e7a;
            font-size: 14px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .message-time {
            font-size: 12px;
            color: #78909c;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .message-status {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .status-new {
            background: #4caf50;
        }

        .status-unread {
            background: #1976d2;
        }

        .status-read {
            background: #9e9e9e;
        }

        .reply-form {
            margin-top: 30px;
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .reply-form textarea {
            width: 100%;
            padding: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            resize: none;
            margin-bottom: 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .reply-form textarea:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25,118,210,0.1);
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        .reply-btn, .delete-btn {
            padding: 12px 24px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .reply-btn {
            background: #1976d2;
            color: white;
        }

        .delete-btn {
            background: #ef5350;
            color: white;
        }

        .reply-btn:hover, .delete-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="message-list">
            <?php foreach ($messages as $message): ?>
            <div class="message-preview <?php 
                echo ($message['is_new'] === '0' || $message['status'] === 0) ? 'unread' : 'read';
            ?>" 
                data-message-id="<?php echo htmlspecialchars($message['message_id']); ?>"
                onclick="showMessage(
                    '<?php echo htmlspecialchars($message['message_id']); ?>', 
                    '<?php echo htmlspecialchars($message['recipient_email']); ?>', 
                    '<?php echo htmlspecialchars($message['subject']); ?>', 
                    '<?php echo htmlspecialchars($message['message_content']); ?>', 
                    '<?php echo isset($message['reply_content']) ? htmlspecialchars($message['reply_content']) : ''; ?>'
                )">
                <div class="message-time"><?php echo date('M d, H:i', strtotime($message['date_sent'])); ?></div>
                <div class="sender-info">
                    <span class="status-indicator <?php 
                        echo ($message['is_new'] == 1) ? 'status-new' : 
                            (($message['status'] === 0) ? 'status-unread' : 'status-read'); 
                    ?>"></span>
                    <?php echo htmlspecialchars($message['recipient_email']); ?>
                </div>
                <div class="message-subject"><?php echo htmlspecialchars($message['subject']); ?></div>
                <div class="message-text"><?php echo substr(htmlspecialchars($message['message_content']), 0, 100) . '...'; ?></div>
                <div class="message-status">
                    <?php if ($message['is_new'] == 1): ?>
                        <span style="color: #4caf50;">New</span>
                    <?php elseif ($message['status'] === 0): ?>
                        <span style="color: #1976d2;">Unread</span>
                    <?php else: ?>
                        <span style="color: #9e9e9e;">Read</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="message-content" id="messageContent">
            <h2>Select a message to view</h2>
        </div>
    </div>
</div>>

    <script>
    // Update the showMessage function
        function showMessage(messageId, email, subject, content, replyContent) {
        const messageContent = document.getElementById('messageContent');
        
        let replySection = '';
        if (replyContent && replyContent !== 'null') {
            replySection = `
                <div class="message-text reply-message" style="margin: 20px 0; padding: 16px; background: #e3f2fd; border-radius: 15px;">
                    <div style="font-weight: bold; margin-bottom: 8px;">Admin Reply:</div>
                    ${replyContent}
                </div>
            `;
        }
        
        messageContent.innerHTML = `
            <h3 style="color: #263238; margin-bottom: 16px; border-radius: 10px;">${subject}</h3>
            <div class="sender-info">From: ${email}</div>
            <div class="message-text" style="margin: 20px 0; padding: 16px; background: #f8f9fa; border-radius: 15px;">
                ${content}
            </div>
            ${replySection}
            <div class="reply-form">
                <form onsubmit="return sendReply('${messageId}', '${email}')">
                    <textarea placeholder="Write a reply..." required rows="4"></textarea>
                    <button type="submit" class="reply-btn">Send Reply</button>
                    <button type="button" class="delete-btn" onclick="deleteMessage('${messageId}')">Delete</button>
                </form>
            </div>
        `;

        const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
        if (messageElement) {
            messageElement.classList.remove('new-message');
        }

        markAsRead(messageId);
    }

    function markAsRead(messageId) {
        // Create FormData object
        const formData = new FormData();
        formData.append('message_id', messageId);

        // Update read status
        fetch('../model/update_message_status.php', {
            method: 'POST',
            body: formData
        });

        // Update viewed status
        fetch('../model/update_message_new_status.php', {
            method: 'POST',
            body: formData
        });
    }

    function deleteMessage(messageId) {
        if(confirm('Are you sure you want to delete this message?')) {
            window.location.href = '../pages/messagesdelete.php?message_id=' + messageId + '&action=delete';
        }
    }

    // Add this in the <head> section
    
    
    // Update the sendReply function
    function sendReply(messageId, email) {
        const textarea = document.querySelector('.reply-form textarea');
        const replyContent = textarea.value;
    
        if (!replyContent.trim()) {
            return false;
        }
    
        const formData = new FormData();
        formData.append('message_id', messageId);
        formData.append('reply_content', replyContent);
        formData.append('recipient_email', email);
    
        fetch('../model/send_reply.php', {  // Updated path to match your existing endpoint
            method: 'POST',
            body: formData
        })
        .then(response => {
            textarea.value = '';
            Swal.fire({
                icon: 'success',
                title: 'Reply Sent!',
                text: 'Your message has been sent to ' + email,
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                position: 'top-end',
                toast: true,
                background: '#4caf50',
                color: '#ffffff',
                iconColor: '#ffffff'
            }).then(() => {
                location.reload();
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Failed to send reply. Please try again.',
                confirmButtonColor: '#ef5350'
            });
        });
    
        return false;
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
