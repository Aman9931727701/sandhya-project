<?php 
ob_start();
session_start();
if(isset($_SESSION['a']))
{
	header("location:admin.php");
	exit();
}
if(isset($_SESSION['tt']))
{
	header("location:tteacher.php");
	exit();
}
if(isset($_SESSION['ss']))
{
	header("location:sstudent.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Explore Learning</title>

    <!-- Google Fonts & Bootstrap 4.6 -->
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
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.96) 100%), url('d.jpg') fixed center/cover no-repeat;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Glassmorphism Navbar */
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
            font-size: 0.92rem;
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
        }

        /* Auth Card */
        .auth-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            backdrop-filter: blur(16px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            padding: 40px;
            width: 100%;
            max-width: 480px;
            transition: all 0.3s ease;
        }

        .auth-card:hover {
            border-color: rgba(6, 182, 212, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 20px rgba(6, 182, 212, 0.1);
        }

        .auth-icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(59, 130, 246, 0.2));
            border: 1px solid rgba(6, 182, 212, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #38bdf8;
            margin: 0 auto 20px;
        }

        .auth-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .auth-subtitle {
            font-size: 0.9rem;
            color: #94a3b8;
            margin-bottom: 28px;
        }

        /* Form Inputs */
        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        .input-group-custom {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 4px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .input-group-custom:focus-within {
            border-color: #38bdf8;
            background: rgba(15, 23, 42, 0.85);
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.2);
        }

        .input-icon {
            width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 1.1rem;
        }

        .input-group-custom:focus-within .input-icon {
            color: #38bdf8;
        }

        .form-control-custom {
            background: transparent;
            border: none;
            color: #ffffff;
            padding: 10px 12px 10px 0;
            font-size: 0.95rem;
            width: 100%;
            outline: none !important;
        }

        .form-control-custom::placeholder {
            color: #64748b;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            color: white !important;
            font-weight: 700;
            border: none;
            padding: 13px;
            border-radius: 12px;
            width: 100%;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.35);
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.45);
        }

        .demo-badge-card {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 14px;
            font-size: 0.78rem;
            color: #94a3b8;
            margin-top: 24px;
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
    <nav class="navbar navbar-expand-lg navbar-dark glass-nav">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <div class="brand-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <span>Explore<span class="text-info">Learning</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-house mr-1"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="teacher.php"><i class="fa-solid fa-chalkboard-user mr-1"></i> Teacher</a></li>
                    <li class="nav-item"><a class="nav-link" href="student.php"><i class="fa-solid fa-user-graduate mr-1"></i> Student</a></li>
                    <li class="nav-item"><a class="nav-link active" href="login.php"><i class="fa-solid fa-right-to-bracket mr-1"></i> Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="enquiry.php"><i class="fa-solid fa-envelope mr-1"></i> Enquiry</a></li>
                </ul>
                <div>
                    <a href="index.php" class="btn btn-sm btn-outline-light px-3" style="border-radius: 8px;">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Back to Courses
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Centered Content -->
    <main class="container my-auto py-5 d-flex align-items-center justify-content-center">
        <div class="auth-card text-center">
            
            <div class="auth-icon-wrapper">
                <i class="fa-solid fa-shield-halved"></i>
            </div>

            <h2 class="auth-title">Welcome Back</h2>
            <p class="auth-subtitle">Sign in to access your student, teacher, or admin dashboard</p>

            <?php 
            if(isset($_SESSION['wrong']))
            {
                echo '<div class="alert alert-danger py-2 px-3 small rounded-lg mb-4 text-left border-0" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5;">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> '.$_SESSION['wrong'].'
                      </div>';
                unset($_SESSION['wrong']);
            }
            ?>

            <form action="check.php" method="POST" class="text-left">
                <!-- User ID / Email Input -->
                <div class="form-group mb-3">
                    <label class="form-label">User ID or Registered Email</label>
                    <div class="input-group-custom">
                        <div class="input-icon">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input type="text" name="n" class="form-control-custom" placeholder="e.g. admin or yourname@example.com" required autocomplete="username">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="form-group mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group-custom">
                        <div class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="p" class="form-control-custom" placeholder="Enter your password" required autocomplete="current-password">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="s" class="btn btn-gradient">
                    Sign In <i class="fa-solid fa-arrow-right-to-bracket ml-1"></i>
                </button>
            </form>

            <!-- Links to register -->
            <div class="mt-4 pt-3 border-top border-secondary text-center small text-muted">
                Don't have an account yet? 
                <div class="mt-2">
                    <a href="student.php" class="text-info font-weight-bold mr-3">Register as Student</a>
                    <span class="text-muted">•</span>
                    <a href="teacher.php" class="text-info font-weight-bold ml-3">Register as Teacher</a>
                </div>
            </div>

            <!-- Demo helper card -->
            <div class="demo-badge-card text-left">
                <div class="font-weight-bold text-white mb-1"><i class="fa-solid fa-circle-info text-info mr-1"></i> Demo Credentials:</div>
                <div class="d-flex justify-content-between py-1 border-bottom border-secondary">
                    <span>Admin:</span> <code class="text-info">argus / academy</code>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom border-secondary">
                    <span>Teacher:</span> <code class="text-info">komal@example.com / pass</code>
                </div>
                <div class="d-flex justify-content-between pt-1">
                    <span>Student:</span> <code class="text-info">aman@example.com / pass</code>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer text-center">
        <div class="container">
            <p class="mb-0">&copy; 2026 Explore Learning. Clean, secure authentication portal.</p>
        </div>
    </footer>

    <!-- Bootstrap & jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
