<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
require_once "php/discussion-function.php";
?>

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
    <?php include "top-navbar.php"; ?>
    <button class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></button>

    <div class="container">
        <?php include "sidebar.php"; ?>

        <main class="forum">
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Search for topics and discussions">
            </div>

            <h2>Discussion Forum</h2>
            <p>Connect with professionals, ask questions, and share insights.</p>

            <div class="new-post-container">
                <form method="POST" action="php/discussion-function.php">
                    <textarea id="new-post-content" name="new-post-content" placeholder="Write something..." required></textarea>
                    <button type="submit" id="post-btn">Post</button>
                </form>
            </div>

            <div class="forum-posts">
                <?php 
                if (count($posts) > 0) {  
                    foreach ($posts as $post) { 
                        $author_name = $post['name'];
                        $first_letter = strtoupper(substr($author_name, 0, 1)); 

                        $post_text = $post['description']; 
                ?>
                <div class="post" data-post-id="<?php echo $post['did']; ?>">
                    <div class="post-header">
                        <div class="avatar-initials"><?php echo $first_letter; ?></div>
                        <div>
                            <h4><?php echo $author_name; ?></h4>
                        </div>
                    </div>
                    <p class="post-text"><?php echo nl2br($post_text); ?></p>
                    <?php if ($post['creatorid'] == $_SESSION['uid']) { ?>
                        <form method="POST" action="discussion.php" onsubmit="return confirm('Delete?');">
                            <input type="hidden" name="delete_post_id" value="<?php echo $post['did']; ?>">
                            <button type="submit" class="delete-btn">🗑️ Delete</button>
                        </form>
                    <?php } ?>
                </div>
                <?php 
                    }
                } else {  
                    echo "<p>No discussions yet. Post new discussion</p>"; 
                } 
                ?>
            </div>
        </main>
    </div>
    <div class="footer">
        <br>
    </div>

    <script src="js/discussion.js"></script>
</body>
</html>
