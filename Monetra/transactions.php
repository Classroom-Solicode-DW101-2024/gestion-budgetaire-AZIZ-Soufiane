<?php
session_start();
require_once 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$month = isset($_GET['month']) ? $_GET['month'] : date('n');

// Get transactions
$sql = "SELECT t.*, c.nom as category_name, c.type as category_type 
        FROM transactions t 
        JOIN categories c ON t.category_id = c.id 
        WHERE t.user_id = ? 
        AND YEAR(t.date_transaction) = ? 
        AND MONTH(t.date_transaction) = ?
        ORDER BY t.date_transaction DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id'], $year, $month]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Monetra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
        <a class="navbar-brand" href="index.php">
                <i class="bi bi-wallet2"></i> Monetra
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="bi bi-house"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="bi bi-graph-up"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="transactions.php"><i class="bi bi-cash-stack"></i> Transactions</a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <span class="nav-link">
                        <i class="bi bi-person-circle"></i> Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                    </span>
                    <a class="nav-link" href="auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-cash-stack"></i> Transactions</h2>
            <a href="transactions/add.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Transaction
            </a>
        </div>

        <!-- Filter Form -->
        <form class="row g-3 mb-4">
            <div class="col-md-4">
                <select name="year" class="form-select">
                    <?php
                    $currentYear = date('Y');
                    for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
                        $selected = ($year == $y) ? 'selected' : '';
                        echo "<option value='$y' $selected>$y</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <select name="month" class="form-select">
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                        $selected = ($month == $m) ? 'selected' : '';
                        echo "<option value='$m' $selected>" . date('F', mktime(0, 0, 0, $m, 1)) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>

        <!-- Transactions Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo date('Y-m-d', strtotime($transaction['date_transaction'])); ?></td>
                            <td><?php echo htmlspecialchars($transaction['category_name']); ?></td>
                            <td>
                                <span class="badge <?php echo $transaction['category_type'] === 'revenu' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo ucfirst($transaction['category_type']); ?>
                                </span>
                            </td>
                            <td><?php echo number_format($transaction['montant'], 2); ?> DH</td>
                            <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                            <td>
                                <a href="transactions/edit.php?id=<?php echo $transaction['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="transactions/delete.php?id=<?php echo $transaction['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this transaction?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="footer-title"><i class="bi bi-wallet2"></i> Monetra</h5>
                    <p>Your trusted partner in personal finance management.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="transactions.php">Transactions</a></li>
                        <li><a href="#">Settings</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="footer-title">Contact</h5>
                    <ul class="footer-contact">
                        <li><i class="bi bi-envelope"></i> contact@monetra.com</li>
                        <li><i class="bi bi-telephone"></i> +212 633895448</li>
                    </ul>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> Monetra. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>