<?php
require_once "session_start.php";

if (isset($_SESSION['user'])) {
    header('Location: profile.php');
    exit;
}


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Окно авторизации</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Иконки Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .auth-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .form-icon {
            font-size: 80px;
            color: #0d6efd;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container text-center">
            <div class="form-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <h2 class="mb-4">Авторизация</h2>
            
          <!-- форм --> 
            <form method="post" action="authorization.php">
                <div class="mb-3 text-start">
                    <label for="login" class="form-label">Логин</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="login" name="login" placeholder="Введите ваш логин" required>
                    </div>
                </div>
                
                <div class="mb-4 text-start">
                    <label for="password" class="form-label">Пароль</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-custom w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Войти
                </button>
                
            </form>
            
            <a href="registration.php" class="btn btn-outline-primary btn-custom w-100">
                <i class="fas fa-user-plus me-2"></i> Зарегистрироваться
            </a>
        </div>
    </div>

    <!-- Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    </script>
</body>
</html>