<?php
require 'Database.php';
require 'User.php';

$db = new Database();
$conn = $db->connect();
$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $user->create($_POST['name'], $_POST['email'], $_POST['phone']);
    } elseif (isset($_POST['update'])) {
        $user->update($_POST['id'], $_POST['name'], $_POST['email'], $_POST['phone']);
    } elseif (isset($_POST['delete'])) {
        $user->delete($_POST['id']);
    }
}

$users = $user->read();
?>
<!DOCTYPE html>
<html>
<head>
    <title>CRUD Operations - OOP with MySQLi</title>
</head>
<body>
    <h1>CRUD Operations</h1>
    <form method="POST">
        <input type="hidden" name="id" value="">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <button type="submit" name="create">Create</button>
        <button type="submit" name="update">Update</button>
    </form>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id']; ?></td>
                <td><?= $user['name']; ?></td>
                <td><?= $user['email']; ?></td>
                <td><?= $user['phone']; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $user['id']; ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
