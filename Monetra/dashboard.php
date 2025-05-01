<?php
session_start();
require_once 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$current_month = date('m');
$current_year = date('Y');

// Get current month's income and expenses
$sql = "SELECT 
            c.type,
            SUM(t.montant) as total
        FROM transactions t
        JOIN categories c ON t.category_id = c.id
        WHERE t.user_id = ?
        AND MONTH(t.date_transaction) = ?
        AND YEAR(t.date_transaction) = ?
        GROUP BY c.type";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $current_month, $current_year]);
$totals = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $totals[$row['type']] = $row['total'];
}

$total_income = $totals['revenu'] ?? 0;
$total_expenses = $totals['depense'] ?? 0;
$current_balance = $total_income - $total_expenses;

// Get totals by category
$sql = "SELECT 
            c.nom as category,
            c.type,
            SUM(t.montant) as total
        FROM transactions t
        JOIN categories c ON t.category_id = c.id
        WHERE t.user_id = ?
        AND MONTH(t.date_transaction) = ?
        AND YEAR(t.date_transaction) = ?
        GROUP BY c.id
        ORDER BY c.type, total DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $current_month, $current_year]);
$category_totals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get highest transactions
$sql = "SELECT 
            t.*,
            c.nom as category_name,
            c.type
        FROM transactions t
        JOIN categories c ON t.category_id = c.id
        WHERE t.user_id = ?
        AND MONTH(t.date_transaction) = ?
        AND YEAR(t.date_transaction) = ?
        AND c.type = ?
        ORDER BY t.montant DESC
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $current_month, $current_year, 'revenu']);
$highest_income = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt->execute([$user_id, $current_month, $current_year, 'depense']);
$highest_expense = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Monetra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
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
                        <a class="nav-link active" href="dashboard.php"><i class="bi bi-graph-up"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transactions.php"><i class="bi bi-cash-stack"></i> Transactions</a>
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
        <div class="dashboard-header text-center">
            <h2 class="mb-3"><i class="bi bi-graph-up"></i> Financial Dashboard</h2>
            <p class="lead mb-0">Your financial summary for <?php echo date('F Y'); ?></p>
        </div>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body text-center">
                        <div class="icon-circle mb-3">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h5 class="card-title">Current Balance</h5>
                        <div class="dashboard-summary <?php echo $current_balance >= 0 ? 'text-success' : 'text-danger'; ?>">
                            <?php echo number_format($current_balance, 2); ?> DH
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body text-center">
                        <div class="icon-circle mb-3">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h5 class="card-title">Total Income</h5>
                        <div class="dashboard-summary text-success">
                            <?php echo number_format($total_income, 2); ?> DH
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body text-center">
                        <div class="icon-circle mb-3">
                            <i class="bi bi-graph-down-arrow"></i>
                        </div>
                        <h5 class="card-title">Total Expenses</h5>
                        <div class="dashboard-summary text-danger">
                            <?php echo number_format($total_expenses, 2); ?> DH
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Breakdown -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="dashboard-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0"><i class="bi bi-arrow-up-circle"></i> Income by Category</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-custom">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($category_totals as $cat): ?>
                                        <?php if ($cat['type'] === 'revenu'): ?>
                                            <tr>
                                                <td><span class="category-tag"><?php echo htmlspecialchars($cat['category']); ?></span></td>
                                                <td class="text-end text-success"><?php echo number_format($cat['total'], 2); ?> DH</td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0"><i class="bi bi-arrow-down-circle"></i> Expenses by Category</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-custom">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($category_totals as $cat): ?>
                                        <?php if ($cat['type'] === 'depense'): ?>
                                            <tr>
                                                <td><span class="category-tag"><?php echo htmlspecialchars($cat['category']); ?></span></td>
                                                <td class="text-end text-danger"><?php echo number_format($cat['total'], 2); ?> DH</td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Highest Transactions -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="dashboard-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0"><i class="bi bi-trophy"></i> Highest Income</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($highest_income): ?>
                            <div class="amount-badge bg-success text-white mb-3">
                                <?php echo number_format($highest_income['montant'], 2); ?> DH
                            </div>
                            <p><strong>Category:</strong> <span class="category-tag"><?php echo htmlspecialchars($highest_income['category_name']); ?></span></p>
                            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($highest_income['date_transaction'])); ?></p>
                            <p class="mb-0"><strong>Description:</strong> <?php echo htmlspecialchars($highest_income['description']); ?></p>
                        <?php else: ?>
                            <p class="text-muted">No income recorded this month</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0"><i class="bi bi-exclamation-triangle"></i> Highest Expense</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($highest_expense): ?>
                            <div class="amount-badge bg-danger text-white mb-3">
                                <?php echo number_format($highest_expense['montant'], 2); ?> DH
                            </div>
                            <p><strong>Category:</strong> <span class="category-tag"><?php echo htmlspecialchars($highest_expense['category_name']); ?></span></p>
                            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($highest_expense['date_transaction'])); ?></p>
                            <p class="mb-0"><strong>Description:</strong> <?php echo htmlspecialchars($highest_expense['description']); ?></p>
                        <?php else: ?>
                            <p class="text-muted">No expenses recorded this month</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

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