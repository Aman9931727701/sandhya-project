// Explore Learning - Standalone Local Node.js Server
// Emulates the exact PHP/MySQL application with zero external npm dependencies.

const http = require('http');
const fs = require('fs');
const path = require('path');
const url = require('url');
const querystring = require('querystring');

const PORT = 3000;
const BASE_DIR = path.resolve(__dirname, '..');

// In-Memory Database matching the exact PHP MySQL Schema
const db = {
  teacher: [
    { tid: 1, tname: 'Dr. Komal Sharma', temail: 'komal@example.com', tpassword: 'pass', tphone: '9876543210', tdetail: 'Senior Computer Science Professor with 10+ years experience in Full Stack Web Development and AI.' },
    { tid: 2, tname: 'Prof. Rajesh Verma', temail: 'rajesh@example.com', tpassword: 'pass', tphone: '9123456780', tdetail: 'Data Scientist and Python Specialist with extensive corporate training background.' }
  ],
  student: [
    { sid: 1, sname: 'Aman Kumar', semail: 'aman@example.com', spassword: 'pass', sphone: '9988776655' },
    { sid: 2, sname: 'Neha Singh', semail: 'neha@example.com', spassword: 'pass', sphone: '9811223344' }
  ],
  course: [
    { coid: 1, tid: 1, cname: 'Full Stack Web Development', cdetail: 'Master HTML, CSS, JavaScript, PHP, MySQL and modern web engineering paradigms.', crate: 5000, cdate: '2026-10-15' },
    { coid: 2, tid: 2, cname: 'Python for Data Science & AI', cdetail: 'Comprehensive curriculum covering NumPy, Pandas, Scikit-learn, and Neural Networks.', crate: 6500, cdate: '2026-10-18' },
    { coid: 3, tid: 1, cname: 'Database Architecture & MySQL', cdetail: 'In-depth database design, relational normalization, indexing, and transactional queries.', crate: 4200, cdate: '2026-10-20' }
  ],
  content: [
    { ctid: 1, tid: 1, coid: 1, chapterno: 1, chaptername: 'Introduction to Web Architecture', chaptercontent: 'load/intro.pdf', chaptervideo: 'https://www.youtube.com/watch?v=HVjjoMvutj4' },
    { ctid: 2, tid: 1, coid: 1, chapterno: 2, chaptername: 'PHP Server Fundamentals', chaptercontent: 'load/php_guide.pdf', chaptervideo: 'https://www.youtube.com/watch?v=m52ljs78S24&list=PL0eyrZgxdwhwwQQZA79OzYwl5ewA7HQih' },
    { ctid: 3, tid: 2, coid: 2, chapterno: 1, chaptername: 'Python Basics & NumPy', chaptercontent: 'load/python_numpy.pdf', chaptervideo: 'https://www.youtube.com/watch?v=6GuyMZ-cSzE' }
  ],
  apply: [
    { aid: 1, tid: 1, sid: 1, coid: 1, startdate: '2026-11-01', enddate: '2026-12-01', amount: 5000, adate: '2026-10-22' },
    { aid: 2, tid: 2, sid: 2, coid: 2, startdate: '2026-11-05', enddate: '2026-12-05', amount: 6500, adate: '2026-10-23' }
  ],
  chat: [
    { chid: 1, senderid: 1, senderRole: 'student', senderName: 'Aman Kumar', receiverid: 1, message: 'Hello Dr. Komal, when will the live query session start?', adate: '2026-10-23', atime: '10:30 AM' },
    { chid: 2, senderid: 1, senderRole: 'teacher', senderName: 'Dr. Komal Sharma', receiverid: 1, message: 'Hi Aman, the live session is scheduled this Saturday at 11:00 AM.', adate: '2026-10-23', atime: '10:35 AM' }
  ],
  enquiry: [
    { eid: 1, name: 'Rohan Sharma', email: 'rohan@example.com', phone: '9871234567', message: 'Do you offer weekend batches for working professionals?', date: '2026-10-21' }
  ]
};

// Sessions Store
const sessions = {};

function parseCookies(req) {
  const list = {};
  const rc = req.headers.cookie;
  if (rc) {
    rc.split(';').forEach(c => {
      const parts = c.split('=');
      list[parts.shift().trim()] = decodeURI(parts.join('='));
    });
  }
  return list;
}

function getSession(req, res) {
  const cookies = parseCookies(req);
  let sid = cookies.SESSION_ID;
  if (!sid || !sessions[sid]) {
    sid = 'sess_' + Math.random().toString(36).substring(2, 12);
    sessions[sid] = {};
    res.setHeader('Set-Cookie', `SESSION_ID=${sid}; Path=/; HttpOnly`);
  }
  return { sid, session: sessions[sid] };
}

// Common Shared Styles and Navigation
function getCommonHead(title = 'Explore Learning', bgImage = 'y.jpg') {
  return `
  <title>${title}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #06b6d4;
      --dark-bg: #0f172a;
      --card-bg: rgba(30, 41, 59, 0.82);
      --border-color: rgba(255, 255, 255, 0.1);
    }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #f1f5f9;
      background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.96) 100%), url('${bgImage}') fixed center/cover no-repeat;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    /* Modern Glassmorphism Navbar */
    .glass-nav {
      background: rgba(15, 23, 42, 0.9) !important;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid var(--border-color);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
    }
    .navbar-brand {
      font-weight: 800;
      font-size: 1.35rem;
      color: #ffffff !important;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .nav-link {
      color: #cbd5e1 !important;
      font-weight: 500;
      font-size: 0.82rem;
      padding: 6px 10px !important;
      border-radius: 10px;
      transition: all 0.25s ease;
      margin: 0 1px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      white-space: nowrap;
    }
    .nav-link i {
      margin-right: 5px !important;
    }
    @media (min-width: 992px) and (max-width: 1400px) {
      .navbar-brand { font-size: 1.15rem; }
      .navbar-brand .badge { display: none !important; }
      .nav-link { font-size: 0.76rem; padding: 5px 7px !important; gap: 4px; }
      .nav-link i { margin-right: 4px !important; }
    }
    .nav-link:hover {
      color: #ffffff !important;
      background: rgba(255, 255, 255, 0.08);
      transform: translateY(-1px);
    }
    /* Active Link with Cyan/Indigo Glow */
    .nav-link-active {
      background: linear-gradient(135deg, #06b6d4, #3b82f6) !important;
      color: #ffffff !important;
      font-weight: 700 !important;
      border-radius: 10px !important;
      box-shadow: 0 4px 14px rgba(6, 182, 212, 0.45) !important;
      transform: translateY(-1px);
    }
    /* Sub-header Breadcrumb / Current Page Indicator Bar */
    .page-indicator-bar {
      background: rgba(10, 15, 29, 0.85);
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(8px);
      padding: 9px 0;
      font-size: 0.82rem;
    }
    /* 3D Glass Cards with Subtle Shining Glow */
    .glass-card {
      background: var(--card-bg);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 20px;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      padding: 32px;
      box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.5), 0 0 25px rgba(6, 182, 212, 0.08), inset 0 1px 1px rgba(255, 255, 255, 0.2);
      transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
    }
    .glass-card:hover {
      transform: translateY(-5px);
      border-color: rgba(6, 182, 212, 0.45);
      box-shadow: 0 25px 50px -10px rgba(0, 0, 0, 0.6), 0 0 35px rgba(6, 182, 212, 0.2), inset 0 1px 2px rgba(255, 255, 255, 0.3);
    }
    /* Stat Cards with 3D Pop & Neon Edge */
    .stat-card {
      background: rgba(30, 41, 59, 0.75);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 18px;
      padding: 24px;
      backdrop-filter: blur(12px);
      transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35), inset 0 1px 1px rgba(255, 255, 255, 0.15);
    }
    .stat-card:hover {
      transform: translateY(-6px) scale(1.02);
      border-color: rgba(6, 182, 212, 0.5);
      box-shadow: 0 20px 35px rgba(0, 0, 0, 0.45), 0 0 20px rgba(6, 182, 212, 0.25), inset 0 1px 2px rgba(255, 255, 255, 0.25);
    }
    /* Glass Tables */
    .table-glass {
      width: 100%;
      color: #f1f5f9;
      border-collapse: separate;
      border-spacing: 0;
      border-radius: 14px;
      overflow: hidden;
      border: 1px solid var(--border-color);
      margin-top: 15px;
    }
    .table-glass th {
      background: rgba(15, 23, 42, 0.95);
      color: #94a3b8;
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 14px 16px;
      font-weight: 700;
      border-bottom: 1px solid var(--border-color);
    }
    .table-glass td {
      padding: 14px 16px;
      font-size: 0.88rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      background: rgba(30, 41, 59, 0.5);
      vertical-align: middle;
    }
    .table-glass tr:hover td {
      background: rgba(30, 41, 59, 0.85);
      color: #ffffff;
    }
    .btn-gradient {
      background: linear-gradient(135deg, #06b6d4, #3b82f6);
      color: white !important;
      font-weight: 700;
      border: none;
      padding: 10px 20px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(6, 182, 212, 0.35);
      transition: all 0.25s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none !important;
    }
    .btn-gradient:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(6, 182, 212, 0.45);
      color: white !important;
    }
    .btn-gradient-danger {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white !important;
      font-weight: 700;
      border: none;
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.35);
    }
    .btn-gradient-danger:hover {
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.45);
      color: white !important;
    }
    /* Forms & Inputs */
    .input-group-custom {
      background: rgba(15, 23, 42, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 12px;
      padding: 2px 8px;
      display: flex;
      align-items: center;
      transition: all 0.25s ease;
    }
    .input-group-custom:focus-within {
      border-color: #06b6d4;
      box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.2);
    }
    .input-icon {
      width: 38px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #94a3b8;
    }
    .form-control-custom {
      background: transparent !important;
      background-color: transparent !important;
      border: none !important;
      color: #ffffff !important;
      padding: 11px 12px 11px 4px;
      font-size: 0.95rem;
      width: 100%;
      outline: none !important;
      box-shadow: none !important;
    }
    .form-control-custom::placeholder {
      color: #64748b !important;
    }
    /* Prevent Chrome/Edge/Safari white autofill background */
    input.form-control-custom:-webkit-autofill,
    input.form-control-custom:-webkit-autofill:hover, 
    input.form-control-custom:-webkit-autofill:focus, 
    input.form-control-custom:-webkit-autofill:active,
    textarea.form-control-custom:-webkit-autofill,
    textarea.form-control-custom:-webkit-autofill:hover,
    textarea.form-control-custom:-webkit-autofill:focus,
    textarea.form-control-custom:-webkit-autofill:active {
      -webkit-box-shadow: 0 0 0 1000px rgba(15, 23, 42, 0.95) inset !important;
      box-shadow: 0 0 0 1000px rgba(15, 23, 42, 0.95) inset !important;
      -webkit-text-fill-color: #ffffff !important;
      caret-color: #ffffff !important;
      transition: background-color 5000s ease-in-out 0s !important;
    }
    /* Stat Cards */
    .stat-card {
      background: rgba(30, 41, 59, 0.75);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 18px;
      padding: 24px;
      backdrop-filter: blur(12px);
      transition: all 0.3s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .stat-card:hover {
      transform: translateY(-4px);
      border-color: rgba(6, 182, 212, 0.4);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
    }
    /* Chat bubbles with 3D depth and shining effect */
    .chat-msg-self {
      background: linear-gradient(135deg, #06b6d4, #2563eb);
      color: white;
      border-radius: 18px 18px 4px 18px;
      padding: 12px 18px;
      max-width: 75%;
      margin-left: auto;
      margin-bottom: 14px;
      box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.3);
      position: relative;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .chat-msg-other {
      background: rgba(30, 41, 59, 0.85);
      color: #f1f5f9;
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 18px 18px 18px 4px;
      padding: 12px 18px;
      max-width: 75%;
      margin-right: auto;
      margin-bottom: 14px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.08);
      position: relative;
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
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  `;
}

function getPublicNav(activePage = 'index.php', activeTitle = 'Home') {
  const links = [
    { href: 'index.php', label: 'HOME', icon: 'fa-house' },
    { href: 'teacher.php', label: 'TEACHER', icon: 'fa-chalkboard-user' },
    { href: 'student.php', label: 'STUDENT', icon: 'fa-user-graduate' },
    { href: 'login.php', label: 'LOGIN', icon: 'fa-right-to-bracket' },
    { href: 'enquiry.php', label: 'ENQUIRY', icon: 'fa-envelope' }
  ];

  const linksHtml = links.map(l => {
    const isActive = (activePage === l.href);
    return `<li class="nav-item">
      <a class="nav-link ${isActive ? 'nav-link-active' : ''}" href="${l.href}">
        <i class="fa-solid ${l.icon} mr-2"></i> ${l.label}
      </a>
    </li>`;
  }).join('');

  return `
  <nav class="navbar navbar-expand-xl navbar-dark glass-nav sticky-top">
    <div class="container">
      <a class="navbar-brand text-white font-weight-bold" href="index.php">
        <div style="width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg, #06b6d4, #3b82f6); display:flex; align-items:center; justify-content:center; color:white; box-shadow:0 4px 14px rgba(6,182,212,0.35); margin-right:10px;">
          <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <span>Explore<span class="text-info">Learning</span></span>
      </a>
      <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#mainNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav mx-auto">
          ${linksHtml}
        </ul>
        <div class="d-flex align-items-center">
          <a href="login.php" class="btn btn-sm btn-outline-light px-3 mr-2" style="border-radius:8px;">
            <i class="fa-solid fa-user-lock mr-1"></i> Login
          </a>
          <a href="student.php" class="btn btn-sm btn-gradient px-3" style="border-radius:8px;">
            <i class="fa-solid fa-user-plus mr-1"></i> Join Free
          </a>
        </div>
      </div>
    </div>
  </nav>
  `;
}

