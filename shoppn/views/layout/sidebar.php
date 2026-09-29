<?php
// The sidebar shows a list of product categories and brands
// so customers can filter products by clicking on them.
//
// We will fetch real data from the database in Task 5 onwards
// when ProductClass and ProductController are built.
// For now this is a placeholder sidebar so the layout works.
?>

<!-- =========================================================
     SIDEBAR
     - Categories and brands listed as clickable links
     - Links pass a GET parameter to index.php so the right
       products load (e.g. index.php?cat=1)
     ========================================================= -->
<aside class="sidebar">

    <div class="sidebar-section">
        <h3>Categories</h3>
        <ul>
            <!-- These will be dynamic (from DB) once ProductClass is built in Task 5 -->
            <li><a href="<?php echo $root; ?>index.php">All Products</a></li>
        </ul>
    </div>

    <div class="sidebar-section">
        <h3>Brands</h3>
        <ul>
            <!-- These will be dynamic (from DB) once ProductClass is built in Task 5 -->
            <li><a href="<?php echo $root; ?>index.php">All Brands</a></li>
        </ul>
    </div>

</aside>
