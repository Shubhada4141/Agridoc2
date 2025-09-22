<?php
session_start();
include('config.php');

if (isset($_POST['admin_sup'])) {
    
    $fieldName = $_POST['fieldName'];
    $area = $_POST['area'];
    $type = $_POST['type'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    $query = "INSERT INTO fielddetails (fieldName,area,type,location,description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sdsss',$fieldName, $area, $type, $location, $description);
    $stmt->execute();

    if ($stmt) {
        $success = "fields are successfully added";
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
    <title>Add field Here - AgriDoc</title>
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
            <h2>AgriDoc</h2>
            <p class="form-subtitle">Add your fields heree!!!</p>
        </div>

        <!--<div class="form-progress">
            <div class="progress-step active" id="step-1"></div>
            <div class="progress-step" id="step-2"></div>
            <div class="progress-step" id="step-3"></div>
            <div class="progress-step" id="step-4"></div>
            <div class="progress-step" id="step-5"></div>
        </div>-->

        <form method='post'>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fieldName" id="fullname" placeholder="Field Name" required>
            </div>
            
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="text" name="area" id="email" placeholder="Area" required>
            </div>

            <div class="input-group">
                <i class="fas fa-user-tag role-buyer" id="role-icon"></i>
                <select name="type" id="role" required onchange="updateRoleIcon()">
                    <option value="">Select Type</option>
                    <option value="Acres">Acres</option>
                    <option value="Hectares">Hectares</option>
                    <option value="Square Meters">Square Meters</option>
                    <option value="Square Feet">Square Feet</option>
                </select>
            </div>

                        <!-- User Location Field -->
            <div class="input-group" style="margin-top: 10px;">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="location" id="location" placeholder="Click to get your location" readonly style="cursor:pointer;">
                <button type="button" id="get-location-btn" style="margin-left:10px; background:#2e7d32; color:#fff; border:none; border-radius:4px; padding:6px 12px; cursor:pointer;">
                    Get Location
                </button>
            </div>
            <style>
                #location {
                    width: 70%;
                    border-radius: 6px;
                    border: 1px solid #2e7d32;
                    padding: 40px;
                    font-size: 15px;
                    background: #f9fff9;
                    color: black;
                }
                #get-location-btn:hover {
                    background: #388e3c;
                }
            </style>
            <script>
                document.getElementById('get-location-btn').addEventListener('click', function() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const lat = position.coords.latitude.toFixed(6);
                            const lon = position.coords.longitude.toFixed(6);
                            document.getElementById('location').value = lat + ', ' + lon;
                        }, function(error) {
                            alert('Unable to retrieve your location. Please allow location access.');
                        });
                    } else {
                        alert('Geolocation is not supported by your browser.');
                    }
                });
            </script>

            <!-- Notes for Farmers (optional) -->
            <div class="input-group" style="margin-top: 10px; justify-content: flex-end;">
                <textarea name="description" id="farmer-notes" rows="3" placeholder="Description" style="width: 100%; resize: vertical; border-radius: 6px; border: 1px solid #2e7d32; padding: 8px; font-size: 15px;" optional></textarea>
            </div>

            <style>
            /* Notes textarea styling */
            #farmer-notes {
                transition: border-color 0.2s;
            }
            #farmer-notes:focus {
                border-color: #43a047;
                outline: none;
            }
            </style>
            <script>
            // Optional: JS to auto-resize textarea and show a message when toggled
            document.addEventListener('DOMContentLoaded', function() {
                const notes = document.getElementById('farmer-notes');
                if (notes) {
                    notes.addEventListener('input', function() {
                        this.style.height = 'auto';
                        this.style.height = (this.scrollHeight) + 'px';
                    });
                }

                // Example: Show a message when Mark as Harvested is toggled
                const harvestedToggle = document.getElementById('harvested-toggle');
                if (harvestedToggle) {
                    harvestedToggle.addEventListener('change', function() {
                        if (this.checked) {
                            this.closest('.input-group').style.background = '#e8f5e9';
                        } else {
                            this.closest('.input-group').style.background = 'none';
                        }
                    });
                }
            });
            </script>

            <button type="submit" id="register-btn" name="admin_sup">
                <span id="register-text">Add Field</span>
            </button>

            <div style="margin-top: 15px; text-align: center;">
                <?php if (isset($success)): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Field Successfully Added',
                            text: '<?php echo $success; ?>',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'main.php';
                        });
                    </script>
                <?php endif; ?>

                <?php if (isset($err)): ?>
                    <p style="color: red;"><?php echo $err; ?></p>
                <?php endif; ?>
            </div>
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
