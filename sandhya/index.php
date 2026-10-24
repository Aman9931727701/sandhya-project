<?php
include "connection.php";

$search = "";
if (isset($_POST['s']) && !empty($_POST['n'])) {
    $search = trim($_POST['n']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Explore Learning - Next-Gen Online Education Platform</title>

    <!-- Google Fonts & Bootstrap 4.6 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #06b6d4;
            --primary-hover: #0891b2;
            --accent: #f59e0b;
            --dark-bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.75);
            --border-color: rgba(255, 255, 255, 0.1);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #f1f5f9;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.96) 100%), url('a.jpg') fixed center/cover no-repeat;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Glassmorphism Navbar */
        .glass-nav {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.5px;
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
            transform: translateY(-1px);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            color: white !important;
            font-weight: 600;
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.35);
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.45);
        }

        .btn-outline-glass {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            color: #f1f5f9;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            backdrop-filter: blur(6px);
            transition: all 0.3s ease;
        }

        .btn-outline-glass:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            padding: 90px 0 60px;
            position: relative;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 30px;
            background: rgba(6, 182, 212, 0.15);
            border: 1px solid rgba(6, 182, 212, 0.35);
            color: #38bdf8;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .hero-title {
            font-size: 3.4rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }

        .text-gradient {
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1.15rem;
            color: #94a3b8;
            line-height: 1.7;
            max-width: 620px;
            margin-bottom: 32px;
        }

        /* Search Box */
        .hero-search-card {
            background: rgba(30, 41, 59, 0.85);
            border: 1px solid var(--border-color);
            backdrop-filter: blur(16px);
            border-radius: 16px;
            padding: 8px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            max-width: 650px;
        }

        .search-input {
            background: transparent;
            border: none;
            color: #ffffff;
            padding: 12px 20px;
            font-size: 1rem;
            outline: none !important;
            width: 100%;
        }

        .search-input::placeholder {
            color: #64748b;
        }

        /* Stats Cards */
        .stat-card {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(6, 182, 212, 0.4);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #38bdf8;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Course Cards (Modern Glassmorphism) */
        .section-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .section-subtitle {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.8rem;
            color: #38bdf8;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .section-title {
            font-size: 2.3rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .course-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            overflow: hidden;
            backdrop-filter: blur(12px);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .course-card:hover {
            transform: translateY(-8px);
            border-color: rgba(6, 182, 212, 0.45);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45), 0 0 20px rgba(6, 182, 212, 0.15);
        }

        .course-card-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .course-badge {
            background: rgba(6, 182, 212, 0.15);
            border: 1px solid rgba(6, 182, 212, 0.3);
            color: #38bdf8;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .course-price {
            font-size: 1.25rem;
            font-weight: 800;
            color: #34d399;
        }

        .course-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            margin-top: 14px;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .course-desc {
            font-size: 0.9rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
        }

        .instructor-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
            margin-bottom: 18px;
        }

        .instructor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 0.9rem;
        }

        .instructor-name {
            font-size: 0.88rem;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 2px;
        }

        .instructor-detail {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* Feature Cards */
        .feature-box {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 32px 24px;
            text-align: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-box:hover {
            transform: translateY(-6px);
            border-color: rgba(6, 182, 212, 0.4);
            background: rgba(30, 41, 59, 0.85);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .feature-icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: rgba(6, 182, 212, 0.12);
            color: #38bdf8;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 1px solid rgba(6, 182, 212, 0.25);
        }

        .feature-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 10px;
        }

        .feature-desc {
            font-size: 0.88rem;
            color: #94a3b8;
            line-height: 1.6;
        }

        /* Footer */
        .site-footer {
            background: #090d16;
            border-top: 1px solid var(--border-color);
            padding: 60px 0 30px;
            color: #94a3b8;
        }

        .footer-heading {
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #94a3b8;
            font-size: 0.88rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .footer-links a:hover {
            color: #38bdf8;
            padding-left: 4px;
        }

        .copyright-bar {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 25px;
            margin-top: 50px;
            font-size: 0.82rem;
            text-align: center;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- Top Sticky Glassmorphism Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark glass-nav sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <div class="brand-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <span>Explore<span class="text-info">Learning</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="fa-solid fa-house mr-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#courses"><i class="fa-solid fa-book-open mr-1"></i> Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features"><i class="fa-solid fa-star mr-1"></i> Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="teacher.php"><i class="fa-solid fa-chalkboard-user mr-1"></i> Teacher</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="student.php"><i class="fa-solid fa-user-graduate mr-1"></i> Student</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="enquiry.php"><i class="fa-solid fa-envelope mr-1"></i> Enquiry</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <a href="login.php" class="btn btn-outline-glass mr-2">
                        <i class="fa-solid fa-right-to-bracket mr-1"></i> Login
                    </a>
                    <a href="student.php" class="btn btn-gradient">
                        <i class="fa-solid fa-user-plus mr-1"></i> Join Free
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="hero-badge">
                        <i class="fa-solid fa-bolt"></i> Elevate Your Tech & Career Skills Today
                    </div>
                    <h1 class="hero-title">
                        Learn Without Limits with <span class="text-gradient">Explore Learning</span>
                    </h1>
                    <p class="hero-desc mx-auto">
                        Connect with industry mentors, access comprehensive curriculum notes, stream high-definition lectures, and build verified skills on an interactive learning platform.
                    </p>

                    <!-- Hero Search Box -->
                    <div class="hero-search-card mx-auto mb-4">
                        <form action="index.php#courses" method="POST" class="d-flex align-items-center">
                            <i class="fa-solid fa-magnifying-glass text-muted ml-3"></i>
                            <input type="text" name="n" class="search-input" placeholder="Search courses (e.g. Python, Full Stack, Database...)" value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" name="s" class="btn btn-gradient px-4 py-2">
                                Search
                            </button>
                        </form>
                    </div>

                    <div class="d-flex justify-content-center flex-wrap gap-2 text-muted small mt-2">
                        <span class="mr-3"><i class="fa-solid fa-check text-info mr-1"></i> 100% Online Flexibility</span>
                        <span class="mr-3"><i class="fa-solid fa-check text-info mr-1"></i> Direct Instructor Chat</span>
                        <span><i class="fa-solid fa-check text-info mr-1"></i> Verified Certifications</span>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="row mt-5 pt-3">
                <div class="col-6 col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Active Courses</div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number">12k+</div>
                        <div class="stat-label">Students Enrolled</div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Mentor Support</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Course Catalog Section -->
    <section id="courses" class="py-5">
        <div class="container">
            <div class="section-header">
                <div class="section-subtitle">Explore Our Catalog</div>
                <h2 class="section-title">Featured Learning Programs</h2>
                <p class="text-muted max-w-2xl mx-auto mt-2">
                    Hand-crafted curriculum designed and taught by verified instructors to take you from fundamentals to mastery.
                </p>
                <?php if (!empty($search)): ?>
                    <div class="mt-3">
                        <span class="badge badge-info p-2 font-weight-normal">
                            Filtered by: <strong>"<?php echo htmlspecialchars($search); ?>"</strong>
                        </span>
                        <a href="index.php" class="text-muted ml-2 small"><i class="fa-solid fa-xmark"></i> Clear filter</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php
                $hasCourses = false;

                if (isset($cn) && $cn) {
                    if (!empty($search)) {
                        $safe_search = mysqli_real_escape_string($cn, $search);
                        $q = "SELECT * FROM course INNER JOIN teacher ON course.tid = teacher.tid 
                              WHERE (course.cname LIKE '%$safe_search%' OR course.cdetail LIKE '%$safe_search%' OR teacher.tname LIKE '%$safe_search%')";
                    } else {
                        $q = "SELECT * FROM course INNER JOIN teacher ON course.tid = teacher.tid ORDER BY course.coid DESC";
                    }

                    $sq = mysqli_query($cn, $q);

                    if ($sq && mysqli_num_rows($sq) > 0) {
                        $hasCourses = true;
                        while ($r = mysqli_fetch_array($sq)) {
                            $coid = $r['coid'];
                            $tid = $r['tid'];
                            $tname = htmlspecialchars($r['tname']);
                            $tdetail = htmlspecialchars($r['tdetail']);
                            $cname = htmlspecialchars($r['cname']);
                            $cdetail = htmlspecialchars($r['cdetail']);
                            $crate = htmlspecialchars($r['crate']);
                            $cdate = htmlspecialchars($r['cdate']);
                            $initial = strtoupper(substr($tname, 0, 1));
                            ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="course-card">
                                    <div class="course-card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="course-badge"><i class="fa-solid fa-hashtag"></i> Course #<?php echo $coid; ?></span>
                                            <span class="course-price">₹<?php echo $crate; ?></span>
                                        </div>

                                        <h3 class="course-title"><?php echo $cname; ?></h3>
                                        <p class="course-desc"><?php echo $cdetail; ?></p>

                                        <div class="instructor-info">
                                            <div class="instructor-avatar"><?php echo $initial; ?></div>
                                            <div>
                                                <div class="instructor-name"><?php echo $tname; ?></div>
                                                <div class="instructor-detail text-truncate" style="max-width: 190px;"><?php echo $tdetail; ?></div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center pt-2">
                                            <span class="text-muted small"><i class="fa-regular fa-calendar-days mr-1"></i> <?php echo $cdate; ?></span>
                                            <a href="scapply.php?coid=<?php echo $coid; ?>&tid=<?php echo $tid; ?>&amt=<?php echo $crate; ?>" class="btn btn-gradient btn-sm px-3">
                                                Enroll Now <i class="fa-solid fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }

                // High-fidelity fallback sample courses if DB is empty or disconnected
                if (!$hasCourses) {
                    $sampleCourses = [
                        [
                            'coid' => 1,
                            'tid' => 1,
                            'cname' => 'Full Stack Web Development Masterclass',
                            'cdetail' => 'Master modern frontend & backend architectures including HTML5, CSS3, JavaScript, PHP, and MySQL databases.',
                            'crate' => 5000,
                            'cdate' => '2026-10-15',
                            'tname' => 'Dr. Komal Sharma',
                            'tdetail' => 'Senior CS Professor with 10+ years engineering & pedagogy experience.'
                        ],
                        [
                            'coid' => 2,
                            'tid' => 2,
                            'cname' => 'Python for Data Science, AI & Machine Learning',
                            'cdetail' => 'Deep dive into NumPy, Pandas, Scikit-learn, Neural Networks, and practical enterprise data engineering workflows.',
                            'crate' => 6500,
                            'cdate' => '2026-10-18',
                            'tname' => 'Prof. Rajesh Verma',
                            'tdetail' => 'Lead Data Scientist and former algorithmic consultant.'
                        ],
                        [
                            'coid' => 3,
                            'tid' => 1,
                            'cname' => 'Database Architecture & High-Performance MySQL',
                            'cdetail' => 'In-depth relational data modeling, query optimization, indexing strategies, normalization, and transactional ACID security.',
                            'crate' => 4200,
                            'cdate' => '2026-10-20',
                            'tname' => 'Dr. Komal Sharma',
                            'tdetail' => 'Senior CS Professor with 10+ years engineering & pedagogy experience.'
                        ]
                    ];

                    foreach ($sampleCourses as $c) {
                        $initial = strtoupper(substr($c['tname'], 0, 1));
                        ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="course-card">
                                <div class="course-card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="course-badge"><i class="fa-solid fa-hashtag"></i> Course #<?php echo $c['coid']; ?></span>
                                        <span class="course-price">₹<?php echo $c['crate']; ?></span>
                                    </div>

                                    <h3 class="course-title"><?php echo $c['cname']; ?></h3>
                                    <p class="course-desc"><?php echo $c['cdetail']; ?></p>

                                    <div class="instructor-info">
                                        <div class="instructor-avatar"><?php echo $initial; ?></div>
                                        <div>
                                            <div class="instructor-name"><?php echo $c['tname']; ?></div>
                                            <div class="instructor-detail text-truncate" style="max-width: 190px;"><?php echo $c['tdetail']; ?></div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-2">
                                        <span class="text-muted small"><i class="fa-regular fa-calendar-days mr-1"></i> <?php echo $c['cdate']; ?></span>
                                        <a href="scapply.php?coid=<?php echo $c['coid']; ?>&tid=<?php echo $c['tid']; ?>&amt=<?php echo $c['crate']; ?>" class="btn btn-gradient btn-sm px-3">
                                            Enroll Now <i class="fa-solid fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Why Choose Us / Features Section -->
    <section id="features" class="py-5" style="background: rgba(15, 23, 42, 0.4); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
        <div class="container py-4">
            <div class="section-header">
                <div class="section-subtitle">The Explore Learning Advantage</div>
                <h2 class="section-title">Designed for Serious Learning</h2>
                <p class="text-muted max-w-2xl mx-auto mt-2">
                    Everything you need to acquire real-world abilities, interact with tutors, and track your educational progress.
                </p>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <h3 class="feature-title">Real-Time Instructor Chat</h3>
                        <p class="feature-desc">Direct messaging pipeline connecting you with your course faculty for instant doubt clearing and tailored project feedback.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <h3 class="feature-title">Curated PDF Notes & Videos</h3>
                        <p class="feature-desc">Comprehensive chapter-by-chapter reading guides, downloadable source references, and embedded video lectures available 24/7.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h3 class="feature-title">Flexible Structured Timelines</h3>
                        <p class="feature-desc">Set personalized start and completion dates tailored around your professional schedule and study pace.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Banner -->
    <section class="py-5">
        <div class="container">
            <div class="p-5 rounded-xl text-center" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2) 0%, rgba(59, 130, 246, 0.2) 100%); border: 1px solid rgba(6, 182, 212, 0.35); border-radius: 20px;">
                <h2 class="font-weight-bold text-white mb-3">Ready to Accelerate Your Career?</h2>
                <p class="text-muted max-w-xl mx-auto mb-4">Join thousands of students building skills in software development, data science, and technology systems.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="student.php" class="btn btn-gradient btn-lg px-4 mr-2">Create Student Account</a>
                    <a href="enquiry.php" class="btn btn-outline-glass btn-lg px-4">Contact Admissions</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Professional Modern Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <a class="navbar-brand mb-3" href="index.php">
                        <div class="brand-icon">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <span>Explore<span class="text-info">Learning</span></span>
                    </a>
                    <p class="text-muted small mt-3 pr-lg-4">
                        A modern educational ecosystem empowering students and instructors through interactive curriculum, streamlined enrollment, and direct mentorship.
                    </p>
                </div>
                <div class="col-6 col-lg-2 mb-4">
                    <h5 class="footer-heading">Platform</h5>
                    <ul class="footer-links">
                        <li><a href="index.php">Browse Courses</a></li>
                        <li><a href="teacher.php">Become a Teacher</a></li>
                        <li><a href="student.php">Student Portal</a></li>
                        <li><a href="login.php">Account Login</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2 mb-4">
                    <h5 class="footer-heading">Resources</h5>
                    <ul class="footer-links">
                        <li><a href="enquiry.php">Help & Support</a></li>
                        <li><a href="index.php#features">Platform Features</a></li>
                        <li><a href="login.php">Admin Portal</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-heading">Need Assistance?</h5>
                    <p class="text-muted small">Have questions about admissions or batch schedules? Get in touch with our counselors.</p>
                    <a href="enquiry.php" class="btn btn-outline-glass btn-sm mt-2">
                        <i class="fa-solid fa-envelope mr-1"></i> Send an Enquiry
                    </a>
                </div>
            </div>

            <div class="copyright-bar">
                <p class="mb-0">&copy; 2026 Explore Learning System. All rights reserved. Designed with modern web standards.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap & jQuery Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
