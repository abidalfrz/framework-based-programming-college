<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="info wrap"><h1>Welcome <em>back.</em></h1><p>Sign in to your ShopEase account.</p>
<?php if (session('error')): ?><p role="alert"><?= esc(session('error')) ?></p><?php endif ?>
<?php if (session('success')): ?><p role="status"><?= esc(session('success')) ?></p><?php endif ?>
<form method="post" action="<?= site_url('login') ?>" class="account-form"><?= csrf_field() ?>
<input type="hidden" name="return_to" value="<?= esc($returnTo) ?>">
<label for="email">Email</label><input id="email" type="email" name="email" required autocomplete="email">
<label for="password">Password</label><input id="password" type="password" name="password" required autocomplete="current-password">
<button class="button button-dark" type="submit">Sign in</button></form><p>New to ShopEase? <a href="<?= site_url('register') ?>">Create an account</a></p></section>
<?= $this->endSection() ?>
