<?php
ob_start();
require_once "session_start.php";
require_once "Validate.php";

$configPath = __DIR__ . '/../../config/s0188328_WEB_5.php';
if (!file_exists($configPath)) {
    die("Config file not found");
}
$config = require_once $configPath;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginUser = validateInput($_POST['login']);
    $password = validateInput($_POST['password']);
    
    if (!empty($loginUser) && !empty($password)) {
        try {
            $conn = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
                $config['username'],
                $config['password']
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $conn->prepare("SELECT * FROM User WHERE LoginUser = ?");
            $stmt->execute([$loginUser]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['PasswordHash'])) {
                $_SESSION['user'] = [
                    'id' => $user['ID'],
                    'loginUser' => $user['LoginUser'],
                    'password_hash' => $user['PasswordHash']
                ];
                
                // Отладочный вывод в лог
                error_log("User auth success: " . $user['LoginUser']);
                
                // Очистка буфера и перенаправление
                ob_end_clean();
                header('Location: profile.php');
                exit;
            }
        } catch (PDOException $e) {
            error_log("Auth error: " . $e->getMessage());
        }
    }
    
    // Если что-то пошло не так
    ob_end_clean();
    header('Location: index.php?error=auth_failed');
    exit;
}
