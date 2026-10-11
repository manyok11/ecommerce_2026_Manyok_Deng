<?php
// =============================================================
// controller/CustomerController.php  —  COMPATIBILITY SHIM
// -------------------------------------------------------------
// The lab spec uses controllers/ (plural). This file is kept so
// any path that still points to controller/ (singular) continues
// to work. It simply loads the real controller from controllers/.
// =============================================================

require_once __DIR__ . '/../controllers/CustomerController.php';
