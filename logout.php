<?php
session_start();
session_unset();
session_destroy(); // Menghapus semua jejak login
header("Location: login.php"); // Melempar balik ke login
exit;