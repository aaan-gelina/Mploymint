document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.querySelector(".search-input");
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener("input", () => {
            const query = searchInput.value.toLowerCase();
            document.querySelectorAll("tbody tr").forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    }

    // Sidebar toggle functionality
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
});