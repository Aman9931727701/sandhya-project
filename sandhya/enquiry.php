<?php
$n=$ns=$e=$es=$p=$ps=$m=$ms="";

function namecheck()
{
	$n=trim($_POST['n']);
	$nc='/^[a-zA-Z ]*$/';
	if(preg_match($nc,$n) && strlen($n)>0)
	{
		return 'y';
	}
	else
	{
		return 'n';
	}
}

function emailcheck()
{
	$e=trim($_POST['e']);
	$ec='/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
	if(preg_match($ec,$e) && strlen($e)>0)
	{
		return 'y';
	}
	else
	{
		return 'n';
	}
}

function phonecheck()
{
	$p=trim($_POST['p']);
	$pc='/^[0-9]{10}$/';
	if(preg_match($pc,$p) && strlen($p)>0)
	{
		return 'y';
	}
	else
	{
		return 'n';
	}
}

function messagecheck()
{
	$m=trim($_POST['m']);
	if(strlen($m)>=4)
	{
		return 'y';
	}
	else
	{
		return 'n';
	}
}

if(isset($_POST['s']))
{
	if(namecheck()=='y')
	{
		$n=trim($_POST['n']);
	}
	else
	{
		$ns="Please enter a valid alphabetic name";
	}
	
	if(emailcheck()=='y')
	{
		$e=trim($_POST['e']);
	}
	else
	{
		$es="Please enter a valid email address";
	}
	
	if(phonecheck()=='y')
	{
		$p=trim($_POST['p']);
	}
	else
	{
		$ps="Please enter a 10-digit phone number";
	}
	
	if(messagecheck()=='y')
	{
		$m=trim($_POST['m']);
	}
	else
	{
		$ms="Message must be at least 4 characters";
	}

	if(namecheck()=='y' && emailcheck()=='y' && phonecheck()=='y' && messagecheck()=='y')
	{
		$d=Date('Y-m-d');
		include "connection.php";
		if(isset($cn) && $cn)
		{
			$safe_n = mysqli_real_escape_string($cn, $n);
			$safe_e = mysqli_real_escape_string($cn, $e);
			$safe_p = mysqli_real_escape_string($cn, $p);
			$safe_m = mysqli_real_escape_string($cn, $m);

			$q="insert into enquiry (name,email,phone,message,date) values('".$safe_n."','".$safe_e."','".$safe_p."','".$safe_m."','".$d."')";
			$sq=mysqli_query($cn,$q);
			if($sq)
			{
				echo '<script>alert("Thank you! Your enquiry has been received. Our counselor will contact you soon.");</script>';
				$n=$ns=$e=$es=$p=$ps=$m=$ms="";
			}
			else 
			{	
				$db_error = mysqli_error($cn);
			}
		}
	}
}

