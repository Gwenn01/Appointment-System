<?php
    if (!isset($_SESSION['adminid'])) {
        header("Location: ../admin_login.php");
        exit();
    }
    require(__DIR__ . '/../Database/database.php');

    $query = "
        SELECT 
            a.id,
            u.name AS client_name,
            t.slot_date,
            t.slot_time,
            s.service_name,
            a.status
        FROM appointments a
        JOIN users u ON a.user_id = u.id
        JOIN time_slots t ON a.time_slot_id = t.id
        JOIN services s ON a.service_id = s.id
        ORDER BY t.slot_date ASC, t.slot_time ASC
    ";

    $result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .dashboard-container {
            margin-top: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .status-pending {
            background-color: #ffc107;
            color: black;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .status-approved {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .status-rejected {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .status-pending {
            background-color: #ffc107;
            padding: 5px 10px;
            border-radius: 5px;
            color: black;
        }
        .status-approved {
            background-color: #28a745;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }
        .status-rejected {
            background-color: #dc3545;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }

    </style>
</head>
<body>
    <div class="container dashboard-container">
        <header class="text-center mb-4">
            <h1 class="fw-bold">Manage Appointments</h1>
        </header>

        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control" id="searchAppointment" placeholder="🔍 Search appointments...">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterStatus">
                    <option value="">Filter by Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add New Appointment</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Client Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="appointmentsTable">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['slot_date']); ?></td>
                            <td><?php echo date("g:i A", strtotime($row['slot_time'])); ?></td>
                            <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                            <td>
                                <?php
                                    $status = strtolower($row['status']);
                                    $statusClass = match($status) {
                                        'pending' => 'status-pending',
                                        'confirmed' => 'status-approved',
                                        'cancelled' => 'status-rejected',
                                        default => 'status-other'
                                    };
                                ?>
                                <span class="<?php echo $statusClass; ?>"><?php echo ucfirst($status); ?></span>
                            </td>
                            <td>
                                <?php if ($status === 'pending'): ?>
                                    <form method="POST" action="Backend/update_status.php" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="Backend/update_status.php" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-x-lg"></i> Reject
                                        </button>
                                    </form>
                                <?php elseif ($status === 'confirmed'): ?>
                                    <button class="btn btn-secondary btn-sm"><i class="bi bi-arrow-repeat"></i> Reschedule</button>
                                <?php else: ?>
                                    <span class="text-muted">No Actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
