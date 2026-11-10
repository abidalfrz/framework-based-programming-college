<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="info wrap"><h1>Create your <em>account.</em></h1><p>Start exploring ShopEase.</p>
<?php if ($errors = session('errors')): ?><div role="alert"><ul><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div><?php endif ?>
<form method="post" action="<?= site_url('register') ?>" class="account-form"><?= csrf_field() ?>
<label for="name">Name</label><input id="name" name="name" value="<?= esc(session('old_name') ?? '') ?>" required autocomplete="name">
<label for="email">Email</label><input id="email" type="email" name="email" value="<?= esc(session('old_email') ?? '') ?>" required autocomplete="email">
<label for="password">Password</label><input id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
<label for="password_confirm">Confirm password</label><input id="password_confirm" type="password" name="password_confirm" required minlength="8" autocomplete="new-password">
<button class="button button-dark" type="submit">Create account</button></form><p>Already have an account? <a href="<?= site_url('login') ?>">Sign in</a></p></section>
<?= $this->endSection() ?>
