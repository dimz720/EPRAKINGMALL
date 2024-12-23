<?php
include_once 'header.php';
echo getHeaderHtml();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./assets/css/styles.css">
  <title>eParking Mall</title>
</head>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

  :root {
    --header-height: 3.5rem;

    --hue: 152;
    --first-color: hsl(var(--hue), 24%, 32%);
    --first-color-alt: hsl(var(--hue), 24%, 28%);
    --first-color-light: hsl(var(--hue), 24%, 66%);
    --first-color-lighten: hsl(var(--hue), 24%, 92%);
    --title-color: hsl(var(--hue), 4%, 15%);
    --text-color: hsl(var(--hue), 4%, 35%);
    --text-color-light: hsl(var(--hue), 4%, 55%);
    --body-color: hsl(var(--hue), 0%, 100%);
    --container-color: #FFF;

    --body-font: 'Poppins', sans-serif;
    --big-font-size: 2rem;
    --h1-font-size: 1.5rem;
    --h2-font-size: 1.25rem;
    --h3-font-size: 1rem;
    --normal-font-size: .938rem;
    --small-font-size: .813rem;
    --smaller-font-size: .75rem;

    --font-medium: 500;
    --font-semi-bold: 600;

    --mb-0-5: .5rem;
    --mb-0-75: .75rem;
    --mb-1: 1rem;
    --mb-1-5: 1.5rem;
    --mb-2: 2rem;
    --mb-2-5: 2.5rem;

    --z-tooltip: 10;
    --z-fixed: 100;
  }

  @media screen and (min-width: 968px) {
    :root {
      --big-font-size: 3.5rem;
      --h1-font-size: 2.25rem;
      --h2-font-size: 1.5rem;
      --h3-font-size: 1.25rem;
      --normal-font-size: 1rem;
      --small-font-size: .875rem;
      --smaller-font-size: .813rem;
    }
  }

  * {
    box-sizing: border-box;
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
  }

  html {
    scroll-behavior: smooth;
  }

  body,
  button,
  input,
  textarea {
    font-family: var(--body-font);
    font-size: var(--normal-font-size);
  }


  button {
    cursor: pointer;
    border: none;
    outline: none;
  }

  .button {
    background-color: #00855D;
    color: var(--container-color);
    display: inline-flex;
    align-items: center;
    column-gap: .5rem;
    padding: 1rem 1.5rem;
    border-radius: .5rem;
    font-weight: var(--font-medium);
  }

  .button--flex {
    display: inline-flex;
    align-items: center;
    column-gap: .5rem;
  }

  .button__icon {
    font-size: 1.25rem;
  }


  h1,
  h2,
  h3 {
    color: var(--title-color);
    font-weight: var(--font-semi-bold);
  }

  ul {
    list-style: none;
  }

  a {
    text-decoration: none;
  }

  img {
    max-width: 100%;
    height: auto;
  }

  .section {
    padding: 5.5rem 0 1rem;
  }

  .section__title,
  .section__title-center {
    font-size: var(--h2-font-size);
    margin-bottom: var(--mb-2);
    line-height: 140%;
  }

  .section__title-center {
    text-align: center;
  }

  .container {
    max-width: 968px;
    margin-left: var(--mb-1-5);
    margin-right: var(--mb-1-5);
  }

  .grid {
    display: grid;
  }

  .main {
    overflow: hidden;
  }

  .show-menu {
    right: 0;
  }

  .scroll-header {
    box-shadow: 0 1px 4px hsla(var(--hue), 4%, 15%, .1);
  }
  .home__container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    gap: 2rem;
    padding: 4rem 0;
  }

  .home__content {
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .home__text {
    max-width: 600px;
  }

  .home__title {
    font-size: 2.5rem;
    color: var(--title-color);
    margin-bottom: 1rem;
    line-height: 1.2;
  }

  .home__description {
    font-size: 1.1rem;
    color: var(--text-color);
    margin-bottom: 0.75rem;
  }

  .home__description-sub {
    font-size: 1rem;
    color: var(--text-color-light);
    margin-bottom: 1.5rem;
    font-weight: 500;
  }

  .home__image-container {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .home__img {
    max-width: 100%;
    height: auto;
  }

  .home__search-form {
    width: 100%;
  }

  .home__search-container {
    display: flex;
    gap: 1rem;
  }

  .home__search-input {
    flex: 1;
    padding: 0.75rem;
    border: 2px solid #00855D;
    border-radius: 0.5rem;
    font-size: 1rem;
  }

  .home__search-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background-color: #00855D;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .home__search-button:hover {
    background-color: var(--first-color-alt);
  }
  .about__container {
    row-gap: 2rem;
  }

  .about__img {
    width: 250px;
    justify-self: center;
  }

  .about__title {
    margin-bottom: var(--mb-1);
  }

  .about__description {
    margin-bottom: var(--mb-2);
  }

  .about__details {
    display: grid;
    row-gap: 1rem;
    margin-bottom: var(--mb-2-5);
  }

  .about__details-description {
    display: inline-flex;
    column-gap: .5rem;
    font-size: var(--small-font-size);
  }

  .about__details-icon {
    font-size: 1rem;
    color: var(--first-color);
    margin-top: .15rem;
  }

  .steps__bg {
    background-color: #00855D;
    padding: 3rem 2rem 2rem;
    border-radius: 1rem;
  }

  .steps__container {
    gap: 2rem;
    padding-top: 1rem;
  }

  .steps__title {
    color: #FFF;
  }

  .steps__card {
    background-color: var(--container-color);
    padding: 2.5rem 3rem 2rem 1.5rem;
    border-radius: 1rem;
  }

  .steps__card-number {
    display: inline-block;
    background-color: #00855D;
    color: #FFF;
    padding: .5rem .75rem;
    border-radius: .25rem;
    font-size: var(--h2-font-size);
    margin-bottom: var(--mb-1-5);
    transition: .3s;
  }

  .steps__card-title {
    font-size: var(--h3-font-size);
    margin-bottom: var(--mb-0-5);
  }

  .steps__card-description {
    font-size: var(--small-font-size);
  }

  .steps__card:hover .steps__card-number {
    transform: translateY(-.25rem);
  }

  .questions {
    background-color: var(--first-color-lighten);
  }

  .questions__container {
    gap: 1.5rem;
    padding: 1.5rem 0;
  }

  .questions__group {
    display: grid;
    row-gap: 1.5rem;
  }

  .questions__item {
    background-color: var(--container-color);
    border-radius: .25rem;
  }

  .questions__item-title {
    font-size: var(--small-font-size);
    font-weight: var(--font-medium);
  }

  .questions__icon {
    font-size: 1.25rem;
    color: var(--title-color);
  }

  .questions__description {
    font-size: var(--smaller-font-size);
    padding: 0 1.25rem 1.25rem 2.5rem;
  }

  .questions__header {
    display: flex;
    align-items: center;
    column-gap: .5rem;
    padding: .75rem .5rem;
    cursor: pointer;
  }

  .questions__content {
    overflow: hidden;
    height: 0;
  }

  .questions__item:hover {
    box-shadow: 0 2px 8px hsla(var(--hue), 4%, 15%, .15);
  }

  @media screen and (max-width: 320px) {
    .container {
      margin-left: var(--mb-1);
      margin-right: var(--mb-1);
    }

    .steps__bg {
      padding: 2rem 1rem;
    }

    .steps__card {
      padding: 1.5rem;
    }

    .product__container {
      grid-template-columns: .6fr;
      justify-content: center;
    }
  }

  @media screen and (min-width: 576px) {
    .steps__container {
      grid-template-columns: repeat(2, 1fr);
    }

    .product__description {
      padding: 0 4rem;
    }

    .product__container {
      grid-template-columns: repeat(2, 170px);
      justify-content: center;
      column-gap: 5rem;
    }

  }

  @media screen and (min-width: 767px) {
    body {
      margin: 0;
    }

    .about__container,
    .questions__container,
    .contact__container,
    .footer__container {
      grid-template-columns: repeat(2, 1fr);
    }

    .questions__container {
      align-items: flex-start;
    }
  }

  @media screen and (min-width: 992px) {
    .container {
      margin-left: auto;
      margin-right: auto;
    }

    .section {
      padding: 8rem 0 1rem;
    }

    .section__title,
    .section__title-center {
      font-size: var(--h1-font-size);
    }

    .about__img {
      width: 380px;
    }

    .steps__container {
      grid-template-columns: repeat(3, 1fr);
    }

    .steps__bg {
      padding: 3.5rem 2.5rem;
    }

    .steps__card-title {
      font-size: var(--normal-font-size);
    }

    .product__description {
      padding: 0 16rem;
    }

    .product__container {
      padding: 4rem 0;
      grid-template-columns: repeat(3, 185px);
      gap: 4rem 6rem;
    }

    .product__img {
      width: 160px;
    }

    .product__circle {
      width: 110px;
      height: 110px;
    }

    .product__title,
    .product__price {
      font-size: var(--normal-font-size);
    }

    .questions__container {
      padding: 1rem 0 4rem;
    }

    .questions__title {
      text-align: initial;
    }

    .questions__group {
      row-gap: 2rem;
    }

    .questions__header {
      padding: 1rem;
    }

    .questions__description {
      padding: 0 3.5rem 2.25rem 2.75rem;
    }
  }

  @media screen and (min-width: 1200px) {

    .about__container {
      column-gap: 7rem;
    }

    .scrollup {
      right: 3rem;
    }
  }

  .search__form {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    font-family: var(--body-font);
  }

  .search__container {
    display: flex;
    max-width: 500px;
    width: 100%;
    gap: var(--mb-0-5);
    margin-top: var(--mb-1-5);
  }

  .search__input {
    flex: 1;
    padding: var(--mb-0-75);
    border: 2px solid #00855D;
    border-radius: .5rem;
    font-size: var(--normal-font-size);
    color: var(--text-color);
    background-color: var(--body-color);
    outline: none;
    transition: .3s;
  }

  .search__input::placeholder {
    color: var(--text-color-light);
  }

  .search__input:focus {
    border-color: var(--first-color);
  }

  .search__button {
    display: inline-flex;
    align-items: center;
    gap: var(--mb-0-5);
    padding: var(--mb-0-75) var(--mb-1-5);
    background-color: #00855D;
    color: var(--container-color);
    border: none;
    border-radius: .5rem;
    font-size: var(--normal-font-size);
    font-weight: var(--font-medium);
    cursor: pointer;
    transition: .3s;
  }

  .search__button:hover {
    background-color: var(--first-color-alt);
  }

  .search__button i {
    font-size: var(--h3-font-size);
  }

  /* Styling khusus untuk role selection */
  .role_selection_container {
    margin-top: 1rem;
    position: relative;
    width: 100%;
    height: 40px;
  }

  .role_selection {
    height: 100%;
    width: 100%;
    padding: 0 1rem;
    font-size: 1rem;
    color: #555;
    border: 1.5px solid #ddd;
    border-radius: 5px;
    background-color: #fff;
    appearance: none;
    outline: none;
    transition: border-color 0.3s ease;
  }

  .role_selection:focus {
    border-color: #007bff;
  }

  /* Icon untuk role selection */
  .role_selection_container i.role_icon {
    position: absolute;
    top: 50%;
    left: 0.5rem;
    transform: translateY(-50%);
    color: #aaa;
    font-size: 1.2rem;
  }

  /* Hover dan Focus effect */
  .role_selection:hover,
  .role_selection:focus {
    border-color: #0056b3;
  }





  /* Responsive */
  @media screen and (max-width: 768px) {
    .search__container {
      flex-direction: column;
    }

    .search__button {
      width: 100%;
      justify-content: center;
    }
  }

  @media screen and (min-width: 968px) {
    .search__input {
      padding: var(--mb-1);
      font-size: var(--normal-font-size);
    }

    .search__button {
      padding: var(--mb-1) var(--mb-2);
    }

    .search__button i {
      font-size: var(--h2-font-size);
    }
  }
</style>

<body>
  <main class="main">
    <section class="home" id="home">
      <div class="home__container container grid">
        <div class="home__content">
          <div class="home__text">
            <h1 class="home__title">
              Discover Faster Than Ever
            </h1>
            <p class="home__description">
              Temukan tempat parkir di mana saja, untuk saat ini atau nanti Bandingkan harga & pilih tempat yang terbaik untuk Anda
            </p>
            <p class="home__description-sub">
              Cari, Booking, Bayar, Parkir, Mudah!
            </p>

            <!-- Search Form -->
            <form action="booking.php" method="GET" class="home__search-form">
              <div class="home__search-container">
                <input type="text"
                  class="home__search-input"
                  placeholder="Cari lokasi mall..."
                  name="search">
                <button type="submit" class="home__search-button">
                  <i class="ri-search-line"></i>
                  Cari
                </button>
              </div>
            </form>
          </div>
        </div>

        <div class="home__image-container">
          <img src="assets/img/discover.svg" alt="Parking Discovery" class="home__img">
        </div>
      </div>
    </section>
    <section class="about section container" id="about">
      <div class="about__container grid">
        <img src="assets/img/time.svg" alt="" class="about__img">

        <div class="about__data">
          <h2 class="section__title about__title">
            Mengapa Memilih Kami?
          </h2>

          <p class="about__description">
            Kami hadir untuk memberikan solusi parkir yang cepat dan nyaman, membantu Anda menghemat waktu dan menghindari kerepotan mencari tempat parkir. Dapatkan pengalaman parkir terbaik hanya dengan beberapa klik.
          </p>

          <div class="about__details">
            <p class="about__details-description">
              <i class="ri-checkbox-fill about__details-icon"></i>
              - Mudah digunakan dan dipesan kapan saja.
            </p>
            <p class="about__details-description">
              <i class="ri-checkbox-fill about__details-icon"></i>
              - Terjamin aman dengan dukungan layanan pelanggan.
            </p>
            <p class="about__details-description">
              <i class="ri-checkbox-fill about__details-icon"></i>
              - Tersedia berbagai pilihan parkir sesuai kebutuhan.
            </p>
            <p class="about__details-description">
              <i class="ri-checkbox-fill about__details-icon"></i>
              -Jaminan uang kembali jika terjadi kendala.
            </p>
          </div>
        </div>
      </div>
    </section>
    <section class="steps section container">
      <div class="steps__bg">
        <h2 class="section__title-center steps__title">
          Langkah Mudah Parkir Mall
        </h2>

        <div class="steps__container grid">
          <div class="steps__card">
            <div class="steps__card-number">01</div>
            <h3 class="steps__card-title">Cari Parkir</h3>
            <p class="steps__card-description">
              Cari dan bandingkan tempat parkir di berbagai lokasi sesuai kebutuhan Anda.
            </p>
          </div>

          <div class="steps__card">
            <div class="steps__card-number">02</div>
            <h3 class="steps__card-title">Booking & Bayar</h3>
            <p class="steps__card-description">
              Reservasi tempat parkir Anda dan lakukan pembayaran dengan aman melalui platform kami.
            </p>
          </div>

          <div class="steps__card">
            <div class="steps__card-number">03</div>
            <h3 class="steps__card-title">Parkir dengan Mudah</h3>
            <p class="steps__card-description">
              Cukup datang ke tempat parkir yang sudah direservasi dan nikmati kemudahan parkir.
            </p>
          </div>
        </div>
      </div>
    </section>
    <section class="contact section container" id="contact">
      <div class="contact__container grid">
        <div class="contact__box">
          <h2 class="section__title">
            Parking Owner ?
          </h2>
          <p class="about__description">Bergabunglah bersama kami dan optimalkan efisiensi pengelolaan parkir di mall Anda. Tingkatkan kemudahan, keamanan, dan kenyamanan pengunjung dalam menemukan tempat parkir yang tersedia dengan teknologi modern yang kami tawarkan.</p>
          <button class="button button--flex" onclick="window.location.href='ownersignup.php'">
            Join us Now
            <i class="ri-arrow-right-up-line button__icon"></i>
          </button>
        </div>

        <img src="assets/img/owner.svg" alt="" class="owner_img">
      </div>
    </section>
  </main>
  <?php include 'footer.php'; ?>

</body>

</html>