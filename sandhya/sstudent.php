<?php 
ob_start();
session_start();
if(!isset($_SESSION['ss']) || $_SESSION['ssid']!=session_id())
{
	header("location:login.php");
	exit();
}
$s = $_SESSION['ss'];
include "connection.php";

$student = [
    'sid' => 1,
    'sname' => 'Student Learner',
    'semail' => $s,
    'sphone' => 'N/A'
];

if(isset($cn) && $cn) {
    $safe_s = mysqli_real_escape_string($cn, $s);
    $q = "SELECT * FROM student WHERE semail='".$safe_s."'";
    $sq = mysqli_query($cn, $q);
    if($sq && $r = mysqli_fetch_array($sq)) {
        $student = $r;
        $_SESSION['sid'] = $r['sid'];
    }
} else {
    $_SESSION['sid'] = 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Student Profile - Explore Learning</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #06b6d4;
            --dark-bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.85);
            --border-color: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #f1f5f9;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.96) 100%), url('y.jpg') fixed center/cover no-repeat;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .glass-nav {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            color: #ffffff !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 14px rgba(6, 182, 212, 0.35);
        }

        .nav-link {
            color: #cbd5e1 !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 8px 14px !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
        }

        .profile-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            backdrop-filter: blur(16px);
            padding: 36px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.4);
        }

        .avatar-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            font-weight: 800;
            box-shadow: 0 10px 25px rgba(6, 182, 212, 0.35);
            margin: 0 auto 20px;
        }

        .info-pill {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .info-icon {
            color: #38bdf8;
            font-size: 1.2rem;
            width: 25px;
            text-align: center;
        }

        .action-card {
            background: rgba(30, 41, 59, 0.65);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px 20px;
            text-align: center;
            color: white;
            transition: all 0.25s ease;
            display: block;
            text-decoration: none !important;
            height: 100%;
        }

        .action-card:hover {
            background: rgba(6, 182, 212, 0.15);
            border-color: #38bdf8;
            transform: translateY(-4px);
            color: #38bdf8;
        }

        .site-footer {
            background: #090d16;
            border-top: 1px solid var(--border-color);
            padding: 24px 0;
            color: #64748b;
            font-size: 0.82rem;
            margin-top: auto;
        }
    </style>
</head>
<body>

    <!-- Glass Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark glass-nav sticky-top">
        <div class="container">
            <a class="navbar-brand" href="sstudent.php">
                <div class="brand-icon">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <span>Student<span class="text-info">Dashboard</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#studentNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="studentNavbar">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="sstudent.php"><i class="fa-solid fa-id-card mr-1"></i> My Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="scourse.php"><i class="fa-solid fa-book-open mr-1"></i> Browse Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="scontent.php"><i class="fa-solid fa-file-pdf mr-1"></i> Study Materials</a></li>
                    <li class="nav-item"><a class="nav-link" href="sdapply.php"><i class="fa-solid fa-clipboard-check mr-1"></i> My Enrollments</a></li>
                    <li class="nav-item"><a class="nav-link" href="schat.php"><i class="fa-solid fa-comments mr-1"></i> Faculty Chat</a></li>
                </ul>
                <div>
                    <a href="end.php" class="btn btn-sm btn-outline-danger px-3 font-weight-bold" style="border-radius: 8px;">
                        <i class="fa-solid fa-power-off mr-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container my-5">
        <div class="row">
            
            <!-- Student Profile Card -->
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="profile-card text-center">
                    <div class="avatar-circle">
                        <?php echo strtoupper(substr($student['sname'], 0, 1)); ?>
                    </div>
                    <span class="badge badge-pill badge-primary px-3 py-1 mb-2 font-weight-bold">
                        <i class="fa-solid fa-graduation-cap mr-1"></i> Active Student
                    </span>
                    <h3 class="font-weight-bold text-white mb-1"><?php echo htmlspecialchars($student['sname']); ?></h3>
                    <p class="text-muted small mb-4">Student ID: #<?php echo $student['sid']; ?></p>

                    <div class="text-left">
                        <div class="info-pill">
                            <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <div class="text-muted small">Email Address</div>
                                <div class="font-weight-bold text-white"><?php echo htmlspecialchars($student['semail']); ?></div>
                            </div>
                        </div>

                        <div class="info-pill">
                            <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                            <div>
                                <div class="text-muted small">Registered Mobile</div>
                                <div class="font-weight-bold text-white"><?php echo htmlspecialchars($student['sphone']); ?></div>
                            </div>
                        </div>

                        <div class="info-pill">
                            <div class="info-icon"><i class="fa-solid fa-shield-check"></i></div>
                            <div>
                                <div class="text-muted small">Security Status</div>
                                <div class="text-success small font-weight-bold"><i class="fa-solid fa-circle-check mr-1"></i> Account Verified & Protected</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Action Hub -->
            <div class="col-lg-7">
                <div class="profile-card">
                    <h4 class="font-weight-bold text-white mb-2">Student Learning Space</h4>
                    <p class="text-muted small mb-4">Quick shortcuts to access your lessons, enroll in programs, and contact mentors.</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="scourse.php" class="action-card">
                                <i class="fa-solid fa-magnifying-glass fa-2x mb-2 text-info"></i>
                                <h5 class="font-weight-bold text-white mb-1">Explore Courses</h5>
                                <p class="text-muted small mb-0">Browse all featured technology and academic programs.</p>
                            </a>
                        </div>

                        <div class="col-md-6 mb-3">
                            <a href="scontent.php" class="action-card">
                                <i class="fa-solid fa-file-pdf fa-2x mb-2 text-primary"></i>
                                <h5 class="font-weight-bold text-white mb-1">Study Materials</h5>
                                <p class="text-muted small mb-0">Download chapter PDF guides and watch lesson streams.</p>
                            </a>
                        </div>

                        <div class="col-md-6 mb-3">
                            <a href="sdapply.php" class="action-card">
                                <i class="fa-solid fa-calendar-check fa-2x mb-2 text-success"></i>
                                <h5 class="font-weight-bold text-white mb-1">My Enrollments</h5>
                                <p class="text-muted small mb-0">View your active batch timelines and application history.</p>
                            </a>
                        </div>

                        <div class="col-md-6 mb-3">
                            <a href="schat.php" class="action-card">
                                <i class="fa-solid fa-comments fa-2x mb-2 text-warning"></i>
                                <h5 class="font-weight-bold text-white mb-1">Teacher Doubt Chat</h5>
                                <p class="text-muted small mb-0">Send private messages to your course instructor.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer text-center">
        <div class="container">
            <p class="mb-0">&copy; 2026 Explore Learning. Student Portal.</p>
        </div>
    </footer>

    <!-- Bootstrap & jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
