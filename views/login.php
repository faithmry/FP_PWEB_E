<?php
$host = " sql304.infinityfree.com"; 
$dbname = "if0_37868054_workflow";
$username = "if0_37868054"; 
$password = "81haP9pwLpvURfG"; 

$message = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->execute([
            ':email' => $email,
            ':password' => $password, 
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $message = "Login successful! Welcome, " . htmlspecialchars($user['name']) . ".";
        } else {
            $message = "Invalid email or password.";
        }
    }
} catch (PDOException $e) {
    $message = "Error connecting to the database: " . $e->getMessage();
}

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
            background: url('edubg.jpg') no-repeat center center fixed;
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
            max-width: 600px;
            width: 100%;
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
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