function getAdminNav(activePage = 'admin.php', activeTitle = 'Dashboard') {
  const links = [
    { href: 'admin.php', label: 'DASHBOARD', icon: 'fa-chart-pie' },
    { href: 'adteacher.php', label: 'TEACHERS', icon: 'fa-chalkboard-user' },
    { href: 'adstudent.php', label: 'STUDENTS', icon: 'fa-user-graduate' },
    { href: 'adcourse.php', label: 'COURSES', icon: 'fa-book-open' },
    { href: 'adcontent.php', label: 'CONTENT', icon: 'fa-file-pdf' },
    { href: 'adapply.php', label: 'APPLY', icon: 'fa-clipboard-check' },
    { href: 'adchat.php', label: 'CHAT', icon: 'fa-comments' },
    { href: 'adenquiry.php', label: 'ENQUIRY', icon: 'fa-envelope' }
  ];

  const linksHtml = links.map(l => {
    const isActive = (activePage === l.href);
    return `<li class="nav-item">
      <a class="nav-link ${isActive ? 'nav-link-active' : ''}" href="${l.href}">
        <i class="fa-solid ${l.icon} mr-2"></i> ${l.label}
      </a>
    </li>`;
  }).join('');

  return `
  <nav class="navbar navbar-expand-xl navbar-dark glass-nav sticky-top">
    <div class="container-fluid px-lg-4">
      <a class="navbar-brand text-white font-weight-bold" href="admin.php">
        <div style="width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg, #ef4444, #f97316); display:flex; align-items:center; justify-content:center; color:white; font-size:1.1rem; box-shadow:0 4px 14px rgba(239,68,68,0.35); margin-right:8px;">
          <i class="fa-solid fa-lock"></i>
        </div>
        <span>Explore<span class="text-danger">Admin</span></span>
        <span class="badge badge-danger ml-2 px-2.5 py-1 text-uppercase" style="font-size:0.7rem; border-radius:6px; letter-spacing:0.5px;">Control Panel</span>
      </a>
      <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#adminNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="adminNavbar">
        <ul class="navbar-nav mx-auto">
          ${linksHtml}
        </ul>
        <div class="d-flex align-items-center">
          <div class="d-flex align-items-center text-white mr-3">
            <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg, #ef4444, #f97316); display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:0.8rem; margin-right:8px;">A</div>
            <div class="small">
              <span class="font-weight-bold d-block leading-none">argus</span>
              <span class="text-muted" style="font-size:0.7rem;">Superadmin</span>
            </div>
          </div>
          <a href="end.php" class="btn btn-sm btn-danger font-weight-bold px-3 py-1.5" style="border-radius:10px;">
            <i class="fa-solid fa-power-off mr-1"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </nav>
  `;
}

function getTeacherNav(activePage = 'tteacher.php', activeTitle = 'Profile') {
  const links = [
    { href: 'tteacher.php', label: 'PROFILE', icon: 'fa-user-tie' },
    { href: 'tcourse.php', label: 'COURSES', icon: 'fa-book-open' },
    { href: 'tcontent.php', label: 'CONTENT', icon: 'fa-file-pdf' },
    { href: 'tchat.php', label: 'CHAT', icon: 'fa-comments' },
    { href: 'tapply.php', label: 'APPLY', icon: 'fa-clipboard-user' }
  ];

  const linksHtml = links.map(l => {
    const isActive = (activePage === l.href);
    return `<li class="nav-item">
      <a class="nav-link ${isActive ? 'nav-link-active' : ''}" href="${l.href}">
        <i class="fa-solid ${l.icon} mr-2"></i> ${l.label}
      </a>
    </li>`;
  }).join('');

  return `
  <nav class="navbar navbar-expand-xl navbar-dark glass-nav sticky-top">
    <div class="container">
      <a class="navbar-brand text-white font-weight-bold" href="tteacher.php">
        <div style="width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg, #06b6d4, #3b82f6); display:flex; align-items:center; justify-content:center; color:white; box-shadow:0 4px 14px rgba(6,182,212,0.35); margin-right:8px;">
          <i class="fa-solid fa-chalkboard-user"></i>
        </div>
        <span>Explore<span class="text-info">Teacher</span></span>
        <span class="badge badge-info ml-2 px-2.5 py-1 text-uppercase" style="font-size:0.7rem; border-radius:6px; letter-spacing:0.5px;">Faculty Portal</span>
      </a>
      <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#teacherNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="teacherNavbar">
        <ul class="navbar-nav mx-auto">
          ${linksHtml}
        </ul>
        <div>
          <a href="end.php" class="btn btn-sm btn-outline-danger font-weight-bold px-3 py-1.5" style="border-radius:10px;">
            <i class="fa-solid fa-power-off mr-1"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </nav>
  `;
}

function getStudentNav(activePage = 'sstudent.php', activeTitle = 'Profile') {
  const links = [
    { href: 'sstudent.php', label: 'PROFILE', icon: 'fa-id-card' },
    { href: 'scourse.php', label: 'COURSES', icon: 'fa-book-open' },
    { href: 'scontent.php', label: 'CONTENT', icon: 'fa-file-pdf' },
    { href: 'sdapply.php', label: 'ENROLLMENTS', icon: 'fa-clipboard-check' },
    { href: 'schat.php', label: 'CHAT', icon: 'fa-comments' }
  ];

  const linksHtml = links.map(l => {
    const isActive = (activePage === l.href);
    return `<li class="nav-item">
      <a class="nav-link ${isActive ? 'nav-link-active' : ''}" href="${l.href}">
        <i class="fa-solid ${l.icon} mr-2"></i> ${l.label}
      </a>
    </li>`;
  }).join('');

  return `
  <nav class="navbar navbar-expand-xl navbar-dark glass-nav sticky-top">
    <div class="container">
      <a class="navbar-brand text-white font-weight-bold" href="sstudent.php">
        <div style="width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg, #10b981, #06b6d4); display:flex; align-items:center; justify-content:center; color:white; box-shadow:0 4px 14px rgba(16,185,129,0.35); margin-right:8px;">
          <i class="fa-solid fa-user-graduate"></i>
        </div>
        <span>Explore<span class="text-success">Student</span></span>
        <span class="badge badge-success ml-2 px-2.5 py-1 text-uppercase" style="font-size:0.7rem; border-radius:6px; letter-spacing:0.5px;">Learner Portal</span>
      </a>
      <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#studentNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="studentNavbar">
        <ul class="navbar-nav mx-auto">
          ${linksHtml}
        </ul>
        <div>
          <a href="end.php" class="btn btn-sm btn-outline-danger font-weight-bold px-3 py-1.5" style="border-radius:10px;">
            <i class="fa-solid fa-power-off mr-1"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </nav>
  `;
}

