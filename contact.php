<!DOCTYPE html>
<head>
    <title>Contact Us | CrimsonGate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg: #050505;
            --card: #111;
            --crimson: #b30000;
            --border: rgba(255, 255, 255, 0.1);
            --text-dim: #888;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: var(--bg);
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .contact-container {
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 40px;
            background: var(--card);
            padding: 40px;
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        .contact-info h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .contact-info h1 span {
            color: var(--crimson);
        }

        .contact-info p {
            color: var(--text-dim);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-item i {
            font-size: 1.2rem;
            color: var(--crimson);
            width: 40px;
            height: 40px;
            background: rgba(179, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .info-item div span {
            display: block;
            font-size: 0.8rem;
            color: var(--text-dim);
        }

        .info-item div p {
            margin: 0;
            color: white;
            font-weight: 600;
        }

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
            font-size: 0.85rem;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-group input,
        .input-group textarea {
            background: #181818;
            border: 1px solid var(--border);
            padding: 12px 15px;
            border-radius: 8px;
            color: white;
            outline: none;
            transition: 0.3s;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            border-color: var(--crimson);
            background: #202020;
        }

        .submit-btn {
            background: var(--crimson);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-weight: 800;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: #ff0000;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(179, 0, 0, 0.3);
        }

        .back-nav {
            margin-top: 30px;
            text-decoration: none;
            color: var(--text-dim);
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .back-nav:hover {
            color: white;
        }

        @media (max-width: 850px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="contact-container">
        <div class="contact-info">
            <h1>Get in <span>Touch</span></h1>
            <p>Have issues with your booking? Our team is here to help you get your tickets sorted.</p>

            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <span>Email Us</span>
                    <p>support@crimsongate.ph</p>
                </div>
            </div>

            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <span>Our Office</span>
                    <p>Minglanilla, Cebu, PH</p>
                </div>
            </div>

            <div class="info-item">
                <i class="fas fa-phone"></i>
                <div>
                    <span>Call Us</span>
                    <p>+63 912 345 6789</p>
                </div>
            </div>
        </div>

        <form class="contact-form" action="#">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" placeholder="Jhon Doe" required>
            </div>
            <div class="input-group">
                <label>Email Address</label>
                <input type="email" placeholder="jhon@email.com" required>
            </div>
            <div class="input-group">
                <label>Message</label>
                <textarea rows="5" placeholder="How can we help you?" required></textarea>
            </div>
            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>

    <a href="index.php" class="back-nav"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>

</body>

</html>