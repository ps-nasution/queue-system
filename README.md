# Queue R/W Digital Antrian System

Sistem antrian digital dengan **dua jenis antrian**:

* **Reservasi** (Prefix: `R`)
* **Walk-in** (Prefix: `W`)

Algoritma pemanggilan mengikuti aturan **2 Reservasi : 1 Walk-in**, dengan prioritas dinamis.
Backend menggunakan **Laravel**, Frontend menggunakan **React + Vite**, dan database **MySQL** (opsional via Docker).

---

## ✨ Fitur

* Ambil nomor antrian untuk Reservasi & Walk-in
* Pemanggilan antrian berbasis skema 2R:1W
* Staff Console (Call Next, Mark Done)
* Display publik dengan **Text-to-Speech (TTS)**
* Dashboard statistik:

  * Jumlah antrian aktif
  * Staff aktif
  * Top 3 staff
  * Rata rata waktu layanan per staff

---

## 📦 Struktur Project

```
queue-system/
  backend/    # Laravel API
  frontend/   # React + Vite UI
  docker/ (opsional untuk MySQL)
```

---

## 🛠 Prasyarat

| Software         | Keterangan                |
| ---------------- | ------------------------- |
| PHP ≥ 8.1        | Laravel backend           |
| Composer         | Instal dependensi Laravel |
| Node.js + npm    | Menjalankan React UI      |
| MySQL 8 / Docker | Database                  |

---

## 🚀 Instalasi Backend (Laravel)

```bash
git clone https://github.com/ps-nasution/queue-system.git
cd queue-system/backend
composer install
cp .env.example .env
php artisan key:generate
```

### Konfigurasi `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=queue_rw
DB_USERNAME=queueuser
DB_PASSWORD=queuepass
```

### Migrasi & Seeder

```bash
php artisan migrate --seed
```

### Jalankan server

```bash
php artisan serve
```

> Default berjalan di [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 💻 Instalasi Frontend (React + Vite)

```bash
cd ../frontend
npm install
```

Jalankan UI:

```bash
npm run dev
```

> Default berjalan di [http://127.0.0.1:5173](http://127.0.0.1:5173)

---

## 🐳 Opsional: Menjalankan MySQL dengan Docker

Buat / gunakan file `docker-compose.yml` di folder docker:

```yaml
version: '3.8'
services:
  mysql:
    image: mysql:8.0
    container_name: queue-mysql
    environment:
      MYSQL_DATABASE: queue_rw
      MYSQL_USER: queueuser
      MYSQL_PASSWORD: queuepass
      MYSQL_ROOT_PASSWORD: rootpass
    ports:
      - "3306:3306"
    volumes:
      - dbdata:/var/lib/mysql
    command: ["mysqld", "--default-authentication-plugin=mysql_native_password"]
volumes:
  dbdata:
```

Jalankan DB:

```bash
docker compose up -d
```

---

## 🧪 Testing Cepat API

```bash
# Ambil tiket R
curl -X POST http://127.0.0.1:8000/api/tickets -H 'Content-Type: application/json' -d '{"type":"R"}'

# Staff (id=1) Call Next
curl -X POST http://127.0.0.1:8000/api/queue/call-next -H 'Content-Type: application/json' -d '{"staff_id":1}'
```

---

## 📄 Lisensi

MIT — bebas dipakai & dikembangkan.

---

## ❤️ Kontribusi 

Pull request sangat diterima! Mau menambahkan fitur realtime menggunakan **Pusher / Laravel Echo / Socket.io**? Silakan.

---

**Selesai.** 🎉
