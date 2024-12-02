<?php
if (!isset($_GET['sha'])) {
    echo "Commit SHA tidak ditemukan.";
    exit;
}

$sha = $_GET['sha'];  // Commit SHA yang dipilih

// GitHub Repository
$repoOwner = 'fauzymadani';  // Ganti dengan username pemilik repo
$repoName = 'TestPatch';      // Ganti dengan nama repository

// URL API GitHub untuk mengambil detail commit berdasarkan SHA
$apiUrl = "https://api.github.com/repos/$repoOwner/$repoName/commits/$sha";

// Mengambil data dari API GitHub menggunakan file_get_contents
$options = [
    "http" => [
        "header" => "User-Agent: PHP"
    ]
];
$context = stream_context_create($options);
$response = file_get_contents($apiUrl, false, $context);
$commitDetails = json_decode($response, true);

if ($commitDetails === null) {
    echo "Tidak bisa mengambil data commit dari GitHub.";
    exit;
}

$commitMessage = $commitDetails['commit']['message'];
$commitDate = $commitDetails['commit']['author']['date'];
$commitAuthor = $commitDetails['commit']['author']['name'];

// Mendapatkan patch/diff dari commit
$patchUrl = "https://github.com/$repoOwner/$repoName/commit/$sha.diff";
$patchContent = file_get_contents($patchUrl);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patch Detail</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Patch Detail</h1>
    
    <h2><?php echo $commitMessage; ?></h2>
    <p>By: <?php echo $commitAuthor; ?> on <?php echo $commitDate; ?></p>
    
    <h3>Patch</h3>
    <pre><?php echo htmlspecialchars($patchContent); ?></pre>

    <a href="index.php">Kembali ke Daftar Commit</a>
</body>
</html>

