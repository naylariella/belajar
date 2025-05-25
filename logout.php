<?php
// Mulai session
session_start();

// Hapus semua data session dan logout user
session_destroy();

// Redirect user ke halaman login setelah logout
header("Location: login.html");

// Pastikan script berhenti setelah redirect
exit();
?>
