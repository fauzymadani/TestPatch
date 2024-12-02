<?php
// GitHub Repository
$repoOwner = 'fauzymadani';  // Ganti dengan username pemilik repo
$repoName = 'TestPatch';      // Ganti dengan nama repository

// URL API GitHub untuk mengambil commit dari repository
$apiUrl = "https://api.github.com/repos/$repoOwner/$repoName/commits";

// Mengambil data dari API GitHub menggunakan file_get_contents
$options = [
    "http" => [
        "header" => "User-Agent: PHP"
    ]
];
$context = stream_context_create($options);
$response = file_get_contents($apiUrl, false, $context);
$commits = json_decode($response, true);

if ($commits === null) {
    echo "Tidak bisa mengambil data commit dari GitHub.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Git Patch Viewer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Git Patch Viewer - <?php echo "$repoOwner/$repoName"; ?></h1>
    
    <h2>Daftar Commit</h2>
    <ul>
        <?php foreach ($commits as $commit) : ?>
            <li>
                <a href="patch_viewer.php?sha=<?php echo $commit['sha']; ?>"><?php echo $commit['commit']['message']; ?></a> 
                - <?php echo $commit['commit']['author']['name']; ?> 
                (<?php echo $commit['commit']['author']['date']; ?>)
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

