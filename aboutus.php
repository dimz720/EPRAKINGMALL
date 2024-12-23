<?php
include_once 'header.php';
echo getHeaderHtml();
?>
<!DOCTYPE html>
<html>

<head>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
  <title>About Us</title>
  <style>
    .about-section {
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: justify;
      padding: 20px;
      margin-top: 60px;
    }

    .about__image {
      width: 100%;
      max-width: 500px;
      height: auto;
      margin: 0 auto 20px;
      display: block;
    }

    .about__title {
      font-size: 36px;
      text-align: center;
      margin-bottom: 20px;
    }

    .about__description {
      max-width: 800px;
      line-height: 1.6;
      text-align: justify;
      color: #333;
    }
  </style>
</head>

<body>
  <section class="about-section">
    <div class="about__container">
      <img src="./assets/img/about.svg" alt="About Us" class="about__image">
      <h1 class="about__title">About US</h1>
      <p class="about__description">
        Selamat datang di eParking Mall, destinasi belanja dan hiburan terdepan di kota ini! Sebagai pusat perbelanjaan modern yang menggabungkan kemudahan teknologi dengan kenyamanan konvensional, eParking Mall mempersembahkan pengalaman belanja yang tak tertandingi bagi pengunjung dari segala kalangan.

        Dengan inovasi teknologi, eParking Mall menawarkan layanan parkir canggih yang membuat kunjungan Anda menjadi lebih efisien. Melalui sistem parkir otomatis yang terhubung secara digital, Anda dapat dengan mudah menemukan tempat parkir yang tersedia dan melakukan pembayaran tanpa kerumitan. Ini hanya salah satu contoh bagaimana kami memanfaatkan teknologi untuk meningkatkan kenyamanan pengalaman belanja Anda.

        Tidak hanya itu, eParking Mall juga menawarkan beragam toko dan merek ternama yang memenuhi segala kebutuhan belanja Anda, mulai dari fashion, kebutuhan rumah tangga, hingga hiburan elektronik terkini. Dengan pilihan yang beragam dan terus diperbarui, Anda pasti akan menemukan sesuatu yang sesuai dengan selera dan gaya hidup Anda di setiap sudut eParking Mall.

        Tak hanya menjadi destinasi belanja, eParking Mall juga merupakan tempat bertemunya komunitas lokal. Dengan adanya ruang terbuka yang dirancang untuk mengadakan berbagai acara dan pertemuan, kami berusaha menciptakan lingkungan yang ramah dan inklusif bagi semua pengunjung. Mulai dari konser musik hingga pameran seni lokal, eParking Mall selalu memiliki sesuatu yang menarik untuk dinikmati bersama keluarga dan teman-teman.

        Kami, tim eParking Mall, berkomitmen untuk terus memberikan pengalaman belanja yang tak terlupakan bagi setiap pengunjung kami. Dengan fokus pada kualitas, kenyamanan, dan inovasi, kami percaya bahwa eParking Mall akan terus menjadi destinasi favorit bagi mereka yang mencari lebih dari sekadar belanja. Terima kasih telah memilih eParking Mall sebagai bagian dari petualangan belanja dan hiburan Anda. Ayo bergabung dan nikmati pengalaman berbelanja yang tak tertandingi di eParking Mall!
      </p>
    </div>
  </section>
  <?php
  include 'footer.php';
  ?>
</body>

</html>