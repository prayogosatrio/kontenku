# Skenario Demo KontenKu

Urutan demo dari login sampai REST API. Perkiraan durasi 10 sampai 15 menit.

## Persiapan

1. Reset database ke data demo yang bersih:
   ```bash
   php artisan migrate:fresh --seed
   ```
2. Nyalakan dua server di dua terminal:
   ```bash
   php artisan serve --port=8000   # browser
   php artisan serve --port=8011   # API internal (Dashboard dan Jadwal)
   ```
3. Siapkan Postman dengan base URL `http://127.0.0.1:8000/api` dan header `Accept: application/json`.

## 1. Login

1. Buka `http://127.0.0.1:8000/posts` tanpa login. Aplikasi mengarahkan ke halaman **Login**.
2. Login dengan password salah. Muncul *"Email atau password salah."*
3. Login sebagai admin: `admin@kontenku.test` / `password`.
4. **Hasil:** masuk ke Dashboard dengan pesan *"Login berhasil, selamat datang Prayogo Adhi Satrio."*

## 2. Dashboard

1. Tunjukkan empat kartu: Total Klien, Campaign Berjalan, Post Dijadwalkan, Post Sudah Tayang.
2. Jelaskan bahwa angkanya diambil dari `GET /api/stats` lewat server kedua (port 8011), bukan query database langsung.

## 3. Tambah Platform (khusus admin)

1. Klik menu **Platform**, lalu **+ Tambah Platform**.
2. Klik **Simpan** tanpa mengisi. Muncul *"Nama platform wajib diisi."*
3. Isi *Nama Platform*: `Threads`, lalu **Simpan**.
4. Coba hapus **Instagram**. Aplikasi menolak karena platform itu masih dipakai post.

## 4. Tambah Klien

1. Klik menu **Klien**, lalu **+ Tambah Klien**.
2. Isi form:
   - Nama Brand: `Samudra Outdoor`
   - Industri: `Perlengkapan Outdoor`
   - Nama Kontak: `Rudi Hartono`
   - Email: `halo@samudraoutdoor.co.id`
   - Nomor Telepon: `081234567890`
   - Status: `Aktif`
3. Klik **Simpan**, lalu cari `Samudra` di kolom pencarian.

## 5. Buat Campaign

1. Klik menu **Campaign**, lalu **+ Buat Campaign**.
2. Tunjukkan bahwa tidak ada pilihan PIC. Yang tampil hanya teks *"PIC campaign: Prayogo Adhi Satrio (otomatis dari akun yang login)."*
3. Isi form:
   - Klien: `Samudra Outdoor`
   - Nama Campaign: `Promo Libur Akhir Tahun`
   - Tanggal Mulai: hari ini
   - Tanggal Selesai: 30 hari lagi
   - Status: `Berjalan`
4. Coba isi Tanggal Selesai sebelum Tanggal Mulai. Muncul *"Tanggal selesai tidak boleh sebelum tanggal mulai."*
5. Perbaiki, lalu **Simpan**. Aplikasi langsung membuka halaman detail campaign.

## 6. Tambah Post

1. Di halaman detail campaign, klik **+ Tambah Post**. Campaign-nya sudah terpilih otomatis.
2. Isi form:
   - Platform: `Instagram`
   - Judul: `Teaser promo akhir tahun`
   - Caption: `Liburan sebentar lagi. Siap-siap, ada kejutan dari Samudra Outdoor!`
   - Format: `Reels`
   - Tanggal Tayang: besok
   - Status: `Dijadwalkan`
3. Klik **Simpan**. Post muncul di tabel dengan badge **Dijadwalkan** dan tombol **Tandai Tayang**.
4. Tambah satu post lagi dengan status `Draft`. Post ini tidak punya tombol **Tandai Tayang**.

## 7. Daftar Post dan Filter

1. Klik menu **Post**.
2. Filter platform `Instagram` dan status `Dijadwalkan`.
3. Tunjukkan pagination di bawah tabel. Filter tetap terbawa saat pindah halaman.

## 8. Jadwal Konten dan Tandai Tayang

1. Klik menu **Jadwal**. Post yang dijadwalkan tampil urut dari tanggal terdekat.
2. Jelaskan bahwa tabel ini diambil dari `GET /api/posts?status=dijadwalkan&urut=terdekat`.
3. Klik **Tandai Tayang** pada *Teaser promo akhir tahun*.
4. **Hasil:** post hilang dari Jadwal. Di halaman detailnya, status menjadi **Tayang**, *Tanggal Ditayangkan* terisi hari ini, dan tombol Edit hilang.
5. Buka **Dashboard**. Angka *Post Dijadwalkan* berkurang satu dan *Post Sudah Tayang* bertambah satu.

## 9. Relasi di Halaman Detail Klien

1. Buka **Klien → Samudra Outdoor**.
2. Campaign *Promo Libur Akhir Tahun* tampil di tabel beserta PIC dan jumlah post-nya.
3. Coba hapus klien ini dari daftar klien. Aplikasi menolak karena klien masih punya campaign.

## 10. Hak Akses Creator

1. **Logout**, lalu login sebagai `creator1@kontenku.test` / `password`.
2. Menu **Platform** tidak muncul di navbar.
3. Ketik `http://127.0.0.1:8000/platforms` di address bar. Muncul halaman **403**.

## 11. Profil dan Ganti Password

1. Klik nama akun di pojok kanan navbar.
2. Isi password lama yang salah. Muncul *"Password lama salah."*
3. Isi dengan benar, lalu simpan. Muncul *"Password berhasil diubah."*

## 12. REST API di Postman

| No | Request | Yang ditunjukkan |
|---|---|---|
| 1 | `GET /posts` | Response berisi `data`, `links`, dan `meta`; platform, campaign, dan klien ter-nested |
| 2 | `GET /posts?status=dijadwalkan&urut=terdekat` | Hasil terfilter, sama dengan halaman Jadwal |
| 3 | `POST /posts` dengan body `{}` | Status **422** dan pesan error per field |
| 4 | `POST /posts` dengan body lengkap | Status **201** |
| 5 | `PUT /posts/{id}/tayang` | `status` menjadi `tayang`, `tanggal_ditayangkan` terisi |
| 6 | `PUT /posts/{id}/tayang` sekali lagi | Status **409** |
| 7 | `GET /posts/999` | Status **404** |
| 8 | `GET /clients` | Daftar klien beserta `jumlah_campaign` |
| 9 | `POST /campaigns` | Status **201**, `status` terbaca `draft` |
| 10 | `GET /stats` | Angka sama dengan kartu di Dashboard |

Contoh body `POST /posts`:

```json
{
    "campaign_id": 1,
    "platform_id": 1,
    "judul": "Pengumuman giveaway akhir tahun",
    "caption": "Ikuti giveaway kami dan menangkan hadiahnya!",
    "format": "feed",
    "tanggal_tayang": "2026-12-05",
    "status": "dijadwalkan"
}
```

Contoh body `POST /campaigns` (`user_id` wajib dikirim karena API tidak memakai session login):

```json
{
    "client_id": 1,
    "user_id": 2,
    "nama_campaign": "Harbolnas 12.12",
    "tanggal_mulai": "2026-12-01",
    "tanggal_selesai": "2026-12-12"
}
```

## 13. (Opsional) Saat Server API Mati

1. Matikan server port `8011` dengan Ctrl+C.
2. Refresh **Dashboard** dan **Jadwal**. Kedua halaman tetap tampil dengan pemberitahuan, tanpa error 500.
3. Nyalakan lagi server port `8011`.