if(isset($_POST['s1']))
{
	$n=$ns=$e=$es=$p=$ps=$m=$ms="";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Enquiry & Support - Explore Learning</title>

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
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.96) 100%), url('e.jpg') fixed center/cover no-repeat;
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

        /* Cards */
        .glass-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            backdrop-filter: blur(16px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            padding: 36px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(6, 182, 212, 0.3);
        }

        .header-icon-badge {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(59, 130, 246, 0.2));
            border: 1px solid rgba(6, 182, 212, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #38bdf8;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .page-subtitle {
            font-size: 0.9rem;
            color: #94a3b8;
            margin-bottom: 25px;
        }

        /* Form Inputs */
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
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
            font-size: 1rem;
        }

        .input-group-custom:focus-within .input-icon {
            color: #38bdf8;
        }

        .form-control-custom {
            background: transparent;
            border: none;
            color: #ffffff;
            padding: 10px 12px 10px 0;
            font-size: 0.92rem;
            width: 100%;
            outline: none !important;
        }

        .form-control-custom::placeholder {
            color: #64748b;
        }

        .error-msg {
            color: #f87171;
            font-size: 0.78rem;
            margin-top: 4px;
            display: block;
            font-weight: 500;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            color: white !important;
            font-weight: 700;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.35);
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.45);
        }

        .btn-glass-secondary {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--border-color);
            color: #cbd5e1;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .btn-glass-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        /* Contact Details Card */
        .contact-box {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .contact-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(6, 182, 212, 0.15);
            color: #38bdf8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
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
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="fa-solid fa-right-to-bracket mr-1"></i> Login</a></li>
                    <li class="nav-item"><a class="nav-link active" href="enquiry.php"><i class="fa-solid fa-envelope mr-1"></i> Enquiry</a></li>
                </ul>
                <div>
                    <a href="index.php" class="btn btn-sm btn-outline-light px-3" style="border-radius: 8px;">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Browse Catalog
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container my-auto py-5">
        <div class="row align-items-center">
            
            <!-- Left Contact Info Column -->
            <div class="col-lg-5 mb-5 mb-lg-0 pr-lg-4">
                <span class="badge badge-pill badge-info px-3 py-1.5 mb-3" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-headset mr-1"></i> Student Support Desk
                </span>
                <h1 class="font-weight-bold text-white mb-3" style="font-size: 2.4rem; line-height: 1.2;">
                    Have Questions? Let's Talk!
                </h1>
                <p class="text-muted mb-4 leading-relaxed">
                    Have doubts about course fees, curriculum structure, or teacher mentorship? Drop us an enquiry and an academic advisor will get back to you shortly.
                </p>

                <div class="contact-box">
                    <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <div class="small text-muted">Email Us</div>
                        <div class="font-weight-bold text-white">admissions@explorelearning.com</div>
                    </div>
                </div>

                <div class="contact-box">
                    <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                    <div>
                        <div class="small text-muted">Direct Phone Support</div>
                        <div class="font-weight-bold text-white">+91 98765 43210 (Mon-Sat, 9am - 7pm)</div>
                    </div>
                </div>

                <div class="contact-box">
                    <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div>
                        <div class="small text-muted">Campus Center</div>
                        <div class="font-weight-bold text-white">Explore Learning Tech Hub, Cyber Park</div>
                    </div>
                </div>
            </div>

            <!-- Right Enquiry Form Column -->
            <div class="col-lg-7">
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h2 class="page-title mb-1">Send An Enquiry</h2>
                            <p class="page-subtitle mb-0">Submit your queries directly to our admissions office</p>
                        </div>
                        <div class="header-icon-badge mb-0">
                            <i class="fa-solid fa-paper-plane"></i>
                        </div>
                    </div>

                    <?php if(isset($db_error)): ?>
                        <div class="alert alert-danger py-2 px-3 small rounded-lg mb-3">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i> Database Error: <?php echo htmlspecialchars($db_error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <!-- Full Name -->
                        <div class="form-group mb-3">
                            <label class="form-label">Your Full Name</label>
                            <div class="input-group-custom">
                                <div class="input-icon"><i class="fa-solid fa-user"></i></div>
                                <input type="text" name="n" class="form-control-custom" placeholder="e.g. Rahul Sharma" value="<?php echo htmlspecialchars($n); ?>" required>
                            </div>
                            <?php if(!empty($ns)): ?><span class="error-msg"><i class="fa-solid fa-circle-exclamation mr-1"></i><?php echo $ns; ?></span><?php endif; ?>
                        </div>

                        <!-- Email & Phone row -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <div class="input-group-custom">
                                    <div class="input-icon"><i class="fa-solid fa-envelope"></i></div>
                                    <input type="email" name="e" class="form-control-custom" placeholder="e.g. rahul@example.com" value="<?php echo htmlspecialchars($e); ?>" required>
                                </div>
                                <?php if(!empty($es)): ?><span class="error-msg"><i class="fa-solid fa-circle-exclamation mr-1"></i><?php echo $es; ?></span><?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <div class="input-group-custom">
                                    <div class="input-icon"><i class="fa-solid fa-phone"></i></div>
                                    <input type="text" name="p" maxlength="10" class="form-control-custom" placeholder="10-digit mobile" value="<?php echo htmlspecialchars($p); ?>" required>
                                </div>
                                <?php if(!empty($ps)): ?><span class="error-msg"><i class="fa-solid fa-circle-exclamation mr-1"></i><?php echo $ps; ?></span><?php endif; ?>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="form-group mb-3">
                            <label class="form-label">Your Message or Question</label>
                            <div class="input-group-custom align-items-start">
                                <div class="input-icon pt-2"><i class="fa-solid fa-comment-dots"></i></div>
                                <textarea name="m" class="form-control-custom" rows="3" placeholder="Tell us what course you are interested in or ask any question..." required><?php echo htmlspecialchars($m); ?></textarea>
                            </div>
                            <?php if(!empty($ms)): ?><span class="error-msg"><i class="fa-solid fa-circle-exclamation mr-1"></i><?php echo $ms; ?></span><?php endif; ?>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex align-items-center gap-3 mt-3 pt-2">
                            <button type="submit" name="s" class="btn btn-gradient flex-grow-1 mr-2">
                                Send Enquiry <i class="fa-solid fa-paper-plane ml-1"></i>
                            </button>
                            <button type="submit" name="s1" class="btn btn-glass-secondary">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer text-center">
        <div class="container">
            <p class="mb-0">&copy; 2026 Explore Learning System. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap & jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
