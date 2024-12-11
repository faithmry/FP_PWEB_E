<?php
    session_start(); 

//cek error
if (isset($_SESSION['login_error'])) {
    $message = $_SESSION['login_error']; // store msg
    unset($_SESSION['login_error']); // clear msg
} else {
    $message = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
            background: url('https://designinglighting.com/wp-content/uploads/2021/01/Hoover-HS-3.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: linear-gradient(135deg, rgba(210, 180, 140, 0.9), rgba(160, 120, 80, 0.8));
            padding: 60px;
            border-radius: 20px;
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 500px;
            color: #333;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        h2 {
            margin-bottom: 30px;
            font-size: 32px;
            color: #4a342e;
            letter-spacing: 1px;
        }

        .input-group {
            margin-bottom: 25px;
            text-align: left;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #5c4538;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 5px 0 10px 0;
            border: none;
            border-radius: 12px;
            box-sizing: border-box;
            font-size: 16px;
            color: #333;
            background: #f7e8dc;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(160, 120, 80, 0.8);
        }

        button {
            padding: 18px 25px;
            background-color: #a0522d;
            color: #fff;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 20px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #8b4513;
            transform: scale(1.1);
        }

        button:active {
            transform: scale(1.05);
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
            color: red;
        }
    </style>
    <!-- Add reCAPTCHA script -->
    <script src="https://www.google.com/recaptcha/api.js?render=6LezwJgqAAAAACMJ86UNBUbYsbDpVuCFaiER4yfv"></script>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="../controllers/process_login.php" id="loginForm">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <!-- This is where the reCAPTCHA token will be added -->
            <input type="hidden" name="recaptcha_token" id="recaptcha_token">
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>

    <script>
        // When the page loads, execute reCAPTCHA and populate the token
        grecaptcha.ready(function() {
            grecaptcha.execute('6LezwJgqAAAAACMJ86UNBUbYsbDpVuCFaiER4yfv', { action: 'login' }).then(function(token) {
                // Add the token to the hidden input
                document.getElementById('recaptcha_token').value = token;
            });
        });
    </script>
</body>
</html>