// HTTP Server
module.exports = function handler(req, res) {
  const parsedUrl = url.parse(req.url, true);
  let pathname = parsedUrl.pathname;
  if (pathname === '/') pathname = '/index.php';
  if (pathname.startsWith('/')) pathname = pathname.substring(1);

  // Serve static images directly
  if (/\.(jpg|png|webp|avif|ico)$/i.test(pathname)) {
    const filePath = path.join(BASE_DIR, pathname);
    if (fs.existsSync(filePath)) {
      res.writeHead(200, { 'Content-Type': 'image/jpeg' });
      return fs.createReadStream(filePath).pipe(res);
    }
  }

  const { sid, session } = getSession(req, res);

  // Body parser helper
  let body = '';
  req.on('data', chunk => { body += chunk.toString(); });
  req.on('end', () => {
    const post = querystring.parse(body);

    // ROUTING LOGIC

    // 1. LOGOUT
    if (pathname === 'end.php') {
      delete sessions[sid];
      res.writeHead(302, { Location: 'login.php' });
      return res.end();
    }

    // 2. CHECK.PHP (Login Processor)
    if (pathname === 'check.php') {
      const n = (post.n || '').trim();
      const p = (post.p || '').trim();

      if (n === 'argus' && p === 'academy') {
        session.a = n;
        session.aid = sid;
        res.writeHead(302, { Location: 'admin.php' });
        return res.end();
      }

      // Check Teacher
      const teacher = db.teacher.find(t => t.temail === n && t.tpassword === p);
      if (teacher) {
        session.tt = n;
        session.ttid = sid;
        session.tid = teacher.tid;
        res.writeHead(302, { Location: 'tteacher.php' });
        return res.end();
      }

      // Check Student
      const student = db.student.find(s => s.semail === n && s.spassword === p);
      if (student) {
        session.ss = n;
        session.ssid = sid;
        session.sid = student.sid;
        res.writeHead(302, { Location: 'sstudent.php' });
        return res.end();
      }

      session.wrong = '*** wrong id or password ';
      res.writeHead(302, { Location: 'login.php' });
      return res.end();
    }

    // 3. LOGIN.PHP (Unified Glassmorphic UI)
    if (pathname === 'login.php') {
      if (session.a) { res.writeHead(302, { Location: 'admin.php' }); return res.end(); }
      if (session.tt) { res.writeHead(302, { Location: 'tteacher.php' }); return res.end(); }
      if (session.ss) { res.writeHead(302, { Location: 'sstudent.php' }); return res.end(); }

      const wrongMsg = session.wrong ? `
        <div class="alert alert-danger py-2 px-3 small rounded-lg mb-4 text-left border-0" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5;">
          <i class="fa-solid fa-triangle-exclamation mr-1"></i> ${session.wrong}
        </div>` : '';
      delete session.wrong;

      const html = `
      <!DOCTYPE html>
      <html lang="en">
      <head>${getCommonHead('Explore Learning - Account Login', 'd.jpg')}</head>
      <body>
        ${getPublicNav('login.php', 'Login / Sign In')}
        <main class="container my-auto py-5 d-flex align-items-center justify-content-center">
          <div class="glass-card text-center" style="max-width: 480px; width: 100%;">
            <div style="width:70px; height:70px; border-radius:20px; background:linear-gradient(135deg, rgba(6,182,212,0.2), rgba(59,130,246,0.2)); border:1px solid rgba(6,182,212,0.35); display:flex; align-items:center; justify-content:center; font-size:1.8rem; color:#38bdf8; margin:0 auto 20px;">
              <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h2 class="text-white font-weight-bold mb-1">Welcome Back</h2>
            <p class="text-muted small mb-4">Sign in to your student, teacher, or admin dashboard</p>
            ${wrongMsg}
            <form action="check.php" method="POST" class="text-left" autocomplete="off">
              <div class="form-group mb-3">
                <label class="text-muted small font-weight-bold text-uppercase">User ID or Registered Email</label>
                <div class="input-group-custom">
                  <div class="input-icon"><i class="fa-solid fa-user"></i></div>
                  <input type="text" name="n" class="form-control-custom" placeholder="e.g. admin or yourname@example.com" autocomplete="off" required>
                </div>
              </div>
              <div class="form-group mb-4">
                <label class="text-muted small font-weight-bold text-uppercase">Password</label>
                <div class="input-group-custom">
                  <div class="input-icon"><i class="fa-solid fa-lock"></i></div>
                  <input type="password" name="p" class="form-control-custom" placeholder="Enter password" minlength="4" autocomplete="new-password" required>
                </div>
              </div>
              <button type="submit" name="s" class="btn btn-gradient w-100">Sign In &rarr;</button>
            </form>
            <div class="mt-4 pt-3 border-top border-secondary text-center small text-muted">
              Don't have an account? <a href="student.php" class="text-info font-weight-bold ml-1">Register as Student</a>
            </div>
            <div class="p-3 rounded mt-3 text-left small border border-secondary" style="background: rgba(15,23,42,0.6);">
              <div class="text-white font-weight-bold mb-1"><i class="fa-solid fa-circle-info text-info mr-1"></i> Demo Credentials:</div>
              <div class="text-muted">Admin: <code class="text-info">argus / academy</code></div>
              <div class="text-muted">Teacher: <code class="text-info">komal@example.com / pass</code></div>
              <div class="text-muted">Student: <code class="text-info">aman@example.com / pass</code></div>
            </div>
          </div>
        </main>
        <footer class="site-footer text-center">
          <div class="container">
            <span class="text-muted">&copy; 2026 Explore Learning System &bull; All Rights Reserved</span>
          </div>
        </footer>
      </body>
      </html>
      `;
      res.writeHead(200, { 'Content-Type': 'text/html' });
      return res.end(html);
    }

    // 4. INDEX.PHP (Home Page - Modern, Beautiful & Fully Scrollable)
    if (pathname === 'index.php') {
      const searchTerm = (post.n || '').toLowerCase();
      let matched = db.course;
      if (searchTerm) {
        matched = db.course.filter(c => {
          const teacher = db.teacher.find(t => t.tid === c.tid) || {};
          return c.cname.toLowerCase().includes(searchTerm) ||
                 c.cdetail.toLowerCase().includes(searchTerm) ||
                 (teacher.tname && teacher.tname.toLowerCase().includes(searchTerm));
        });
      }

      let courseCards = '';
      if (matched.length > 0) {
        matched.forEach(c => {
          const teacher = db.teacher.find(t => t.tid === c.tid) || { tname: 'Instructor', tdetail: 'Expert faculty' };
          const initial = (teacher.tname || 'I').charAt(0).toUpperCase();
          courseCards += `
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-3d-shine h-100 border-0">
              <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span class="badge badge-info px-2.5 py-1.5" style="border-radius: 6px; font-weight: 700;">Course #${c.coid}</span>
                  <span class="font-weight-bold" style="font-size: 1.25rem; color: #34d399;">₹${c.crate}</span>
                </div>
                <h4 class="card-title text-white font-weight-bold mb-2" style="font-size: 1.2rem; line-height: 1.4;">${c.cname}</h4>
                <p class="card-text text-muted small flex-grow-1" style="line-height: 1.6;">${c.cdetail}</p>
                <div class="d-flex align-items-center gap-3 pt-3 border-top border-secondary mt-3 mb-3">
                  <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #a855f7); display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; margin-right: 12px;">
                    ${initial}
                  </div>
                  <div>
                    <div class="text-light font-weight-bold small">${teacher.tname}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">${teacher.tdetail.slice(0, 45)}...</div>
                  </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-2">
                  <span class="text-muted small"><i class="far fa-calendar-alt mr-1"></i> ${c.cdate}</span>
                  <a href="scapply.php?coid=${c.coid}&tid=${c.tid}&amt=${c.crate}" class="btn btn-info btn-sm font-weight-bold px-3 py-1.5" style="border-radius: 8px;">
                    Enroll Now &rarr;
                  </a>
                </div>
              </div>
            </div>
          </div>
          `;
        });
      } else {
        courseCards = '<div class="col-12 text-center text-white py-5"><h3>No courses found matching your query.</h3><a href="index.php" class="btn btn-outline-light btn-sm mt-3">Reset Search</a></div>';
      }

      const html = `
      <!DOCTYPE html>
      <html lang="en">
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Explore Learning - Next-Gen Online Education Platform</title>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
          html { scroll-behavior: smooth; }
          body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #f1f5f9;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.96) 100%), url('a.jpg') fixed center/cover no-repeat;
            min-height: 100vh;
          }
          .glass-nav {
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
          }
          .nav-link { color: #cbd5e1 !important; font-weight: 500; padding: 8px 16px !important; }
          .nav-link:hover { color: #fff !important; }
          .hero-section { padding: 90px 0 60px; }
          .hero-title { font-size: 3.2rem; font-weight: 800; line-height: 1.15; letter-spacing: -1px; }
          .text-gradient { background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
          .stat-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 22px;
            text-align: center;
            backdrop-filter: blur(14px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.15);
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
          }
          .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.12), transparent);
            transform: skewX(-20deg);
            transition: 0.6s ease;
          }
          .stat-card:hover {
            transform: translateY(-6px) scale(1.02);
            border-color: rgba(6, 182, 212, 0.5);
            box-shadow: 0 18px 36px rgba(0,0,0,0.5), 0 0 20px rgba(6, 182, 212, 0.25);
          }
          .stat-card:hover::after {
            left: 140%;
          }
          .feature-box {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 18px;
            padding: 32px 24px;
            text-align: center;
            height: 100%;
            backdrop-filter: blur(14px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.15);
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
          }
          .feature-box:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: rgba(56, 189, 248, 0.5);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5), 0 0 25px rgba(56, 189, 248, 0.3);
          }
          .card-3d-shine {
            background: rgba(30, 41, 59, 0.75);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 18px;
            box-shadow: 0 12px 28px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.18);
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
          }
          .card-3d-shine::after {
            content: '';
            position: absolute;
            top: 0;
            left: -120%;
            width: 70%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transform: skewX(-20deg);
            transition: 0.7s ease;
            pointer-events: none;
          }
          .card-3d-shine:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: rgba(6, 182, 212, 0.55);
            box-shadow: 0 22px 45px rgba(0,0,0,0.55), 0 0 25px rgba(6, 182, 212, 0.3);
          }
          .card-3d-shine:hover::after {
            left: 140%;
          }
        </style>
      </head>
      <body>
        ${getPublicNav()}
        
        <!-- Hero Section -->
        <header class="hero-section text-center">
          <div class="container">
            <span class="badge badge-pill badge-info px-3 py-2 mb-3" style="font-size: 0.85rem;"><i class="fas fa-bolt mr-1"></i> Elevate Your Tech & Career Skills Today</span>
            <h1 class="hero-title text-white mt-2">Learn Without Limits with <span class="text-gradient">Explore Learning</span></h1>
            <p class="lead text-muted mx-auto my-3" style="max-width: 650px;">
              Connect with expert mentors, access interactive study modules, track your enrolled courses, and build verified skills.
            </p>
            <div class="card p-2 mx-auto my-4 shadow-lg border-0" style="max-width: 600px; background: rgba(30, 41, 59, 0.85); border-radius: 16px;">
              <form action="index.php#courses" method="POST" class="d-flex">
                <input type="text" name="n" class="form-control bg-transparent text-white border-0 px-3" placeholder="Search courses (e.g. Full Stack, Python, Database...)" value="${searchTerm}">
                <button type="submit" name="s" class="btn btn-info font-weight-bold px-4" style="border-radius: 12px;">Search</button>
              </form>
            </div>
            
            <!-- Quick Stats -->
            <div class="row mt-5">
              <div class="col-6 col-md-3 mb-3"><div class="stat-card"><h2 class="text-info font-weight-bold mb-1">50+</h2><small class="text-muted">Active Courses</small></div></div>
              <div class="col-6 col-md-3 mb-3"><div class="stat-card"><h2 class="text-info font-weight-bold mb-1">12k+</h2><small class="text-muted">Students Enrolled</small></div></div>
              <div class="col-6 col-md-3 mb-3"><div class="stat-card"><h2 class="text-info font-weight-bold mb-1">98%</h2><small class="text-muted">Satisfaction Rate</small></div></div>
              <div class="col-6 col-md-3 mb-3"><div class="stat-card"><h2 class="text-info font-weight-bold mb-1">24/7</h2><small class="text-muted">Mentor Support</small></div></div>
            </div>
          </div>
        </header>

        <!-- Course Catalog Section -->
        <section id="courses" class="py-5">
          <div class="container">
            <div class="text-center mb-5">
              <span class="text-info font-weight-bold text-uppercase small" style="letter-spacing: 2px;">Explore Our Catalog</span>
              <h2 class="text-white font-weight-bold mt-1">Featured Learning Programs</h2>
              <p class="text-muted">Hand-crafted curriculum designed to take you from fundamentals to real-world mastery.</p>
            </div>
            <div class="row">
              ${courseCards}
            </div>
          </div>
        </section>

        <!-- Features / Why Choose Us -->
        <section class="py-5" style="background: rgba(15, 23, 42, 0.5); border-top: 1px solid rgba(255,255,255,0.08); border-bottom: 1px solid rgba(255,255,255,0.08);">
          <div class="container">
            <div class="text-center mb-5">
              <span class="text-info font-weight-bold text-uppercase small" style="letter-spacing: 2px;">Platform Advantages</span>
              <h2 class="text-white font-weight-bold mt-1">Why Learn With Us</h2>
            </div>
            <div class="row">
              <div class="col-md-4 mb-4">
                <div class="feature-box">
                  <div style="font-size: 2rem; color: #38bdf8; margin-bottom: 15px;"><i class="fas fa-comments"></i></div>
                  <h4 class="text-white font-weight-bold">Live Mentor Chat</h4>
                  <p class="text-muted small">Instant messaging directly with course faculty for quick doubt resolution and guidance.</p>
                </div>
              </div>
              <div class="col-md-4 mb-4">
                <div class="feature-box">
                  <div style="font-size: 2rem; color: #38bdf8; margin-bottom: 15px;"><i class="fas fa-file-pdf"></i></div>
                  <h4 class="text-white font-weight-bold">Notes & Video Lessons</h4>
                  <p class="text-muted small">Chapter-by-chapter reading materials, downloadable PDFs, and embedded video lectures.</p>
                </div>
              </div>
              <div class="col-md-4 mb-4">
                <div class="feature-box">
                  <div style="font-size: 2rem; color: #38bdf8; margin-bottom: 15px;"><i class="fas fa-shield-alt"></i></div>
                  <h4 class="text-white font-weight-bold">Flexible Timelines</h4>
                  <p class="text-muted small">Choose your start and completion dates to match your personal and professional schedule.</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Call to Action Banner -->
        <section class="py-5 text-center">
          <div class="container">
            <div class="p-5 rounded" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2) 0%, rgba(59, 130, 246, 0.2) 100%); border: 1px solid rgba(6, 182, 212, 0.35); border-radius: 20px;">
              <h2 class="text-white font-weight-bold mb-3">Ready to Accelerate Your Career?</h2>
              <p class="text-muted mb-4">Join thousands of students learning full-stack development, data science, and modern technology.</p>
              <a href="student.php" class="btn btn-info font-weight-bold px-4 py-2 mr-2">Create Student Account</a>
              <a href="enquiry.php" class="btn btn-outline-light font-weight-bold px-4 py-2">Contact Admissions</a>
            </div>
          </div>
        </section>

        <!-- Footer -->
        <footer class="py-4 text-center text-muted border-top border-secondary" style="background: #090d16;">
          <div class="container">
            <p class="mb-0 small">&copy; 2026 Explore Learning System. All rights reserved.</p>
          </div>
        </footer>
      </body>
      </html>
      `;
      res.writeHead(200, { 'Content-Type': 'text/html' });
      return res.end(html);
    }

    // 5. TEACHER.PHP (Modern Glassmorphic UI)
    if (pathname === 'teacher.php') {
      let msg = '';
      let n_val = '', e_val = '', p_val = '', d_val = '';
      if (post.s) {
        n_val = (post.n || '').trim();
        e_val = (post.e || '').trim();
        const password = (post.pa || '').trim();
        p_val = (post.p || '').trim();
        d_val = (post.d || '').trim();

        const nameRegex = /^[a-zA-Z\s.]{2,50}$/;
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const phoneRegex = /^[6-9][0-9]{9}$/;

        const errors = [];
        if (!nameRegex.test(n_val)) {
          errors.push('Full Name must contain only letters, dots and spaces (minimum 2 characters).');
        }
        if (!emailRegex.test(e_val)) {
          errors.push('Please enter a valid email address (e.g. name@domain.com).');
        }
        if (!password || password.length < 4) {
          errors.push('Password must be at least 4 characters long.');
        }
        if (!phoneRegex.test(p_val)) {
          errors.push('Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9.');
        }
        if (!d_val || d_val.length < 5) {
          errors.push('Profile Bio must be at least 5 characters long.');
        }

        if (errors.length === 0) {
          const newTid = db.teacher.length + 1;
          db.teacher.push({ tid: newTid, tname: n_val, temail: e_val, tpassword: password, tphone: p_val, tdetail: d_val });
          msg = `<script>alert("Thanks for teacher registration! You can now login."); window.location.href="login.php";</script>`;
        } else {
          msg = `<div class="alert alert-danger py-2.5 px-3 small rounded-lg mb-3 border-0" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5;"><i class="fa-solid fa-triangle-exclamation mr-1.5"></i> ${errors.join('<br>')}</div>`;
        }
      }

      const html = `
      <!DOCTYPE html>
      <html lang="en">
      <head>${getCommonHead('Explore Learning - Teacher Registration', 'z.jpg')}</head>
      <body>
        ${getPublicNav('teacher.php', 'Teacher Registration')}
        <main class="container my-auto py-5">
          <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0 pr-lg-4">
              <span class="badge badge-pill badge-info px-3 py-1.5 mb-3" style="font-size: 0.8rem;"><i class="fa-solid fa-chalkboard-user mr-1"></i> Faculty Recruitment</span>
              <h1 class="font-weight-bold text-white mb-3" style="font-size: 2.4rem; line-height: 1.2;">Share Your Knowledge with Global Learners</h1>
              <p class="text-muted mb-4 leading-relaxed">Teach what you love. Publish courses, set batch rates, upload notes and mentor students worldwide.</p>
              <div class="d-flex align-items-center gap-3 mb-3 text-muted small"><i class="fa-solid fa-check text-info mr-2"></i> Create and price courses with full autonomy</div>
              <div class="d-flex align-items-center gap-3 mb-3 text-muted small"><i class="fa-solid fa-check text-info mr-2"></i> Attach chapter PDF guides and video lecture streams</div>
              <div class="d-flex align-items-center gap-3 text-muted small"><i class="fa-solid fa-check text-info mr-2"></i> Live 1-on-1 private messaging with students</div>
            </div>
            <div class="col-lg-7">
              <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div>
                    <h2 class="font-weight-bold text-white mb-1" style="font-size: 1.85rem;">Teacher Registration</h2>
                    <p class="text-muted small mb-0">Apply to become an instructor on Explore Learning</p>
                  </div>
                  <div style="width: 55px; height: 55px; border-radius: 16px; background: rgba(6,182,212,0.15); border: 1px solid rgba(6,182,212,0.3); color: #38bdf8; display:flex; align-items:center; justify-content:center; font-size: 1.5rem;">
                    <i class="fa-solid fa-chalkboard-user"></i>
                  </div>
                </div>
                ${msg}
                <form action="teacher.php" method="POST" class="mt-4" autocomplete="off">
                  <div class="form-group mb-3">
                    <label class="text-muted small font-weight-bold text-uppercase">Full Name & Title</label>
                    <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-user-tie"></i></div><input type="text" name="n" value="${n_val}" class="form-control-custom" placeholder="e.g. Dr. Komal Sharma" pattern="^[a-zA-Z\\s.]{2,50}$" title="Only letters and spaces allowed (minimum 2 characters)" autocomplete="off" required></div>
                  </div>
                  <div class="form-group mb-3">
                    <label class="text-muted small font-weight-bold text-uppercase">Email Address</label>
                    <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-envelope"></i></div><input type="email" name="e" value="${e_val}" class="form-control-custom" placeholder="e.g. komal@example.com" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$" title="Enter a valid email address" autocomplete="off" required></div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="text-muted small font-weight-bold text-uppercase">Password</label>
                      <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-lock"></i></div><input type="password" name="pa" class="form-control-custom" placeholder="Min. 4 chars" minlength="4" autocomplete="new-password" required></div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="text-muted small font-weight-bold text-uppercase">Phone Number</label>
                      <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-phone"></i></div><input type="tel" name="p" value="${p_val}" maxlength="10" class="form-control-custom" placeholder="10-digit mobile" pattern="^[6-9][0-9]{9}$" title="Enter a 10-digit mobile number starting with 6, 7, 8, or 9" autocomplete="off" required></div>
                    </div>
                  </div>
                  <div class="form-group mb-3">
                    <label class="text-muted small font-weight-bold text-uppercase">Profile Bio & Specialization</label>
                    <div class="input-group-custom align-items-start"><div class="input-icon pt-2"><i class="fa-solid fa-award"></i></div><textarea name="d" class="form-control-custom" rows="3" minlength="5" placeholder="Degree, pedagogical experience, and expertise areas..." required>${d_val}</textarea></div>
                  </div>
                  <div class="d-flex align-items-center gap-3 pt-2">
                    <button type="submit" name="s" value="Register" class="btn btn-gradient flex-grow-1 mr-2">Register as Instructor &rarr;</button>
                    <a href="login.php" class="btn btn-outline-light" style="border-radius: 12px; font-weight:600; padding:12px 20px;">Sign In</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </main>
        <footer class="site-footer text-center">
          <div class="container">
            <span class="text-muted">&copy; 2026 Explore Learning System &bull; All Rights Reserved</span>
          </div>
        </footer>
      </body>
      </html>
      `;
      res.writeHead(200, { 'Content-Type': 'text/html' });
      return res.end(html);
    }

    // 6. STUDENT.PHP (Modern Glassmorphic UI)
    if (pathname === 'student.php') {
      let msg = '';
      let n_val = '', e_val = '', p_val = '';
      if (post.s) {
        n_val = (post.n || '').trim();
        e_val = (post.e || '').trim();
        const password = (post.pa || '').trim();
        p_val = (post.p || '').trim();

        const nameRegex = /^[a-zA-Z\s]{2,50}$/;
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const phoneRegex = /^[6-9][0-9]{9}$/;

        const errors = [];
        if (!nameRegex.test(n_val)) {
          errors.push('Full Name must contain only letters and spaces (minimum 2 characters).');
        }
        if (!emailRegex.test(e_val)) {
          errors.push('Please enter a valid email address (e.g. name@domain.com).');
        }
        if (!password || password.length < 4) {
          errors.push('Password must be at least 4 characters long.');
        }
        if (!phoneRegex.test(p_val)) {
          errors.push('Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9.');
        }

        if (errors.length === 0) {
          const newSid = db.student.length + 1;
          db.student.push({ sid: newSid, sname: n_val, semail: e_val, spassword: password, sphone: p_val });
          msg = `<script>alert("Thanks for student registration! You can now login."); window.location.href="login.php";</script>`;
        } else {
          msg = `<div class="alert alert-danger py-2.5 px-3 small rounded-lg mb-3 border-0" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5;"><i class="fa-solid fa-triangle-exclamation mr-1.5"></i> ${errors.join('<br>')}</div>`;
        }
      }

      const html = `
      <!DOCTYPE html>
      <html lang="en">
      <head>${getCommonHead('Explore Learning - Student Registration', 'y.jpg')}</head>
      <body>
        ${getPublicNav('student.php', 'Student Registration')}
        <main class="container my-auto py-5">
          <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0 pr-lg-4">
              <span class="badge badge-pill badge-info px-3 py-1.5 mb-3" style="font-size: 0.8rem;"><i class="fa-solid fa-sparkles mr-1"></i> Admissions Open</span>
              <h1 class="font-weight-bold text-white mb-3" style="font-size: 2.4rem; line-height: 1.2;">Start Your Path to Tech Mastery</h1>
              <p class="text-muted mb-4 leading-relaxed">Join Explore Learning to study with top faculty, access comprehensive course modules, and interact directly with your instructors.</p>
              <div class="d-flex align-items-center gap-3 mb-3 text-muted small"><i class="fa-solid fa-check text-info mr-2"></i> Structured syllabus with downloadable notes</div>
              <div class="d-flex align-items-center gap-3 mb-3 text-muted small"><i class="fa-solid fa-check text-info mr-2"></i> Direct private messaging with course professors</div>
              <div class="d-flex align-items-center gap-3 text-muted small"><i class="fa-solid fa-check text-info mr-2"></i> Verified certificate upon course completion</div>
            </div>
            <div class="col-lg-7">
              <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div>
                    <h2 class="font-weight-bold text-white mb-1" style="font-size: 1.85rem;">Create Student Profile</h2>
                    <p class="text-muted small mb-0">Fill in your information to register as a student</p>
                  </div>
                  <div style="width: 55px; height: 55px; border-radius: 16px; background: rgba(6,182,212,0.15); border: 1px solid rgba(6,182,212,0.3); color: #38bdf8; display:flex; align-items:center; justify-content:center; font-size: 1.5rem;">
                    <i class="fa-solid fa-user-graduate"></i>
                  </div>
                </div>
                ${msg}
                <form action="student.php" method="POST" class="mt-4" autocomplete="off">
                  <div class="form-group mb-3">
                    <label class="text-muted small font-weight-bold text-uppercase">Full Name</label>
                    <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-user"></i></div><input type="text" name="n" value="${n_val}" class="form-control-custom" placeholder="e.g. Aman Kumar" pattern="^[a-zA-Z\\s]{2,50}$" title="Only letters and spaces allowed (minimum 2 characters)" autocomplete="off" required></div>
                  </div>
                  <div class="form-group mb-3">
                    <label class="text-muted small font-weight-bold text-uppercase">Email Address</label>
                    <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-envelope"></i></div><input type="email" name="e" value="${e_val}" class="form-control-custom" placeholder="e.g. aman@example.com" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$" title="Enter a valid email address" autocomplete="off" required></div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="text-muted small font-weight-bold text-uppercase">Create Password</label>
                      <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-lock"></i></div><input type="password" name="pa" class="form-control-custom" placeholder="Min. 4 characters" minlength="4" autocomplete="new-password" required></div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="text-muted small font-weight-bold text-uppercase">Phone Number</label>
                      <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-phone"></i></div><input type="tel" name="p" value="${p_val}" maxlength="10" class="form-control-custom" placeholder="10-digit mobile" pattern="^[6-9][0-9]{9}$" title="Enter a 10-digit mobile number starting with 6, 7, 8, or 9" autocomplete="off" required></div>
                    </div>
                  </div>
                  <div class="d-flex align-items-center gap-3 pt-2">
                    <button type="submit" name="s" value="Register" class="btn btn-gradient flex-grow-1 mr-2">Complete Registration &rarr;</button>
                    <a href="login.php" class="btn btn-outline-light" style="border-radius: 12px; font-weight:600; padding:12px 20px;">Sign In</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </main>
        <footer class="site-footer text-center">
          <div class="container">
            <span class="text-muted">&copy; 2026 Explore Learning System &bull; All Rights Reserved</span>
          </div>
        </footer>
      </body>
      </html>
      `;
      res.writeHead(200, { 'Content-Type': 'text/html' });
      return res.end(html);
    }

    // 7. ENQUIRY.PHP (Modern Glassmorphic UI)
    if (pathname === 'enquiry.php') {
      let msg = '';
      let n_val = '', e_val = '', p_val = '', m_val = '';
      if (post.s) {
        n_val = (post.n || '').trim();
        e_val = (post.e || '').trim();
        p_val = (post.p || '').trim();
        m_val = (post.m || '').trim();

        const nameRegex = /^[a-zA-Z\s]{2,50}$/;
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const phoneRegex = /^[6-9][0-9]{9}$/;

        const errors = [];
        if (!nameRegex.test(n_val)) {
          errors.push('Full Name must contain only letters (at least 2 characters).');
        }
        if (!emailRegex.test(e_val)) {
          errors.push('Please enter a valid email address (e.g., name@domain.com).');
        }
        if (!phoneRegex.test(p_val)) {
          errors.push('Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9.');
        }
        if (!m_val || m_val.length < 5) {
          errors.push('Message should be at least 5 characters long.');
        }

        if (errors.length === 0) {
          const newEid = db.enquiry.length + 1;
          db.enquiry.push({ eid: newEid, name: n_val, email: e_val, phone: p_val, message: m_val, date: new Date().toISOString().slice(0, 10) });
          msg = `<div class="alert alert-success py-2.5 px-3 small rounded-lg mb-3 border-0" style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.4); color: #6ee7b7;"><i class="fa-solid fa-circle-check mr-1.5"></i> Thanks for your enquiry! Our admissions counselor will contact you soon.</div>`;
          n_val = ''; e_val = ''; p_val = ''; m_val = '';
        } else {
          msg = `<div class="alert alert-danger py-2.5 px-3 small rounded-lg mb-3 border-0" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5;"><i class="fa-solid fa-triangle-exclamation mr-1.5"></i> ${errors.join('<br>')}</div>`;
        }
      }

      const html = `
      <!DOCTYPE html>
      <html lang="en">
      <head>${getCommonHead('Explore Learning - Student Support & Enquiry', 'e.jpg')}</head>
      <body>
        ${getPublicNav('enquiry.php', 'Enquiry & Support')}
        <main class="container my-auto py-5">
          <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0 pr-lg-4">
              <span class="badge badge-pill badge-info px-3 py-1.5 mb-3" style="font-size: 0.8rem;"><i class="fa-solid fa-headset mr-1"></i> Student Support Desk</span>
              <h1 class="font-weight-bold text-white mb-3" style="font-size: 2.4rem; line-height: 1.2;">Have Questions? Let's Talk!</h1>
              <p class="text-muted mb-4 leading-relaxed">Have doubts about course fees, curriculum structure, or teacher mentorship? Drop us an enquiry and an academic advisor will get back to you shortly.</p>
              <div class="p-3 rounded mb-3 border border-secondary" style="background: rgba(15,23,42,0.6);">
                <div class="text-white font-weight-bold"><i class="fa-solid fa-envelope text-info mr-2"></i> admissions@explorelearning.com</div>
              </div>
              <div class="p-3 rounded mb-3 border border-secondary" style="background: rgba(15,23,42,0.6);">
                <div class="text-white font-weight-bold"><i class="fa-solid fa-phone text-info mr-2"></i> +91 98765 43210 (Mon-Sat, 9am - 7pm)</div>
              </div>
            </div>
            <div class="col-lg-7">
              <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div>
                    <h2 class="font-weight-bold text-white mb-1" style="font-size: 1.85rem;">Send An Enquiry</h2>
                    <p class="text-muted small mb-0">Submit your queries directly to our admissions office</p>
                  </div>
                  <div style="width: 55px; height: 55px; border-radius: 16px; background: rgba(6,182,212,0.15); border: 1px solid rgba(6,182,212,0.3); color: #38bdf8; display:flex; align-items:center; justify-content:center; font-size: 1.5rem;">
                    <i class="fa-solid fa-paper-plane"></i>
                  </div>
                </div>
                ${msg}
                <form action="enquiry.php" method="POST" class="mt-4">
                  <div class="form-group mb-3">
                    <label class="text-muted small font-weight-bold text-uppercase">Your Full Name</label>
                    <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-user"></i></div><input type="text" name="n" value="${n_val}" class="form-control-custom" placeholder="e.g. Rahul Sharma" pattern="^[a-zA-Z\\s]{2,50}$" title="Only letters and spaces allowed (minimum 2 characters)" required></div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="text-muted small font-weight-bold text-uppercase">Email Address</label>
                      <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-envelope"></i></div><input type="email" name="e" value="${e_val}" class="form-control-custom" placeholder="e.g. rahul@example.com" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$" title="Enter a valid email address (e.g. user@domain.com)" required></div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="text-muted small font-weight-bold text-uppercase">Phone Number</label>
                      <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-phone"></i></div><input type="tel" name="p" value="${p_val}" maxlength="10" class="form-control-custom" placeholder="10-digit mobile" pattern="^[6-9][0-9]{9}$" title="Enter a valid 10-digit mobile number starting with 6, 7, 8, or 9" required></div>
                    </div>
                  </div>
                  <div class="form-group mb-3">
                    <label class="text-muted small font-weight-bold text-uppercase">Your Message</label>
                    <div class="input-group-custom align-items-start"><div class="input-icon pt-2"><i class="fa-solid fa-comment-dots"></i></div><textarea name="m" class="form-control-custom" rows="3" minlength="5" placeholder="Tell us what course you are interested in or ask any question..." required>${m_val}</textarea></div>
                  </div>
                  <div class="pt-2">
                    <button type="submit" name="s" value="Send Enquiry" class="btn btn-gradient w-100">Submit Enquiry &rarr;</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </main>
        <footer class="site-footer text-center">
          <div class="container">
            <span class="text-muted">&copy; 2026 Explore Learning System &bull; All Rights Reserved</span>
          </div>
        </footer>
      </body>
      </html>
      `;
      res.writeHead(200, { 'Content-Type': 'text/html' });
      return res.end(html);
    }

    // 8. ADMIN DASHBOARD & MODULES
    if (pathname.startsWith('admin') || pathname.startsWith('ad')) {
      if (!session.a) { res.writeHead(302, { Location: 'login.php' }); return res.end(); }

      // Route: addelete*
      if (pathname === 'addeletecourse.php') {
        const coid = parseInt(parsedUrl.query.coid);
        db.course = db.course.filter(c => c.coid !== coid);
        res.writeHead(302, { Location: 'adcourse.php' });
        return res.end();
      }
      if (pathname === 'addeleteteacher.php') {
        const tid = parseInt(parsedUrl.query.tid);
        db.teacher = db.teacher.filter(t => t.tid !== tid);
        res.writeHead(302, { Location: 'adteacher.php' });
        return res.end();
      }
      if (pathname === 'addeletestudent.php') {
        const sid_del = parseInt(parsedUrl.query.sid);
        db.student = db.student.filter(s => s.sid !== sid_del);
        res.writeHead(302, { Location: 'adstudent.php' });
        return res.end();
      }
      if (pathname === 'addeleteenquiry.php') {
        const eid = parseInt(parsedUrl.query.eid);
        db.enquiry = db.enquiry.filter(e => e.eid !== eid);
        res.writeHead(302, { Location: 'adenquiry.php' });
        return res.end();
      }

      let contentTable = '';
      let pageTitle = 'Dashboard';

      if (pathname === 'admin.php') {
        pageTitle = 'Dashboard Overview';
        const recentApplies = db.apply.slice(-4).reverse().map(a => {
          const st = db.student.find(s => s.sid === a.sid) || { sname: 'Student #' + a.sid };
          const cr = db.course.find(c => c.coid === a.coid) || { cname: 'Course #' + a.coid };
          return `<tr>
            <td class="font-weight-bold text-white"><i class="fa-solid fa-graduation-cap text-info mr-2"></i>${cr.cname}</td>
            <td>${st.sname}</td>
            <td class="text-success font-weight-bold">₹${a.amount}</td>
            <td class="text-muted small">${a.adate}</td>
          </tr>`;
        }).join('');

        const recentEnquiries = db.enquiry.slice(-4).reverse().map(e => `
          <tr>
            <td class="font-weight-bold text-white"><i class="fa-solid fa-user-circle text-warning mr-2"></i>${e.name}</td>
            <td class="small text-truncate" style="max-width:200px;">${e.message}</td>
            <td><a href="tel:${e.phone}" class="text-info">${e.phone}</a></td>
            <td class="text-muted small">${e.date}</td>
          </tr>
        `).join('');

        contentTable = `
        <div class="row mb-4">
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="stat-card" style="border-left: 4px solid #06b6d4;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small text-uppercase font-weight-bold">Faculty Members</span>
                <div style="width:40px; height:40px; border-radius:10px; background:rgba(6,182,212,0.15); color:#38bdf8; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                  <i class="fa-solid fa-chalkboard-user"></i>
                </div>
              </div>
              <div class="d-flex align-items-baseline justify-content-between">
                <h2 class="text-white font-weight-bold mb-0">${db.teacher.length}</h2>
                <a href="adteacher.php" class="small text-info font-weight-bold">Manage &rarr;</a>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="stat-card" style="border-left: 4px solid #10b981;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small text-uppercase font-weight-bold">Registered Students</span>
                <div style="width:40px; height:40px; border-radius:10px; background:rgba(16,185,129,0.15); color:#34d399; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                  <i class="fa-solid fa-user-graduate"></i>
                </div>
              </div>
              <div class="d-flex align-items-baseline justify-content-between">
                <h2 class="text-white font-weight-bold mb-0">${db.student.length}</h2>
                <a href="adstudent.php" class="small text-success font-weight-bold">Manage &rarr;</a>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="stat-card" style="border-left: 4px solid #f59e0b;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small text-uppercase font-weight-bold">Published Courses</span>
                <div style="width:40px; height:40px; border-radius:10px; background:rgba(245,158,11,0.15); color:#fbbf24; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                  <i class="fa-solid fa-book-open"></i>
                </div>
              </div>
              <div class="d-flex align-items-baseline justify-content-between">
                <h2 class="text-white font-weight-bold mb-0">${db.course.length}</h2>
                <a href="adcourse.php" class="small text-warning font-weight-bold">Manage &rarr;</a>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="stat-card" style="border-left: 4px solid #ef4444;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small text-uppercase font-weight-bold">User Enquiries</span>
                <div style="width:40px; height:40px; border-radius:10px; background:rgba(239,68,68,0.15); color:#f87171; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                  <i class="fa-solid fa-envelope-open-text"></i>
                </div>
              </div>
              <div class="d-flex align-items-baseline justify-content-between">
                <h2 class="text-white font-weight-bold mb-0">${db.enquiry.length}</h2>
                <a href="adenquiry.php" class="small text-danger font-weight-bold">View All &rarr;</a>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6 mb-4">
            <div class="glass-card p-4 h-100">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-weight-bold text-white mb-0"><i class="fa-solid fa-clipboard-check text-info mr-2"></i> Recent Enrollments</h5>
                <a href="adapply.php" class="btn btn-sm btn-outline-info font-weight-bold px-2 py-1" style="font-size:0.75rem;">View All</a>
              </div>
              <div class="table-responsive">
                <table class="table-glass mb-0">
                  <thead><tr><th>Course</th><th>Student</th><th>Fee</th><th>Date</th></tr></thead>
                  <tbody>${recentApplies || '<tr><td colspan="4" class="text-center text-muted">No enrollments yet</td></tr>'}</tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mb-4">
            <div class="glass-card p-4 h-100">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-weight-bold text-white mb-0"><i class="fa-solid fa-headset text-warning mr-2"></i> Recent Enquiries</h5>
                <a href="adenquiry.php" class="btn btn-sm btn-outline-warning font-weight-bold px-2 py-1" style="font-size:0.75rem;">View All</a>
              </div>
              <div class="table-responsive">
                <table class="table-glass mb-0">
                  <thead><tr><th>Sender</th><th>Query</th><th>Phone</th><th>Date</th></tr></thead>
                  <tbody>${recentEnquiries || '<tr><td colspan="4" class="text-center text-muted">No enquiries recorded</td></tr>'}</tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        `;
      } else if (pathname === 'adteacher.php') {
        pageTitle = 'Registered Teachers';
        let rows = db.teacher.map(t => `
          <tr>
            <td class="text-muted font-weight-bold">#${t.tid}</td>
            <td class="font-weight-bold text-white">
              <div class="d-flex align-items-center">
                <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg, #06b6d4, #3b82f6); display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:0.85rem; margin-right:10px;">
                  ${(t.tname || 'T').charAt(0).toUpperCase()}
                </div>
                <div>
                  <span class="d-block leading-none">${t.tname}</span>
                  <small class="text-muted">Instructor</small>
                </div>
              </div>
            </td>
            <td><code class="text-info">${t.temail}</code></td>
            <td>${t.tphone}</td>
            <td class="text-muted small" style="max-width:240px;">${t.tdetail}</td>
            <td class="text-right">
              <a href="addeleteteacher.php?tid=${t.tid}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete instructor ${t.tname}?')">
                <i class="fa-solid fa-trash mr-1"></i> Delete
              </a>
            </td>
          </tr>
        `).join('');
        contentTable = `
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge badge-pill badge-info px-3 py-1.5"><i class="fa-solid fa-users mr-1"></i> Total Teachers: ${db.teacher.length}</span>
          <a href="teacher.php" class="btn btn-sm btn-gradient"><i class="fa-solid fa-user-plus mr-1"></i> Register New Teacher</a>
        </div>
        <div class="table-responsive">
          <table class="table-glass">
            <thead><tr><th>TID</th><th>Instructor</th><th>Email</th><th>Phone</th><th>Specialization</th><th class="text-right">Action</th></tr></thead>
            <tbody>${rows || '<tr><td colspan="6" class="text-center text-muted">No teachers found</td></tr>'}</tbody>
          </table>
        </div>`;
      } else if (pathname === 'adstudent.php') {
        pageTitle = 'Registered Students';
        let rows = db.student.map(s => `
          <tr>
            <td class="text-muted font-weight-bold">#${s.sid}</td>
            <td class="font-weight-bold text-white">
              <div class="d-flex align-items-center">
                <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg, #10b981, #06b6d4); display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:0.85rem; margin-right:10px;">
                  ${(s.sname || 'S').charAt(0).toUpperCase()}
                </div>
                <div>
                  <span class="d-block leading-none">${s.sname}</span>
                  <small class="text-muted">Learner</small>
                </div>
              </div>
            </td>
            <td><code class="text-info">${s.semail}</code></td>
            <td>${s.sphone}</td>
            <td class="text-right">
              <a href="addeletestudent.php?sid=${s.sid}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete student ${s.sname}?')">
                <i class="fa-solid fa-trash mr-1"></i> Delete
              </a>
            </td>
          </tr>
        `).join('');
        contentTable = `
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge badge-pill badge-success px-3 py-1.5"><i class="fa-solid fa-user-graduate mr-1"></i> Total Enrolled Students: ${db.student.length}</span>
          <a href="student.php" class="btn btn-sm btn-gradient"><i class="fa-solid fa-user-plus mr-1"></i> Register New Student</a>
        </div>
        <div class="table-responsive">
          <table class="table-glass">
            <thead><tr><th>SID</th><th>Student Name</th><th>Email Address</th><th>Phone</th><th class="text-right">Action</th></tr></thead>
            <tbody>${rows || '<tr><td colspan="5" class="text-center text-muted">No students registered yet</td></tr>'}</tbody>
          </table>
        </div>`;
      } else if (pathname === 'adcourse.php') {
        pageTitle = 'All Courses';
        let rows = db.course.map(c => {
          const teacher = db.teacher.find(t => t.tid === c.tid) || { tname: 'Instructor #' + c.tid };
          return `
          <tr>
            <td class="text-muted font-weight-bold">#${c.coid}</td>
            <td class="font-weight-bold text-white">
              <div class="d-flex align-items-center">
                <i class="fa-solid fa-book-open text-info mr-2"></i>
                <span>${c.cname}</span>
              </div>
            </td>
            <td><span class="badge badge-pill badge-secondary px-2.5 py-1">${teacher.tname}</span></td>
            <td class="text-muted small" style="max-width:240px;">${c.cdetail}</td>
            <td><span class="badge badge-success px-2.5 py-1">₹${c.crate}</span></td>
            <td class="text-muted small">${c.cdate}</td>
            <td class="text-right">
              <a href="addeletecourse.php?coid=${c.coid}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete course ${c.cname}?')">
                <i class="fa-solid fa-trash mr-1"></i> Delete
              </a>
            </td>
          </tr>`;
        }).join('');
        contentTable = `
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge badge-pill badge-warning px-3 py-1.5"><i class="fa-solid fa-book-open mr-1"></i> Total Active Courses: ${db.course.length}</span>
        </div>
        <div class="table-responsive">
          <table class="table-glass">
            <thead><tr><th>COID</th><th>Course Title</th><th>Instructor</th><th>Syllabus Details</th><th>Fee</th><th>Date Added</th><th class="text-right">Action</th></tr></thead>
            <tbody>${rows || '<tr><td colspan="7" class="text-center text-muted">No courses cataloged yet</td></tr>'}</tbody>
          </table>
        </div>`;
      } else if (pathname === 'adenquiry.php') {
        pageTitle = 'User Enquiries';
        let rows = db.enquiry.map(e => `
          <tr>
            <td class="text-muted font-weight-bold">#${e.eid}</td>
            <td class="font-weight-bold text-white">${e.name}</td>
            <td><code class="text-info">${e.email}</code></td>
            <td><a href="tel:${e.phone}" class="text-white">${e.phone}</a></td>
            <td class="text-muted small" style="max-width:280px;">${e.message}</td>
            <td class="text-muted small">${e.date}</td>
            <td class="text-right">
              <a href="addeleteenquiry.php?eid=${e.eid}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete enquiry #${e.eid}?')">
                <i class="fa-solid fa-trash mr-1"></i> Delete
              </a>
            </td>
          </tr>
        `).join('');
        contentTable = `
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge badge-pill badge-danger px-3 py-1.5"><i class="fa-solid fa-envelope-open-text mr-1"></i> Total Enquiries: ${db.enquiry.length}</span>
        </div>
        <div class="table-responsive">
          <table class="table-glass">
            <thead><tr><th>EID</th><th>Full Name</th><th>Email</th><th>Phone</th><th>Message Content</th><th>Date</th><th class="text-right">Action</th></tr></thead>
            <tbody>${rows || '<tr><td colspan="7" class="text-center text-muted">No enquiries recorded yet</td></tr>'}</tbody>
          </table>
        </div>`;
      } else if (pathname === 'adapply.php') {
        pageTitle = 'Course Enrollments';
        let rows = db.apply.map(a => {
          const st = db.student.find(s => s.sid === a.sid) || { sname: 'Student #' + a.sid };
          const cr = db.course.find(c => c.coid === a.coid) || { cname: 'Course #' + a.coid };
          const tr = db.teacher.find(t => t.tid === a.tid) || { tname: 'Teacher #' + a.tid };
          return `
          <tr>
            <td class="text-muted font-weight-bold">#${a.aid}</td>
            <td class="font-weight-bold text-white">${st.sname}</td>
            <td><span class="text-info">${cr.cname}</span></td>
            <td>${tr.tname}</td>
            <td class="small text-muted">${a.startdate} &rarr; ${a.enddate}</td>
            <td><span class="badge badge-success px-2 py-1">₹${a.amount}</span></td>
            <td class="small text-muted">${a.adate}</td>
          </tr>`;
        }).join('');
        contentTable = `
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge badge-pill badge-info px-3 py-1.5"><i class="fa-solid fa-clipboard-check mr-1"></i> Active Enrollments: ${db.apply.length}</span>
        </div>
        <div class="table-responsive">
          <table class="table-glass">
            <thead><tr><th>AID</th><th>Student</th><th>Course</th><th>Faculty</th><th>Batch Duration</th><th>Amount</th><th>Applied Date</th></tr></thead>
            <tbody>${rows || '<tr><td colspan="7" class="text-center text-muted">No enrollment records available</td></tr>'}</tbody>
          </table>
        </div>`;
      } else if (pathname === 'adcontent.php') {
        pageTitle = 'Course Contents';
        let rows = db.content.map(cn => {
          const cr = db.course.find(c => c.coid === cn.coid) || { cname: 'Course #' + cn.coid };
          return `
          <tr>
            <td class="text-muted font-weight-bold">#${cn.ctid}</td>
            <td class="font-weight-bold text-white">${cr.cname}</td>
            <td><span class="badge badge-pill badge-info px-2 py-1">Chapter ${cn.chapterno}</span></td>
            <td class="text-white">${cn.chaptername}</td>
            <td><span class="badge badge-secondary"><i class="fa-solid fa-file-pdf mr-1"></i> PDF Attached</span></td>
            <td><a href="${cn.chaptervideo}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa-solid fa-play mr-1"></i> Watch Video</a></td>
          </tr>`;
        }).join('');
        contentTable = `
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge badge-pill badge-info px-3 py-1.5"><i class="fa-solid fa-file-lines mr-1"></i> Total Chapter Modules: ${db.content.length}</span>
        </div>
        <div class="table-responsive">
          <table class="table-glass">
            <thead><tr><th>CTID</th><th>Course</th><th>Chapter #</th><th>Chapter Title</th><th>Document</th><th>Video Lesson</th></tr></thead>
            <tbody>${rows || '<tr><td colspan="6" class="text-center text-muted">No chapter content uploaded yet</td></tr>'}</tbody>
          </table>
        </div>`;
      } else if (pathname === 'adchat.php') {
        pageTitle = 'Chat Audit Log';
        let rows = db.chat.map(ch => {
          let sName = ch.senderName;
          if (!sName) {
            if (ch.senderRole === 'teacher') {
              const tr = db.teacher.find(t => t.tid === ch.senderid);
              sName = tr ? tr.tname : 'Teacher #' + ch.senderid;
            } else {
              const st = db.student.find(s => s.sid === ch.senderid);
              sName = st ? st.sname : 'Student #' + ch.senderid;
            }
          }
          const roleBadge = ch.senderRole === 'teacher'
            ? `<span class="badge badge-primary px-2.5 py-1"><i class="fa-solid fa-chalkboard-user mr-1"></i> ${sName} (Teacher)</span>`
            : `<span class="badge badge-success px-2.5 py-1"><i class="fa-solid fa-user-graduate mr-1"></i> ${sName} (Student)</span>`;
          return `
          <tr>
            <td class="text-muted font-weight-bold">#${ch.chid}</td>
            <td>${roleBadge}</td>
            <td class="text-white"><div class="p-2 rounded bg-dark border border-secondary">${ch.message}</div></td>
            <td class="text-info small font-weight-bold"><i class="fa-regular fa-clock mr-1"></i>${ch.atime || '10:00 AM'} &bull; ${ch.adate}</td>
          </tr>
          `;
        }).join('');
        contentTable = `
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge badge-pill badge-info px-3 py-1.5"><i class="fa-solid fa-comments mr-1"></i> Total Chat Messages: ${db.chat.length}</span>
        </div>
        <div class="table-responsive">
          <table class="table-glass">
            <thead><tr><th>CHID</th><th>Sender</th><th>Message Content</th><th>Date & Time</th></tr></thead>
            <tbody>${rows || '<tr><td colspan="4" class="text-center text-muted">No chat messages found in audit logs</td></tr>'}</tbody>
          </table>
        </div>`;
      }

      const html = `
      <!DOCTYPE html>
      <html lang="en">
      <head>${getCommonHead('Explore Learning - ' + pageTitle, 'y.jpg')}</head>
      <body>
        ${getAdminNav(pathname, pageTitle)}
        <main class="container-fluid px-lg-5 py-4 my-auto">
          <div class="glass-card mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
              <div class="d-flex align-items-center">
                <div style="width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg, rgba(239,68,68,0.2), rgba(249,115,22,0.2)); border:1px solid rgba(239,68,68,0.35); display:flex; align-items:center; justify-content:center; color:#f87171; font-size:1.3rem; margin-right:15px;">
                  <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                  <h2 class="text-white font-weight-bold mb-0">${pageTitle}</h2>
                  <p class="text-muted small mb-0 mt-0.5">Administrator Control Hub &bull; Signed in as <strong class="text-info">${session.a}</strong></p>
                </div>
              </div>
              <div class="mt-3 mt-md-0 d-flex gap-2">
                <a href="admin.php" class="btn btn-sm btn-outline-light mr-2"><i class="fa-solid fa-chart-pie mr-1"></i> Dashboard</a>
                <a href="end.php" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-power-off mr-1"></i> Sign Out</a>
              </div>
            </div>
            ${contentTable}
          </div>
        </main>
        <footer class="site-footer text-center">
          <div class="container-fluid px-lg-5">
            <span class="text-muted">&copy; 2026 Explore Learning System &bull; Admin Operations Portal</span>
          </div>
        </footer>
      </body>
      </html>
      `;
      res.writeHead(200, { 'Content-Type': 'text/html' });
      return res.end(html);
    }

    // 9. TEACHER PORTAL (tteacher, tcourse, tcontent, tcontentadd, tchat, tapply)
    if (pathname.startsWith('t')) {
      if (!session.tt) { res.writeHead(302, { Location: 'login.php' }); return res.end(); }
      const teacher = db.teacher.find(t => t.temail === session.tt) || db.teacher[0];
      session.tid = teacher.tid;

      if (pathname === 'tteacher.php') {
        const pageTitle = 'Instructor Profile';
        const myCourses = db.course.filter(c => c.tid === teacher.tid);
        const myApps = db.apply.filter(a => a.tid === teacher.tid);

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'z.jpg')}</head>
        <body>
          ${getTeacherNav(pathname, pageTitle)}
          <main class="container my-auto py-5">
            <div class="row">
              <div class="col-lg-4 mb-4">
                <div class="glass-card text-center p-4 h-100">
                  <div style="width:90px; height:90px; border-radius:50%; background:linear-gradient(135deg, #06b6d4, #3b82f6); display:flex; align-items:center; justify-content:center; color:white; font-size:2.2rem; font-weight:bold; margin:0 auto 16px; box-shadow:0 8px 24px rgba(6,182,212,0.4);">
                    ${(teacher.tname || 'T').charAt(0).toUpperCase()}
                  </div>
                  <h3 class="font-weight-bold text-white mb-1">${teacher.tname}</h3>
                  <span class="badge badge-pill badge-info px-3 py-1 mb-3">Faculty Instructor &bull; ID #${teacher.tid}</span>
                  <p class="text-muted small mb-4">${teacher.tdetail}</p>
                  <div class="pt-3 border-top border-secondary text-left">
                    <div class="mb-2.5 small"><i class="fa-solid fa-envelope text-info mr-2"></i><code class="text-white">${teacher.temail}</code></div>
                    <div class="mb-2.5 small"><i class="fa-solid fa-phone text-info mr-2"></i><span class="text-white">${teacher.tphone}</span></div>
                    <div class="small"><i class="fa-solid fa-circle-check text-success mr-2"></i><span class="text-success font-weight-bold">Active Instructor Account</span></div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="glass-card p-4 mb-4">
                  <h4 class="font-weight-bold text-white mb-3"><i class="fa-solid fa-chart-line text-info mr-2"></i> Teaching Overview</h4>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <div class="stat-card" style="border-left: 4px solid #06b6d4;">
                        <span class="text-muted small text-uppercase font-weight-bold">Courses Created</span>
                        <div class="d-flex align-items-baseline justify-content-between mt-2">
                          <h2 class="text-white font-weight-bold mb-0">${myCourses.length}</h2>
                          <a href="tcourse.php" class="small text-info font-weight-bold">View Courses &rarr;</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <div class="stat-card" style="border-left: 4px solid #10b981;">
                        <span class="text-muted small text-uppercase font-weight-bold">Enrolled Students</span>
                        <div class="d-flex align-items-baseline justify-content-between mt-2">
                          <h2 class="text-white font-weight-bold mb-0">${myApps.length}</h2>
                          <a href="tapply.php" class="small text-success font-weight-bold">View Applications &rarr;</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="glass-card p-4">
                  <h4 class="font-weight-bold text-white mb-3"><i class="fa-solid fa-bolt text-warning mr-2"></i> Quick Faculty Actions</h4>
                  <div class="d-flex flex-wrap gap-2">
                    <a href="tcourse.php" class="btn btn-gradient mr-2 mb-2"><i class="fa-solid fa-plus-circle mr-1.5"></i> Publish New Course</a>
                    <a href="tcontent.php" class="btn btn-outline-info font-weight-bold mr-2 mb-2" style="border-radius:10px;"><i class="fa-solid fa-file-pdf mr-1.5"></i> Upload Chapter Materials</a>
                    <a href="tchat.php" class="btn btn-outline-light font-weight-bold mb-2" style="border-radius:10px;"><i class="fa-solid fa-comments mr-1.5"></i> Student Chat Support</a>
                  </div>
                </div>
              </div>
            </div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Faculty Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }

      if (pathname === 'tcourse.php') {
        const pageTitle = 'My Courses';
        if (post.s) {
          const name = (post.n || '').trim();
          const detail = (post.d || '').trim();
          const rate = parseInt(post.ra) || 0;
          const newCoid = db.course.length + 1;
          db.course.push({ coid: newCoid, tid: teacher.tid, cname: name, cdetail: detail, crate: rate, cdate: new Date().toISOString().slice(0, 10) });
        }

        const myCourses = db.course.filter(c => c.tid === teacher.tid);
        let rows = myCourses.map(c => `
          <tr>
            <td class="text-muted font-weight-bold">#${c.coid}</td>
            <td class="font-weight-bold text-white"><i class="fa-solid fa-book-open text-info mr-2"></i>${c.cname}</td>
            <td class="text-muted small" style="max-width:240px;">${c.cdetail}</td>
            <td><span class="badge badge-success px-2.5 py-1">₹${c.crate}</span></td>
            <td class="text-muted small">${c.cdate}</td>
            <td class="text-right">
              <a href="tcontentadd.php?coid=${c.coid}" class="btn btn-sm btn-gradient">
                <i class="fa-solid fa-plus mr-1"></i> Add Content
              </a>
            </td>
          </tr>
        `).join('');

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'f.jpg')}</head>
        <body>
          ${getTeacherNav(pathname, pageTitle)}
          <main class="container my-auto py-5">
            <div class="glass-card mb-4 p-4">
              <h3 class="font-weight-bold text-white mb-1"><i class="fa-solid fa-plus-circle text-info mr-2"></i> Publish New Course</h3>
              <p class="text-muted small mb-4">Create a new course offering for enrolled and prospective students</p>
              <form action="tcourse.php" method="POST" autocomplete="off">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="text-muted small font-weight-bold text-uppercase">Course Title</label>
                    <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-heading"></i></div><input type="text" name="n" class="form-control-custom" placeholder="e.g. Master React & Node.js" minlength="3" required></div>
                  </div>
                  <div class="col-md-5 mb-3">
                    <label class="text-muted small font-weight-bold text-uppercase">Syllabus Description</label>
                    <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-align-left"></i></div><input type="text" name="d" class="form-control-custom" placeholder="Key topics covered, prerequisites..." minlength="5" required></div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label class="text-muted small font-weight-bold text-uppercase">Course Fee (₹)</label>
                    <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-indian-rupee-sign"></i></div><input type="number" name="ra" class="form-control-custom" placeholder="e.g. 4500" min="100" required></div>
                  </div>
                </div>
                <div class="text-right pt-2">
                  <button type="submit" name="s" value="Add Course" class="btn btn-gradient"><i class="fa-solid fa-cloud-arrow-up mr-1.5"></i> Publish Course Now</button>
                </div>
              </form>
            </div>

            <div class="glass-card p-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="font-weight-bold text-white mb-0"><i class="fa-solid fa-book-open text-info mr-2"></i> Your Published Courses</h4>
                <span class="badge badge-pill badge-info px-3 py-1.5">${myCourses.length} Courses</span>
              </div>
              <div class="table-responsive">
                <table class="table-glass">
                  <thead><tr><th>COID</th><th>Course Name</th><th>Description</th><th>Fee</th><th>Date Published</th><th class="text-right">Action</th></tr></thead>
                  <tbody>${rows || '<tr><td colspan="6" class="text-center text-muted">You have not created any courses yet. Use the form above to add your first course!</td></tr>'}</tbody>
                </table>
              </div>
            </div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Faculty Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }

      if (pathname === 'tcontentadd.php') {
        const coid = parseInt(parsedUrl.query.coid) || 1;
        const pageTitle = 'Add Chapter Content';
        const targetCourse = db.course.find(c => c.coid === coid) || { cname: 'Course #' + coid };

        if (post.s) {
          const chapno = parseInt(post.c) || 1;
          const chapname = (post.cn || '').trim();
          const video = (post.v || '').trim();
          db.content.push({ ctid: db.content.length + 1, tid: teacher.tid, coid: coid, chapterno: chapno, chaptername: chapname, chaptercontent: 'load/sample.pdf', chaptervideo: video });
          res.writeHead(302, { Location: 'tcontent.php' });
          return res.end();
        }

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'f.jpg')}</head>
        <body>
          ${getTeacherNav(pathname, pageTitle)}
          <main class="container my-auto py-5 d-flex align-items-center justify-content-center">
            <div class="glass-card p-4" style="max-width: 650px; width:100%;">
              <div class="d-flex align-items-center mb-3 pb-3 border-bottom border-secondary">
                <div style="width:50px; height:50px; border-radius:14px; background:rgba(6,182,212,0.15); color:#38bdf8; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin-right:15px;">
                  <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <div>
                  <h3 class="font-weight-bold text-white mb-0">Add Chapter Content</h3>
                  <p class="text-muted small mb-0">For Course: <strong class="text-info">${targetCourse.cname}</strong> (ID #${coid})</p>
                </div>
              </div>
              <form action="tcontentadd.php?coid=${coid}" method="POST" class="mt-4" autocomplete="off">
                <div class="form-group mb-3">
                  <label class="text-muted small font-weight-bold text-uppercase">Chapter Number</label>
                  <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-list-ol"></i></div><input type="number" name="c" class="form-control-custom" placeholder="e.g. 1" min="1" required></div>
                </div>
                <div class="form-group mb-3">
                  <label class="text-muted small font-weight-bold text-uppercase">Chapter Title</label>
                  <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-bookmark"></i></div><input type="text" name="cn" class="form-control-custom" placeholder="e.g. Introduction to Asynchronous Architecture" minlength="3" required></div>
                </div>
                <div class="form-group mb-4">
                  <label class="text-muted small font-weight-bold text-uppercase">Video Lecture Embed / YouTube URL</label>
                  <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-video"></i></div><input type="url" name="v" class="form-control-custom" placeholder="e.g. https://www.youtube.com/watch?v=..." required></div>
                </div>
                <div class="d-flex gap-3">
                  <button type="submit" name="s" value="Save Chapter" class="btn btn-gradient flex-grow-1 mr-2"><i class="fa-solid fa-check mr-1.5"></i> Save Chapter Module</button>
                  <a href="tcourse.php" class="btn btn-outline-light" style="border-radius:10px;">Back to Courses</a>
                </div>
              </form>
            </div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Faculty Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }

      if (pathname === 'tcontent.php') {
        const pageTitle = 'Course Materials';
        const myContent = db.content.filter(cn => cn.tid === teacher.tid);
        let rows = myContent.map(cn => {
          const cr = db.course.find(c => c.coid === cn.coid) || { cname: 'Course #' + cn.coid };
          return `
          <tr>
            <td class="text-muted font-weight-bold">#${cn.ctid}</td>
            <td class="font-weight-bold text-white">${cr.cname}</td>
            <td><span class="badge badge-pill badge-info px-2.5 py-1">Chapter ${cn.chapterno}</span></td>
            <td class="text-white">${cn.chaptername}</td>
            <td><span class="badge badge-secondary"><i class="fa-solid fa-file-pdf mr-1"></i> PDF Guide</span></td>
            <td><a href="${cn.chaptervideo}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa-solid fa-play mr-1"></i> Watch Video</a></td>
          </tr>`;
        }).join('');

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'z.jpg')}</head>
        <body>
          ${getTeacherNav(pathname, pageTitle)}
          <main class="container my-auto py-5">
            <div class="glass-card p-4">
              <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
                <div>
                  <h3 class="font-weight-bold text-white mb-1"><i class="fa-solid fa-file-pdf text-info mr-2"></i> Uploaded Course Materials</h3>
                  <p class="text-muted small mb-0">All syllabus guides, notes, and video lectures uploaded under your courses</p>
                </div>
                <a href="tcourse.php" class="btn btn-gradient btn-sm"><i class="fa-solid fa-plus mr-1"></i> Add to a Course</a>
              </div>
              <div class="table-responsive">
                <table class="table-glass">
                  <thead><tr><th>ID</th><th>Course</th><th>Chapter #</th><th>Chapter Title</th><th>Reading Notes</th><th>Video Lesson</th></tr></thead>
                  <tbody>${rows || '<tr><td colspan="6" class="text-center text-muted">No course materials uploaded yet. Go to "Courses" and click "Add Content".</td></tr>'}</tbody>
                </table>
              </div>
            </div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Faculty Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }

      if (pathname === 'tapply.php') {
        const pageTitle = 'Enrolled Students';
        const myApps = db.apply.filter(a => a.tid === teacher.tid);
        let rows = myApps.map(a => {
          const st = db.student.find(s => s.sid === a.sid) || {};
          const cr = db.course.find(c => c.coid === a.coid) || {};
          return `
          <tr>
            <td class="text-muted font-weight-bold">#${a.aid}</td>
            <td class="font-weight-bold text-white">
              <div class="d-flex align-items-center">
                <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg, #10b981, #06b6d4); display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:0.8rem; margin-right:8px;">
                  ${(st.sname || 'S').charAt(0).toUpperCase()}
                </div>
                <span>${st.sname || 'Student #' + a.sid}</span>
              </div>
            </td>
            <td><code class="text-info">${st.semail || '-'}</code></td>
            <td class="text-info font-weight-bold">${cr.cname || 'Course #' + a.coid}</td>
            <td class="text-muted small">${a.startdate} &rarr; ${a.enddate}</td>
            <td><span class="badge badge-success px-2.5 py-1">₹${a.amount}</span></td>
            <td class="text-muted small">${a.adate}</td>
          </tr>`;
        }).join('');

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'x.jpg')}</head>
        <body>
          ${getTeacherNav(pathname, pageTitle)}
          <main class="container my-auto py-5">
            <div class="glass-card p-4">
              <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
                <div>
                  <h3 class="font-weight-bold text-white mb-1"><i class="fa-solid fa-user-graduate text-info mr-2"></i> Students Enrolled in Your Batches</h3>
                  <p class="text-muted small mb-0">Track student progress, registration timelines, and fee settlements</p>
                </div>
                <span class="badge badge-pill badge-success px-3 py-1.5">${myApps.length} Learners Enrolled</span>
              </div>
              <div class="table-responsive">
                <table class="table-glass">
                  <thead><tr><th>AID</th><th>Student Name</th><th>Student Email</th><th>Course</th><th>Batch Timeline</th><th>Paid Fee</th><th>Enrollment Date</th></tr></thead>
                  <tbody>${rows || '<tr><td colspan="7" class="text-center text-muted">No students have enrolled in your courses yet.</td></tr>'}</tbody>
                </table>
              </div>
            </div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Faculty Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }

      if (pathname === 'tchat.php') {
        const pageTitle = 'Student Chat Desk';
        if (post.s && post.m && post.m.trim()) {
          const now = new Date();
          const atime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
          const adate = now.toISOString().slice(0, 10);
          db.chat.push({
            chid: db.chat.length + 1,
            senderid: teacher.tid,
            senderRole: 'teacher',
            senderName: teacher.tname,
            receiverid: 1,
            message: post.m.trim(),
            adate,
            atime
          });
        }
        let chatRows = db.chat.map(ch => {
          const isMe = (ch.senderRole === 'teacher' && ch.senderid === teacher.tid);
          let displayName = ch.senderName;
          if (!displayName) {
            if (ch.senderRole === 'teacher') {
              const tr = db.teacher.find(t => t.tid === ch.senderid);
              displayName = tr ? tr.tname : 'Teacher';
            } else {
              const st = db.student.find(s => s.sid === ch.senderid);
              displayName = st ? st.sname : 'Student';
            }
          }
          const timeStr = ch.atime || '10:00 AM';
          const dateStr = ch.adate || new Date().toISOString().slice(0, 10);

          return `
          <div class="${isMe ? 'chat-msg-self' : 'chat-msg-other'}">
            <div class="small font-weight-bold ${isMe ? 'text-white' : 'text-info'} mb-1 d-flex align-items-center justify-content-between">
              <span>
                <i class="fa-solid ${isMe ? 'fa-user-tie' : 'fa-user-graduate'} mr-1.5"></i>
                ${isMe ? `You (${teacher.tname})` : displayName}
              </span>
              <span class="small font-weight-normal opacity-75 ml-2" style="font-size:0.75rem;">
                ${isMe ? '<span class="badge badge-light text-primary px-1.5 py-0.5">Faculty</span>' : '<span class="badge badge-info px-1.5 py-0.5">Student</span>'}
              </span>
            </div>
            <div style="line-height:1.5; font-size:0.95rem; word-break:break-word;">${ch.message}</div>
            <div class="${isMe ? 'text-right' : 'text-left'} small text-muted mt-2 pt-1 border-top" style="font-size:0.72rem; border-color:rgba(255,255,255,0.15) !important; color:${isMe ? 'rgba(255,255,255,0.75)' : '#94a3b8'} !important;">
              <i class="fa-regular fa-clock mr-1"></i>${timeStr} &bull; <i class="fa-regular fa-calendar-alt ml-1 mr-1"></i>${dateStr}
            </div>
          </div>
          `;
        }).join('');

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'f.jpg')}</head>
        <body>
          ${getTeacherNav(pathname, pageTitle)}
          <main class="container my-auto py-5">
            <div class="glass-card p-4 mx-auto" style="max-width: 820px; box-shadow: 0 20px 50px rgba(0,0,0,0.6), 0 0 30px rgba(6,182,212,0.25);">
              <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary">
                <div class="d-flex align-items-center">
                  <div style="width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg, #06b6d4, #3b82f6); display:flex; align-items:center; justify-content:center; color:white; font-size:1.3rem; margin-right:14px; box-shadow:0 6px 18px rgba(6,182,212,0.4);">
                    <i class="fa-solid fa-comments"></i>
                  </div>
                  <div>
                    <h4 class="font-weight-bold text-white mb-0">Student Consultation Desk</h4>
                    <p class="text-muted small mb-0">Logged in as <strong class="text-info">${teacher.tname}</strong> &bull; Interactive 2-way messaging</p>
                  </div>
                </div>
                <span class="badge badge-pill badge-info px-3 py-1.5" style="box-shadow:0 0 12px rgba(6,182,212,0.5);"><i class="fa-solid fa-circle text-success mr-1" style="font-size:0.6rem;"></i> Live Connected</span>
              </div>
              <div class="p-3.5 rounded mb-3" style="background: rgba(15,23,42,0.75); border:1px solid rgba(255,255,255,0.1); height:400px; overflow-y:auto; display:flex; flex-direction:column; box-shadow: inset 0 2px 10px rgba(0,0,0,0.5);">
                ${chatRows || '<div class="text-center text-muted my-auto">No chat conversations yet. Type a message below to start!</div>'}
              </div>
              <form action="tchat.php" method="POST">
                <div class="input-group">
                  <input type="text" name="m" class="form-control-custom mr-2" style="background: rgba(15,23,42,0.85) !important; border:1px solid rgba(255,255,255,0.16) !important; border-radius:12px; height:48px;" placeholder="Type your reply to student..." required autocomplete="off">
                  <div class="input-group-append">
                    <button type="submit" name="s" value="Send" class="btn btn-gradient px-4" style="border-radius:12px; height:48px;"><i class="fa-solid fa-paper-plane mr-1.5"></i> Send</button>
                  </div>
                </div>
              </form>
            </div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Faculty Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }
    }

    // 10. STUDENT PORTAL (sstudent, scourse, scontent, scapply, sdapply, schat)
    if (pathname.startsWith('s')) {
      if (!session.ss) { res.writeHead(302, { Location: 'login.php' }); return res.end(); }
      const student = db.student.find(s => s.semail === session.ss) || db.student[0];
      session.sid = student.sid;

      if (pathname === 'sstudent.php') {
        const pageTitle = 'Student Profile';
        const myApps = db.apply.filter(a => a.sid === student.sid);

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'y.jpg')}</head>
        <body>
          ${getStudentNav(pathname, pageTitle)}
          <main class="container my-auto py-5">
            <div class="row">
              <div class="col-lg-4 mb-4">
                <div class="glass-card text-center p-4 h-100">
                  <div style="width:90px; height:90px; border-radius:50%; background:linear-gradient(135deg, #10b981, #06b6d4); display:flex; align-items:center; justify-content:center; color:white; font-size:2.2rem; font-weight:bold; margin:0 auto 16px; box-shadow:0 8px 24px rgba(16,185,129,0.4);">
                    ${(student.sname || 'S').charAt(0).toUpperCase()}
                  </div>
                  <h3 class="font-weight-bold text-white mb-1">${student.sname}</h3>
                  <span class="badge badge-pill badge-success px-3 py-1 mb-3">Verified Student &bull; SID #${student.sid}</span>
                  <div class="pt-3 border-top border-secondary text-left">
                    <div class="mb-2.5 small"><i class="fa-solid fa-envelope text-info mr-2"></i><code class="text-white">${student.semail}</code></div>
                    <div class="mb-2.5 small"><i class="fa-solid fa-phone text-info mr-2"></i><span class="text-white">${student.sphone}</span></div>
                    <div class="small"><i class="fa-solid fa-shield-check text-success mr-2"></i><span class="text-success font-weight-bold">Learner Portal Active</span></div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="glass-card p-4 mb-4">
                  <h4 class="font-weight-bold text-white mb-3"><i class="fa-solid fa-graduation-cap text-success mr-2"></i> Learning Dashboard</h4>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <div class="stat-card" style="border-left: 4px solid #10b981;">
                        <span class="text-muted small text-uppercase font-weight-bold">Active Enrollments</span>
                        <div class="d-flex align-items-baseline justify-content-between mt-2">
                          <h2 class="text-white font-weight-bold mb-0">${myApps.length}</h2>
                          <a href="sdapply.php" class="small text-success font-weight-bold">View My Courses &rarr;</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <div class="stat-card" style="border-left: 4px solid #06b6d4;">
                        <span class="text-muted small text-uppercase font-weight-bold">Course Catalog</span>
                        <div class="d-flex align-items-baseline justify-content-between mt-2">
                          <h2 class="text-white font-weight-bold mb-0">${db.course.length}</h2>
                          <a href="scourse.php" class="small text-info font-weight-bold">Browse Courses &rarr;</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="glass-card p-4">
                  <h4 class="font-weight-bold text-white mb-3"><i class="fa-solid fa-compass text-info mr-2"></i> Quick Actions</h4>
                  <div class="d-flex flex-wrap gap-2">
                    <a href="scourse.php" class="btn btn-gradient mr-2 mb-2"><i class="fa-solid fa-magnifying-glass mr-1.5"></i> Find New Courses</a>
                    <a href="scontent.php" class="btn btn-outline-info font-weight-bold mr-2 mb-2" style="border-radius:10px;"><i class="fa-solid fa-file-pdf mr-1.5"></i> Access Study Materials</a>
                    <a href="schat.php" class="btn btn-outline-light font-weight-bold mb-2" style="border-radius:10px;"><i class="fa-solid fa-comments mr-1.5"></i> Chat with Instructors</a>
                  </div>
                </div>
              </div>
            </div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Student Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }

      if (pathname === 'scourse.php') {
        const pageTitle = 'Available Courses';
        let cards = db.course.map(c => {
          const teacher = db.teacher.find(t => t.tid === c.tid) || { tname: 'Instructor #' + c.tid };
          return `
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="glass-card h-100 p-4 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <span class="badge badge-pill badge-info px-2.5 py-1" style="font-size:0.75rem;"><i class="fa-solid fa-chalkboard-user mr-1"></i> ${teacher.tname}</span>
                  <span class="badge badge-success px-2.5 py-1 font-weight-bold">₹${c.crate}</span>
                </div>
                <h4 class="font-weight-bold text-white mb-2 mt-2">${c.cname}</h4>
                <p class="text-muted small mb-4">${c.cdetail}</p>
              </div>
              <div class="pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                <span class="text-muted small"><i class="fa-solid fa-calendar-day mr-1"></i> ${c.cdate}</span>
                <a href="scapply.php?coid=${c.coid}&tid=${c.tid}&amt=${c.crate}" class="btn btn-sm btn-gradient">
                  Enroll Now &rarr;
                </a>
              </div>
            </div>
          </div>
          `;
        }).join('');

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'c.jpg')}</head>
        <body>
          ${getStudentNav(pathname, pageTitle)}
          <main class="container my-auto py-5">
            <div class="glass-card mb-4 p-4 text-center">
              <h2 class="font-weight-bold text-white mb-2">Explore Available Courses</h2>
              <p class="text-muted mb-0">Choose from industry-vetted courses taught by distinguished faculty</p>
            </div>
            <div class="row">${cards}</div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Student Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }

      if (pathname === 'scapply.php') {
        const coid = parseInt(parsedUrl.query.coid) || 1;
        const tid = parseInt(parsedUrl.query.tid) || 1;
        const amt = parseInt(parsedUrl.query.amt) || 5000;
        const pageTitle = 'Course Enrollment';
        const targetCourse = db.course.find(c => c.coid === coid) || { cname: 'Course #' + coid };
        const targetTeacher = db.teacher.find(t => t.tid === tid) || { tname: 'Faculty Instructor' };

        if (post.s) {
          const sdate = post.d || '2026-11-01';
          const edate = post.ed || '2026-12-01';
          db.apply.push({ aid: db.apply.length + 1, tid, sid: student.sid, coid, startdate: sdate, enddate: edate, amount: amt, adate: new Date().toISOString().slice(0, 10) });
          res.writeHead(302, { Location: 'sdapply.php' });
          return res.end();
        }

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'x.jpg')}</head>
        <body>
          ${getStudentNav(pathname, pageTitle)}
          <main class="container my-auto py-5 d-flex align-items-center justify-content-center">
            <div class="glass-card p-4" style="max-width: 600px; width:100%;">
              <div class="text-center mb-4">
                <div style="width:70px; height:70px; border-radius:20px; background:linear-gradient(135deg, rgba(16,185,129,0.2), rgba(6,182,212,0.2)); border:1px solid rgba(16,185,129,0.35); display:flex; align-items:center; justify-content:center; color:#34d399; font-size:1.8rem; margin:0 auto 15px;">
                  <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h3 class="font-weight-bold text-white mb-1">Confirm Course Enrollment</h3>
                <p class="text-muted small">Course: <strong class="text-info">${targetCourse.cname}</strong> &bull; Mentor: <strong class="text-white">${targetTeacher.tname}</strong></p>
              </div>
              <form action="scapply.php?coid=${coid}&tid=${tid}&amt=${amt}" method="POST">
                <div class="form-group mb-3">
                  <label class="text-muted small font-weight-bold text-uppercase">Preferred Start Date</label>
                  <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-calendar-day"></i></div><input type="date" name="d" class="form-control-custom" value="2026-11-01" required></div>
                </div>
                <div class="form-group mb-3">
                  <label class="text-muted small font-weight-bold text-uppercase">Target Completion Date</label>
                  <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-calendar-check"></i></div><input type="date" name="ed" class="form-control-custom" value="2026-12-01" required></div>
                </div>
                <div class="form-group mb-4">
                  <label class="text-muted small font-weight-bold text-uppercase">Enrollment Fee</label>
                  <div class="input-group-custom"><div class="input-icon"><i class="fa-solid fa-indian-rupee-sign"></i></div><input type="text" name="a" value="₹${amt}" class="form-control-custom font-weight-bold text-success" readonly></div>
                </div>
                <div class="d-flex gap-2">
                  <button type="submit" name="s" value="Confirm Enrollment" class="btn btn-gradient flex-grow-1 mr-2"><i class="fa-solid fa-circle-check mr-1.5"></i> Confirm Enrollment</button>
                  <a href="scourse.php" class="btn btn-outline-light" style="border-radius:10px;">Cancel</a>
                </div>
              </form>
            </div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Student Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }

      if (pathname === 'sdapply.php') {
        const pageTitle = 'My Enrollments';
        const myApps = db.apply.filter(a => a.sid === student.sid);
        let rows = myApps.map(a => {
          const cr = db.course.find(c => c.coid === a.coid) || {};
          const tr = db.teacher.find(t => t.tid === a.tid) || {};
          return `
          <tr>
            <td class="text-muted font-weight-bold">#${a.aid}</td>
            <td class="font-weight-bold text-white"><i class="fa-solid fa-book-open text-info mr-2"></i>${cr.cname || 'Course #' + a.coid}</td>
            <td><span class="badge badge-pill badge-secondary px-2.5 py-1">${tr.tname || 'Teacher #' + a.tid}</span></td>
            <td class="text-muted small">${a.startdate} &rarr; ${a.enddate}</td>
            <td><span class="badge badge-success px-2.5 py-1">₹${a.amount}</span></td>
            <td><span class="badge badge-pill badge-success"><i class="fa-solid fa-circle-check mr-1"></i> Active</span></td>
            <td class="text-right">
              <a href="scontent.php" class="btn btn-sm btn-outline-info font-weight-bold"><i class="fa-solid fa-folder-open mr-1"></i> Study Notes</a>
            </td>
          </tr>`;
        }).join('');

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'y.jpg')}</head>
        <body>
          ${getStudentNav(pathname, pageTitle)}
          <main class="container my-auto py-5">
            <div class="glass-card p-4">
              <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
                <div>
                  <h3 class="font-weight-bold text-white mb-1"><i class="fa-solid fa-clipboard-check text-success mr-2"></i> Your Course Enrollments</h3>
                  <p class="text-muted small mb-0">Access learning modules, schedule timelines, and certificates</p>
                </div>
                <a href="scourse.php" class="btn btn-gradient btn-sm"><i class="fa-solid fa-plus mr-1"></i> Enroll in New Course</a>
              </div>
              <div class="table-responsive">
                <table class="table-glass">
                  <thead><tr><th>AID</th><th>Course</th><th>Instructor</th><th>Duration</th><th>Fee Paid</th><th>Status</th><th class="text-right">Action</th></tr></thead>
                  <tbody>${rows || '<tr><td colspan="7" class="text-center text-muted">You have not enrolled in any courses yet. <a href="scourse.php" class="text-info font-weight-bold">Browse Courses Now</a></td></tr>'}</tbody>
                </table>
              </div>
            </div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Student Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }

      if (pathname === 'scontent.php') {
        const pageTitle = 'Study Materials';
        let rows = db.content.map(cn => {
          const cr = db.course.find(c => c.coid === cn.coid) || { cname: 'Course #' + cn.coid };
          return `
          <tr>
            <td class="text-muted font-weight-bold">#${cn.ctid}</td>
            <td class="font-weight-bold text-white">${cr.cname}</td>
            <td><span class="badge badge-pill badge-info px-2.5 py-1">Chapter ${cn.chapterno}</span></td>
            <td class="text-white">${cn.chaptername}</td>
            <td><span class="badge badge-secondary"><i class="fa-solid fa-file-pdf mr-1"></i> PDF Ready</span></td>
            <td class="text-right">
              <a href="${cn.chaptervideo}" target="_blank" class="btn btn-sm btn-gradient"><i class="fa-solid fa-play mr-1"></i> Watch Video</a>
            </td>
          </tr>`;
        }).join('');

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'f.jpg')}</head>
        <body>
          ${getStudentNav(pathname, pageTitle)}
          <main class="container my-auto py-5">
            <div class="glass-card p-4">
              <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
                <div>
                  <h3 class="font-weight-bold text-white mb-1"><i class="fa-solid fa-file-lines text-info mr-2"></i> Course Chapters & Video Tutorials</h3>
                  <p class="text-muted small mb-0">Download notes, review chapter slides, and stream recorded faculty lectures</p>
                </div>
                <span class="badge badge-pill badge-info px-3 py-1.5">${db.content.length} Modules</span>
              </div>
              <div class="table-responsive">
                <table class="table-glass">
                  <thead><tr><th>ID</th><th>Course</th><th>Chapter #</th><th>Chapter Title</th><th>Reading Notes</th><th class="text-right">Lecture Video</th></tr></thead>
                  <tbody>${rows || '<tr><td colspan="6" class="text-center text-muted">No course materials available at this time.</td></tr>'}</tbody>
                </table>
              </div>
            </div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Student Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }

      if (pathname === 'schat.php') {
        const pageTitle = 'Instructor Chat';
        if (post.s && post.m && post.m.trim()) {
          const now = new Date();
          const atime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
          const adate = now.toISOString().slice(0, 10);
          db.chat.push({
            chid: db.chat.length + 1,
            senderid: student.sid,
            senderRole: 'student',
            senderName: student.sname,
            receiverid: 1,
            message: post.m.trim(),
            adate,
            atime
          });
        }
        let chatRows = db.chat.map(ch => {
          const isMe = (ch.senderRole === 'student' && ch.senderid === student.sid);
          let displayName = ch.senderName;
          if (!displayName) {
            if (ch.senderRole === 'teacher') {
              const tr = db.teacher.find(t => t.tid === ch.senderid);
              displayName = tr ? tr.tname : 'Teacher';
            } else {
              const st = db.student.find(s => s.sid === ch.senderid);
              displayName = st ? st.sname : 'Student';
            }
          }
          const timeStr = ch.atime || '10:00 AM';
          const dateStr = ch.adate || new Date().toISOString().slice(0, 10);

          return `
          <div class="${isMe ? 'chat-msg-self' : 'chat-msg-other'}">
            <div class="small font-weight-bold ${isMe ? 'text-white' : 'text-info'} mb-1 d-flex align-items-center justify-content-between">
              <span>
                <i class="fa-solid ${isMe ? 'fa-user-graduate' : 'fa-chalkboard-user'} mr-1.5"></i>
                ${isMe ? `You (${student.sname})` : displayName}
              </span>
              <span class="small font-weight-normal opacity-75 ml-2" style="font-size:0.75rem;">
                ${isMe ? '<span class="badge badge-light text-primary px-1.5 py-0.5">Student</span>' : '<span class="badge badge-info px-1.5 py-0.5">Faculty</span>'}
              </span>
            </div>
            <div style="line-height:1.5; font-size:0.95rem; word-break:break-word;">${ch.message}</div>
            <div class="${isMe ? 'text-right' : 'text-left'} small text-muted mt-2 pt-1 border-top" style="font-size:0.72rem; border-color:rgba(255,255,255,0.15) !important; color:${isMe ? 'rgba(255,255,255,0.75)' : '#94a3b8'} !important;">
              <i class="fa-regular fa-clock mr-1"></i>${timeStr} &bull; <i class="fa-regular fa-calendar-alt ml-1 mr-1"></i>${dateStr}
            </div>
          </div>
          `;
        }).join('');

        const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>${getCommonHead('Explore Learning - ' + pageTitle, 'f.jpg')}</head>
        <body>
          ${getStudentNav(pathname, pageTitle)}
          <main class="container my-auto py-5">
            <div class="glass-card p-4 mx-auto" style="max-width: 820px; box-shadow: 0 20px 50px rgba(0,0,0,0.6), 0 0 30px rgba(6,182,212,0.25);">
              <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary">
                <div class="d-flex align-items-center">
                  <div style="width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg, #06b6d4, #3b82f6); display:flex; align-items:center; justify-content:center; color:white; font-size:1.3rem; margin-right:14px; box-shadow:0 6px 18px rgba(6,182,212,0.4);">
                    <i class="fa-solid fa-comments"></i>
                  </div>
                  <div>
                    <h4 class="font-weight-bold text-white mb-0">Message Your Instructors</h4>
                    <p class="text-muted small mb-0">Logged in as <strong class="text-info">${student.sname}</strong> &bull; Ask doubts & get direct answers</p>
                  </div>
                </div>
                <span class="badge badge-pill badge-success px-3 py-1.5" style="box-shadow:0 0 12px rgba(16,185,129,0.5);"><i class="fa-solid fa-circle mr-1" style="font-size:0.6rem;"></i> Connected</span>
              </div>
              <div class="p-3.5 rounded mb-3" style="background: rgba(15,23,42,0.75); border:1px solid rgba(255,255,255,0.1); height:400px; overflow-y:auto; display:flex; flex-direction:column; box-shadow: inset 0 2px 10px rgba(0,0,0,0.5);">
                ${chatRows || '<div class="text-center text-muted my-auto">No chat conversations yet. Type your question below!</div>'}
              </div>
              <form action="schat.php" method="POST">
                <div class="input-group">
                  <input type="text" name="m" class="form-control-custom mr-2" style="background: rgba(15,23,42,0.85) !important; border:1px solid rgba(255,255,255,0.16) !important; border-radius:12px; height:48px;" placeholder="Type your doubt or question..." required autocomplete="off">
                  <div class="input-group-append">
                    <button type="submit" name="s" value="Send" class="btn btn-gradient px-4" style="border-radius:12px; height:48px;"><i class="fa-solid fa-paper-plane mr-1.5"></i> Send</button>
                  </div>
                </div>
              </form>
            </div>
          </main>
          <footer class="site-footer text-center">
            <div class="container">
              <span class="text-muted">&copy; 2026 Explore Learning System &bull; Student Portal</span>
            </div>
          </footer>
        </body>
        </html>
        `;
        res.writeHead(200, { 'Content-Type': 'text/html' });
        return res.end(html);
      }
    }

    // Default 404
    res.writeHead(404, { 'Content-Type': 'text/plain' });
    res.end('404 Page Not Found: ' + pathname);
  });
};
