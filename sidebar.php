<?php
$uid = $_SESSION['uid'];
include 'dbconnect.php';

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

$current_page = basename($_SERVER['PHP_SELF']);
$profileimg = $_SESSION["profileimg"] ?? 'profile.jpg';
$is_default_img = $profileimg === 'profile.jpg';
?>
<link rel="stylesheet" href="css/sidebar.css">
<aside class="sidebar" id="sidebar">
<ul class="menu">
    <?php if ($_SESSION['type'] === 'company'): ?>
      <li><a href="./joblist.php?type=company" class="menu-item"><i class="fas fa-briefcase"></i> Jobs</a></li>
      <li><a href="./applicant_list.php" class="menu-item"><i class="fas fa-users"></i> Applicant List</a></li>
    <?php else: ?>
      <li><a href="./joblist.php" class="menu-item"><i class="fas fa-briefcase"></i> Jobs</a></li>
      <li><a href="./my_joblist.php" class="menu-item"><i class="fas fa-list"></i> My Jobs List</a></li>
      <li><a href="./discussion.php" class="menu-item"><i class="fas fa-comments"></i> Discussion</a></li>
      <li><a href="./profile.php" class="menu-item <?php echo $current_page === 'profile.php' ? 'active' : ''; ?>"><i class="fas fa-user"></i> Profile</a></li>
    <?php endif; ?>
  </ul>

  <div class="settings">
    <h4>SETTINGS</h4>
    <a href="settings.php" class="menu-item"><i class="fas fa-cog"></i> Settings</a>
  </div>

  <div class="user-profile">
    <?php if (!$is_default_img): ?>
      <div class="avatar-img">
        <img src="<?php echo htmlspecialchars($profileimg); ?>" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
      </div>
    <?php else: ?>
      <div class="avatar-initials">
        <?php echo strtoupper($name[0]); ?>
      </div>
    <?php endif; ?>
    <div>
      <h5><?php echo htmlspecialchars($name); ?></h5>
      <p><?php echo htmlspecialchars($email); ?></p>
    </div>
  </div>
</aside>
