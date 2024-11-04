<?php
include 'connection.php';
$query = "SELECT * FROM books";
$result = sqlsrv_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - E-Book Library</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>E-Book Library Dashboard</h1>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 class="page-title">Books Management</h2>
                <a href="add.php" class="button">+ Add New Book</a>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th width="25%">Description</th>
                        <th>Year</th>
                        <th>PDF</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result) {
                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) { 
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php 
                                $description = htmlspecialchars($row['description']);
                                echo strlen($description) > 100 ? substr($description, 0, 100) . "..." : $description;
                            ?></td>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($row['file_url']); ?>" 
                                   target="_blank" 
                                   class="button button-secondary" 
                                   style="padding: 5px 10px; font-size: 12px;">
                                    View PDF
                                </a>
                            </td>
                            <td class="action-links">
                                <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this book?')" 
                                   style="color: #dc2626;">Delete</a>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">
                                No books found in the library.
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>