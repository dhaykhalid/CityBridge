<?php
session_start();
include "db.php";

// ===== Stats =====
$total = $conn->query("SELECT COUNT(*) c FROM permit")->fetch_assoc()['c'];
$pending = $conn->query("SELECT COUNT(*) c FROM permit WHERE status='pending'")->fetch_assoc()['c'];
$approved = $conn->query("SELECT COUNT(*) c FROM permit WHERE status='approved'")->fetch_assoc()['c'];
$rejected = $conn->query("SELECT COUNT(*) c FROM permit WHERE status='rejected'")->fetch_assoc()['c'];

// ===== Admin info (عدّلي حسب جداولك) =====
$admin = [
  "first_name" => "wasan",
  "last_name" => "Alamri",
  "role" => "System Administrator",
  "phone" => "+966 5593286681",
  "email" => "email@example.com",
  "username" => "WasanAlamri"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - CityBridge</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<!-- ===== HEADER (نفس حقك) ===== -->
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

<!-- ===== ADMIN INFO (نفس تصميمك) ===== -->
<div class="panel">
<h3>Administrator Information</h3>

<div class="info-row"><span>FIRST NAME</span><span><?php echo $admin['first_name']; ?></span></div>
<div class="info-row"><span>LAST NAME</span><span><?php echo $admin['last_name']; ?></span></div>
<div class="info-row"><span>ROLE</span><span><?php echo $admin['role']; ?></span></div>
<div class="info-row"><span>PHONE NUMBER</span><span><?php echo $admin['phone']; ?></span></div>
<div class="info-row"><span>EMAIL</span><span><?php echo $admin['email']; ?></span></div>
<div class="info-row"><span>USERNAME</span><span><?php echo $admin['username']; ?></span></div>

</div>

<!-- ===== STATS (نفس الكروت) ===== -->
<div class="stats">

<div class="stat">
<p>TOTAL REQUESTS</p>
<span><?php echo $total; ?></span>
</div>

<div class="stat">
<p>PENDING</p>
<span class="pending"><?php echo $pending; ?></span>
</div>

<div class="stat">
<p>APPROVED</p>
<span class="approved"><?php echo $approved; ?></span>
</div>

<div class="stat">
<p>REJECTED</p>
<span class="rejected"><?php echo $rejected; ?></span>
</div>

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
$result = $conn->query("SELECT * FROM permit WHERE status='pending'");
while($row = $result->fetch_assoc()){
?>

<div class="request-row">
<span>#CB-<?php echo $row['permit_id']; ?></span>
<span><?php echo ucfirst($row['permit_type']); ?> Permit</span>
<span>User <?php echo $row['user_account_id']; ?></span>
<span><?php echo $row['submitted_date']; ?></span>
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
$result = $conn->query("SELECT * FROM permit WHERE status='approved'");
while($row = $result->fetch_assoc()){
?>

<div class="request-row">
<span>#CB-<?php echo $row['permit_id']; ?></span>
<span><?php echo ucfirst($row['permit_type']); ?> Permit</span>
<span>User <?php echo $row['user_account_id']; ?></span>
<span><?php echo $row['approved_date']; ?></span>
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
$result = $conn->query("SELECT * FROM permit WHERE status='rejected'");
while($row = $result->fetch_assoc()){
?>

<div class="request-row">
<span>#CB-<?php echo $row['permit_id']; ?></span>
<span><?php echo ucfirst($row['permit_type']); ?> Permit</span>
<span>User <?php echo $row['user_account_id']; ?></span>
<span><?php echo $row['rejection_reason']; ?></span>
<span><a href="#">View</a></span>
</div>

<?php } ?>
</div>

</main>

<!-- ===== FOOTER (نفس حقك) ===== -->
<footer>
<p>© 2026 CityBridge. Smart cities, seamless access.</p>
</footer>

</body>
</html>