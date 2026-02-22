<?php
session_start();
include 'db.php'; 


$selected_seats = isset($_POST['seats']) ? $_POST['seats'] : [];
$event_id = isset($_POST['event_id']) ? $_POST['event_id'] : 1; // Default to 1 for testing


$total_price = 0;
foreach ($selected_seats as $seat) {
    $row_letter = substr($seat, 0, 1);
    $total_price += in_array($row_letter, ['A', 'B', 'C', 'D', 'E']) ? 5000 : 2000;
}


if (empty($selected_seats)) {
    header("Location: seatno.php?error=noselection");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout | CrimsonGate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">

    <style>
        /* ... Keep your existing styles here ... */
        :root {
            --bg: #050505;
            --crimson: #ff2e2e;
            --maya: #00ffaa;
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.1);
        }

        body {
            background: var(--bg);
            color: white;
            font-family: 'Outfit', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .payment-container {
            background: var(--glass);
            padding: 30px;
            border-radius: 24px;
            border: 1px solid var(--border);
            width: 100%;
            max-width: 450px;
            backdrop-filter: blur(15px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            box-sizing: border-box;
        }

        .summary-box {
            background: rgba(255, 255, 255, 0.02);
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 25px;
            border-left: 4px solid var(--crimson);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.75rem;
            color: #888;
            text-transform: uppercase;
            font-weight: 700;
        }

        input {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #333;
            border-radius: 10px;
            color: white;
            margin-bottom: 15px;
            outline: none;
            box-sizing: border-box;
        }

        .methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 25px;
        }

        .method-btn {
            padding: 12px 5px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #333;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            font-size: 0.75rem;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            color: #888;
        }

        .method-btn i {
            font-size: 1.2rem;
        }

        .method-btn.active {
            border-color: var(--crimson);
            color: white;
            background: rgba(255, 46, 46, 0.1);
            box-shadow: 0 0 10px rgba(255, 46, 46, 0.2);
        }

        .method-btn.active.maya-style {
            border-color: var(--maya);
            color: var(--maya);
            background: rgba(0, 255, 170, 0.05);
        }

        .pay-now-btn {
            width: 100%;
            padding: 18px;
            background: var(--crimson);
            color: white;
            border: none;
            border-radius: 15px;
            font-weight: 900;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .pay-now-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 46, 46, 0.3);
        }

        .dimmed {
            opacity: 0.2;
            pointer-events: none;
            transform: scale(0.98);
        }
    </style>
</head>

<body>

    <div class="payment-container">
        <h2 style="margin:0 0 20px 0; font-weight: 900;">Checkout</h2>

        <div class="summary-box">
            <label>Selected Seats</label>
            <p style="margin:0; font-size: 1rem; color: #eee; font-weight: 700;">
                <?php echo implode(', ', $selected_seats); ?>
            </p>
            <p style="margin:10px 0 0 0; font-size: 1.8rem; font-weight: 900; color: var(--crimson);">
                ₱<?php echo number_format($total_price); ?>
            </p>
        </div>

        <form action="process_payment.php" method="POST" id="payment-form">
            <?php foreach ($selected_seats as $seat): ?>
                <input type="hidden" name="final_seats[]" value="<?php echo $seat; ?>">
            <?php endforeach; ?>
            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
            <input type="hidden" name="total_amount" value="<?php echo $total_price; ?>">
            <input type="hidden" name="method" id="method-input" value="card">

            <label>Select Method</label>
            <div class="methods">
                <div class="method-btn active" onclick="selectMethod('card', this)">
                    <i class="fas fa-credit-card"></i> Card
                </div>
                <div class="method-btn" onclick="selectMethod('gcash', this)">
                    <i class="fas fa-wallet"></i> GCash
                </div>
                <div class="method-btn" onclick="selectMethod('maya', this)">
                    <i class="fas fa-bolt"></i> Maya
                </div>
            </div>

            <div id="card-fields">
                <label>Card Details</label>
                <input type="text" name="card_name" placeholder="Cardholder Name">
                <input type="text" name="card_num" placeholder="1234 5678 9101 1121">
                <div style="display:flex; gap:10px;">
                    <input type="text" name="card_exp" placeholder="MM/YY">
                    <input type="password" name="card_cvv" placeholder="CVV">
                </div>
            </div>

            <button type="submit" class="pay-now-btn" id="submit-btn">Authorize Payment</button>
        </form>
    </div>

    <script>
        function selectMethod(method, element) {
            document.querySelectorAll('.method-btn').forEach(btn => btn.classList.remove('active', 'maya-style'));
            element.classList.add('active');
            if (method === 'maya') element.classList.add('maya-style');

            document.getElementById('method-input').value = method;
            const cardSection = document.getElementById('card-fields');
            const submitBtn = document.getElementById('submit-btn');

            if (method === 'card') {
                cardSection.classList.remove('dimmed');
                submitBtn.innerText = "Authorize Payment";
                submitBtn.style.background = "#ff2e2e";
                submitBtn.style.color = "white";
            } else {
                cardSection.classList.add('dimmed');
                submitBtn.innerText = "Pay with " + method.toUpperCase();
                submitBtn.style.background = (method === 'maya') ? '#00ffaa' : '#0055ff';
                submitBtn.style.color = (method === 'maya') ? 'black' : 'white';
            }
        }
    </script>

</body>

</html>