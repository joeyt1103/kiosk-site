<?php
$dataDir = __DIR__ . "/data";
$csvFile = $dataDir . "/volunteer_log.csv";

if (!is_dir($dataDir)) { mkdir($dataDir, 0755, true); }

$name  = isset($_POST["name"])  ? trim($_POST["name"])  : "";
$hours = isset($_POST["hours"]) ? trim($_POST["hours"]) : "";

if ($name === "" || $hours === "" || !is_numeric($hours) || floatval($hours) < 0) {
  http_response_code(400); echo "Invalid submission."; exit;
}

$timestamp = date("Y-m-d H:i:s");
$needsHeader = !file_exists($csvFile);

$fp = fopen($csvFile, "a");
if (!$fp) { http_response_code(500); echo "Could not open log file."; exit; }

if (flock($fp, LOCK_EX)) {
  if ($needsHeader) { fputcsv($fp, ["Timestamp","Name","Hours"]); }
  fputcsv($fp, [$timestamp, $name, number_format((float)$hours, 2, ".", "")]);
  fflush($fp);
  flock($fp, LOCK_UN);
}
fclose($fp);
?>
<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="refresh" content="2; url=index.html">
  <style>body{font-family:system-ui;padding:20px}</style>
</head>
<body>
  <h3>Submitted successfully ✅</h3>
  <p>Returning to the home screen…</p>
</body>
</html>
