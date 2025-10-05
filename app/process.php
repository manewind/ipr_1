<?php
session_start();
require_once 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.html');
    exit;
}

$event_type = trim($_POST['event_type'] ?? '');
$number_of_people = trim($_POST['number_of_people'] ?? '');
$menu_preferences = trim($_POST['menu_preferences'] ?? '');
$contact_phone = trim($_POST['contact_phone'] ?? '');

if (empty($event_type)) {
    $errors[] = "Тип мероприятия обязателен для заполнения";
}

if (empty($number_of_people)) {
    $errors[] = "Количество человек обязательно для заполнения";
} elseif (!is_numeric($number_of_people) || $number_of_people < 1 || $number_of_people > 1000) {
    $errors[] = "Количество человек должно быть числом от 1 до 1000";
}

if (empty($contact_phone)) {
    $errors[] = "Контактный телефон обязателен для заполнения";
} elseif (!preg_match('/^[\+]\d{1,3}\d{7,15}$/', $contact_phone)) {
    $errors[] = "Неверный формат телефона. Используйте формат: +79991234567";
}

if (!empty($errors)) {
    displayErrors($errors);
    exit;
}

try {
    $sql = "INSERT INTO event_requests (event_type, number_of_people, menu_preferences, contact_phone) 
            VALUES (:event_type, :number_of_people, :menu_preferences, :contact_phone)";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':event_type' => $event_type,
        ':number_of_people' => (int)$number_of_people,
        ':menu_preferences' => $menu_preferences,
        ':contact_phone' => $contact_phone
    ]);
    
    displaySuccess();
    
} catch (PDOException $e) {
    $errors[] = "Ошибка при сохранении данных: " . $e->getMessage();
    displayErrors($errors);
}

function displayErrors($errors) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ошибки валидации</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="alert alert-danger">
                <h4 class="alert-heading">Обнаружены ошибки:</h4>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
                <hr>
                <a href="form.html" class="btn btn-outline-danger">Вернуться к форме</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}

function displaySuccess() {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Успешная отправка</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="alert alert-success">
                <h4 class="alert-heading">Успешно!</h4>
                <p>Ваша заявка на мероприятие была успешно сохранена.</p>
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="form.html" class="btn btn-outline-success">Отправить новую заявку</a>
                    <a href="view_requests.php" class="btn btn-primary">Посмотреть все заявки</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>
