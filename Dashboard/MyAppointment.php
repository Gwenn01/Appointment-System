<?php
if (!isset($_SESSION['userid'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments | Appointment System</title>
    <link rel="stylesheet" href="Dashboard/style/appointment.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>

<?php
require(__DIR__ . '/../Database/database.php');

$user_id = $_SESSION['userid'] ?? null;
$appointments = [];

if ($user_id) {
    $query = "
        SELECT 
            a.id,
            s.service_name,
            t.slot_date,
            t.start_time,
            a.status
        FROM appointments a
        JOIN services s ON a.service_id = s.id
        JOIN time_slots t ON a.time_slot_id = t.id
        WHERE a.user_id = ?
        ORDER BY t.slot_date DESC, t.start_time DESC
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[] = $row;
    }
}
?>

<div class="appointments-container">
    <h2 class="title"><i class="bi bi-calendar-check"></i> My Appointments</h2>

    <div class="table-wrapper">
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($appointments) > 0): ?>
                    <?php foreach ($appointments as $appt): ?>
                        <?php
                            $start = date("g:i A", strtotime($appt['start_time']));
                            $status = strtolower($appt['status']);
                            $badgeClass = match($status) {
                                'confirmed' => 'badge-confirmed',
                                'pending' => 'badge-pending',
                                'cancelled' => 'badge-cancelled',
                                default => 'badge-secondary'
                            };
                        ?>
                        <tr>
                            <td><?= htmlspecialchars(str_pad($appt['id'], 3, '0', STR_PAD_LEFT)) ?></td>
                            <td><?= htmlspecialchars($appt['service_name']) ?></td>
                            <td><?= htmlspecialchars($appt['slot_date']) ?></td>
                            <td><?= $start ?></td>
                            <td><span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span></td>
                            <td>
                                <?php if ($status !== 'cancelled'): ?>
                                    <form method="POST" action="Backend/cancel_appointment.php" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                        <input type="hidden" name="appointment_id" value="<?= $appt['id'] ?>">
                                        <button type="submit" class="cancel-btn">
                                            <i class="bi bi-x-circle"></i> Cancel
                                        </button>
                                    </form>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="no-data">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
