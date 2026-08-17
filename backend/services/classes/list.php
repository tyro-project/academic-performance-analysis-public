<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('GET');
require_auth(['admin', 'faculty', 'department']);
$db   = get_db();
$stmt = $db->query("SELECT * FROM classdata ORDER BY ClassName ASC");
json_response($stmt->fetchAll(), 'Classes retrieved');
