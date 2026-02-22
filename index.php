<?php
session_start();
include "db.php";

// 1. SESSION & LOGOUT HANDLING
if (isset($_SESSION['email'])) {
    session_unset();
    session_destroy();
    header("Location: login.php?logged_out=1");
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CrimsonGate | Experience the Sound</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* CSS VARIABLES - Fixes the undefined colors */
        :root {
            --crimson: #ff2e2e;
            --card: rgba(255, 255, 255, 0.05);
            --border: rgba(255, 255, 255, 0.1);
            --text-dim: #bbb;
            --bg-dark: rgba(0, 0, 0, 0.85);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            background-image: linear-gradient(rgba(7, 5, 5, 0.6), rgba(0, 0, 0, 0.6)), url("style/back.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            line-height: 1.6;
            color: white;
            min-height: 100vh;
        }

        /* HEADER */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 10%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(15px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border);
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--crimson);
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        nav a {
            color: #bbb;
            text-decoration: none;
            margin-left: 35px;
            font-size: 0.9rem;
            text-transform: uppercase;
            transition: 0.4s;
        }

        nav a:hover {
            color: #fff;
        }

        /* HERO SECTION */
        .hero {
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 10%;
        }

        .hero h1 {
            font-size: clamp(3.5rem, 8vw, 5rem);
            font-weight: 900;
            margin-bottom: 20px;
            background: linear-gradient(to bottom, #fff 30%, var(--crimson) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* FEATURES SECTION */
        .features {
            padding: 100px 10%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: var(--card);
            padding: 40px;
            border-radius: 24px;
            border: 1px solid var(--border);
            transition: 0.4s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: var(--crimson);
        }

        .feature-card i {
            font-size: 2.5rem;
            color: var(--crimson);
            margin-bottom: 20px;
        }

        /* CONTACT SECTION / FOOTER */
        footer {
            padding: 100px 10%;
            background: var(--bg-dark);
            backdrop-filter: blur(10px);
            border-top: 1px solid var(--border);
        }

        .contact-container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 60px;
            text-align: left;
        }

        .contact-info h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .contact-info h1 span {
            color: var(--crimson);
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-item i {
            color: var(--crimson);
            font-size: 1.5rem;
            width: 30px;
        }

        /* FORM STYLING */
        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .input-group label {
            font-size: 0.9rem;
            color: #fff;
            font-weight: 500;
        }

        .input-group input,
        .input-group textarea {
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            transition: 0.3s;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            outline: none;
            border-color: var(--crimson);
            background: rgba(255, 255, 255, 0.1);
        }

        .submit-btn {
            padding: 18px;
            background: var(--crimson);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(255, 46, 46, 0.2);
        }

        .submit-btn:hover {
            background: #d61e1e;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255, 46, 46, 0.4);
        }

        @media (max-width: 768px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">CrimsonGate</div>
        <nav>
            <a href="#features">Features</a>
        </nav>
    </header>

    <section class="hero">
        <h1>Own the Moment.</h1>
        <p>The premier gateway for live music in the Philippines.</p>
        <br>
        <a href="login.php" class="cta-btn"
            style="text-decoration:none; color:white; background:var(--crimson); padding: 15px 30px; border-radius: 10px; font-weight:bold;">Get
            Started Now</a>
    </section>

    <section class="features" id="features">
        <div class="feature-card">
            <i class="fas fa-map-marked-alt"></i>
            <h3>Live Mapping</h3>
            <p>Pick your exact spot with our high-fidelity interactive arena charts.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-shield-alt"></i>
            <h3>Ironclad Security</h3>
            <p>Industry-leading encryption protecting every transaction.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-bolt"></i>
            <h3>Instant Entry</h3>
            <p>Receive your QR-coded dynamic tickets instantly.</p>
        </div>
    </section>

    <footer>
        <div class="contact-container">
            <div class="contact-info">
                <h1>Get in <span>Touch</span></h1>
                <p>Have issues with your booking? Our team is here to help.</p>

                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong style="display:block">Email Us</strong>
                        <span style="color:var(--text-dim)">support@crimsongate.ph</span>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong style="display:block">Our Office</strong>
                        <span style="color:var(--text-dim)">Minglanilla, Cebu, PH</span>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <strong style="display:block">Call Us</strong>
                        <span style="color:var(--text-dim)">+63 912 345 6789</span>
                    </div>
                </div>
            </div>

            <form class="contact-form" action="process_contact.php" method="POST">
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" placeholder="John Doe" required>
                </div>
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="john@email.com" required>
                </div>
                <div class="input-group">
                    <label>Message</label>
                    <textarea name="message" rows="5" placeholder="How can we help you?" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </footer>

</body>

</html>