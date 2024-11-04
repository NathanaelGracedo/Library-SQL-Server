<?php
include 'connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $year = $_POST['year'];
    
    // Proses Upload File
    $targetDir = "uploads/";
    $fileName = basename($_FILES["file_pdf"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $maxFileSize = 10 * 1024 * 1024; // 10MB
    $uploadSuccess = false;
    $errorMessage = "";

    if ($fileType != 'pdf') {
        $errorMessage = "Sorry, only PDF files are allowed.";
    } elseif ($_FILES["file_pdf"]["size"] > $maxFileSize) {
        $errorMessage = "Sorry, your file is too large. Maximum size is 10MB.";
    } else {
        if (move_uploaded_file($_FILES["file_pdf"]["tmp_name"], $targetFilePath)) {
            $query = "INSERT INTO books (title, author, description, year, file_url) VALUES (?, ?, ?, ?, ?)";
            $params = array($title, $author, $description, $year, $targetFilePath);
            if(sqlsrv_query($conn, $query, $params)) {
                header('Location: index.php');
                exit;
            } else {
                $errorMessage = "Database error occurred. Please try again.";
            }
        } else {
            $errorMessage = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book - E-Book Library</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>E-BOOK LIBRARY DASHBOARD</h1>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h2 class="page-title">Add New Book</h2>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           required 
                           value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="author">Author:</label>
                    <input type="text" 
                           id="author" 
                           name="author" 
                           required
                           value="<?php echo isset($_POST['author']) ? htmlspecialchars($_POST['author']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" 
                              name="description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="year">Year:</label>
                    <input type="number" 
                           id="year" 
                           name="year" 
                           required
                           min="1800"
                           max="<?php echo date('Y'); ?>"
                           value="<?php echo isset($_POST['year']) ? htmlspecialchars($_POST['year']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="file_pdf">PDF File:</label>
                    <input type="file" 
                           id="file_pdf" 
                           name="file_pdf" 
                           accept="application/pdf" 
                           required>
                    <small style="display: block; margin-top: 5px; color: #666;">
                        Maximum file size: 10MB. Only PDF files are allowed.
                    </small>
                </div>

                <div class="form-actions">
                    <a href="index.php" class="button button-secondary">Cancel</a>
                    <button type="submit" class="button">Save Book</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>