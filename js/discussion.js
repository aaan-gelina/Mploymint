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

    postBtn.addEventListener("click", () => {
        const content = postContent.value.trim();
        if (content) {
            addPost(content);
            postContent.value = "";
        }
    });

    function addPost(content) {
        const formattedContent = content.replace(/\n/g, '<br>');
        const postDiv = document.createElement("div");
        postDiv.classList.add("post");
        postDiv.innerHTML = `
            <div class="post-header">
                <div class="avatar-initials">${getInitials("Anonymous")}</div>
                <div>
                    <h4>Anonymous</h4>
                    <p>Just now</p>
                </div>
            </div>
            <p class="post-text">${formattedContent}</p>
            <div class="post-actions">
                <button class="like-btn">❤️ 0</button>
                <button class="comment-btn">💬 Comment</button>
                <button class="more-btn">...</button>
                <button class="delete-btn" style="display: none;">🗑️ Delete</button>
            </div>
            <div class="comments-section" style="display: none;">
                <h4>Comments</h4>
                <div class="comments-list"></div>
                <div class="comment-input-container">
                    <textarea class="comment-input" placeholder="Write a comment..."></textarea>
                    <button class="post-comment-btn">Post</button>
                </div>
            </div>
        `;
        forumPosts.prepend(postDiv);
    }

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

    forumPosts.addEventListener("click", (event) => {
        if (event.target.classList.contains("like-btn")) {
            let count = parseInt(event.target.textContent.split(" ")[1]);
            event.target.textContent = `❤️ ${count + 1}`;
        }

        if (event.target.classList.contains("comment-btn")) {
            const commentsSection = event.target.closest(".post").querySelector(".comments-section");
            commentsSection.style.display = commentsSection.style.display === "none" ? "block" : "none";
        }

        if (event.target.classList.contains("post-comment-btn")) {
            const commentInput = event.target.previousElementSibling;
            const commentText = commentInput.value.trim();
            if (commentText) {
                const formattedComment = commentText.replace(/\n/g, '<br>');
                const commentDiv = document.createElement("div");
                commentDiv.classList.add("comment");
                commentDiv.innerHTML = `<strong>Anonymous:</strong> ${formattedComment}`;
                event.target.closest(".comments-section").querySelector(".comments-list").appendChild(commentDiv);
                commentInput.value = "";
            }
        }

        if (event.target.classList.contains("more-btn")) {
            const deleteBtn = event.target.nextElementSibling;
            deleteBtn.style.display = deleteBtn.style.display === "none" ? "inline-block" : "none";
        }

        if (event.target.classList.contains("delete-btn")) {
            const post = event.target.closest(".post");
            post.remove();
        }
    });
});
