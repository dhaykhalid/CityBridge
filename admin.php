<?php
session_start();
include "db.php";

// ==== حماية الصفحة ====
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = (int)$_SESSION['user_id'];

// ==== معلومات الأدمن من الداتابيس (عدّلي أسماء الجداول/الأعمدة لو مختلفة) ====
$adminStmt = $conn->prepare("
  SELECT a.username,
         ua.first_name, ua.last_name, ua.phone_number, ua.email
  FROM account a
  JOIN user_account ua ON a.account_id = ua.account_id
  WHERE a.account_id = ?
");
$adminStmt->bind_param("i", $user_id);
$adminStmt->execute();
$admin = $adminStmt->get_result()->fetch_assoc();

// ==== الإحصائيات ====
$total    = $conn->query("SELECT COUNT(*) c FROM permit")->fetch_assoc()['c'];
$pending  = $conn->query("SELECT COUNT(*) c FROM permit WHERE status='pending'")->fetch_assoc()['c'];
$approved = $conn->query("SELECT COUNT(*) c FROM permit WHERE status='approved'")->fetch_assoc()['c'];
$rejected = $conn->query("SELECT COUNT(*) c FROM permit WHERE status='rejected'")->fetch_assoc()['c'];

// ==== دوال مساعدة للتنسيق ====
function fmt_date($d){
  return $d ? date("M j, Y", strtotime($d)) : "-";
}
function type_label($t){
  return ucfirst($t) . " Permit";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - CityBridge</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<!-- ===== HEADER (نفس تصميمك) ===== -->
<header class="user-header">
  <svg viewBox="0 0 500 72" width="320" xmlns="http://www.w3.org/2000/svg">
    <text x="0" y="44" font-size="42" font-weight="700">
      <tspan fill="#e8f0fb">City</tspan><tspan fill="#4baee8">Bridge</tspan>
    </text>
    <line x1="0" y1="52" x2="220" y2="52" stroke="#4baee8" stroke-width="1.6" opacity="0.4" />
    <text x="0" y="67" font-size="10.5" fill="#7dcef8" letter-spacing="4" opacity="0.82">
      CONNECTING COMMUNITIES
    </text>
  </svg>
  <nav>
    <a href="Home.html" class="logout">LOG OUT</a>
  </nav>
</header>

<main class="dashboard">

<div class="page-title">
  <h1>Admin Dashboard</h1>
  <p>Manage and review all permit requests submitted by users.</p>
</div>

<!-- ===== ADMIN INFO ===== -->
<div class="panel">
  <h3>ADMINISTRATOR INFORMATION</h3>

  <div class="info-row"><span>FIRST NAME</span><span><?php echo htmlspecialchars($admin['first_name'] ?? ''); ?></span></div>
  <div class="info-row"><span>LAST NAME</span><span><?php echo htmlspecialchars($admin['last_name'] ?? ''); ?></span></div>
  <div class="info-row"><span>ROLE</span><span>System Administrator</span></div>
  <div class="info-row"><span>PHONE NUMBER</span><span><?php echo htmlspecialchars($admin['phone_number'] ?? ''); ?></span></div>
  <div class="info-row"><span>EMAIL</span><span><?php echo htmlspecialchars($admin['email'] ?? ''); ?></span></div>
  <div class="info-row"><span>USERNAME</span><span><?php echo htmlspecialchars($admin['username'] ?? ''); ?></span></div>
</div>

<!-- ===== STATS ===== -->
<div class="stats">
  <div class="stat"><p>TOTAL REQUESTS</p><span><?php echo $total; ?></span></div>
  <div class="stat"><p>PENDING</p><span class="pending"><?php echo $pending; ?></span></div>
  <div class="stat"><p>APPROVED</p><span class="approved"><?php echo $approved; ?></span></div>
  <div class="stat"><p>REJECTED</p><span class="rejected"><?php echo $rejected; ?></span></div>
</div>

<!-- ===== PENDING ===== -->
<div class="panel">
  <h3>PENDING REQUESTS</h3>

  <div class="table-header">
    <span>PERMIT ID</span>
    <span>TYPE</span>
    <span>APPLICANT</span>
    <span>APPROVAL DATE</span>
    <span>ACTION</span>
  </div>

  <?php
  $res = $conn->query("SELECT * FROM permit WHERE status='pending' ORDER BY permit_id DESC");
  while($row = $res->fetch_assoc()){
  ?>
  <div class="request-row">
    <span>#CB-<?php echo $row['permit_id']; ?></span>
    <span><?php echo type_label($row['permit_type']); ?></span>
    <span>User <?php echo $row['user_account_id']; ?></span>
    <span><?php echo fmt_date($row['submitted_date']); ?></span>
    <span>
      <a href="approve.php?id=<?php echo $row['permit_id']; ?>">Approve</a> |
      <a href="reject.php?id=<?php echo $row['permit_id']; ?>">Reject</a>
    </span>
  </div>
  <?php } ?>
</div>

<!-- ===== APPROVED ===== -->
<div class="panel">
  <h3>APPROVED REQUESTS</h3>

  <div class="table-header">
    <span>PERMIT ID</span>
    <span>TYPE</span>
    <span>APPLICANT</span>
    <span>APPROVAL DATE</span>
    <span>ACTION</span>
  </div>

  <?php
  $res = $conn->query("SELECT * FROM permit WHERE status='approved' ORDER BY permit_id DESC");
  while($row = $res->fetch_assoc()){
  ?>
  <div class="request-row">
    <span>#CB-<?php echo $row['permit_id']; ?></span>
    <span><?php echo type_label($row['permit_type']); ?></span>
    <span>User <?php echo $row['user_account_id']; ?></span>
    <span><?php echo fmt_date($row['approved_date']); ?></span>
    <span><a href="#">View</a></span>
  </div>
  <?php } ?>
</div>

<!-- ===== REJECTED ===== -->
<div class="panel">
  <h3>REJECTED REQUESTS</h3>

  <div class="table-header">
    <span>PERMIT ID</span>
    <span>TYPE</span>
    <span>APPLICANT</span>
    <span>APPROVAL DATE</span>
    <span>ACTION</span>
  </div>

  <?php
  $res = $conn->query("SELECT * FROM permit WHERE status='rejected' ORDER BY permit_id DESC");
  while($row = $res->fetch_assoc()){
  ?>
  <div class="request-row">
    <span>#CB-<?php echo $row['permit_id']; ?></span>
    <span><?php echo type_label($row['permit_type']); ?></span>
    <span>User <?php echo $row['user_account_id']; ?></span>
    <span><?php echo htmlspecialchars($row['rejection_reason'] ?? '-'); ?></span>
    <span><a href="#">View</a></span>
  </div>
  <?php } ?>
</div>

</main>

<footer>
  <p>© 2026 CityBridge. Smart cities, seamless access.</p>
</footer>

</body>
</html>