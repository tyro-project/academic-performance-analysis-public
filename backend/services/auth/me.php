<?php
require_once __DIR__ . '/../../bootstrap.php';

method_required('GET');
require_auth();
json_response(current_user(), 'Authenticated');
