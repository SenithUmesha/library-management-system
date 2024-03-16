<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
    ::after,
    ::before {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    a {
        text-decoration: none;
    }

    li {
        list-style: none;
    }

    h1 {
        font-weight: 600;
        font-size: 1.5rem;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    .wrapper {
        display: flex;
    }

    .main {
        min-height: 100vh;
        width: 100%;
        overflow: hidden;
        transition: all 0.35s ease-in-out;
        background-color: #fafbfe;
    }

    #sidebar {
        width: 70px;
        min-width: 70px;
        z-index: 1000;
        transition: all .25s ease-in-out;
        background-color: #0e2238;
        display: flex;
        flex-direction: column;
    }

    #sidebar.expand {
        width: 230px;
        min-width: 230px;
    }

    .d-flex {
        margin-top: 1.15rem;
    }

    .toggle-btn {
        background-color: transparent;
        cursor: pointer;
        border: 0;
        padding: 1rem 1.5rem;
    }

    .toggle-btn i {
        font-size: 1.5rem;
        color: #FFF;
    }

    .sidebar-logo {
        margin: auto 0;
    }

    .sidebar-logo a {
        color: #FFF;
        font-size: 1.15rem;
        font-weight: 600;
    }

    #sidebar:not(.expand) .sidebar-logo,
    #sidebar:not(.expand) a.sidebar-link span {
        display: none;
    }

    .sidebar-nav {
        padding: 2rem 0;
        flex: 1 1 auto;
    }

    a.sidebar-link {
        padding: .625rem 1.625rem;
        color: #FFF;
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    .sidebar-link i {
        font-size: 1.2rem;
        margin-right: 1rem;
    }

    a.sidebar-link:hover {
        background-color: rgba(255, 255, 255, .075);
        border-left: 3px solid #3b7ddd;
    }

    .sidebar-item {
        position: relative;
        height: 50px;
    }

    #sidebar:not(.expand) .sidebar-item .sidebar-dropdown {
        position: absolute;
        top: 0;
        left: 70px;
        background-color: #0e2238;
        padding: 0;
        min-width: 15rem;
        display: none;
    }

    #sidebar:not(.expand) .sidebar-item:hover .has-dropdown+.sidebar-dropdown {
        display: block;
        max-height: 50px;
        width: 100%;
        opacity: 1;
    }

    #sidebar.expand .sidebar-link[data-bs-toggle="collapse"]::after {
        border: solid;
        border-width: 0 .075rem .075rem 0;
        content: "";
        display: inline-block;
        padding: 2px;
        position: absolute;
        right: 1.5rem;
        top: 1.4rem;
        transform: rotate(-135deg);
        transition: all .2s ease-out;
    }

    #sidebar.expand .sidebar-link[data-bs-toggle="collapse"].collapsed::after {
        transform: rotate(45deg);
        transition: all .2s ease-out;
    }

    .sidebar-footer {
        position: relative;
        bottom: 0;
    }
</style>

<body>
    <aside id="sidebar">
        <div class="d-flex">
            <button class="toggle-btn" type="button">
                <i class="lni lni-grid-alt"></i>
            </button>
            <div class="sidebar-logo">
                <a href="#">Library Management System</a>
            </div>
        </div>
        <ul class="sidebar-nav">
            <?php if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == "admin") { ?>
                <li class="sidebar-item <?php echo $current_page == 'students.php' ? 'active' : ''; ?>">
                    <a href="students.php" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Students</span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $current_page == 'admins.php' ? 'active' : ''; ?>">
                    <a href="admins.php" class="sidebar-link">
                        <i class="lni lni-users"></i>
                        <span>Admins</span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $current_page == 'books.php' ? 'active' : ''; ?>">
                    <a href="books.php" class="sidebar-link">
                        <i class="bi bi-book"></i>
                        <span>Books</span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $current_page == 'borrowed_books.php' ? 'active' : ''; ?>">
                    <a href="borrowed_books.php" class="sidebar-link">
                        <i class="bi bi-book-half"></i>
                        <span>Borrowed Books</span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $current_page == 'returned_books.php' ? 'active' : ''; ?>">
                    <a href="returned_books.php" class="sidebar-link">
                        <i class="bi bi-arrow-return-right"></i>
                        <span>Returned Books</span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $current_page == 'fines.php' ? 'active' : ''; ?>">
                    <a href="fines.php" class="sidebar-link">
                        <i class="lni lni-coin"></i>
                        <span>Fines</span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $current_page == 'reservations.php' ? 'active' : ''; ?>">
                    <a href="reservations.php" class="sidebar-link">
                        <i class="bi bi-bookmark"></i>
                        <span>Reservations</span>
                    </a>
                </li>
            <?php } elseif (isset($_SESSION['account_type']) && $_SESSION['account_type'] == "student") { ?>
                <li class="sidebar-item  <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                    <a href="profile.php" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $current_page == 'books.php' ? 'active' : ''; ?>">
                    <a href="books.php" class="sidebar-link">
                        <i class="bi bi-book"></i>
                        <span>Books</span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $current_page == 'borrowed_books.php' ? 'active' : ''; ?>">
                    <a href="borrowed_books.php" class="sidebar-link">
                        <i class="bi bi-book-half"></i>
                        <span>Borrowed Books</span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $current_page == 'returned_books.php' ? 'active' : ''; ?>">
                    <a href="returned_books.php" class="sidebar-link">
                        <i class="bi bi-arrow-return-right"></i>
                        <span>Returned Books</span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $current_page == 'fines.php' ? 'active' : ''; ?>">
                    <a href="fines.php" class="sidebar-link">
                        <i class="lni lni-coin"></i>
                        <span>Fines</span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo $current_page == 'reservations.php' ? 'active' : ''; ?>">
                    <a href="reservations.php" class="sidebar-link">
                        <i class="bi bi-bookmark"></i>
                        <span>Reservations</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <div class="sidebar-footer">
            <a href="../logout.php" class="sidebar-link">
                <i class="lni lni-exit"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <script>
        const hamBurger = document.querySelector(".toggle-btn");

        hamBurger.addEventListener("click", function() {
            document.querySelector("#sidebar").classList.toggle("expand");
        });
    </script>

</body>