<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .footer {
            padding: 40px 80px;
            background-color: #00855D;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
            color: white;
        }

        .footer-section {
            flex: 1;
            min-width: 200px;
        }

        .footer-section h2 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #f0f0f0;
        }

        .footer-section p {
            color: #e0e0e0;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }

        .contact-info {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .contact-info li {
            margin-bottom: 8px;
            color: #e0e0e0;
            font-size: 14px;
        }

        .credit-cards {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .credit-card-icon {
            width: 50px;
            height: 30px;
            background-color: white;
            border-radius: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 10px;
            font-weight: bold;
            color: #00855D;
        }

        .copyright {
            width: 100%;
            text-align: center;
            color: #cccccc;
            font-size: 12px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .footer {
                padding: 30px 20px;
            }
            
            .footer-section {
                flex: 100%;
            }
        }
    </style>
</head>
<body>
    <footer class="footer">
        <div class="footer-section">
            <h2>eParking Mall</h2>
            <p>eParking Mall memudahkan pencarian, pemesanan, dan pembayaran tempat parkir secara online.</p>
        </div>

        <div class="footer-section">
            <h2 onclick="window.location.href='aboutus.php'">Tentang Kami</h2>
            <p onclick="window.location.href='faqs.php'">FAQs</p>
        </div>

        <div class="footer-section">
            <h2>Hubungi Kami</h2>
            <ul class="contact-info">
                <li>eParkingMall@gmail.com</li>
                <li>0812 1119 9999</li>
            </ul>
        </div>

        <div class="footer-section">
            <h2>Kami bekerja sama dengan bank:</h2>
            <div class="credit-cards">
                <div class="credit-card-icon">MANDIRI</div>
                <div class="credit-card-icon">BRI</div>
                <div class="credit-card-icon">BCA</div>
                <div class="credit-card-icon">BNI</div>
            </div>
        </div>

        <div class="copyright">
            © eParking Mall
        </div>
    </footer>
</body>
</html>