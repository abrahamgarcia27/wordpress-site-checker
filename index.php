<?php

// Function to check if a website is WordPress
function isWordPress($url) {
    if (!preg_match("/^http(s)?:\/\//", $url)) {
        $url = "http://" . $url;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        return false;
    }

    list($headers, $body) = explode("\r\n\r\n", $response, 2);

    if (stripos($headers, "x-powered-by: WordPress") !== false) {
        return true;
    }

    if (preg_match('/<meta name="generator" content="WordPress/i', $body)) {
        return true;
    }

    $commonPaths = ["/wp-content/", "/wp-includes/", "/wp-admin/"];
    foreach ($commonPaths as $path) {
        $pathUrl = rtrim($url, "/") . $path;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pathUrl);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_exec($ch);
        $pathHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($pathHttpCode === 200) {
            return true;
        }
    }

    return false;
}

// Handle file upload and processing
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["csv_file"])) {
    $uploadedFile = $_FILES["csv_file"]["tmp_name"];
    if (($handle = fopen($uploadedFile, "r")) !== false) {
        $results = [["site", "is_wordpress"]]; // CSV header
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $site = $data[0]; // Assuming the website URL is in the first column
            $isWordPress = isWordPress($site) ? "Yes" : "No";
            $results[] = [$site, $isWordPress];
        }
        fclose($handle);

        // Generate output CSV
        $outputFile = "results.csv";
        $outputHandle = fopen($outputFile, "w");
        foreach ($results as $row) {
            fputcsv($outputHandle, $row);
        }
        fclose($outputHandle);

        // Offer the file for download
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename={$outputFile}");
        readfile($outputFile);
        unlink($outputFile); // Delete the temporary file
        exit;
    } else {
        echo "Failed to process the uploaded file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordPress Site Checker</title>
</head>
<body>
    <h1>Check if Websites are WordPress</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="csv_file">Upload a CSV file:</label>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        <button type="submit">Check Websites</button>
    </form>
</body>
</html>
