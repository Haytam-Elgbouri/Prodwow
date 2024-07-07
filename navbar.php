<div class="navbar">
    <div class="logo div">
        <img src="img/prodwow logo.png" alt="" class="logo">
    </div>
    <div class="search-container">
        <form action="search.php" method="GET">
            <input type="text" name="query" placeholder="Search...">
            <button type="submit">
                <img src="img/icons/white/search-icon.png" alt="" class="icons search-icon">
            </button>
        </form>
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="product.php">Products</a>
        <a href="AboutUs.php">About us</a>
        <?php if (isset($_SESSION['userId']) || isset($_SESSION['adminId'])): ?>
            <?php if (isset($_SESSION['userId'])): ?>
                <a href="myaccount.php"><?php echo $_SESSION['email']; ?></a>
            <?php endif; ?>
            <?php if (isset($_SESSION['adminId'])): ?>
                <a href="admindashboard.php">Admin Dashboard</a>
            <?php endif; ?>
            <form action="logout.php" method="POST" style="display:inline;">
                <button type="submit" class="login">Logout</button>
            </form>
        <?php else: ?>
            <a class="login" href="login.php">Sign In</a>
        <?php endif; ?>
    </div>
</div>
