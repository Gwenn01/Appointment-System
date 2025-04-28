<?php
require(__DIR__ . '/../Database/database.php');

if (!isset($_SESSION['userid'])) {
    if (!isset($_SESSION['userid'])) {
        header("Location: ../login.php");
        exit();
    }
}

$user_id = $_SESSION['userid'];
$serviceOptions = [];
$upcoming = [];
$bookedSlots = [];
$selectedDate = '';

// Fetch available services
$serviceResult = mysqli_query($conn, "SELECT id, service_name FROM services ORDER BY service_name ASC");
while ($row = mysqli_fetch_assoc($serviceResult)) {
    $serviceOptions[] = $row;
}

// Fetch upcoming appointment
$query = "
    SELECT a.id, s.service_name, t.slot_date, t.start_time, a.status
    FROM appointments a
    JOIN services s ON a.service_id = s.id
    JOIN time_slots t ON a.time_slot_id = t.id
    WHERE a.user_id = ?
      AND t.slot_date >= CURDATE()
      AND a.status IN ('pending', 'confirmed')
    ORDER BY t.slot_date ASC, t.start_time ASC
    LIMIT 1
";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($result)) {
    $upcoming = $row;
}

// Check booked slots
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_date'])) {
    $selectedDate = $_POST['slot_date'];
    $stmt = mysqli_prepare($conn, "SELECT start_time FROM time_slots WHERE slot_date = ? AND is_booked = 1");
    mysqli_stmt_bind_param($stmt, "s", $selectedDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $bookedSlots[] = date("g:i A", strtotime($row['start_time']));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments | Customer Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Dashboard/style/home.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

<!-- Header -->
<header class="header">
    <h4>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h4>
    <button class="btn-light" id="openModalBtn">
        <i class="fa-solid fa-plus"></i> New Appointment
    </button>
</header>

<!-- Main Content -->
<main class="container">

    <!-- Section: Check Available Appointment -->
    <section class="section">
        <h5 class="section-title"><i class="fa-solid fa-magnifying-glass"></i> Check Available Appointment</h5>
        <form method="POST" action="" class="form-grid">
            <div class="form-group">
                <label for="datePicker" class="form-label">Select Date</label>
                <input type="date" name="slot_date" id="datePicker" class="form-control" value="<?= htmlspecialchars($selectedDate) ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" name="check_date" class="btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i> Check Availability
                </button>
            </div>
        </form>

        <?php if (!empty($selectedDate)): ?>
            <div class="alert-info">
                <strong>Booked Slots on <?= htmlspecialchars($selectedDate) ?>:</strong><br>
                <?= count($bookedSlots) > 0 ? implode(', ', $bookedSlots) : 'No slots booked yet.' ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Section: Upcoming Appointment -->
    <section class="section">
        <h5 class="section-title"><i class="fa-solid fa-calendar-days"></i> Upcoming Appointment</h5>

        <?php if (!empty($upcoming)): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-heart-circle-check"></i> <?= htmlspecialchars($upcoming['service_name']) ?></h5>
                    <p>
                        <strong>Date:</strong> <?= htmlspecialchars($upcoming['slot_date']) ?><br>
                        <strong>Time:</strong> <?= date("g:i A", strtotime($upcoming['start_time'])) ?><br>
                        <strong>Status:</strong>
                        <span class="badge <?= $upcoming['status'] === 'confirmed' ? 'bg-success' : 'bg-warning' ?>">
                            <?= ucfirst($upcoming['status']) ?>
                        </span>
                    </p>
                    <form method="POST" action="Backend/cancel_appointment.php" onsubmit="return confirm('Cancel this appointment?');">
                        <input type="hidden" name="appointment_id" value="<?= $upcoming['id'] ?>">
                        <button class="btn-outline-danger">
                            <i class="fa-solid fa-xmark-circle"></i> Cancel
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert-secondary">You have no upcoming appointments.</div>
        <?php endif; ?>
    </section>

</main>

<!-- Modal: Book Appointment -->
<div class="modal" id="newAppointmentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-calendar-plus"></i> Book an Appointment</h5>
            <button type="button" class="close-modal" id="closeModalBtn">&times;</button>
        </div>

        <div class="modal-body">
            <?php if (!empty($selectedDate)): ?>
                <form method="POST" action="Backend/book_appointment.php">
                    <input type="hidden" name="slot_date" value="<?= htmlspecialchars($selectedDate) ?>">

                    <div class="form-group">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" id="startTime" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Service</label>
                        <select name="service" class="form-control" required>
                            <option value="">Choose a service</option>
                            <?php foreach ($serviceOptions as $service): ?>
                                <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['service_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Any special instructions..."></textarea>
                    </div>

                    <button type="submit" class="btn-success w-100">Set Appointment</button>
                </form>
            <?php else: ?>
                <div class="alert-warning">Please check available slots first before booking.</div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Scripts -->
<script src="Dashboard/script/home.js"></script>
<script>
    // Modal Handling
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modal = document.getElementById('newAppointmentModal');

    openModalBtn.addEventListener('click', () => {
        modal.classList.add('show');
    });

    closeModalBtn.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    window.addEventListener('click', (e) => {
        if (e.target == modal) {
            modal.classList.remove('show');
        }
    });

    // Date Validation
    const datePicker = document.getElementById('datePicker');
    const today = new Date().toISOString().split('T')[0];
    datePicker.min = today;

    datePicker.addEventListener('change', () => {
        const day = new Date(datePicker.value).getDay();
        if (day === 0 || day === 6) {
            alert("Appointments are only available on weekdays. Please choose another date.");
            datePicker.value = '';
        }
    });
</script>
</body>
</html>
