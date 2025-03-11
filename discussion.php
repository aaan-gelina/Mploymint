<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mploymint</title>
    <link rel="stylesheet" href="css/discussion.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header class="navbar">
        <h1 class="logo">Mploymint</h1>
        <nav>
            <a href="login.html" class="login-btn">Login</a>
            <button class="btn-post-job">Post a job</button>
        </nav>
    </header>

    <button class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></button>

    <div class="container">
        <aside class="sidebar" id="sidebar">
            <ul class="menu">
                <li><a href="#" class="menu-item"><i class="fas fa-briefcase"></i> Jobs</a></li>
                <li><a href="#" class="menu-item"><i class="fas fa-list"></i> My Jobs List</a></li>
                <li><a href="discussion.html" class="menu-item active"><i class="fas fa-comments"></i> Discussion</a></li>
            </ul>

            <div class="settings">
                <h4>SETTINGS</h4>
                <a href="settings.html" class="menu-item"><i class="fas fa-cog"></i> Settings</a>
            </div>

            <div class="user-profile">
                <div class="avatar-initials"></div>
                <div>
                    <h5>Kevin Kim</h5>
                    <p>kevin@gmail.com</p>
                </div>
            </div>
        </aside>

        <main class="forum">
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Search for topics and discussions">
            </div>

            <h2>Discussion Forum</h2>
            <p>Connect with professionals, ask questions, and share insights.</p>

            <div class="new-post-container">
                <textarea id="new-post-content" placeholder="Write something..."></textarea>
                <button id="post-btn">Post</button>
            </div>

            <div class="forum-posts"></div>
        </main>
    </div>

    <div class="footer">
        <br>
    </div>

    <script src="js/discussion.js"></script>
</body>
</html>
