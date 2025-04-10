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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .chat-container {
            margin: 20px;
            margin-top: 100px;
            max-width: 1585px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            display: flex;
            height: calc(100vh - 150px);
            overflow: hidden;
        }

        .message-list {
            width: 350px;
            border-right: 1px solid #e0e0e0;
            overflow-y: auto;
            background: #ffffff;
            height: 100%;
        }

        .message-preview {
            padding: 15px;
            cursor: pointer;
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .message-preview:hover {
            background: #f8f9fa;
        }

        .message-preview.unread {
            background: #f0f7ff;
        }

        .message-preview.active {
            background: #e3f2fd;
        }

        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #e1e1e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #666;
            flex-shrink: 0;
        }

        .message-info {
            flex-grow: 1;
            min-width: 0;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 5px;
        }

        .sender-info {
            font-size: 15px;
            color: #2c3e50;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .message-time {
            font-size: 12px;
            color: #95a5a6;
            flex-shrink: 0;
        }

        .message-preview-content {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .message-subject {
            font-weight: 500;
            color: #34495e;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .message-text {
            color: #7f8c8d;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-left: auto;
            flex-shrink: 0;
        }

        .status-new { background: #2ecc71; }
        .status-unread { background: #3498db; }
        .status-read { background: #bdc3c7; }

        .message-content {
            flex: 1;
            padding: 30px;
            padding-top: 40px;
            overflow-y: auto;
            background: #fff;
            display: flex;
            flex-direction: column;
        }

        .reply-form {
            margin-top: auto;
            padding: 20px;
            border-top: 1px solid #f0f0f0;
            background: #ffffff;
        }

        .reply-form textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            resize: none;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        .reply-btn, .delete-btn {
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            border: none;
        }

        .reply-btn {
            background: #3498db;
            color: white;
        }

        .delete-btn {
            background: #e74c3c;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .chat-container {
                margin: 10px;
                margin-top: 90px;
                height: calc(100vh - 100px);
            }

            .message-list {
                width: 300px;
            }
        }

        @media (max-width: 768px) {
            .chat-container {
                flex-direction: column;
                height: calc(100vh - 80px);
                margin-top: 80px;
            }

            .message-list {
                width: 100%;
                height: 40%;
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
            }

            .message-content {
                height: 60%;
                padding: 15px;
                padding-top: 25px;
            }

            .reply-form {
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .chat-container {
                margin: 5px;
                margin-top: 70px;
                border-radius: 15px;
            }

            .message-preview {
                padding: 10px;
            }

            .avatar {
                width: 35px;
                height: 35px;
                font-size: 12px;
            }

            .sender-info {
                font-size: 13px;
            }

            .message-subject {
                font-size: 12px;
            }

            .message-text {
                font-size: 11px;
            }

            .reply-btn, .delete-btn {
                padding: 8px 16px;
                font-size: 14px;
            }
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
                <div class="avatar">
                    <?php echo strtoupper(substr($message['recipient_email'], 0, 2)); ?>
                </div>
                <div class="message-info">
                    <div class="message-header">
                        <div class="sender-info"><?php echo htmlspecialchars($message['recipient_email']); ?></div>
                        <div class="message-time"><?php echo date('M d, H:i', strtotime($message['date_sent'])); ?></div>
                    </div>
                    <div class="message-preview-content">
                        <div class="message-subject"><?php echo htmlspecialchars($message['subject']); ?></div>
                        <span class="status-indicator <?php 
                            echo ($message['is_new'] == 1) ? 'status-new' : 
                                (($message['status'] === 0) ? 'status-unread' : 'status-read'); 
                        ?>"></span>
                    </div>
                    <div class="message-text"><?php echo substr(htmlspecialchars($message['message_content']), 0, 50) . '...'; ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="message-content" id="messageContent">
            <h2>Select a message to view</h2>
        </div>
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

<?php include 'nav/admin_footer.php'; ?>