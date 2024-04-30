<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Secure Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <?php include 'php/header.php'; ?> <!-- Include the common header -->
    <div class="container mt-5">
        <h2>Generate a Secure Password</h2>
        <form action="php/process_generate.php" method="POST">
            <div class="mb-3">
                <label for="length" class="form-label">Password Length</label>
                <input type="number" class="form-control" id="length" name="length" min="8" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Password</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
