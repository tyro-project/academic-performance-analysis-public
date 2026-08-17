<?php
require_once __DIR__ . '/../../bootstrap.php';

method_required('POST');
require_auth(['admin']);

$file = get_uploaded_file('excel_file');
if (!$file) json_error('Excel file is required', 400);

$allowed = ['xlsx', 'xls', 'csv'];
$ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed)) {
    json_error('Only xlsx, xls, csv files are allowed', 400);
}

$tmp_path = UPLOAD_PATH . '/' . uniqid('import_', true) . '.' . $ext;
move_uploaded_file($file['tmp_name'], $tmp_path);

use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load($tmp_path);
$sheet       = $spreadsheet->getActiveSheet()->toArray();
unlink($tmp_path);

array_shift($sheet); // remove header row

$db      = get_db();
$created = 0;
$updated = 0;
$errors  = [];

foreach ($sheet as $i => $row) {
    $name    = trim($row[0] ?? '');
    $rollid  = trim($row[1] ?? '');
    $email   = trim($row[2] ?? '');
    $classid = trim($row[3] ?? '');

    if (empty($name) || empty($rollid)) {
        $errors[] = "Row " . ($i + 2) . ": Name and Roll ID are required";
        continue;
    }

    $check = $db->prepare("SELECT StudentId FROM studentdata WHERE studentEmail = :email LIMIT 1");
    $check->execute([':email' => $email]);
    $exists = $check->fetch();

    if ($exists) {
        $stmt = $db->prepare("UPDATE studentdata SET StudentName = :name, RollId = :rollid, ClassId = :classid WHERE studentEmail = :email");
        $stmt->execute([':name' => $name, ':rollid' => $rollid, ':classid' => $classid, ':email' => $email]);
        $updated++;
    } else {
        $stmt = $db->prepare("INSERT INTO studentdata (StudentName, RollId, studentEmail, ClassId) VALUES (:name, :rollid, :email, :classid)");
        $stmt->execute([':name' => $name, ':rollid' => $rollid, ':email' => $email, ':classid' => $classid]);
        $created++;
    }
}

json_response(['created' => $created, 'updated' => $updated, 'errors' => $errors], 'Import completed');
