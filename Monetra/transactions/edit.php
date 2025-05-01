<?php
session_start();
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];

// Get transaction data
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    header("Location: ../transactions.php");
    exit();
}

// Get categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY type, nom");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $montant = floatval($_POST['montant']);
    $description = $_POST['description'];
    $date = $_POST['date'];

    $sql = "UPDATE transactions 
            SET category_id = ?, montant = ?, description = ?, date_transaction = ? 
            WHERE id = ? AND user_id = ?";
    
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$category_id, $montant, $description, $date, $id, $user_id])) {
        header("Location: ../transactions.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction - Monetra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="bi bi-pencil"></i> Edit Transaction</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <optgroup label="Income">
                                        <?php foreach ($categories as $category): ?>
                                            <?php if ($category['type'] === 'revenu'): ?>
                                                <option value="<?php echo $category['id']; ?>"
                                                    <?php echo $category['id'] === $transaction['category_id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category['nom']); ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="Expense">
                                        <?php foreach ($categories as $category): ?>
                                            <?php if ($category['type'] === 'depense'): ?>
                                                <option value="<?php echo $category['id']; ?>"
                                                    <?php echo $category['id'] === $transaction['category_id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category['nom']); ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount (DH)</label>
                                <input type="number" step="0.01" name="montant" class="form-control" 
                                       value="<?php echo $transaction['montant']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($transaction['description']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" 
                                       value="<?php echo $transaction['date_transaction']; ?>" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Update Transaction</button>
                                <a href="../transactions.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>