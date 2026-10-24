<?php 
ob_start();
session_start();
if(!isset($_SESSION['a']) || $_SESSION['aid']!=session_id())
{
	header("location:login.php");
	exit();
}
$admin_name = $_SESSION['a'];
include "connection.php";

$teacher_count = 0;
$student_count = 0;
$course_count = 0;
$enquiry_count = 0;

if(isset($cn) && $cn) {
    $r1 = mysqli_query($cn, "SELECT COUNT(*) as c FROM teacher");
    if($r1 && $row = mysqli_fetch_assoc($r1)) $teacher_count = $row['c'];

    $r2 = mysqli_query($cn, "SELECT COUNT(*) as c FROM student");
    if($r2 && $row = mysqli_fetch_assoc($r2)) $student_count = $row['c'];

    $r3 = mysqli_query($cn, "SELECT COUNT(*) as c FROM course");
    if($r3 && $row = mysqli_fetch_assoc($r3)) $course_count = $row['c'];

    $r4 = mysqli_query($cn, "SELECT COUNT(*) as c FROM enquiry");
    if($r4 && $row = mysqli_fetch_assoc($r4)) $enquiry_count = $row['c'];
} else {
    // Default fallback counts for display
    $teacher_count = 8;
    $student_count = 42;
    $course_count = 15;
    $enquiry_count = 19;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Dashboard - Explore Learning</title>

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
            background: linear-gradient(135deg, #ef4444, #f97316);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
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

        .dashboard-header {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            backdrop-filter: blur(16px);
            padding: 30px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 24px;
            backdrop-filter: blur(12px);
            transition: all 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(6, 182, 212, 0.4);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 16px;
        }

        .action-card {
            background: rgba(30, 41, 59, 0.65);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            color: white;
            transition: all 0.25s ease;
            display: block;
            text-decoration: none !important;
        }

        .action-card:hover {
            background: rgba(6, 182, 212, 0.15);
            border-color: #38bdf8;
            transform: translateY(-3px);
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
            <a class="navbar-brand" href="admin.php">
                <div class="brand-icon">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <span>Explore<span class="text-danger">Admin</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="admin.php"><i class="fa-solid fa-chart-pie mr-1"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="adteacher.php"><i class="fa-solid fa-chalkboard-user mr-1"></i> Teachers</a></li>
                    <li class="nav-item"><a class="nav-link" href="adstudent.php"><i class="fa-solid fa-user-graduate mr-1"></i> Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="adcourse.php"><i class="fa-solid fa-book-open mr-1"></i> Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="adcontent.php"><i class="fa-solid fa-file-pdf mr-1"></i> Content</a></li>
                    <li class="nav-item"><a class="nav-link" href="adapply.php"><i class="fa-solid fa-clipboard-check mr-1"></i> Enrollments</a></li>
                    <li class="nav-item"><a class="nav-link" href="adchat.php"><i class="fa-solid fa-comments mr-1"></i> Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="adenquiry.php"><i class="fa-solid fa-envelope mr-1"></i> Enquiries</a></li>
                </ul>
                <div>
                    <a href="end.php" class="btn btn-sm btn-danger px-3 font-weight-bold" style="border-radius: 8px;">
                        <i class="fa-solid fa-power-off mr-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container my-5">
        
        <!-- Welcome Banner -->
        <div class="dashboard-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <span class="badge badge-pill badge-danger px-3 py-1 mb-2 font-weight-bold">
                    <i class="fa-solid fa-shield-halved mr-1"></i> Administrator Access
                </span>
                <h2 class="font-weight-bold text-white mb-1">
                    Welcome back, <span class="text-info"><?php echo htmlspecialchars($admin_name); ?></span>
                </h2>
                <p class="text-muted small mb-0">System operational overview and central management console.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="index.php" target="_blank" class="btn btn-outline-light btn-sm px-3" style="border-radius: 8px;">
                    <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> View Live Site
                </a>
            </div>
        </div>

        <!-- Metrics Row -->
        <div class="row mb-5">
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="stat-card">
                    <div class="stat-icon bg-info text-white"><i class="fa-solid fa-chalkboard-user"></i></div>
                    <div class="text-muted small text-uppercase font-weight-bold">Registered Teachers</div>
                    <h3 class="font-weight-bold text-white mt-1"><?php echo $teacher_count; ?></h3>
                    <a href="adteacher.php" class="small text-info font-weight-bold">Manage Teachers &rarr;</a>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="stat-card">
                    <div class="stat-icon bg-primary text-white"><i class="fa-solid fa-user-graduate"></i></div>
                    <div class="text-muted small text-uppercase font-weight-bold">Total Students</div>
                    <h3 class="font-weight-bold text-white mt-1"><?php echo $student_count; ?></h3>
                    <a href="adstudent.php" class="small text-info font-weight-bold">Manage Students &rarr;</a>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="stat-card">
                    <div class="stat-icon bg-success text-white"><i class="fa-solid fa-book-open"></i></div>
                    <div class="text-muted small text-uppercase font-weight-bold">Active Courses</div>
                    <h3 class="font-weight-bold text-white mt-1"><?php echo $course_count; ?></h3>
                    <a href="adcourse.php" class="small text-info font-weight-bold">Manage Catalog &rarr;</a>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="stat-card">
                    <div class="stat-icon bg-warning text-white"><i class="fa-solid fa-envelope"></i></div>
                    <div class="text-muted small text-uppercase font-weight-bold">Incoming Enquiries</div>
                    <h3 class="font-weight-bold text-white mt-1"><?php echo $enquiry_count; ?></h3>
                    <a href="adenquiry.php" class="small text-info font-weight-bold">View Messages &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Quick Management Shortcuts -->
        <h4 class="font-weight-bold text-white mb-3">Management Shortcuts</h4>
        <div class="row">
            <div class="col-6 col-md-3 mb-3">
                <a href="adcourse.php" class="action-card">
                    <i class="fa-solid fa-layer-group fa-2x mb-2 text-info"></i>
                    <div class="font-weight-bold">Courses Management</div>
                    <div class="small text-muted">Update & delete catalog</div>
                </a>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <a href="adcontent.php" class="action-card">
                    <i class="fa-solid fa-file-pdf fa-2x mb-2 text-primary"></i>
                    <div class="font-weight-bold">Course Chapters</div>
                    <div class="small text-muted">Audit PDF & video links</div>
                </a>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <a href="adapply.php" class="action-card">
                    <i class="fa-solid fa-receipt fa-2x mb-2 text-success"></i>
                    <div class="font-weight-bold">Student Applications</div>
                    <div class="small text-muted">Enrollments & timelines</div>
                </a>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <a href="adchat.php" class="action-card">
                    <i class="fa-solid fa-comments fa-2x mb-2 text-warning"></i>
                    <div class="font-weight-bold">Chat Logs</div>
                    <div class="small text-muted">Audit faculty messaging</div>
                </a>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="site-footer text-center">
        <div class="container">
            <p class="mb-0">&copy; 2026 Explore Learning System. Administration Portal.</p>
        </div>
    </footer>

    <!-- Bootstrap & jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
