<?php
// index.php - application entry point
// Admins go to the dashboard. Customers and guests see the store home page.

require_once 'core/core.php';

if (is_admin()) {
    redirect('views/admin/dashboard.php');
}

require 'views/home.php';
