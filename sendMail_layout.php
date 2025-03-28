    <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Send Email</title>
            <style>
                body {
                    background-color: #f5f5f5;
                    font-family: 'Arial', sans-serif;
                }
                
                .container {
                    max-width: 600px;
                    margin: 170px auto;
                    background-color: #ffffff;
                    border-radius: 15px;
                    padding: 50px;
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
                }

                h2 {
                    text-align: center;
                    font-size: 32px;
                    margin-bottom: 30px;
                    color: #2c1810;
                    font-family: 'Georgia', serif;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }

                label {  
                    font-size: 14px;
                    font-weight: 500;
                    color: #555;
                    margin-bottom: 8px;
                    display: block;
                }

                input[type="email"],
                input[type="text"],
                textarea {
                    width: 100%;
                    padding: 12px;
                    margin-bottom: 15px;
                    border: 2px solid #e0e0e0;
                    border-radius: 6px;
                    font-size: 15px;
                    box-sizing: border-box;
                    transition: all 0.3s ease;
                    background-color: #fafafa;
                }

                input[type="email"]:focus,
                input[type="text"]:focus,
                textarea:focus {
                    border-color: #8b5e3c;
                    background-color: #fff;
                    outline: none;
                    box-shadow: 0 0 5px rgba(139, 94, 60, 0.2);
                }

                textarea {
                    height: 120px;
                    resize: vertical;
                }

                .error-message {
                    color: #dc3545;
                    font-size: 13px;
                    margin-bottom: 12px;
                }

                button[type="submit"] {
                    background-color: #8b5e3c;
                    color: white;
                    padding: 15px 25px;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 16px;
                    width: 100%;
                    transition: all 0.3s ease;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    font-weight: bold;
                }

                button[type="submit"]:hover {
                    background-color: #6d4b2f;
                    transform: translateY(-2px);
                }

                @media (max-width: 480px) {
                    .container {
                        margin: 20px;
                        padding: 25px;
                    }

                    h2 {
                        font-size: 24px;
                        margin-bottom: 20px;
                    }
                }
            </style>
        </head>
            <body>
                <div class="container">
                    <h2>FeedBack</h2>
                        <form id="emailForm" action="send_mail.php" method="post">
                            <label for="email">Recipient Email:</label>
                            <input type="email" name="email" required>
                            <div class="error-message"></div>

                            <label for="subject">Subject:</label>
                            <input type="text" name="subject" required>
                            <div class="error-message"></div>

                            <label for="message">Message:</label>
                            <textarea name="message" required></textarea>
                            <div class="error-message"></div>

                            <button type="submit">Send Email</button>
                        </form>
                </div>
                <script>
                    document.getElementById('emailForm').addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        try {
                            const formData = new FormData(this);
                            
                            const response = await fetch('send_mail.php', {
                                method: 'POST',
                                body: formData
                            });

                            const data = await response.json();
                            
                            if (data.success) {
                                alert(data.message);
                                this.reset(); // Clear form
                            } else {
                                throw new Error(data.message);
                            }

                        } catch (error) {
                            alert(error.message);
                            console.error('Error:', error);
                        }
                    });
                </script>
            </body>
        </html>
