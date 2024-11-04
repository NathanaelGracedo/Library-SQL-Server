<?php
include 'connection.php';
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $year = $_POST['year'];
    $query = "UPDATE books SET title = ?, author = ?, description = ?, year = ? WHERE id = ?";
    $params = array($title, $author, $description, $year, $id);
    
    $result = sqlsrv_query($conn, $query, $params);
    if ($result) {
        header('Location: index.php');
    } else {
        $error = "Failed to update the book. Please try again.";
    }
} else {
    $query = "SELECT * FROM books WHERE id = ?";
    $params = array($id);
    $result = sqlsrv_query($conn, $query, $params);
    $book = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    
    if (!$book) {
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - E-Book Library</title>
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
            <h2 class="page-title">Edit Book Details</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" 
                           name="title" 
                           value="<?php echo htmlspecialchars($book['title']); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label>Author:</label>
                    <input type="text" 
                           name="author" 
                           value="<?php echo htmlspecialchars($book['author']); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description"><?php echo htmlspecialchars($book['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Year:</label>
                    <input type="number" 
                           name="year" 
                           value="<?php echo htmlspecialchars($book['year']); ?>" 
                           required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="button">Update Book</button>
                    <a href="index.php" class="button button-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.querySelector("form").addEventListener("submit", function(event) {
        const title = document.querySelector("[name='title']").value;
        const author = document.querySelector("[name='author']").value;
        const description = document.querySelector("[name='description']").value;
        const year = document.querySelector("[name='year']").value;

        // Validasi input
        const regex = /^[a-zA-Z0-9\s.,'-]+$/;
        if (!regex.test(title) || !regex.test(author) || !regex.test(description) || isNaN(year)) {
            alert("Please enter valid input. Avoid special characters in title, author, and description.");
            event.preventDefault();
            return;
        }

        // Validasi tahun
        const currentYear = new Date().getFullYear();
        if (year < 1800 || year > currentYear) {
            alert("Please enter a valid year between 1800 and " + currentYear);
            event.preventDefault();
            return;
        }
    });
    </script>
</body>
</html>