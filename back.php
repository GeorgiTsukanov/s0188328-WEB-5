<?php
$config = require_once __DIR__ . '/../../config/s0188328_WEB_5.php';
require_once 'WorkWithSQL.php';
require_once 'Validate.php';
require_once "session_start.php";

function generateLPH($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $loginUser = '';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $loginUser .= $chars[random_int(0, strlen($chars) - 1)];
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return [
            'loginUser' => $loginUser,
            'password' => $password,
            'passwordHash' => $hashedPassword
        ];
}

$action = validateInput($_POST['action']);
$fio = validateInput($_POST['fio']);
$phoneForCookie = validateInput($_POST['phone']);
$phone = "8".str_replace("-", "", $phoneForCookie);
$email = validateInput($_POST['email']);
$birthday = validateInput($_POST['birthday']);
$gender = validateInput($_POST['gender']);
$biography = validateInput($_POST['biography']);
$languages = $_POST['languages'] ?? [];

$error = isValidatePost($fio, $email, $phone, $birthday, $gender, $languages);

if ($action === "add"){
    if (!empty($error)){
        setcookie('form_errors', json_encode($error), 0, '/');
        setcookie('fio', $fio, 0, '/');
        setcookie('phone', $phoneForCookie, 0, '/');
        setcookie('email', $email, 0, '/');
        setcookie('birthday', $birthday, 0, '/');
        setcookie('gender', $gender, 0, '/');
        setcookie('biography', $biography, 0, '/');
        setcookie('languages', json_encode($languages), 0, '/');
        // Перенаправляем обратно на форму с ошибками
        header('Location: registration.php');
        exit;
    }
    else {
        $user = generateLPH();
        insertApplication(
            getConnection($config), 
            $user['loginUser'], 
            $user['passwordHash'], 
            makeAssociativeArray($fio), 
            $phone, 
            $email, 
            $birthday, 
            $gender, 
            $biography, 
            $languages
        );
        setcookie('fio', $fio, time() + 365 * 24 * 3600, '/');
        setcookie('phone', $phoneForCookie, time() + 365 * 24 * 3600, '/');
        setcookie('email', $email, time() + 365 * 24 * 3600, '/');
        setcookie('birthday', $birthday, time() + 365 * 24 * 3600, '/');
        setcookie('gender', $gender, time() + 365 * 24 * 3600, '/');
        setcookie('biography', $biography, time() + 365 * 24 * 3600, '/');
        setcookie('languages', json_encode($languages), time() + 365 * 24 * 3600, '/');

        setcookie('Uform_errors', json_encode($error), 0, '/');
        setcookie('Ufio', $fio, 0, '/');
        setcookie('Uphone', $phoneForCookie, 0, '/');
        setcookie('Uemail', $email, 0, '/');
        setcookie('Ubirthday', $birthday, 0, '/');
        setcookie('Ugender', $gender, 0, '/');
        setcookie('Ubiography', $biography, 0, '/');
        setcookie('Ulanguages', json_encode($languages), 0, '/');

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Регистрация завершена</title>
            <style>
                .credentials {
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                    border-radius: 5px;
                    padding: 20px;
                    margin: 20px auto;
                    max-width: 600px;
                }
                .credential-item {
                    margin-bottom: 10px;
                }
                .label {
                    font-weight: bold;
                    display: inline-block;
                    width: 120px;
                }
            </style>
        </head>
        <body>
            <div class="credentials">
                <h2>Регистрация успешно завершена!</h2>
                <div class="credential-item">
                    <span class="label">Логин:</span>
                    <span>{$user['loginUser']}</span>
                </div>
                <div class="credential-item">
                    <span class="label">Пароль:</span>
                    <span>{$user['password']}</span>
                </div>
                <p>Пожалуйста, сохраните эти данные в надежном месте.</p>
            </div>
        </body>
        </html>
        HTML;
        
        echo $html;
    }
}

if ($action === "update"){
    if (!empty($error)){
        setcookie('Uform_errors', json_encode($error), 0, '/');
        setcookie('Ufio', $fio, 0, '/');
        setcookie('Uphone', $phoneForCookie, 0, '/');
        setcookie('Uemail', $email, 0, '/');
        setcookie('Ubirthday', $birthday, 0, '/');
        setcookie('Ugender', $gender, 0, '/');
        setcookie('Ubiography', $biography, 0, '/');
        setcookie('Ulanguages', json_encode($languages), 0, '/');
        // Перенаправляем обратно на форму с ошибками
        header('Location: profile.php');
        exit;
    }
    else {
        if (isset($_SESSION['user'])) {
                updateApplication(
                    getConnection($config),
                    $_SESSION['user']['id'],
                    makeAssociativeArray($fio),
                    $phone, 
                    $email, 
                    $birthday, 
                    $gender, 
                    $biography, 
                    $languages
                );
                setcookie('Ufio', $fio, time() + 365 * 24 * 3600, '/');
                setcookie('Uphone', $phoneForCookie, time() + 365 * 24 * 3600, '/');
                setcookie('Uemail', $email, time() + 365 * 24 * 3600, '/');
                setcookie('Ubirthday', $birthday, time() + 365 * 24 * 3600, '/');
                setcookie('Ugender', $gender, time() + 365 * 24 * 3600, '/');
                setcookie('Ubiography', $biography, time() + 365 * 24 * 3600, '/');
                setcookie('Ulanguages', json_encode($languages), time() + 365 * 24 * 3600, '/');
        }
    }
}
?>