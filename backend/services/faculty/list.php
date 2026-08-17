<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('GET');
require_auth(['admin', 'department']);
$db   = get_db();
$stmt = $db->query("SELECT * FROM facultydata ORDER BY FacultyName ASC");
json_response($stmt->fetchAll(), 'Faculty retrieved');
