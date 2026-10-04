<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'POS System') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/pos.css') ?>">
</head>
<body>
    <header class="site-header">
        <div class="container header-content">
            <strong>POS System</strong>

            <nav aria-label="Main navigation">
                <a href="<?= site_url('/') ?>">Dashboard</a>
                <a href="<?= site_url('products') ?>">Products</a>
                <a href="<?= site_url('customer-accounts') ?>">Customer Accounts</a>
                <a href="<?= site_url('user-accounts') ?>">User Accounts</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <?= $this->renderSection('content') ?>
    </main>
</body>
</html>