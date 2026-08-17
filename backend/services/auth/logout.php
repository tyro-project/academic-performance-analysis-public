<?php
require_once __DIR__ . '/../../bootstrap.php';

method_required('POST');
clear_session();
json_response(null, 'Logged out successfully');
