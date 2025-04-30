<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monetra - Budget Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">
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
                        <a class="nav-link" href="transactions.php"><i class="bi bi-cash-stack"></i> Transactions</a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <span class="nav-link">
                        <i class="bi bi-person-circle"></i> Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </span>
                    <a class="nav-link" href="auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Manage Your Finances Smartly</h1>
            <p class="lead mb-4">Track your expenses, analyze your spending patterns, and achieve your financial goals.</p>
            <a href="dashboard.php" class="btn btn-light btn-lg">Get Started <i class="bi bi-arrow-right"></i></a>
        </div>
    </section>

    <section class="container my-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <div class="icon-circle">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h5 class="card-title">Track Expenses</h5>
                        <p class="card-text">Monitor your daily expenses and income with easy-to-use tracking tools.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <div class="icon-circle">
                            <i class="bi bi-pie-chart-fill"></i>
                        </div>
                        <h5 class="card-title">Visual Reports</h5>
                        <p class="card-text">View detailed reports and charts to understand your spending patterns.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <div class="icon-circle">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="card-title">Secure Management</h5>
                        <p class="card-text">Keep your financial data safe with our secure platform.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h5 class="footer-title"><i class="bi bi-wallet2"></i> Monetra</h5>
                        <p class="footer-description">
                            Your trusted partner in personal finance management. Track, analyze, and improve your financial health with Monetra.
                        </p>
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-section">
                        <h5 class="footer-title">Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="transactions.php">Transactions</a></li>
                            <li><a href="#">Reports</a></li>
                            <li><a href="#">Settings</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-section">
                        <h5 class="footer-title">Resources</h5>
                        <ul class="footer-links">
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">Documentation</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-section">
                        <h5 class="footer-title">Contact Us</h5>
                        <ul class="footer-contact">
                            <li><i class="bi bi-envelope"></i> conact@monetra.com</li>
                            <li><i class="bi bi-telephone"></i> +212 633895448</li>
                            <li><i class="bi bi-geo-alt"></i> 123 Finance Street, Tangier 90000</li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="footer-bottom">
                <p class="copyright">&copy; <?php echo date('Y'); ?> Monetra. All rights reserved.</p>
            </div>
        </div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>