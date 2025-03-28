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
            height: calc(100vh - 150px); /* Adjusted height */
            overflow: hidden;
        }

        .message-list {
            width: 360px;
            border-right: 1px solid #e4e6eb;
            overflow-y: auto;
            background: #f8f9fa;
            border-top-left-radius: 25px;
            border-bottom-left-radius: 25px;
            height: 100%; /* Added fixed height */
            max-height: calc(100vh - 150px); /* Added max-height */
        }

        .message-preview {
            padding: 16px;
            border-bottom: 1px solid #e4e6eb;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 15px;
            margin: 5px;
        }

        .message-preview:hover {
            background: #fff;
            transform: translateX(4px);
            border-radius: 15px;
        }

        .message-preview.unread {
            background: #e3f2fd;
            font-weight: 600;
            position: relative;
            border-radius: 15px;
        }

        .message-preview.unread:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #1976d2;
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
        }

        .message-content {
            flex: 1;
            padding: 28px;
            overflow-y: auto;
            background: #fff;
            border-top-right-radius: 25px;
            border-bottom-right-radius: 25px;
            height: 100%; /* Added fixed height */
            max-height: calc(100vh - 150px); /* Added max-height */
        }

        .sender-info {
            font-size: 14px;
            color: #546e7a;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            border-radius: 10px;
            padding: 5px;
        }

        .message-subject {
            font-weight: 600;
            margin-bottom: 6px;
            color: #263238;
            font-size: 15px;
            border-radius: 10px;
            padding: 5px;
        }

        .message-text {
            color: #546e7a;
            font-size: 14px;
            line-height: 1.5;
            border-radius: 10px;
            padding: 5px;
        }

        .message-time {
            font-size: 12px;
            color: #78909c;
            margin-top: 4px;
            border-radius: 10px;
            padding: 5px;
        }

        .reply-form {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            margin-top: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .reply-form textarea {
            width: 100%;
            padding: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            resize: none;
            margin-bottom: 12px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .reply-form textarea:focus {
            outline: none;
            border-color: #1976d2;
        }

        .reply-btn {
            background: #1976d2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .reply-btn:hover {
            background: #1565c0;
        }

        .delete-btn {
            background: #ef5350;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            cursor: pointer;
            margin-left: 12px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .delete-btn:hover {
            background: #e53935;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="message-list">
            <?php foreach ($messages as $message): ?>
            <style>
                .message-preview.new-message {
                    position: relative;
                }

                .message-preview.new-message:after {
                    content: 'NEW';
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    background: #4caf50;
                    color: white;
                    padding: 2px 8px;
                    border-radius: 12px;
                    font-size: 11px;
                    font-weight: bold;
                }
            </style>
            <div class="message-preview <?php 
                echo ($message['is_new'] === '0' || $message['status'] === 0) ? 'unread ' : '';
                echo ($message['is_new'] == 1) ? 'new-message' : 'read';
            ?>" 
                data-message-id="<?php echo htmlspecialchars($message['message_id']); ?>"
                onclick="showMessage(
                    '<?php echo htmlspecialchars($message['message_id']); ?>', 
                    '<?php echo htmlspecialchars($message['recipient_email']); ?>', 
                    '<?php echo htmlspecialchars($message['subject']); ?>', 
                    '<?php echo htmlspecialchars($message['message_content']); ?>', 
                    '<?php echo isset($message['reply_content']) ? htmlspecialchars($message['reply_content']) : ''; ?>'
                )">

                <div class="sender-info"><?php echo htmlspecialchars($message['recipient_email']); ?></div>
                <div class="message-subject"><?php echo htmlspecialchars($message['subject']); ?></div>
                <div class="message-text"><?php echo substr(htmlspecialchars($message['message_content']), 0, 50) . '...'; ?></div>
                <div class="message-time"><?php echo date('M d, H:i', strtotime($message['date_sent'])); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="message-content" id="messageContent">
            <h2>Select a message to view</h2>
        </div>
    </div>

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
