<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">';
echo '<div class="container-fluid">';
echo '<a class="navbar-brand" href="../dashboard.php">Password Manager</a>';
echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">';
echo '<span class="navbar-toggler-icon"></span>';
echo '</button>';
echo '<div class="collapse navbar-collapse" id="navbarNav">';
echo '<ul class="navbar-nav ms-auto">';

if (isset($_SESSION['user_id'])) {
    echo '<li class="nav-item"><a class="nav-link" href="../dashboard.php">Dashboard</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="../store_password.php">Store Password</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="../generate_password.php">Generate Password</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>';
} else {
    echo '<li class="nav-item"><a class="nav-link" href="signin.php">Sign In</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>';
}

echo '</ul>';
echo '</div>';
echo '</div>';
echo '</nav>';
?>
