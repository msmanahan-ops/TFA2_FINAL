<?php
$errors = session()->getFlashdata('errors') ?? [];
$isEdit = $user !== null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $isEdit ? 'Edit User' : 'New User' ?></title>
</head>
<body>
    <h1><?= $isEdit ? 'Edit User' : 'New User' ?></h1>
    <p><a href="<?= site_url('users') ?>">Back to users</a></p>

    <?php if (isset($errors['form'])): ?>
        <p style="color:red"><?= esc($errors['form']) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= esc($action, 'attr') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <p>
            <label for="username">Username</label><br>
            <input id="username" name="username" required maxlength="50"
                   value="<?= old('username', $user['username'] ?? '') ?>">
            <br><span style="color:red"><?= esc($errors['username'] ?? '') ?></span>
        </p>

        <p>
            <label for="full_name">Full name</label><br>
            <input id="full_name" name="full_name" required maxlength="100"
                   value="<?= old('full_name', $user['full_name'] ?? '') ?>">
            <br><span style="color:red"><?= esc($errors['full_name'] ?? '') ?></span>
        </p>

        <p>
            <label for="email">Email</label><br>
            <input id="email" type="email" name="email" required
                   value="<?= old('email', $user['email'] ?? '') ?>">
            <br><span style="color:red"><?= esc($errors['email'] ?? '') ?></span>
        </p>

        <p>
            <label for="password">
                Password<?= $isEdit ? ' (leave blank to keep the current password)' : '' ?>
            </label><br>
            <input id="password" type="password" name="password"
                   <?= $isEdit ? '' : 'required' ?> minlength="8">
            <br><span style="color:red"><?= esc($errors['password'] ?? '') ?></span>
        </p>

        <?php if ($isEdit): ?>
            <p>
                <label for="avatar">Profile picture (JPG or PNG, up to 2 MB)</label><br>
                <input id="avatar" type="file" name="avatar" accept=".jpg,.jpeg,.png">
                <br><span style="color:red"><?= esc($errors['avatar'] ?? '') ?></span>
            </p>

            <?php if (! empty($user['avatar'])): ?>
                <img src="<?= base_url('uploads/avatars/' . rawurlencode($user['avatar'])) ?>"
                     alt="Current avatar" width="100" height="100">
            <?php endif; ?>
        <?php endif; ?>

        <button type="submit"><?= $isEdit ? 'Save changes' : 'Add user' ?></button>
    </form>
</body>
</html>