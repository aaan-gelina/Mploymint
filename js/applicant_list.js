document.addEventListener("DOMContentLoaded", function() {
    const applicantData = [
        { name: "Jane Doe", email: "jane@example.com", resume: "Link", status: "Pending" },
        { name: "John Smith", email: "john@example.com", resume: "Link", status: "Reviewed" },
        { name: "Alice Johnson", email: "alice@example.com", resume: "Link", status: "Pending" },
        { name: "Bob Brown", email: "bob@example.com", resume: "Link", status: "Reviewed" },
        { name: "Carol White", email: "carol@example.com", resume: "Link", status: "Pending" },
        { name: "David Green", email: "david@example.com", resume: "Link", status: "Pending" },
        { name: "Eva Black", email: "eva@example.com", resume: "Link", status: "Reviewed" },
        { name: "Frank Grey", email: "frank@example.com", resume: "Link", status: "Pending" }
    ];

    const applicantTable = document.getElementById("applicantTable");

    applicantData.forEach(applicant => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${applicant.name}</td>
            <td>${applicant.email}</td>
            <td><a href="${applicant.resume}">View</a></td>
            <td>${applicant.status}</td>
        `;
        applicantTable.appendChild(row);
    });

    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
});