<?php
$uid = $_SESSION['uid'];

$uid_query = $db->prepare("SELECT name, email, type FROM user WHERE uid = ?");
    $uid_query->bind_param("i", $uid);
    $uid_query->execute();
    $uid_query->bind_result($name, $email, $type);
    $uid_query->fetch();
    $uid_query->close();

    if (!(htmlspecialchars($type) == "company")) {
      $name_parts = explode("`", $name);
      $firstname = $name_parts[0] ?? '';
      $lastname = $name_parts[1] ?? '';
      $name = $firstname . " " . $lastname;
  }
?>
<link rel="stylesheet" href="css/sidebar.css">
<aside class="sidebar" id="sidebar">
  <ul class="menu">
    <li><a href="#" class="menu-item"><i class="fas fa-briefcase"></i> Jobs</a></li>
    <li><a href="#" class="menu-item"><i class="fas fa-list"></i> My Jobs List</a></li>
    <li><a href="discussion.php" class="menu-item active"><i class="fas fa-comments"></i> Discussion</a></li>
  </ul>

  <div class="settings">
    <h4>SETTINGS</h4>
    <a href="settings.php" class="menu-item"><i class="fas fa-cog"></i> Settings</a>
  </div>

  <div class="user-profile">
    <div class="avatar-initials">
      <?php echo strtoupper($name[0]); ?>
    </div>
    <div>
      <h5><?php echo htmlspecialchars($name); ?></h5>
      <p><?php echo htmlspecialchars($email); ?></p>
    </div>
  </div>
</aside>

