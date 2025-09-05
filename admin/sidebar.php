<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon"><img src="../public/img/logo.png" alt="Rainbucks Logo" style="height:32px; width:auto;" onerror="this.style.display='none';this.parentNode.textContent='R';"></div>
            <div class="logo-text">Rainbucks</div>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-item">
            <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </div>
        
        <div class="nav-item">
            <a href="#" class="nav-link" onclick="toggleSubmenu('packages-menu')">
                <i class="fas fa-box"></i>
                Packages
                <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
            </a>
            <div class="nav-submenu <?php echo strpos($_SERVER['PHP_SELF'], 'packages.php') !== false ? 'open' : ''; ?>" id="packages-menu">
                <a href="packages.php?action=add" class="nav-link">
                    <i class="fas fa-plus"></i>
                    Add
                </a>
                <a href="packages.php" class="nav-link">
                    <i class="fas fa-list"></i>
                    List
                </a>
            </div>
        </div>
        
        <div class="nav-item">
            <a href="#" class="nav-link" onclick="toggleSubmenu('courses-menu')">
                <i class="fas fa-graduation-cap"></i>
                Courses
                <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
            </a>
            <div class="nav-submenu <?php echo strpos($_SERVER['PHP_SELF'], 'courses.php') !== false ? 'open' : ''; ?>" id="courses-menu">
                <a href="courses.php?action=add" class="nav-link">
                    <i class="fas fa-plus"></i>
                    Add
                </a>
                <a href="courses.php" class="nav-link">
                    <i class="fas fa-list"></i>
                    List
                </a>
            </div>
        </div>
        
        <div class="nav-item">
            <a href="#" class="nav-link" onclick="toggleSubmenu('testimonials-menu')">
                <i class="fas fa-comments"></i>
                Testimonials
                <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
            </a>
            <div class="nav-submenu <?php echo strpos($_SERVER['PHP_SELF'], 'testimonials.php') !== false ? 'open' : ''; ?>" id="testimonials-menu">
                <a href="testimonials.php?action=add" class="nav-link">
                    <i class="fas fa-plus"></i>
                    Add
                </a>
                <a href="testimonials.php" class="nav-link">
                    <i class="fas fa-list"></i>
                    List
                </a>
            </div>
        </div>
    </nav>
</div>

<script>
function toggleSubmenu(menuId) {
    const submenu = document.getElementById(menuId);
    submenu.classList.toggle('open');
}
</script>
