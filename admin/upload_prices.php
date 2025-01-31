<?php
session_start();
require '../vendor/autoload.php';  // Load the PhpSpreadsheet library
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['upload'])) {
    $targetDir = "../uploads/";
    $fileName = basename($_FILES["excelFile"]["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);

    // Check file type (only Excel files)
    if ($fileType == "xlsx" || $fileType == "xls") {
        if (move_uploaded_file($_FILES["excelFile"]["tmp_name"], $targetFile)) {
            echo "File uploaded successfully.";
            
            // Load and parse the Excel file using PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($targetFile);
            $sheet = $spreadsheet->getActiveSheet();
            
            // Loop through each row in the Excel file
            foreach ($sheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $productId = null;
                $newPrice = null;

                foreach ($cellIterator as $cell) {
                    // Assuming first column is product_id and second column is the new price
                    if ($cell->getColumn() == 'A') {
                        $productId = $cell->getValue();  // Product ID from column A
                    } elseif ($cell->getColumn() == 'B') {
                        $newPrice = $cell->getValue();  // New price from column B
                    }
                }

                // Update the price in the database
                if ($productId && $newPrice) {
                    $query = $conn->prepare("INSERT INTO price_updates (product_id, new_price) VALUES (?, ?)");
                    $query->bind_param("id", $productId, $newPrice);
                    $query->execute();

                    // Optionally, update the product's base price directly
                    $updateQuery = $conn->prepare("UPDATE products SET base_price = ? WHERE id = ?");
                    $updateQuery->bind_param("di", $newPrice, $productId);
                    $updateQuery->execute();
                }
            }

            echo "Prices updated successfully.";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Only Excel files are allowed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Price File</title>
</head>
<body>
    <h2>Upload Price File</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="excelFile" required>
        <button type="submit" name="upload">Upload</button>
    </form>
</body>
</html>
