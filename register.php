<?php
session_start();
include('config.php');

if (isset($_POST['admin_sup'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = sha1(md5($_POST['password'])); // Double encrypt

    $query = "INSERT INTO userdetails (role, fullname, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssss', $role, $fullname, $email, $password);
    $stmt->execute();

    if ($stmt) {
        $success = "Account created successfully! Redirecting to login page...";
    } else {
        $err = "Error! Please try again later.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="images/logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Register - AgriDoc</title>
    <link rel="icon" type="image/png" href="/AgriTech/images/logo.png">
    <link rel="stylesheet" href="assets/css/register.css">
    <link rel="stylesheet" href="assets/css/theme.css">
</head>

<body>
    <div class="form-container">
        <div style="position: absolute; top: 20px; right: 20px;">
            <button class="theme-toggle" aria-label="Toggle dark/light mode"
                style="background: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.3); color: #2e7d32;">
                <i class="fas fa-sun sun-icon"></i>
                <i class="fas fa-moon moon-icon"></i>
                <span class="theme-text">Light</span>
            </button>
        </div>

        <div class="brand-section">
            <div class="brand-icon">
                <i class="fas fa-seedling"></i>
            </div>
            <h2>Join AgriDoc</h2>
            <p class="form-subtitle">Create your account to get started</p>
        </div>

        <div class="form-progress">
            <div class="progress-step active" id="step-1"></div>
            <div class="progress-step" id="step-2"></div>
            <div class="progress-step" id="step-3"></div>
            <div class="progress-step" id="step-4"></div>
            <div class="progress-step" id="step-5"></div>
        </div>

        <form method='post'>
            <div class="input-group">
                <i class="fas fa-user-tag role-buyer" id="role-icon"></i>
                <select name="role" id="role" required onchange="updateRoleIcon()">
                    <option value="">Select Your Role</option>
                    <option value="farmer">🚜 Farmer</option>
                </select>
            </div>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fullname" id="fullname" placeholder="Enter your full name" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Create a strong password"
                    required oninput="checkPasswordStrength()">
                <button type="button" class="password-toggle" onclick="togglePassword()">
                    <i class="fas fa-eye" id="password-eye"></i>
                </button>
            </div>

            <div class="password-strength">
                <div class="strength-bar" id="strength-bar"></div>
            </div>
            <div class="strength-text" id="strength-text"></div>

            <button type="submit" id="register-btn" name="admin_sup">
                <span id="register-text">Create Account</span>
            </button>

            <div style="margin-top: 15px; text-align: center;">
                <?php if (isset($success)): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful',
                            text: '<?php echo $success; ?>',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'login.php';
                        });
                    </script>
                <?php endif; ?>

                <?php if (isset($err)): ?>
                    <p style="color: red;"><?php echo $err; ?></p>
                <?php endif; ?>
            </div>

            <p>Already have an account? <a href="login.php">Sign In</a></p>
        </form>
    </div>

    <script src="assets/js/register.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/auth.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            redirectIfLoggedIn();
        });
    </script>
</body>

</html>
