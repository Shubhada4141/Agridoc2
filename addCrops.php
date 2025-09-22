<?php
session_start();
include('config.php');

if (isset($_POST['admin_sup'])) {
    
    $cropname = $_POST['cropName'];
    $variety = $_POST['variety'];
    $role = $_POST['role'];
    $crop_date = $_POST['crop_date'];
    $harvested = $_POST['harvested'];
    $farmer_notes = $_POST['farmer_notes'];

    $query = "INSERT INTO cropdetails (cropname,variety,role, crop_date,harvested,farmer_notes) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssis',$cropname, $variety, $role, $crop_date, $harvested, $farmer_notes);
    $stmt->execute();

    if ($stmt) {
        $success = "Crops are successfully added";
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
    <title>Add Crops Here - AgriDoc</title>
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
            <p class="form-subtitle">Add your crops here!!!</p>
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
                <input type="text" name="cropName" id="fullname" placeholder="Crop Name" required>
            </div>
            
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="text" name="variety" id="email" placeholder="Variety" required>
            </div>

            <div class="input-group">
                <i class="fas fa-user-tag role-buyer" id="role-icon"></i>
                <select name="role" id="role" required onchange="updateRoleIcon()">
                    <option value="">Select Crop Type</option>
                    <option value="vegetables">vegetables</option>
                    <option value="Fruit">Fruit</option>
                    <option value="Grain">Grain</option>
                    <option value="Legume">Legume</option>
                    <option value="Herb">Herb</option>
                    <option value="Flower">Flower</option>
                    <option value="Root Crop">Root Crop</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="input-group">
                <i class="fas fa-user-tag role-buyer" id="role-icon"></i>
                <select name="fields" id="role" onchange="updateRoleIcon()">
                    <option value="">Fields</option>
                    <option value="farmer"></option>
                </select>
            </div>

            <!-- Date Selection Field -->
            <div class="input-group">
                <i class="fas fa-calendar-alt"></i>
                <input type="text" name="crop_date" id="crop-date" placeholder="Select Date" readonly required>
            </div>
            <div id="calendar-container"></div>
            
            <style>
                /* Calendar Styles */
                #calendar-container {
                    margin: 10px 0 0 0;
                    display: none;
                    background: #fff;
                    border: 1px solid #2e7d32;
                    border-radius: 8px;
                    box-shadow: 0 2px 8px rgba(46,125,50,0.08);
                    padding: 10px;
                    z-index: 100;
                    position: absolute;
                }
                .calendar-table {
                    width: 100%;
                    border-collapse: collapse;
                    text-align: center;
                }
                .calendar-table th, .calendar-table td {
                    padding: 6px;
                    cursor: pointer;
                }
                .calendar-table th {
                    background: #2e7d32;
                    color: #fff;
                }
                .calendar-table td:hover {
                    background: #c8e6c9;
                }
                .calendar-selected {
                    background: #43a047 !important;
                    color: #fff;
                    border-radius: 50%;
                }
                .calendar-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 5px;
                }
                .calendar-nav {
                    background: none;
                    border: none;
                    color: #2e7d32;
                    font-size: 18px;
                    cursor: pointer;
                }
            </style>
            <script>
                // Simple Calendar JS
                const cropDateInput = document.getElementById('crop-date');
                const calendarContainer = document.getElementById('calendar-container');
                let selectedDate = null;
                let currentMonth = new Date().getMonth();
                let currentYear = new Date().getFullYear();

                cropDateInput.addEventListener('focus', () => {
                    calendarContainer.style.display = 'block';
                    positionCalendar();
                    renderCalendar(currentMonth, currentYear);
                });

                document.addEventListener('click', function(e) {
                    if (!calendarContainer.contains(e.target) && e.target !== cropDateInput) {
                        calendarContainer.style.display = 'none';
                    }
                });

                function positionCalendar() {
                    const rect = cropDateInput.getBoundingClientRect();
                    calendarContainer.style.top = (rect.bottom + window.scrollY) + 'px';
                    calendarContainer.style.left = (rect.left + window.scrollX) + 'px';
                }

                function renderCalendar(month, year) {
                    calendarContainer.innerHTML = '';
                    const calendarHeader = document.createElement('div');
                    calendarHeader.className = 'calendar-header';
                    calendarHeader.innerHTML = `
                        <button class="calendar-nav" onclick="prevMonth()">&lt;</button>
                        <span>${year} - ${month + 1}</span>
                        <button class="calendar-nav" onclick="nextMonth()">&gt;</button>
                    `;
                    calendarContainer.appendChild(calendarHeader);

                    const table = document.createElement('table');
                    table.className = 'calendar-table';
                    const days = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
                    let thead = '<tr>';
                    for (let d of days) thead += `<th>${d}</th>`;
                    thead += '</tr>';
                    table.innerHTML = thead;

                    const firstDay = new Date(year, month, 1).getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();

                    let date = 1;
                    for (let i = 0; i < 6; i++) {
                        let row = document.createElement('tr');
                        for (let j = 0; j < 7; j++) {
                            let cell = document.createElement('td');
                            if (i === 0 && j < firstDay) {
                                cell.innerHTML = '';
                            } else if (date > daysInMonth) {
                                cell.innerHTML = '';
                            } else {
                                cell.innerHTML = date;
                                cell.onclick = function() {
                                    selectedDate = new Date(year, month, date);
                                    cropDateInput.value = selectedDate.toISOString().slice(0,10);
                                    highlightSelected(cell);
                                    calendarContainer.style.display = 'none';
                                };
                                if (
                                    selectedDate &&
                                    selectedDate.getDate() === date &&
                                    selectedDate.getMonth() === month &&
                                    selectedDate.getFullYear() === year
                                ) {
                                    cell.className = 'calendar-selected';
                                }
                                date++;
                            }
                            row.appendChild(cell);
                        }
                        table.appendChild(row);
                        if (date > daysInMonth) break;
                    }
                    calendarContainer.appendChild(table);
                }

                function highlightSelected(cell) {
                    const cells = calendarContainer.querySelectorAll('td');
                    cells.forEach(c => c.classList.remove('calendar-selected'));
                    cell.classList.add('calendar-selected');
                }

                window.prevMonth = function() {
                    currentMonth--;
                    if (currentMonth < 0) {
                        currentMonth = 11;
                        currentYear--;
                    }
                    renderCalendar(currentMonth, currentYear);
                }
                window.nextMonth = function() {
                    currentMonth++;
                    if (currentMonth > 11) {
                        currentMonth = 0;
                        currentYear++;
                    }
                    renderCalendar(currentMonth, currentYear);
                }
            </script>

            <!-- Add this just below the calendar-container div -->
            <div class="input-group" style="margin-top: 10px; align-items: center;">
                <label for="harvested-toggle" style="margin-right: 10px; font-weight: 500;">
                    Mark as Harvested
                </label>
                <label class="switch">
                    <input type="checkbox" id="harvested-toggle" name="harvested">
                    <span class="slider round"></span>
                </label>
            </div>

            <style>
            /* Toggle Switch Styles */
            .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            vertical-align:middle;
            }
            .switch input {
            opacity: 0;
            width: 0;
            height: 0;
            }
            .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
            }
            .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            }
            input:checked + .slider {
            background-color: #2e7d32;
            }
            input:checked + .slider:before {
            transform: translateX(20px);
            }
            </style>

            <!-- Notes for Farmers (optional) -->
            <div class="input-group" style="margin-top: 10px; justify-content: flex-end;">
                <textarea name="farmer_notes" id="farmer-notes" rows="3" placeholder="Add notes for farmers (optional)" style="width: 100%; resize: vertical; border-radius: 6px; border: 1px solid #2e7d32; padding: 8px; font-size: 15px;" optional></textarea>
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
            <!--<div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Create a strong password"
                    required oninput="checkPasswordStrength()">
                <button type="button" class="password-toggle" onclick="togglePassword()">
                    <i class="fas fa-eye" id="password-eye"></i>
                </button>
            </div>-->

            <button type="submit" id="register-btn" name="admin_sup">
                <span id="register-text">Add Crop</span>
            </button>

            <div style="margin-top: 15px; text-align: center;">
                <?php if (isset($success)): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Crop Successfully Added',
                            text: '<?php echo $success; ?>',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'addCrops.php';
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
