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
  $sql = "SELECT * FROM messages 
      WHERE status IN ('unread', 'read') 
      ORDER BY date_sent DESC";
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
            height: calc(100vh - 30px);
            overflow: hidden;
        }

        .message-list {
            width: 360px;
            border-right: 1px solid #e4e6eb;
            overflow-y: auto;
            background: #f8f9fa;
            border-top-left-radius: 25px;
            border-bottom-left-radius: 25px;
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
            <div class="message-preview <?php echo ($message['status'] === '0' || $message['status'] === 0) ? 'unread' : ''; ?>"
                 onclick="showMessage('<?php echo htmlspecialchars($message['message_id']); ?>', 
                                    '<?php echo htmlspecialchars($message['recipient_email']); ?>', 
                                    '<?php echo htmlspecialchars($message['subject']); ?>', 
                                    '<?php echo htmlspecialchars($message['message_content']); ?>')">
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
    function showMessage(messageId, email, subject, content) {
        const messageContent = document.getElementById('messageContent');
        messageContent.innerHTML = `
            <h3 style="color: #263238; margin-bottom: 16px; border-radius: 10px;">${subject}</h3>
            <div class="sender-info">From: ${email}</div>
            <div class="message-text" style="margin: 20px 0; padding: 16px; background: #f8f9fa; border-radius: 15px;">${content}</div>
            <div class="reply-form">
                <form onsubmit="return sendReply('${messageId}', '${email}')">
                    <textarea placeholder="Write a reply..." required rows="4"></textarea>
                    <button type="submit" class="reply-btn">Send Reply</button>
                    <button type="button" class="delete-btn" onclick="deleteMessage('${messageId}')">Delete</button>
                </form>
            </div>
        `;
        markAsRead(messageId);
    }

    function markAsRead(messageId) {
        fetch('../model/update_message_status.php', {
            method: 'POST',
            body: new FormData().append('message_id', messageId)
        });
    }

    function deleteMessage(messageId) {
        if(confirm('Are you sure you want to delete this message?')) {
            window.location.href = '../pages/messagesdelete.php?message_id=' + messageId + '&action=delete';
        }
    }

    function sendReply(messageId, email) {
        // Implement your reply logic here
        return false;
    }
    </script>
</body>
</html>
