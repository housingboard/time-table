<?php
header('Content-Type: application/octet-stream');

// Define the name the file will be saved as
header('Content-Disposition: attachment; filename="index.html"');

// Optional: Tell the browser not to cache
header('Cache-Control: no-cache, must-revalidate');
header('Expires: 0');

require("timetable.php");

?>
