<?php
session_start();

if (isset($_SESSION['admin'])) {
    $admin = $_SESSION['admin'];
}

if (isset($_SESSION['student-name'])) {
    $student = $_SESSION['student-name'];
}

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
        max-height: 15em;
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
            <li class="sidebar-item <?php echo $current_page == 'viewstudents_new.php' ? 'active' : ''; ?>">
                <a href="viewstudents_new.php" class="sidebar-link">
                    <i class="lni lni-user"></i>
                    <span>Students</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="bookstable_new.php" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#books" aria-expanded="false" aria-controls="books">
                    <i class="lni lni-book"></i>
                    <span>Books</span>
                </a>

                <ul id="books" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item <?php echo $current_page == 'bookstable_new.php' ? 'active' : ''; ?>">
                        <a href="bookstable_new.php" class="sidebar-link">All Books</a>
                    </li>
                </ul>
                <ul id="books" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item <?php echo $current_page == 'bookstable_new.php' ? 'active' : ''; ?>">
                        <a href="borrowedbooks_new.php" class="sidebar-link">Borrowed Books</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-item <?php echo $current_page == 'users_new.php' ? 'active' : ''; ?>">
                <a href="users_new.php" class="sidebar-link">
                    <i class="lni lni-users"></i>
                    <span>Admins</span>
                </a>
            </li>
            <li class="sidebar-item <?php echo $current_page == 'fines_new.php' ? 'active' : ''; ?>">
                <a href="fines_new.php" class="sidebar-link">
                    <i class="lni lni-coin"></i>
                    <span>Fines</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo $current_page == 'returned_books.php' ? 'active' : ''; ?>">
                <a href="returned_books.php" class="sidebar-link">
                    <i class="bi bi-arrow-return-right"></i>
                    <span>Returned Books</span>
                </a>
            </li>



            <li class="sidebar-item <?php echo $current_page == 'reserved_books_admin.php' ? 'active' : ''; ?>">
                <a href="reserved_books_admin.php" class="sidebar-link">
                    <i class="bi bi-arrow-down"></i>
                    <span>Reserved Books</span>
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <a href="index.php" class="sidebar-link">
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