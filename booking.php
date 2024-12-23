<?php
include_once 'header.php';
echo getHeaderHtml();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eParking Mall</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        .search-container {
            padding: 2rem;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .search-box {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        .search-box input {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            color: #333;
            background-color: #f0f4ff;
            outline: none;
        }
        .search-box button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #00855D;
            cursor: pointer;
            font-size: 20px;
        }
        #map {
            width: 100%;
            height: 400px;
            z-index: 1;
        }
        .controls {
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-bottom: 1px solid #ddd;
        }
        .sort-button {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: white;
            color: #00855D;
            cursor: pointer;
            font-size: 1rem;
        }
        .sort-button:hover {
            background-color: #f0f4ff;
        }
        .parking-container {
            display: flex;
            padding: 2rem;
            gap: 2rem;
        }
        .parking-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .parking-spot {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            gap: 1rem;
            height: 200px;
            background: white;
        }
        .parking-image-container {
            width: 160px;
            flex-shrink: 0;
        }
        .parking-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        .parking-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .parking-title {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .parking-location {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .pricing-options {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .price-option {
            flex: 1;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s ease;
        }
        .price-option.active {
            border-color: #00855D;
            background-color: #f0f4ff;
        }
        .price-option:hover {
            border-color: #00855D;
        }
        .price-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.3rem;
        }
        .price-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #00855D;
        }
        .book-button {
            background-color: #00855D;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            margin-top: auto;
            transition: background-color 0.2s ease;
        }
        .book-button:hover {
            background-color: #00855D;
        }
        .custom-popup {
            text-align: center;
            padding: 10px;
        }
        .custom-popup h3 {
            margin: 0 0 10px 0;
            color: #00855D;
        }
        .custom-popup p {
            margin: 5px 0;
        }
        @media (max-width: 768px) {
            .parking-container {
                flex-direction: column;
            }
            .parking-spot {
                flex-direction: column;
                height: auto;
            }
            .parking-image-container {
                width: 100%;
                height: 200px;
            }
        }
    </style>
</head>

<body>

    <div class="search-container">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Kemana kamu pergi?">
            <button onclick="searchLocation()">
                <i class="uil uil-search"></i>
            </button>
        </div>
    </div>

    <div id="map"></div>

    <div class="controls">
        <button class="sort-button" onclick="toggleSort()">Termurah ▼</button>
    </div>
    <div class="parking-container">
        <div class="parking-column">
            <div class="parking-spot" data-hourly="5000" data-monthly="150000" data-lat="-7.2575" data-lng="112.7378">
                <div class="parking-image-container">
                    <img src="./assets/img/spots/1.png" alt="Tunjungan Plaza" class="parking-image">
                </div>
                <div class="parking-info">
                    <div class="parking-title">Tunjungan Plaza</div>
                    <div class="parking-location">Jl. Jenderal Basuki Rachmat</div>
                    <div class="pricing-options">
                        <div class="price-option active" onclick="selectPrice(this, 'hourly')">
                            <div class="price-label">Per Jam</div>
                            <div class="price-value">Rp5.000</div>
                        </div>
                        <div class="price-option" onclick="selectPrice(this, 'monthly')">
                            <div class="price-label">Per Hari</div>
                            <div class="price-value">Rp150.000</div>
                        </div>
                    </div>
                    <button class="book-button">PESAN SEKARANG</button>
                </div>
            </div>

            <div class="parking-spot" data-hourly="4000" data-monthly="120000" data-lat="-7.2890" data-lng="112.7184">
                <div class="parking-image-container">
                    <img src="./assets/img/spots/3.png" alt="Galaxy Mall" class="parking-image">
                </div>
                <div class="parking-info">
                    <div class="parking-title">Galaxy Mall</div>
                    <div class="parking-location">Jl. Dharmahusada Indah Timur</div>
                    <div class="pricing-options">
                        <div class="price-option active" onclick="selectPrice(this, 'hourly')">
                            <div class="price-label">Per Jam</div>
                            <div class="price-value">Rp4.000</div>
                        </div>
                        <div class="price-option" onclick="selectPrice(this, 'monthly')">
                            <div class="price-label">Per Hari</div>
                            <div class="price-value">Rp120.000</div>
                        </div>
                    </div>
                    <button class="book-button">PESAN SEKARANG</button>
                </div>
            </div>

            <div class="parking-spot" data-hourly="6000" data-monthly="180000" data-lat="-7.2643" data-lng="112.7441">
                <div class="parking-image-container">
                    <img src="./assets/img/spots/5.png" alt="Pakuwon Mall" class="parking-image">
                </div>
                <div class="parking-info">
                    <div class="parking-title">Pakuwon Mall</div>
                    <div class="parking-location">Jl. Mayjen Sungkono</div>
                    <div class="pricing-options">
                        <div class="price-option active" onclick="selectPrice(this, 'hourly')">
                            <div class="price-label">Per Jam</div>
                            <div class="price-value">Rp6.000</div>
                        </div>
                        <div class="price-option" onclick="selectPrice(this, 'monthly')">
                            <div class="price-label">Per Hari</div>
                            <div class="price-value">Rp180.000</div>
                        </div>
                    </div>
                    <button class="book-button">PESAN SEKARANG</button>
                </div>
            </div>

            <div class="parking-spot" data-hourly="3000" data-monthly="100000" data-lat="-7.2432" data-lng="112.7378">
                <div class="parking-image-container">
                    <img src="./assets/img/spots/7.png" alt="Ciputra World" class="parking-image">
                </div>
                <div class="parking-info">
                    <div class="parking-title">Ciputra World</div>
                    <div class="parking-location">Jl. Mayjend Sungkono No.89</div>
                    <div class="pricing-options">
                        <div class="price-option active" onclick="selectPrice(this, 'hourly')">
                            <div class="price-label">Per Jam</div>
                            <div class="price-value">Rp3.000</div>
                        </div>
                        <div class="price-option" onclick="selectPrice(this, 'monthly')">
                            <div class="price-label">Per Hari</div>
                            <div class="price-value">Rp100.000</div>
                        </div>
                    </div>
                    <button class="book-button">PESAN SEKARANG</button>
                </div>
            </div>

            <div class="parking-spot" data-hourly="4500" data-monthly="135000" data-lat="-7.2758" data-lng="112.7194">
                <div class="parking-image-container">
                    <img src="./assets/img/spots/9.png" alt="Royal Plaza" class="parking-image">
                </div>
                <div class="parking-info">
                    <div class="parking-title">Royal Plaza</div>
                    <div class="parking-location">Jl. Ahmad Yani</div>
                    <div class="pricing-options">
                        <div class="price-option active" onclick="selectPrice(this, 'hourly')">
                            <div class="price-label">Per Jam</div>
                            <div class="price-value">Rp4.500</div>
                        </div>
                        <div class="price-option" onclick="selectPrice(this, 'monthly')">
                            <div class="price-label">Per Hari</div>
                            <div class="price-value">Rp135.000</div>
                        </div>
                    </div>
                    <button class="book-button">PESAN SEKARANG</button>
                </div>
            </div>
        </div>

        <div class="parking-column">
            <div class="parking-spot" data-hourly="5500" data-monthly="165000" data-lat="-7.2554" data-lng="112.7324">
                <div class="parking-image-container">
                    <img src="./assets/img/spots/2.png" alt="Grand City" class="parking-image">
                </div>
                <div class="parking-info">
                    <div class="parking-title">Grand City</div>
                    <div class="parking-location">Jl. Gubeng Pojok</div>
                    <div class="pricing-options">
                        <div class="price-option active" onclick="selectPrice(this, 'hourly')">
                            <div class="price-label">Per Jam</div>
                            <div class="price-value">Rp5.500</div>
                        </div>
                        <div class="price-option" onclick="selectPrice(this, 'monthly')">
                            <div class="price-label">Per Hari</div>
                            <div class="price-value">Rp165.000</div>
                        </div>
                    </div>
                    <button class="book-button">PESAN SEKARANG</button>
                </div>
            </div>

            <div class="parking-spot" data-hourly="3500" data-monthly="105000" data-lat="-7.2612" data-lng="112.7522">
                <div class="parking-image-container">
                    <img src="./assets/img/spots/4.png" alt="Delta Plaza" class="parking-image">
                </div>
                <div class="parking-info">
                    <div class="parking-title">Delta Plaza</div>
                    <div class="parking-location">Jl. Pemuda</div>
                    <div class="pricing-options">
                        <div class="price-option active" onclick="selectPrice(this, 'hourly')">
                            <div class="price-label">Per Jam</div>
                            <div class="price-value">Rp3.500</div>
                        </div>
                        <div class="price-option" onclick="selectPrice(this, 'monthly')">
                            <div class="price-label">Per Hari</div>
                            <div class="price-value">Rp105.000</div>
                        </div>
                    </div>
                    <button class="book-button">PESAN SEKARANG</button>
                </div>
            </div>

            <div class="parking-spot" data-hourly="4000" data-monthly="120000" data-lat="-7.2498" data-lng="112.7284">
                <div class="parking-image-container">
                    <img src="./assets/img/spots/6.png" alt="BG Junction" class="parking-image">
                </div>
                <div class="parking-info">
                    <div class="parking-title">BG Junction</div>
                    <div class="parking-location">Jl. Bubutan</div>
                    <div class="pricing-options">
                        <div class="price-option active" onclick="selectPrice(this, 'hourly')">
                            <div class="price-label">Per Jam</div>
                            <div class="price-value">Rp4.000</div>
                        </div>
                        <div class="price-option" onclick="selectPrice(this, 'monthly')">
                            <div class="price-label">Per Hari</div>
                            <div class="price-value">Rp120.000</div>
                        </div>
                    </div>
                    <button class="book-button">PESAN SEKARANG</button>
                </div>
            </div>

            <div class="parking-spot" data-hourly="5000" data-monthly="150000" data-lat="-7.2712" data-lng="112.7198">
                <div class="parking-image-container">
                    <img src="./assets/img/spots/8.png" alt="Plaza Marina" class="parking-image">
                </div>
                <div class="parking-info">
                    <div class="parking-title">Plaza Marina</div>
                    <div class="parking-location">Jl. Margorejo Indah</div>
                    <div class="pricing-options">
                        <div class="price-option active" onclick="selectPrice(this, 'hourly')">
                            <div class="price-label">Per Jam</div>
                            <div class="price-value">Rp5.000</div>
                        </div>
                        <div class="price-option" onclick="selectPrice(this, 'monthly')">
                            <div class="price-label">Per Hari</div>
                            <div class="price-value">Rp150.000</div>
                        </div>
                    </div>
                    <button class="book-button">PESAN SEKARANG</button>
                </div>
            </div>

            <div class="parking-spot" data-hourly="4500" data-monthly="135000" data-lat="-7.2834" data-lng="112.7178">
                <div class="parking-image-container">
                    <img src="./assets/img/spots/10.png" alt="East Coast Center" class="parking-image">
                </div>
                <div class="parking-info">
                    <div class="parking-title">East Coast Center</div>
                    <div class="parking-location">Jl. Kejawan Putih Mutiara</div>
                    <div class="pricing-options">
                        <div class="price-option active" onclick="selectPrice(this, 'hourly')">
                            <div class="price-label">Per Jam</div>
                            <div class="price-value">Rp4.500</div>
                        </div>
                        <div class="price-option" onclick="selectPrice(this, 'monthly')">
                            <div class="price-label">Per Hari</div>
                            <div class="price-value">Rp135.000</div>
                        </div>
                    </div>
                    <button class="book-button">PESAN SEKARANG</button>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-7.2575, 112.7378], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const parkingIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const markers = new Map();

        document.querySelectorAll('.parking-spot').forEach(spot => {
            const lat = parseFloat(spot.dataset.lat);
            const lng = parseFloat(spot.dataset.lng);
            const title = spot.querySelector('.parking-title').textContent;
            const hourlyRate = spot.querySelector('.price-value').textContent;

            const marker = L.marker([lat, lng], {
                icon: parkingIcon
            }).addTo(map);

            const popupContent = `
                <div class="custom-popup">
                    <h3>${title}</h3>
                    <p>Hourly Rate: ${hourlyRate}</p>
                </div>
            `;

            marker.bindPopup(popupContent);
            markers.set(title.toLowerCase(), {
                marker,
                spot
            });
        });

        function searchLocation() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();

            const markerData = markers.get(searchInput);
            if (markerData) {
                const {
                    marker,
                    spot
                } = markerData;
                const position = marker.getLatLng();
                map.setView(position, 16);
                marker.openPopup();

                spot.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                spot.style.backgroundColor = '#f0f7f4';

                setTimeout(() => {
                    spot.style.backgroundColor = 'white';
                }, 2000);

                return;
            }

            const endpoint = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchInput)}`;

            fetch(endpoint)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        map.setView([lat, lon], 16);
                    }
                })
                .catch(error => {
                    console.error('Error searching location:', error);
                });
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();

            document.querySelectorAll('.parking-spot').forEach(spot => {
                spot.style.backgroundColor = 'white';
            });

            document.querySelectorAll('.parking-spot').forEach(spot => {
                const title = spot.querySelector('.parking-title').textContent.toLowerCase();
                if (title.includes(searchText) && searchText !== '') {
                    spot.style.backgroundColor = '#f0f7f4';
                }
            });
        });

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchLocation();
            }
        });

        let ascending = true;

        function selectPrice(element, type) {
            const parent = element.parentElement;
            parent.querySelectorAll('.price-option').forEach(option => {
                option.classList.remove('active');
            });
            element.classList.add('active');
        }

        function toggleSort() {
            ascending = !ascending;
            const button = document.querySelector('.sort-button');
            button.textContent = ascending ? 'Termurah ▼' : 'Termahal ▲';

            const container = document.querySelector('.parking-container');
            const spots = Array.from(document.querySelectorAll('.parking-spot'));

            const activeType = document.querySelector('.price-option.active').querySelector('.price-label').textContent.toLowerCase();

            spots.sort((a, b) => {
                const priceA = parseInt(a.dataset[activeType]);
                const priceB = parseInt(b.dataset[activeType]);
                return ascending ? priceA - priceB : priceB - priceA;
            });

            const leftColumn = container.querySelector('.parking-column:first-child');
            const rightColumn = container.querySelector('.parking-column:last-child');
            leftColumn.innerHTML = '';
            rightColumn.innerHTML = '';

            spots.forEach((spot, index) => {
                if (index % 2 === 0) {
                    leftColumn.appendChild(spot);
                } else {
                    rightColumn.appendChild(spot);
                }
            });
        }

        document.querySelectorAll('.book-button').forEach(button => {
            button.addEventListener('click', function() {
                const parkingSpot = this.closest('.parking-spot');
                const title = parkingSpot.querySelector('.parking-title').textContent;
                const location = parkingSpot.querySelector('.parking-location').textContent;
                const hourly = parkingSpot.getAttribute('data-hourly');
                const monthly = parkingSpot.getAttribute('data-monthly');
                const lat = parkingSpot.getAttribute('data-lat');
                const lng = parkingSpot.getAttribute('data-lng');
                const activePriceOption = parkingSpot.querySelector('.price-option.active .price-label').textContent.toLowerCase();
                const url = `payment.php?title=${encodeURIComponent(title)}&location=${encodeURIComponent(location)}&hourly=${hourly}&monthly=${monthly}&lat=${lat}&lng=${lng}&selected=${activePriceOption}`;
                window.location.href = url;
            });
        });
    </script>
</body>

</html>