<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS Login</title>
</head>
<body>
    <h1>POS Login</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;">
            <?= esc(session()->getFlashdata('error')) ?>
        </p>
    <?php endif; ?>

    <form action="<?= site_url('login') ?>" method="post">
        <?= csrf_field() ?>

        <div>
            <label for="username">Username</label>
            <input id="username" name="username" type="text"
                   value="<?= esc(old('username')) ?>" required>
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>

        <button type="submit">Log in</button>
    </form>
</body>
</html>