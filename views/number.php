

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 95.6vh;
            display: flex;
            align-items: center;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
        }

        h3 {
            color: #2c3e50;
            font-size: 32px;
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
        }

        h3::after {
            content: '';
            display: block;
            width: 60%;
            height: 3px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            position: absolute;
            bottom: -10px;
            left: 20%;
        }

        pre {
            font-family: 'Segoe UI', sans-serif;
            white-space: pre-wrap;
            line-height: 1.8;
            color: #505965;
            margin: 30px 0;
            font-size: 16px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
        }

        a {
            display: inline-block;
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 16px;
        }

        a:first-child {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        a:last-child {
            background: linear-gradient(90deg, #2c3e50 0%, #3498db 100%);
        }

        a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .gcash-number {
            font-size: 28px;
            color: #2c3e50;
            background: #f8f9fa;
            padding: 15px 30px;
            border-radius: 50px;
            display: inline-block;
            margin: 20px 0;
            font-weight: bold;
            border: 2px dashed #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>GCash Payment Details</h3>
        <div class="gcash-number">09942689414</div>
        <pre>
This is our Gcash number send your payment here for advance payment, and full payment. 
For cash payment, just dial the number above to tell that you have to pay in cash if needed (optional).

Click the link below for you to send the proof of your payment. 
Thank you!!</pre>
        <div class="button-group">
            <a href="../pages/process.php">Continue to Payment Verification</a>
            <a href="../pages/roombooking.php">Back to Cash Payment Reservation</a>
        </div>
    </div>
</body>
</html>