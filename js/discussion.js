document.addEventListener("DOMContentLoaded", () => {
    const postBtn = document.getElementById("post-btn");
    const postContent = document.getElementById("new-post-content");
    const forumPosts = document.querySelector(".forum-posts");
    const searchInput = document.querySelector(".search-input");
    const menuToggle = document.getElementById("menu-toggle");
    const sidebar = document.getElementById("sidebar");

    menuToggle.addEventListener("click", () => {
        sidebar.classList.toggle("active");
    });

    document.addEventListener("click", (event) => {
        if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
            sidebar.classList.remove("active");
        }
    });

    const userProfile = document.querySelector(".user-profile");
    const userNameElement = userProfile.querySelector("h5");
    const avatarInitialsElement = userProfile.querySelector(".avatar-initials");

    if (userNameElement && avatarInitialsElement) {
        const fullName = userNameElement.textContent.trim();
        avatarInitialsElement.textContent = getInitials(fullName);
    }

    postBtn.addEventListener("click", (event) => {
        const content = postContent.value.trim();
        if (content) {
            document.querySelector("form").submit();
        } else {
            alert("Please enter some text before posting!");
            event.preventDefault();
        }
    });

    function getInitials(name) {
        const words = name.split(" ");
        if (words.length >= 2) {
            return words[0][0].toUpperCase() + words[words.length - 1][0].toUpperCase();
        }
        return name[0].toUpperCase();
    }

    searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase();
        document.querySelectorAll(".post").forEach(post => {
            const text = post.querySelector(".post-text").textContent.toLowerCase();
            post.style.display = text.includes(query) ? "block" : "none";
        });
    });
});
