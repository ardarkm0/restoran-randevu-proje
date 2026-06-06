<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_COOKIE['user_id'])) {
    header('Location: /');
    exit;
}

$user_id = $_COOKIE['user_id'];
$db = getDB();

// Handle Form Submissions
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = trim(htmlspecialchars($_POST['name'] ?? ''));
        if (!empty($name)) {
            $stmt = $db->prepare("UPDATE users SET name = ? WHERE id = ?");
            $stmt->bind_param('si', $name, $user_id);
            if ($stmt->execute()) {
                // Update cookie
                setcookie('user_name', $name, time() + 60 * 60 * 24 * 30, '/', '', false, false);
                $_COOKIE['user_name'] = $name; // for current page load
                $success_msg = 'Profile updated successfully.';
            } else {
                $error_msg = 'Failed to update profile.';
            }
            $stmt->close();
        }
    } elseif (isset($_POST['change_password'])) {
        $current_pw = $_POST['current_password'] ?? '';
        $new_pw = $_POST['new_password'] ?? '';

        if (!empty($current_pw) && strlen($new_pw) >= 6) {
            // Verify current password
            $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if ($user && password_verify($current_pw, $user['password'])) {
                $hashed = password_hash($new_pw, PASSWORD_BCRYPT);
                $upd = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $upd->bind_param('si', $hashed, $user_id);
                $upd->execute();
                $upd->close();
                $success_msg = 'Password changed successfully.';
            } else {
                $error_msg = 'Incorrect current password.';
            }
        } else {
            $error_msg = 'Password must be at least 6 characters.';
        }
    } elseif (isset($_POST['cancel_reservation'])) {
        $res_id = intval($_POST['reservation_id']);
        $stmt = $db->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $res_id, $user_id);
        if ($stmt->execute()) {
            $success_msg = 'Reservation cancelled.';
        }
        $stmt->close();
    }
}

// Fetch user data
$stmt = $db->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
$userData = $res->fetch_assoc();
$stmt->close();

// Fetch reservations
$res_stmt = $db->prepare("SELECT id, date, time, guests, seating, status FROM reservations WHERE user_id = ? ORDER BY date DESC, time DESC");
$res_stmt->bind_param('i', $user_id);
$res_stmt->execute();
$reservations = $res_stmt->get_result();
$res_stmt->close();

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina | My Profile</title>
    <link rel="stylesheet" href="../profile.css">
</head>
<body>

<header>
    <a href="/home" class="logo">Lumina<span>.</span></a>
    <a href="/home" class="back-home">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
        Back to Home
    </a>
</header>

<div class="container">
    <aside class="sidebar">
        <div class="profile-avatar">
            <?= strtoupper(substr($_COOKIE['user_name'] ?? 'G', 0, 1)) ?>
        </div>
        <div class="profile-name"><?= htmlspecialchars($_COOKIE['user_name'] ?? 'Guest') ?></div>
        <div class="profile-email"><?= htmlspecialchars($userData['email'] ?? '') ?></div>

        <button class="tab-btn active" onclick="switchTab('reservations', this)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            My Reservations
        </button>
        <button class="tab-btn" onclick="switchTab('settings', this)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            Account Settings
        </button>
        <a href="/api/logout.php" class="tab-btn logout" style="text-decoration:none;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            Sign Out
        </a>
    </aside>

    <main class="content">
        <?php if($success_msg): ?>
            <div class="alert alert-success show"><?= htmlspecialchars($success_msg) ?></div>
        <?php endif; ?>
        <?php if($error_msg): ?>
            <div class="alert alert-error show"><?= htmlspecialchars($error_msg) ?></div>
        <?php endif; ?>

        <!-- Reservations Tab -->
        <div id="reservations" class="tab-content active">
            <h2>Your Reservations</h2>
            <div class="res-list">
                <?php if($reservations->num_rows > 0): ?>
                    <?php while($row = $reservations->fetch_assoc()): ?>
                        <?php 
                            $dateStr = date('F j, Y', strtotime($row['date']));
                            $timeStr = date('g:i A', strtotime($row['time']));
                            $isFuture = strtotime($row['date'] . ' ' . $row['time']) > time();
                            $status = $row['status'];
                        ?>
                        <div class="res-card">
                            <div class="res-info">
                                <?php if($status === 'cancelled'): ?>
                                    <div class="res-badge cancelled">Cancelled</div>
                                <?php else: ?>
                                    <div class="res-badge active"><?= $isFuture ? 'Upcoming' : 'Completed' ?></div>
                                <?php endif; ?>
                                <h3><?= $dateStr ?> at <?= $timeStr ?></h3>
                                <div class="res-details">
                                    <span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        <?= $row['guests'] ?> Guests
                                    </span>
                                    <span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                        <?= htmlspecialchars($row['seating']) ?>
                                    </span>
                                </div>
                            </div>
                            <?php if($isFuture && $status !== 'cancelled'): ?>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                    <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="cancel_reservation" class="btn btn-danger">Cancel</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color:var(--text-muted);">You have no reservations yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Settings Tab -->
        <div id="settings" class="tab-content">
            <h2>Account Settings</h2>
            
            <div class="card">
                <div class="card-header">Personal Information</div>
                <form method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($_COOKIE['user_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" value="<?= htmlspecialchars($userData['email'] ?? '') ?>" disabled style="opacity:0.6; cursor:not-allowed;">
                        <small style="color:#666; font-size:11px; margin-top:4px; display:block;">Email address cannot be changed.</small>
                    </div>
                    <button type="submit" name="update_profile" class="btn">Save Changes</button>
                </form>
            </div>

            <div class="card">
                <div class="card-header">Change Password</div>
                <form method="POST">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" required minlength="6">
                    </div>
                    <button type="submit" name="change_password" class="btn">Update Password</button>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
function switchTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    btn.classList.add('active');
}
// Remove alerts after a few seconds
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => a.classList.remove('show'));
}, 4000);
</script>
</body>
</html>
