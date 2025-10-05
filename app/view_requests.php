<?php
require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT * FROM event_requests ORDER BY created_at DESC");
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка при получении данных: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Просмотр заявок</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Все заявки на мероприятия</h1>
        
        <?php if (empty($requests)): ?>
            <div class="alert alert-info">Заявок пока нет.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Тип мероприятия</th>
                            <th>Количество человек</th>
                            <th>Предпочтения по меню</th>
                            <th>Контактный телефон</th>
                            <th>Дата создания</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['id']); ?></td>
                                <td><?php echo htmlspecialchars($request['event_type']); ?></td>
                                <td><?php echo htmlspecialchars($request['number_of_people']); ?></td>
                                <td><?php echo htmlspecialchars($request['menu_preferences'] ?: 'Не указано'); ?></td>
                                <td><?php echo htmlspecialchars($request['contact_phone']); ?></td>
                                <td><?php echo htmlspecialchars($request['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <a href="form.html" class="btn btn-primary">Создать новую заявку</a>
    </div>
</body>
</html>
