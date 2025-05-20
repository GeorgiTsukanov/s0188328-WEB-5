<?php
// Должно быть самым первым в файле, ДО любого HTML
require_once "session_start.php";

// Жёсткая очистка буфера
while (ob_get_level()) ob_end_clean();

// Проверка авторизации ДО любого вывода
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$fio = $_COOKIE['Ufio'] ?? '';
$phone = $_COOKIE['Uphone'] ?? '';
$email = $_COOKIE['Uemail'] ?? '';
$birthday = $_COOKIE['Ubirthday'] ?? '';
$gender = $_COOKIE['Ugender'] ?? '';
$biography = $_COOKIE['Ubiography'] ?? '';
$languages = isset($_COOKIE['Ulanguages']) ? json_decode($_COOKIE['Ulanguages'], true) : [];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница регистрации</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title text-center">ВАША ГОТОВАЯ ЗАЯВКА</h3>
                    </div>
                    <div class="card-body">
                        <form action="back.php" method="post">
                            <!-- фиксированная переменная для post запроса -->
                            <input type="hidden" name="action" id = "action" value="update">
                            <!-- ФИО -->
                            <div class="mb-3">
                                <label for="fio" class="form-label">ФИО</label>
                                <input type="text" 
                                    class="form-control <?= isset($errors['fio']) ? 'is-invalid' : '' ?>" 
                                    id="fio" 
                                    name="fio"
                                    value="<?= htmlspecialchars($fio ?? '') ?>"
                                    required
                                >
                                <?php if (isset($errors['fio'])): ?>
                                        <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['fio']) ?>
                                        </div>
                                <?php endif; ?>
                            </div>

                            <!-- Номер телефона -->
                            <div class="mb-3">
                                <label for="input-group" class="form-label">Номер телефона</label>
                                <div class="input-group">
                                    <span class="input-group-text">+7</span>
                                    <input type="tel" 
                                        class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
                                        id="phone" 
                                        name="phone"
                                        value="<?= htmlspecialchars($phone ?? '') ?>" 
                                        required
                                    >
                                    <?php if (isset($errors['phone'])): ?>
                                        <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['phone']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email"
                                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                    id="email" 
                                    name="email" 
                                    value="<?= htmlspecialchars($email ?? '') ?>"
                                    required
                                >
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                    <?= htmlspecialchars($errors['email']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- ДР -->
                            <div class="mb-3">
                                <label for="birthday" class="form-label">Дата рождения</label>
                                <input type="date" 
                                    class="form-control <?= isset($errors['birthday']) ? 'is-invalid' : '' ?>" 
                                    id="birthday" 
                                    name="birthday" 
                                    value="<?= htmlspecialchars($birthday ?? '') ?>"
                                    required
                                >
                                <?php if (isset($errors['birthday'])): ?>
                                    <div class="invalid-feedback">
                                    <?= htmlspecialchars($errors['birthday']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Пол -->
                            <div class="mb-3">
                                <label for="gender" class="form-label">Пол</label>
                                <select class="form-select <?= isset($errors['gender']) ? 'is-invalid' : '' ?>" 
                                        id="gender" 
                                        name="gender" 
                                        required>
                                    <option value="male" <?= $gender === 'male' ? 'selected' : '' ?>>Мужской</option>
                                    <option value="female" <?= $gender === 'female' ? 'selected' : '' ?>>Женский</option>
                                </select>
                                
                                <?php if (isset($errors['gender'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['gender']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- ЯП -->
                            <div class="mb-3">
                                <label for="languages" class="form-label">Любимый язык программирования</label>
                                <select class="form-select <?= isset($errors['languages']) ? 'is-invalid' : '' ?>" 
                                        id="languages" 
                                        name="languages[]" 
                                        multiple 
                                        required>
                                    <?php
                                    $options = [
                                        1 => 'Python',
                                        2 => 'JavaScript',
                                        3 => 'Java',
                                        4 => 'C++',
                                        5 => 'PHP',
                                        6 => 'Ruby',
                                        7 => 'Go',
                                        8 => 'C',
                                        9 => 'C#'
                                    ];
                                    
                                    foreach ($options as $value => $label) {
                                        $selected = in_array($value, $languages) ? 'selected' : '';
                                        echo "<option value=\"$value\" $selected>$label</option>";
                                    }
                                    ?>
                                </select>
                                
                                <?php if (isset($errors['languages'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['languages']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Биография -->
                            <div class="mb-3">
                                <label for="biography" class="form-label">Биография</label>
                                <textarea 
                                    class="form-control <?= isset($errors['birthday']) ? 'is-invalid' : '' ?>" 
                                    id="biography" 
                                    name="biography" 
                                    rows="4"
                                    required><?= htmlspecialchars($biography ?? '') ?></textarea>
                                <?php if (isset($errors['biography'])): ?>
                                    <div class="invalid-feedback">
                                    <?= htmlspecialchars($errors['biography']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                             <!-- чекбоксе -->
                            <div class="mb-3">
                                <input type="checkbox" id="checkbox" name="checkbox" required> с контрактом ознакомлен(а)
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Обновить</button>
                            </div>
                        </form>

                        <a href="logout.php" class="btn btn-outline-primary btn-custom w-100">
                            <i class="fa fa-umbrella"></i> Выйти из аккаунта
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>