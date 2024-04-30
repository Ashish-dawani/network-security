<?php
include 'php/db_connect.php';
include 'php/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php"); // Redirect to signin if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT id, website, email, password, iv FROM stored_passwords WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$encryption_key = 'tmAKfQYvMvJ3pmifvrZJcogmzfHeRYV6'; // Replace with your actual encryption key handling method

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Password Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    .password-strength-meter {
        height: 10px;
        background-color: lightgray;
        margin-top: 5px;
    }

    .password-strength-meter-fill {
        height: 100%;
    }
</style>

</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Your Stored Passwords</h2>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Website</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Password Strength</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['website']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td>
                                <?php
                                // Decrypt the password
                                $encrypted_password = base64_decode($row['password']);
                                $iv = base64_decode($row['iv']);
                                $decrypted_password = openssl_decrypt($encrypted_password, "aes-256-cbc", $encryption_key, 0, $iv);
                                ?>
                                <input type="password" class="password-field form-control" id="password-<?= $row['id'] ?>" value="<?= htmlspecialchars($decrypted_password) ?>" readonly>
                            </td>
                            <td>
                                <div class="password-strength-meter" id="password-strength-meter-<?= $row['id'] ?>"></div>
                            </td>
                            <td><button onclick="toggleVisibility('password-<?= $row['id'] ?>', 'password-strength-meter-<?= $row['id'] ?>');" class="btn btn-primary">Show/Hide</button> <button onclick="deletePassword(<?= $row['id'] ?>);" class="btn btn-danger">Delete</button></td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function toggleVisibility(passwordId, strengthMeterId) {
            var passwordInput = document.getElementById(passwordId);
            var strengthMeter = document.getElementById(strengthMeterId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                updatePasswordStrength(passwordId, strengthMeterId);
            } else {
                passwordInput.type = "password";
                strengthMeter.style.width = '0%'; // Reset strength meter when hiding password
            }
        }
        
        function updatePasswordStrength(passwordId, strengthMeterId) {
            var password = document.getElementById(passwordId).value;
            var strengthMeter = document.getElementById(strengthMeterId);
            var strength = calculatePasswordStrength(password);
            strengthMeter.style.width = strength + '%';
            strengthMeter.style.backgroundColor = strength < 50 ? 'red' : strength < 80 ? 'yellow' : 'green';
        }
        
        function deletePassword(passwordId) {
            if (confirm("Are you sure you want to delete this password?")) {
                fetch('php/delete_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + passwordId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Password deleted successfully!');
                        window.location.reload();  // Reload the page to update the list
                    } else {
                        alert('Error deleting password: ' + data.error);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
            }
        }
        
        function calculatePasswordStrength(password) {
    var strength = 0;
    var regex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/; // Corrected special characters regex
    var length = password.length;
    
    // Length-based strength calculation
    strength += Math.min(5, length / 4) * 10;
    
    // Add points for each type of character
    if (password.match(/[a-z]/)) strength += 10;
    if (password.match(/[A-Z]/)) strength += 10;
    if (password.match(/[0-9]/)) strength += 10;
    if (password.match(regex)) strength += 20;
    
    return strength;
}

document.querySelectorAll('.password-field').forEach(function(input) {
    var id = input.id.split('-')[1];
    var meter = document.getElementById('password-strength-meter-' + id);

    input.addEventListener('keyup', function() {
        var password = input.value;
        var strength = calculatePasswordStrength(password);
        meter.style.width = strength + '%';
        meter.style.backgroundColor = strength < 50 ? 'red' : strength < 80 ? 'yellow' : 'green';
    });
});
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
