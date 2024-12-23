<?php
include_once 'header.php';
echo getHeaderHtml();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - eParkingMall</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .faq-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }

        .faq-header {
            text-align: center;
            padding: 2rem 0;
            background: #00855D;
            color: white;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .faq-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .faq-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .faq-item {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .faq-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .faq-question {
            font-size: 1.1rem;
            font-weight: 600;
            color: #00855D;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-question::after {
            content: '+';
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-question::after {
            transform: rotate(45deg);
        }

        .faq-answer {
            color: #666;
            line-height: 1.6;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 200px;
            margin-top: 1rem;
        }

        .search-box {
            margin: 2rem auto;
            max-width: 600px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 1rem 1.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 30px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #00966B;
        }

        @media (max-width: 768px) {
            .faq-header h1 {
                font-size: 2rem;
            }
            
            .faq-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="faq-container">
        <div class="faq-header">
            <h1>Pertanyaan yang Sering Diajukan</h1>
            <p>Temukan jawaban untuk pertanyaan umum tentang layanan kami</p>
        </div>

        <div class="search-box">
            <input type="text" placeholder="Cari pertanyaan..." id="faqSearch">
        </div>

        <div class="faq-grid">
            <div class="faq-item">
                <div class="faq-question">Bagaimana cara booking tempat parkir?</div>
                <div class="faq-answer">Cukup cari lokasi yang diinginkan, pilih tempat parkir yang Anda suka, dan selesaikan pemesanan dengan sistem pembayaran yang aman.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Apakah saya bisa mengubah booking?</div>
                <div class="faq-answer">Ya, Anda dapat mengubah atau membatalkan pemesanan melalui platform kami sesuai dengan kebijakan pembatalan yang berlaku.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Metode pembayaran apa yang diterima?</div>
                <div class="faq-answer">Kami menerima berbagai metode pembayaran, termasuk kartu kredit, kartu debit, dan dompet digital untuk kenyamanan Anda.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Seberapa awal saya harus tiba?</div>
                <div class="faq-answer">Kami menyarankan untuk tiba 5-10 menit sebelum waktu pemesanan Anda untuk memastikan pengalaman parkir yang lancar.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Apakah tempat parkir saya dijamin?</div>
                <div class="faq-answer">Ya, setelah Anda melakukan pemesanan dan pembayaran, tempat parkir Anda dijamin tersedia sesuai waktu yang dipesan.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Bagaimana jika saya terlambat?</div>
                <div class="faq-answer">Hubungi layanan pelanggan kami segera jika Anda terlambat. Kami akan membantu mengatur ulang waktu parkir Anda sesuai ketersediaan.</div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.faq-item').forEach(item => {
            item.addEventListener('click', () => {
                item.classList.toggle('active');
            });
        });

        document.getElementById('faqSearch').addEventListener('keyup', function(e) {
            const searchText = e.target.value.toLowerCase();
            document.querySelectorAll('.faq-item').forEach(item => {
                const question = item.querySelector('.faq-question').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
                
                if (question.includes(searchText) || answer.includes(searchText)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>