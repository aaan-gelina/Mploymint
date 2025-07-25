document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // Handle file input styling
    const fileInput = document.getElementById('resume');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const fileName = this.files[0]?.name;
            if (fileName) {
                // You could add UI feedback here if needed, like updating the label
                const label = document.querySelector('.resume-upload .file-label');
                if (label) {
                    label.textContent = fileName;
                }
                console.log('Selected file:', fileName);
            }
        });
    }

    // Form submission handling
    const profileForm = document.querySelector('.profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            // Add any form validation if needed
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!name || !email) {
                e.preventDefault();
                alert('Name and email are required fields');
            }
        });
    }
});