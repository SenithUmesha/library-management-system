// scripts.js
function highlightNavItem(item) {
    // Remove 'active' class from all navbar items
    var navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(function(navItem) {
        navItem.classList.remove('active');
    });

    // Add 'active' class to the clicked navbar item
    item.parentNode.classList.add('active');
}
