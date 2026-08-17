<?php
require_once __DIR__ . '/../../bootstrap.php';

method_required('POST');

$body = get_json_body();
$role     = trim($body['role'] ?? '');
$username = trim($body['username'] ?? '');
$password = trim($body['password'] ?? '');

if (empty($role) || empty($username) || empty($password)) {
    json_error('Role, username and password are required', 400);
}

$db = get_db();

switch ($role) {

    case 'admin':
        $stmt = $db->prepare("SELECT * FROM admindata WHERE Username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['Password'])) {
            json_error('Invalid username or password', 401);
        }
        set_session([
            'id'       => $user['id'],
            'username' => $user['Username'],
            'name'     => $user['Username'],
            'role'     => 'admin',
        ]);
        break;

    case 'department':
        $stmt = $db->prepare("SELECT * FROM departmentdata WHERE Username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        if (!$user || $password !== $user['Password']) {
            json_error('Invalid username or password', 401);
        }
        set_session([
            'id'       => $user['id'],
            'username' => $user['Username'],
            'name'     => $user['DepartmentName'] ?? $user['Username'],
            'role'     => 'department',
        ]);
        break;

    case 'faculty':
        $stmt = $db->prepare("SELECT * FROM facultydata WHERE FacultyName = :name LIMIT 1");
        $stmt->execute([':name' => $username]);
        $user = $stmt->fetch();
        if (!$user || (string)$password !== (string)$user['FacultyCode']) {
            json_error('Invalid name or faculty code', 401);
        }
        set_session([
            'id'       => $user['id'],
            'username' => $user['FacultyName'],
            'name'     => $user['FacultyName'],
            'role'     => 'faculty',
        ]);
        break;

    case 'student':
        $stmt = $db->prepare("SELECT * FROM studentdata WHERE StudentName = :name LIMIT 1");
        $stmt->execute([':name' => $username]);
        $user = $stmt->fetch();
        if (!$user || (string)$password !== (string)$user['RollId']) {
            json_error('Invalid name or roll ID', 401);
        }
        set_session([
            'id'        => $user['StudentId'],
            'username'  => $user['StudentName'],
            'name'      => $user['StudentName'],
            'role'      => 'student',
        ]);
        break;

    default:
        json_error('Invalid role', 400);
}

json_response(current_user(), 'Login successful');
