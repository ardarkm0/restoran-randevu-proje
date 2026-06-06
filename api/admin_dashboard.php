<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /admin_login.html');
    exit;
}
require_once 'db.php';

$db = getDB();
$msg = '';
$msgType = 'ok';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $id = intval($_POST['delete_id']);
        $s = $db->prepare("DELETE FROM reservations WHERE id = ?");
        $s->bind_param('i', $id);
        $s->execute(); $s->close();
        $msg = 'Reservation deleted successfully.';
    } elseif (isset($_POST['update_id'], $_POST['status'])) {
        $id     = intval($_POST['update_id']);
        $status = in_array($_POST['status'], ['pending','confirmed','cancelled']) ? $_POST['status'] : 'pending';
        $s = $db->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        $s->bind_param('si', $status, $id);
        $s->execute(); $s->close();
        $msg = 'Reservation status updated.';
    }
}

// Fetch all reservations with user info
$rows = [];
$res  = $db->query("
    SELECT r.*, u.name AS guest_name, u.email AS guest_email
    FROM reservations r
    LEFT JOIN users u ON r.user_id = u.id
    ORDER BY r.date DESC, r.time DESC
");
if ($res) $rows = $res->fetch_all(MYSQLI_ASSOC);

// Stats
$total     = count($rows);
$confirmed = count(array_filter($rows, fn($r) => $r['status'] === 'confirmed'));
$pending   = count(array_filter($rows, fn($r) => $r['status'] === 'pending'));
$cancelled = count(array_filter($rows, fn($r) => $r['status'] === 'cancelled'));
$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina | Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{
            --gold:#d4af37;--gold-dim:rgba(212,175,55,0.12);--gold-light:#f0d060;
            --dark:#0a0a0a;--dark2:#0f0f0f;--dark3:#141414;--dark4:#1a1a1a;
            --text:#f0ede8;--muted:#666;--border:rgba(212,175,55,0.18);
            --green:rgba(74,222,128,1);--green-dim:rgba(74,222,128,0.12);
            --red:rgba(248,113,113,1);--red-dim:rgba(248,113,113,0.12);
            --transition:0.3s cubic-bezier(0.4,0,0.2,1);
        }
        
        /* ── LIGHT MODE GLOBALS ── */
        body.light-mode {
            --dark: #fcfbf9;
            --dark2: #f2efe9;
            --dark3: #e8e4db;
            --text: #1a1a1a;
            --muted: #555;
            --border: rgba(212, 175, 55, 0.4);
            --gold-dim: rgba(212, 175, 55, 0.15);
        }
        body { transition: background-color 0.4s ease, color 0.4s ease; }
        body.light-mode .sidebar { background: var(--dark2); }
        body.light-mode .stat-card { background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        body.light-mode .t-row { background: #fff; border-bottom: 1px solid rgba(0,0,0,0.05); }
        body.light-mode .t-row:hover { background: rgba(212,175,55,0.05); }
        body.light-mode select.status-sel { background: #fff; color: #000; border-color: rgba(0,0,0,0.1); }
        body.light-mode .cl-item { color: #555; }
        body.light-mode .search-wrap input { background: #fff; border-color: rgba(0,0,0,0.1); color: #000; }
        body.light-mode .search-wrap input::placeholder { color: #888; }
        body.light-mode .table-card { background: #ffffff; border-color: rgba(0,0,0,0.05); box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        body.light-mode thead th { background: rgba(0,0,0,0.02); border-bottom-color: rgba(0,0,0,0.05); color: #555; }
        body.light-mode tbody tr { border-bottom-color: rgba(0,0,0,0.04); }
        body.light-mode tbody tr:hover { background: rgba(212,175,55,0.04); }
        
        .global-theme-toggle {
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 8px; border: 1px solid var(--border);
            background: var(--dark2); color: var(--text); cursor: pointer; transition: var(--transition);
        }
        .global-theme-toggle:hover { background: var(--gold-dim); color: var(--gold); border-color: var(--gold); }
        .global-theme-toggle .sun-icon { display: none; }
        body.light-mode .global-theme-toggle .moon-icon { display: none; }
        body.light-mode .global-theme-toggle .sun-icon { display: block; }

        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Poppins',sans-serif;background:var(--dark);color:var(--text);display:flex;min-height:100vh;}

        
        .sidebar{width:220px;background:var(--dark2);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;height:100vh;z-index:100;}
        .sb-logo{padding:28px 24px 24px;font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:700;letter-spacing:2px;border-bottom:1px solid rgba(255,255,255,0.05);}
        .sb-logo span{color:var(--gold);}
        .sb-nav{flex:1;padding:16px 0;}
        .sb-item{display:flex;align-items:center;gap:12px;padding:12px 24px;color:#666;font-size:13px;font-weight:400;text-decoration:none;transition:var(--transition);cursor:pointer;border:none;background:none;width:100%;text-align:left;}
        .sb-item:hover{color:var(--text);background:rgba(255,255,255,0.03);}
        .sb-item.active{color:var(--gold);background:var(--gold-dim);}
        .sb-item svg{flex-shrink:0;opacity:0.7;}
        .sb-item.active svg{opacity:1;}
        .sb-section{padding:16px 24px 8px;font-size:10px;text-transform:uppercase;letter-spacing:2px;color:#333;}
        .sb-bottom{padding:16px 0;border-top:1px solid rgba(255,255,255,0.05);}
        .sb-admin{padding:16px 24px;display:flex;align-items:center;gap:12px;}
        .sb-avatar{width:36px;height:36px;background:var(--gold-dim);border:1px solid var(--border);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;color:var(--gold);font-weight:600;flex-shrink:0;}
        .sb-admin-name{font-size:13px;font-weight:500;color:var(--text);}
        .sb-admin-role{font-size:11px;color:#444;}

        
        .main{margin-left:220px;flex:1;padding:36px 40px;min-height:100vh;}
        .page-header{margin-bottom:32px;display:flex;justify-content:space-between;align-items:flex-start;}
        .page-header h1{font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;margin-bottom:4px;}
        .page-header p{color:var(--muted);font-size:13px;}
        .logout-btn{display:flex;align-items:center;gap:8px;padding:9px 18px;background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.25);border-radius:8px;color:#f87171;font-family:'Poppins',sans-serif;font-size:12px;font-weight:500;cursor:pointer;text-decoration:none;transition:var(--transition);}
        .logout-btn:hover{background:rgba(248,113,113,0.18);}

        
        .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:32px;}
        .stat-card{background:var(--dark3);border:1px solid rgba(255,255,255,0.05);border-radius:14px;padding:22px 24px;position:relative;overflow:hidden;}
        .stat-card::after{content:'';position:absolute;top:0;left:0;right:0;height:2px;}
        .stat-card.gold::after{background:linear-gradient(90deg,var(--gold),transparent);}
        .stat-card.green::after{background:linear-gradient(90deg,var(--green),transparent);}
        .stat-card.amber::after{background:linear-gradient(90deg,#fbbf24,transparent);}
        .stat-card.red::after{background:linear-gradient(90deg,var(--red),transparent);}
        .stat-num{font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:700;line-height:1;margin-bottom:6px;}
        .stat-card.gold .stat-num{color:var(--gold);}
        .stat-card.green .stat-num{color:var(--green);}
        .stat-card.amber .stat-num{color:#fbbf24;}
        .stat-card.red .stat-num{color:var(--red);}
        .stat-label{font-size:12px;color:var(--muted);letter-spacing:0.3px;}

        
        .flash{padding:12px 18px;border-radius:10px;font-size:13px;margin-bottom:24px;display:flex;align-items:center;gap:10px;animation:fadeIn 0.3s ease;}
        .flash-ok{background:var(--green-dim);border:1px solid rgba(74,222,128,0.25);color:var(--green);}
        @keyframes fadeIn{from{opacity:0;transform:translateY(-6px);}to{opacity:1;transform:translateY(0);}}

        
        .table-card{background:var(--dark3);border:1px solid rgba(255,255,255,0.05);border-radius:16px;overflow:hidden;}
        .table-card-header{display:flex;justify-content:space-between;align-items:center;padding:18px 24px;border-bottom:1px solid rgba(255,255,255,0.05);}
        .table-card-title{font-size:14px;font-weight:600;}
        .table-card-count{font-size:12px;color:var(--muted);}
        .table-wrap{overflow-x:auto;}
        table{width:100%;border-collapse:collapse;min-width:900px;}
        thead th{padding:11px 16px;text-align:left;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:#444;font-weight:500;background:rgba(255,255,255,0.02);border-bottom:1px solid rgba(255,255,255,0.04);}
        tbody tr{border-bottom:1px solid rgba(255,255,255,0.03);transition:var(--transition);}
        tbody tr:last-child{border-bottom:none;}
        tbody tr:hover{background:rgba(255,255,255,0.02);}
        td{padding:13px 16px;font-size:13px;vertical-align:middle;}
        .td-id{color:#333;font-size:12px;}
        .td-guest-name{font-weight:500;color:var(--text);}
        .td-guest-email{font-size:11px;color:#444;margin-top:2px;}
        .td-muted{color:#555;}
        .td-menu{max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#555;font-size:11px;}

        
        .badge{display:inline-flex;align-items:center;padding:4px 12px;border-radius:50px;font-size:11px;font-weight:600;letter-spacing:0.3px;}
        .badge-pending{background:rgba(251,191,36,0.12);color:#fbbf24;border:1px solid rgba(251,191,36,0.25);}
        .badge-confirmed{background:var(--green-dim);color:var(--green);border:1px solid rgba(74,222,128,0.25);}
        .badge-cancelled{background:var(--red-dim);color:var(--red);border:1px solid rgba(248,113,113,0.25);}

        
        .actions{display:flex;gap:6px;flex-wrap:wrap;}
        .btn-sm{padding:5px 12px;border-radius:6px;font-size:11px;font-weight:500;cursor:pointer;font-family:'Poppins',sans-serif;border:1px solid transparent;transition:var(--transition);}
        .btn-confirm{background:var(--green-dim);border-color:rgba(74,222,128,0.3);color:var(--green);}
        .btn-confirm:hover{background:rgba(74,222,128,0.22);}
        .btn-cancel{background:rgba(251,191,36,0.1);border-color:rgba(251,191,36,0.3);color:#fbbf24;}
        .btn-cancel:hover{background:rgba(251,191,36,0.18);}
        .btn-delete{background:var(--red-dim);border-color:rgba(248,113,113,0.3);color:var(--red);}
        .btn-delete:hover{background:rgba(248,113,113,0.22);}
        .act-form{display:inline;}

        
        .empty{text-align:center;padding:60px 20px;color:#333;}
        .empty-icon{font-size:48px;margin-bottom:16px;opacity:0.3;}
        .empty p{font-size:14px;}

        
        .search-wrap{position:relative;}
        .search-wrap svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#444;}
        .search-inp{padding:8px 12px 8px 36px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:8px;color:var(--text);font-family:'Poppins',sans-serif;font-size:12px;outline:none;transition:var(--transition);width:220px;}
        .search-inp:focus{border-color:var(--gold);}
        .search-inp::placeholder{color:#333;}
    </style>
</head>
<body>


<aside class="sidebar">
    <div class="sb-logo">Lumina<span>.</span></div>
    <nav class="sb-nav">
        <div class="sb-section">Management</div>
        <a class="sb-item active" href="admin_dashboard.php">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Reservations
        </a>
    </nav>
    <div class="sb-bottom">
        <div class="sb-admin">
            <div class="sb-avatar"><?= strtoupper(substr($_SESSION['admin_name'], 0, 1)) ?></div>
            <div>
                <div class="sb-admin-name"><?= htmlspecialchars($_SESSION['admin_name']) ?></div>
                <div class="sb-admin-role">Administrator</div>
            </div>
        </div>
    </div>
</aside>


<main class="main">
    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p><?= date('l, d F Y') ?> · Lumina Fine Dining</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <button class="global-theme-toggle" id="globalThemeToggle" aria-label="Toggle Theme">
                <svg class="sun-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                <svg class="moon-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
            </button>
            <a href="/admin/logout" class="logout-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Sign Out
            </a>
        </div>
    </div>

    <?php if ($msg): ?>
    <div class="flash flash-ok">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <?= htmlspecialchars($msg) ?>
    </div>
    <?php endif; ?>

    
    <div class="stats-grid">
        <div class="stat-card gold">
            <div class="stat-num"><?= $total ?></div>
            <div class="stat-label">Total Reservations</div>
        </div>
        <div class="stat-card green">
            <div class="stat-num"><?= $confirmed ?></div>
            <div class="stat-label">Confirmed</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-num"><?= $pending ?></div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card red">
            <div class="stat-num"><?= $cancelled ?></div>
            <div class="stat-label">Cancelled</div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="chart-container" style="background:var(--dark3); border:1px solid rgba(255,255,255,0.05); border-radius:16px; padding:24px; margin-bottom:32px; display:flex; align-items:center; justify-content:center; height: 300px;">
        <canvas id="reservationsChart"></canvas>
    </div>

    <!-- Reservations Table -->
    <div class="table-card">
        <div class="table-card-header">
            <div>
                <span class="table-card-title">All Reservations</span>
                <span class="table-card-count" style="margin-left:12px;"><?= $total ?> total</span>
            </div>
            <div class="search-wrap">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input class="search-inp" type="text" id="searchInp" placeholder="Search guest or date…" oninput="filterTable(this.value)">
            </div>
        </div>

        <?php if (empty($rows)): ?>
        <div class="empty">
            <div class="empty-icon">📋</div>
            <p>No reservations yet.</p>
        </div>
        <?php else: ?>
        <div class="table-wrap">
            <table id="reservTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Guest</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Guests</th>
                        <th>Seating</th>
                        <th>Menu</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <td class="td-id">#<?= $r['id'] ?></td>
                    <td>
                        <div class="td-guest-name"><?= htmlspecialchars($r['guest_name'] ?? 'Walk-in') ?></div>
                        <div class="td-guest-email"><?= htmlspecialchars($r['guest_email'] ?? '–') ?></div>
                    </td>
                    <td><?= htmlspecialchars($r['date']) ?></td>
                    <td class="td-muted"><?= substr($r['time'], 0, 5) ?></td>
                    <td class="td-muted"><?= $r['guests'] ?></td>
                    <td class="td-muted"><?= htmlspecialchars($r['seating']) ?></td>
                    <td><div class="td-menu" title="<?= htmlspecialchars($r['menu_items'] ?? '') ?>"><?= htmlspecialchars($r['menu_items'] ?: '–') ?></div></td>
                    <td>
                        <span class="badge badge-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span>
                    </td>
                    <td>
                        <div class="actions">
                            <?php if ($r['status'] !== 'confirmed'): ?>
                            <form class="act-form" method="POST">
                                <input type="hidden" name="update_id" value="<?= $r['id'] ?>">
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="btn-sm btn-confirm">✓ Confirm</button>
                            </form>
                            <?php endif; ?>
                            <?php if ($r['status'] !== 'cancelled'): ?>
                            <form class="act-form" method="POST">
                                <input type="hidden" name="update_id" value="<?= $r['id'] ?>">
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn-sm btn-cancel">Cancel</button>
                            </form>
                            <?php endif; ?>
                            <form class="act-form" method="POST" onsubmit="return confirm('Delete this reservation permanently?')">
                                <input type="hidden" name="delete_id" value="<?= $r['id'] ?>">
                                <button type="submit" class="btn-sm btn-delete">✕ Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function filterTable(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#reservTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

    // Global Theme Toggle
    document.addEventListener('DOMContentLoaded', () => {
        const tBtn = document.getElementById('globalThemeToggle');
        if (!tBtn) return;
        
        function applyTheme(isLight) {
            if (isLight) {
                document.body.classList.add('light-mode');
            } else {
                document.body.classList.remove('light-mode');
            }
        }

        if(localStorage.getItem('lumina_theme') === 'light') applyTheme(true);

        tBtn.addEventListener('click', () => {
            const isLight = !document.body.classList.contains('light-mode');
            applyTheme(isLight);
            localStorage.setItem('lumina_theme', isLight ? 'light' : 'dark');
        });
    });

    const ctx = document.getElementById('reservationsChart').getContext('2d');
    new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Confirmed', 'Pending', 'Cancelled'],
        datasets: [{
            data: [<?= $confirmed ?>, <?= $pending ?>, <?= $cancelled ?>],
            backgroundColor: [
                'rgba(74, 222, 128, 0.8)',
                'rgba(251, 191, 36, 0.8)',
                'rgba(248, 113, 113, 0.8)'
            ],
            borderColor: [
                'rgba(74, 222, 128, 1)',
                'rgba(251, 191, 36, 1)',
                'rgba(248, 113, 113, 1)'
            ],
            borderWidth: 1,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: { color: '#ccc', font: { family: "'Poppins', sans-serif", size: 12 } }
            },
            title: {
                display: true,
                text: 'Reservation Status Distribution',
                color: '#d4af37',
                font: { family: "'Cormorant Garamond', serif", size: 20, weight: 'bold' }
            }
        },
        cutout: '70%'
    }
});
</script>
</body>
</html>
