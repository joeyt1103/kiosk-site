<?php
$dataDir = __DIR__ . "/data";
$csvFile = $dataDir . "/entertainment_ratings.csv";

if (!is_dir($dataDir)) { mkdir($dataDir, 0755, true); }

$event = isset($_POST["event_name"]) ? trim($_POST["event_name"]) : "";
$rating = isset($_POST["rating"]) ? trim($_POST["rating"]) : "";
$comments = isset($_POST["comments"]) ? trim($_POST["comments"]) : "";

if ($rating === "" || !ctype_digit($rating) || intval($rating) < 1 || intval($rating) > 5) {
  http_response_code(400); echo "Invalid submission."; exit;
}

$timestamp = date("Y-m-d H:i:s");
$needsHeader = !file_exists($csvFile);

$fp = fopen($csvFile, "a");
if (!$fp) { http_response_code(500); echo "Could not open log file."; exit; }

if (flock($fp, LOCK_EX)) {
  if ($needsHeader) { fputcsv($fp, ["Timestamp","EventName","Rating(1-5)","Comments"]); }
  fputcsv($fp, [$timestamp, $event, intval($rating), $comments]);
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
