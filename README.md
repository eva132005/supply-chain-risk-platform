markdown# 🌐 Global Supply Chain Risk Intelligence Platform

Platform monitoring risiko rantai pasok global berbasis multi-API dan analitik data.

## 📋 Deskripsi

Sistem yang memantau risiko logistik global dengan mengintegrasikan data cuaca, ekonomi, kurs mata uang, berita, dan pelabuhan dari berbagai negara, kemudian menghitung **Risk Score** per negara menggunakan algoritma Weighted Risk Model.

## 🚀 Fitur Utama

- **Global Country Dashboard** — Pantau data 250+ negara
- **Risk Scoring Engine** — Algoritma penilaian risiko (Weather + Inflation + Currency + News)
- **Global Weather Monitoring** — Data cuaca real-time via Open-Meteo API
- **Currency Impact Dashboard** — Kurs mata uang real-time dengan Chart.js
- **News Intelligence** — Berita supply chain + Lexicon-based Sentiment Analysis
- **Port Location Dashboard** — Peta 7698 pelabuhan global via Leaflet.js
- **Data Visualization** — Grafik GDP, inflasi, kurs, dan risk trend
- **Country Comparison Engine** — Bandingkan risiko antar negara
- **Favorite Monitoring List** — Watchlist negara yang dipantau
- **Admin Dashboard** — Kelola user, artikel, dan dataset pelabuhan

## 🛠️ Teknologi

### Backend
- PHP 8.3 + Laravel 11
- MySQL
- REST API (36 endpoints)

### Frontend
- Bootstrap 5
- Chart.js
- Leaflet.js
- JavaScript ES6 + AJAX

### API Eksternal
| API | Data |
|-----|------|
| Open-Meteo | Cuaca global |
| World Bank API | GDP, Inflasi, Populasi |
| REST Countries (mledoze) | Data 250 negara |
| ExchangeRate API | Kurs mata uang real-time |
| NewsData.io | Berita ekonomi & logistik |
| OpenFlights Dataset | 7698 data pelabuhan |

## 🗄️ Database

15+ tabel:
- `users`, `countries`, `risk_scores`
- `weather_data`, `economic_data`, `exchange_rates`
- `news_cache`, `ports`, `watchlists`
- `articles`, `positive_words`, `negative_words`
- + tabel bawaan Laravel

## 🧠 Risk Scoring Algorithm
Risk Score = (Weather × 30%) + (Inflation × 20%) + (News × 40%) + (Currency × 10%)

| Score | Level |
|-------|-------|
| 0-30 | Low Risk |
| 31-60 | Medium Risk |
| 61-100 | High Risk |

## ⚙️ Instalasi

```bash
# Clone repository
git clone https://github.com/eva132005/supply-chain-risk-platform.git
cd supply-chain-risk-platform

# Install dependencies
composer install
npm install && npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed --class=SentimentWordSeeder

# Sync data
php artisan sync:countries
php artisan sync:ports

# Jalankan server
php artisan serve
```

## 📡 REST API Endpoints (36 endpoints)
GET  /api/countries
GET  /api/countries/{code}
GET  /api/countries/region/{region}
GET  /api/countries/search/{query}
GET  /api/countries/{code}/summary
GET  /api/weather
GET  /api/weather/{code}
GET  /api/economic
GET  /api/economic/{code}
GET  /api/economic/top/gdp
GET  /api/currency
GET  /api/currency/{code}
GET  /api/currency/compare/{codeA}/{codeB}
GET  /api/news
GET  /api/news/{code}
GET  /api/news/sentiment/{sentiment}
GET  /api/news/latest/{limit}
GET  /api/ports
GET  /api/ports/{id}
GET  /api/ports/country/{code}
GET  /api/ports/search/{query}
GET  /api/risk
GET  /api/risk/{code}
POST /api/risk/calculate/{code}
POST /api/risk/calculate-all
GET  /api/risk/level/{level}
GET  /api/risk/top/{limit}
GET  /api/stats/overview
GET  /api/stats/sentiment-summary
GET  /api/stats/risk-summary
GET  /api/stats/top-risk-countries
... dan 5 endpoint lainnya

## 👤 Default Admin
Email: siska@gmail.com
Role: admin

## 📁 Struktur Project
app/
├── Http/Controllers/
│   ├── Api/          # REST API Controllers
│   ├── Admin/        # Admin Controllers
│   └── DashboardController.php
├── Models/           # Eloquent Models
├── Services/         # API Integration Services
└── Console/Commands/ # Artisan Commands

## 👩‍💻 Developer

**Nama:** EVA KHAIRANI HASIBUAN 
**Project:** UAS Full Stack Web Development  
**Stack:** Laravel + MySQL + Bootstrap + Chart.js + Leaflet.js