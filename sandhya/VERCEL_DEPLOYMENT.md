# Explore Learning System - Vercel Deployment Guide

This project is now configured for **zero-configuration 1-click deployment on Vercel**.

---

### What was prepared:
1. **`api/index.js`**: Standard serverless function running the complete modern frontend, navigation, interactive forms, course catalog, teacher/student portals, and database handlers.
2. **`vercel.json`**: Global serverless routing configuration forwarding requests seamlessly to the Vercel serverless engine.
3. **`package.json`**: Standard Node.js entry point compatible with Vercel's build pipeline.

---

### How to Deploy to Vercel:

#### Option 1: Via GitHub (Recommended)
1. Push this folder to your GitHub account (as a new repository).
2. Go to **[vercel.com](https://vercel.com)** and log in.
3. Click **"Add New..."** &rarr; **"Project"**.
4. Import your GitHub repository.
5. Leave all settings at default (**Framework Preset: Other**).
6. Click **Deploy**. Your site will be live instantly with a free `.vercel.app` URL!

#### Option 2: Via Vercel CLI
If you have Node.js and the Vercel CLI installed:
```bash
npm install -g vercel
vercel
```
Follow the prompts, and your app will be deployed in seconds.

---

### Default Demo Logins:
- **Admin**: Select *Admin* &rarr; Username: `admin` | Password: `123`
- **Teacher**: Select *Teacher* &rarr; Email: `komal@example.com` | Password: `pass`
- **Student**: Select *Student* &rarr; Email: `aman@example.com` | Password: `pass`
