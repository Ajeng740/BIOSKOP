<?php
require_once __DIR__ . '/helpers/auth.php';
logout_user();
session_start();
set_flash('success', 'Logout berhasil.');
redirect('login.php');
