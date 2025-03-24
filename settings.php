<?php
    session_start();
    include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';

    if (!isset($_SESSION['uid'])) {
        die("You must log in to update your account.");
    }

    $uid = $_SESSION['uid'];

    $user_query = $db->prepare("SELECT email, name, type, description, profileimg FROM user WHERE uid = ?");
    $user_query->bind_param("i", $uid);
    $user_query->execute();
    $user_query->bind_result($email, $name, $type, $description, $profileimg);
    $user_query->fetch();
    $user_query->close();

    if (!(htmlspecialchars($type) == "company")) {
        $name_parts = explode("`", $name);
        $firstname = $name_parts[0] ?? '';
        $lastname = $name_parts[1] ?? '';
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings</title>
    <link rel="stylesheet" href="./css/settings.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  </head>
  <body>
    <?php include "top-navbar.php"; ?>
    <button class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <div class="cont">
        <?php include "sidebar.php"; ?>
        <main class="layout">
            <div class="container">
                <div class="image-container">
                    <div class="image">
                        <img src="<?= htmlspecialchars($profileimg ?? 'img/profile.jpg'); ?>" alt="Profile Photo">
                    </div>
                    <label class="updatephoto">
                        <a id="trigger-upload">Update Photo</a>
                        <input type="file" id="profile-img-input" style="display: none;" accept="image/*">
                    </label>
                </div>
                <div class="settings">
                    <div class="userdata">
                        <div>
                            <p><label for="email">Email: </label><?= htmlspecialchars($email); ?></p>
                            <?php if (!(htmlspecialchars($type) == "company")) { ?>
                                <p><label for="firstname">First Name: </label><?= htmlspecialchars($firstname); ?></p>
                                <p><label for="lastname">Last Name: </label><?= htmlspecialchars($lastname); ?></p>
                            <?php } else { ?>
                                <p><label for="name">Company Name: </label><?= htmlspecialchars($name); ?></p>
                            <?php } ?>
                        </div>
                        <div>
                            <p class="para"><label for="desc">Description: </label><?= htmlspecialchars($description); ?></p>
                        </div>
                    </div>
                    <button type="button" id="edit" class="button">Edit Info</button>
                </div>
                <div class="update-container">
                    <form id="update-form" class="update-settings">
                        <div class="form-container">
                            <div>
                                <label>Update Account Info</label>
                                <p class="inputs"><label for="email">Email: </label><input name="email" type="email" value="<?= htmlspecialchars($email); ?>" required /></p>
                                <?php if (!(htmlspecialchars($type) == "company")) { ?>
                                    <p class="inputs"><label for="firstname">First Name: </label><input name="firstname" type="text" value="<?= htmlspecialchars($firstname); ?>" required /></p>
                                    <p class="inputs"><label for="lastname">Last Name: </label><input name="lastname" type="text" value="<?= htmlspecialchars($lastname); ?>" required /></p>
                                <?php } else { ?>
                                    <p class="inputs"><label for="name">Company Name: </label><input name="name" type="text" value="<?= htmlspecialchars($name); ?>" required /></p>
                                <?php } ?>
                                </div>
                            <div>
                                <label for="desc">Update Description</label>
                                <textarea id="desc" name="description" rows="4" placeholder="Enter your description here..." required><?= htmlspecialchars($description); ?></textarea>
                            </div>
                        </div>
                        <button type="button" id="submit-update" class="submit">Submit Changes</button>
                    </form>
                </div>
                <div class="reset-container">
                    <button class="button resetbutton" id="show-reset-form">Reset Password?</button>
                    <div class="resetform-container" style="display: none;">
                        <form id="reset-password-form">
                            <label>Reset Password</label>
                            <input name="pass" type="password" placeholder="Current Password" required />
                            <input name="newpass" type="password" placeholder="New Password" required />
                            <input name="confirmpass" type="password" placeholder="Re-enter New Password" required />
                        </form>
                        <div class="button-container">
                            <button class="button cancel" id="cancel-reset">Cancel</button>
                            <button type="submit" class="submit" id="reset-password-btn">Reset Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div class="footer">
      <br>
    </div>
    <script src="./js/settings.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const submitBtn = document.getElementById("submit-update");

            if (submitBtn) {
                submitBtn.addEventListener("click", function() {
                    const form = document.getElementById("update-form");
                    const formData = new FormData(form);

                    console.log("Submitting profile update:", Object.fromEntries(formData));

                    fetch("/Mploymint/php/updateuser.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                    })
                    .catch(error => console.error("Error:", error));
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const resetBtn = document.getElementById("show-reset-form");
            const resetContainer = document.querySelector(".resetform-container");
            const cancelBtn = document.getElementById("cancel-reset");
            const resetPasswordBtn = document.getElementById("reset-password-btn");
            
            resetBtn.addEventListener("click", function() {
                resetContainer.style.display = "block";
            });

            cancelBtn.addEventListener("click", function(event) {
                event.preventDefault();
                resetContainer.style.display = "none";
            });

            resetPasswordBtn.addEventListener("click", function(event) {
                event.preventDefault();

                const form = document.getElementById("reset-password-form");
                const formData = new FormData(form);

                console.log("Submitting password reset:", Object.fromEntries(formData));

                fetch("/Mploymint/php/resetpassword.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        window.location.href = window.location.href;
                    }
                })
                .catch(error => console.error("Error:", error));
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const uploadTrigger = document.getElementById("trigger-upload");
            const fileInput = document.getElementById("profile-img-input");

            uploadTrigger.addEventListener("click", function() {
                fileInput.click();
            });

            fileInput.addEventListener("change", function(event) {
                const file = event.target.files[0];

                if (file) {
                    const formData = new FormData();
                    formData.append("profileimg", file);

                    console.log("Uploading file:", file.name);

                    fetch("/Mploymint/php/uploadphoto.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Profile photo updated successfully!");
                            window.location.reload();
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
                }
            });
        });
    </script>
  </body>
</html>