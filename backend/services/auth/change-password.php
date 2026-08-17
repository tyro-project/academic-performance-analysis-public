<?php
require_once __DIR__ . '/../../bootstrap.php';

method_required('POST');
require_auth(['admin']);

$body        = get_json_body();
$current     = trim($body['current_password'] ?? '');
$new_pass    = trim($body['new_password'] ?? '');
$confirm     = trim($body['confirm_password'] ?? '');

if (empty($current) || empty($new_pass) || empty($confirm)) {
    json_error('All password fields are required', 400);
}
if ($new_pass !== $confirm) {
    json_error('New password and confirmation do not match', 400);
}
if (strlen($new_pass) < 8) {
    json_error('Password must be at least 8 characters', 400);
}

$db   = get_db();
$user = current_user();

$stmt = $db->prepare("SELECT Password FROM admindata WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $user['id']]);
$row  = $stmt->fetch();

if (!$row || !password_verify($current, $row['Password'])) {
    json_error('Current password is incorrect', 401);
}

$hashed = password_hash($new_pass, PASSWORD_BCRYPT);
$update = $db->prepare("UPDATE admindata SET Password = :password WHERE id = :id");
$update->execute([':password' => $hashed, ':id' => $user['id']]);

json_response(null, 'Password updated successfully');
