# Dokumentasi REST API KontenKu

Base URL: `http://127.0.0.1:8000/api`

Setiap request menyertakan header `Accept: application/json`. Untuk `POST` dan `PUT` yang membawa body, tambahkan `Content-Type: application/json`. Semua endpoint saat ini publik (belum memakai autentikasi token).

Contoh response di bawah diambil dari pemanggilan nyata ke aplikasi dengan data hasil `php artisan migrate:fresh --seed`. Data seeder dibuat acak, jadi isi di database lain akan berbeda.

## Ringkasan Endpoint

| No | Method | URL | Body | Status Code |
|---|---|---|---|---|
| 1 | `GET` | [`/api/posts`](#1-get-apiposts) | - | 200 |
| 2 | `GET` | [`/api/posts/{id}`](#2-get-apipostsid) | - | 200, 404 |
| 3 | `POST` | [`/api/posts`](#3-post-apiposts) | `campaign_id`, `platform_id`, `judul`, `caption`, `format`, `tanggal_tayang`, `status` (`draft`/`dijadwalkan`) | 201, 422 |
| 4 | `PUT` | [`/api/posts/{id}`](#4-put-apipostsid) | Sama seperti `POST /api/posts` | 200, 404, 409, 422 |
| 5 | `PUT` | [`/api/posts/{id}/tayang`](#5-put-apipostsidtayang) | - | 200, 404, 409 |
| 6 | `DELETE` | [`/api/posts/{id}`](#6-delete-apipostsid) | - | 200, 404 |
| 7 | `GET` | [`/api/clients`](#7-get-apiclients) | - | 200 |
| 8 | `GET` | [`/api/clients/{id}`](#8-get-apiclientsid) | - | 200, 404 |
| 9 | `GET` | [`/api/campaigns`](#9-get-apicampaigns) | - | 200 |
| 10 | `GET` | [`/api/campaigns/{id}`](#10-get-apicampaignsid) | - | 200, 404 |
| 11 | `POST` | [`/api/campaigns`](#11-post-apicampaigns) | `client_id`, `user_id`, `nama_campaign`, `tujuan` (opsional), `tanggal_mulai`, `tanggal_selesai` | 201, 422 |
| 12 | `GET` | [`/api/stats`](#12-get-apistats) | - | 200 |

## Arti Status Code

| Kode | Arti |
|---|---|
| `200 OK` | Berhasil membaca, memperbarui, atau menghapus |
| `201 Created` | Berhasil membuat data baru |
| `404 Not Found` | Data dengan id tersebut tidak ada |
| `409 Conflict` | Aksi tidak sesuai kondisi data (misalnya post sudah tayang) |
| `422 Unprocessable Entity` | Data gagal validasi; detail per field ada di `errors` |

## Detail Endpoint

### 1. GET /api/posts

Daftar post, 10 per halaman. Query opsional: `judul` (pencarian sebagian), `status` (`draft`/`dijadwalkan`/`tayang`), `platform_id`, `urut=terdekat` (tanggal tayang terdekat dulu), `page`.

**Filter status + urut terdekat** - `GET /api/posts?status=dijadwalkan&urut=terdekat` → `200 OK`

Response:

```json
{
    "data": [
        {
            "id": 5,
            "judul": "Kolaborasi dengan kreator",
            "caption": "Kolaborasi dengan kreator dari Tani Makmur. Jangan sampai ketinggalan, cek selengkapnya di profil kami!",
            "format": "feed",
            "tanggal_tayang": "2026-11-21",
            "tanggal_ditayangkan": null,
            "status": "dijadwalkan",
            "platform": {
                "id": 2,
                "nama_platform": "TikTok"
            },
            "campaign": {
                "id": 1,
                "nama_campaign": "Promo Akhir Tahun Tani Makmur"
            },
            "klien": {
                "id": 7,
                "nama_brand": "Tani Makmur"
            }
        },
        {
            "id": 2,
            "judul": "Kolaborasi dengan kreator",
            "caption": "Kolaborasi dengan kreator dari Glow Skincare. Jangan sampai ketinggalan, cek selengkapnya di profil kami!",
            "format": "reels",
            "tanggal_tayang": "2026-11-24",
            "tanggal_ditayangkan": null,
            "status": "dijadwalkan",
            "platform": {
                "id": 3,
                "nama_platform": "LinkedIn"
            },
            "campaign": {
                "id": 3,
                "nama_campaign": "Kampanye Ramadan Glow Skincare"
            },
            "klien": {
                "id": 4,
                "nama_brand": "Glow Skincare"
            }
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/posts?page=1",
        "last": "http://127.0.0.1:8000/api/posts?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": "(dihilangkan agar ringkas)",
        "path": "http://127.0.0.1:8000/api/posts",
        "per_page": 10,
        "to": 2,
        "total": 2
    }
}
```


**Filter judul** - `GET /api/posts?judul=giveaway` → `200 OK`

Response:

```json
{
    "data": [
        {
            "id": 12,
            "judul": "Pengumuman giveaway",
            "caption": "Pengumuman giveaway dari Tani Makmur. Jangan sampai ketinggalan, cek selengkapnya di profil kami!",
            "format": "artikel",
            "tanggal_tayang": "2026-11-09",
            "tanggal_ditayangkan": "2026-11-09",
            "status": "tayang",
            "platform": {
                "id": 5,
                "nama_platform": "YouTube"
            },
            "campaign": {
                "id": 1,
                "nama_campaign": "Promo Akhir Tahun Tani Makmur"
            },
            "klien": {
                "id": 7,
                "nama_brand": "Tani Makmur"
            }
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/posts?page=1",
        "last": "http://127.0.0.1:8000/api/posts?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": "(dihilangkan agar ringkas)",
        "path": "http://127.0.0.1:8000/api/posts",
        "per_page": 10,
        "to": 1,
        "total": 1
    }
}
```


### 2. GET /api/posts/{id}

Detail satu post beserta platform, campaign, dan kliennya.

**Sukses** - `GET /api/posts/1` → `200 OK`

Response:

```json
{
    "data": {
        "id": 1,
        "judul": "Testimoni pelanggan",
        "caption": "Testimoni pelanggan dari Glow Skincare. Jangan sampai ketinggalan, cek selengkapnya di profil kami!",
        "format": "artikel",
        "tanggal_tayang": "2026-11-19",
        "tanggal_ditayangkan": null,
        "status": "draft",
        "platform": {
            "id": 2,
            "nama_platform": "TikTok"
        },
        "campaign": {
            "id": 3,
            "nama_campaign": "Kampanye Ramadan Glow Skincare"
        },
        "klien": {
            "id": 4,
            "nama_brand": "Glow Skincare"
        }
    }
}
```


**Id tidak ada** - `GET /api/posts/999` → `404 Not Found`

Response:

```json
{
    "message": "No query results for model [App\\Models\\Post] 999"
}
```


### 3. POST /api/posts

Tambah post. Validasinya memakai `StorePostRequest` yang sama dengan form web.

**Sukses** - `POST /api/posts` → `201 Created`

Body:

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

Response:

```json
{
    "data": {
        "id": 31,
        "judul": "Pengumuman giveaway akhir tahun",
        "caption": "Ikuti giveaway kami dan menangkan hadiahnya!",
        "format": "feed",
        "tanggal_tayang": "2026-12-05",
        "tanggal_ditayangkan": null,
        "status": "dijadwalkan",
        "platform": {
            "id": 1,
            "nama_platform": "Instagram"
        },
        "campaign": {
            "id": 1,
            "nama_campaign": "Promo Akhir Tahun Tani Makmur"
        },
        "klien": {
            "id": 7,
            "nama_brand": "Tani Makmur"
        }
    }
}
```


**Body kosong** - `POST /api/posts` → `422 Unprocessable Entity`

Body:

```json
{}
```

Response:

```json
{
    "message": "Campaign wajib diisi. (and 6 more errors)",
    "errors": {
        "campaign_id": [
            "Campaign wajib diisi."
        ],
        "platform_id": [
            "Platform wajib diisi."
        ],
        "judul": [
            "Judul wajib diisi."
        ],
        "caption": [
            "Caption wajib diisi."
        ],
        "format": [
            "Format wajib diisi."
        ],
        "tanggal_tayang": [
            "Tanggal tayang wajib diisi."
        ],
        "status": [
            "Status wajib diisi."
        ]
    }
}
```


### 4. PUT /api/posts/{id}

Perbarui post. Post yang sudah tayang tidak bisa diubah (409).

**Sukses** - `PUT /api/posts/31` → `200 OK`

Body:

```json
{
    "campaign_id": 1,
    "platform_id": 2,
    "judul": "Pengumuman giveaway (revisi)",
    "caption": "Ikuti giveaway kami dan menangkan hadiahnya!",
    "format": "reels",
    "tanggal_tayang": "2026-12-06",
    "status": "dijadwalkan"
}
```

Response:

```json
{
    "data": {
        "id": 31,
        "judul": "Pengumuman giveaway (revisi)",
        "caption": "Ikuti giveaway kami dan menangkan hadiahnya!",
        "format": "reels",
        "tanggal_tayang": "2026-12-06",
        "tanggal_ditayangkan": null,
        "status": "dijadwalkan",
        "platform": {
            "id": 2,
            "nama_platform": "TikTok"
        },
        "campaign": {
            "id": 1,
            "nama_campaign": "Promo Akhir Tahun Tani Makmur"
        },
        "klien": {
            "id": 7,
            "nama_brand": "Tani Makmur"
        }
    }
}
```


### 5. PUT /api/posts/{id}/tayang

Tandai post sudah tayang: `status` jadi `tayang` dan `tanggal_ditayangkan` diisi tanggal hari ini. Hanya untuk post berstatus `dijadwalkan`.

**Sukses** - `PUT /api/posts/31/tayang` → `200 OK`

Response:

```json
{
    "data": {
        "id": 31,
        "judul": "Pengumuman giveaway (revisi)",
        "caption": "Ikuti giveaway kami dan menangkan hadiahnya!",
        "format": "reels",
        "tanggal_tayang": "2026-12-06",
        "tanggal_ditayangkan": "2026-11-16",
        "status": "tayang",
        "platform": {
            "id": 2,
            "nama_platform": "TikTok"
        },
        "campaign": {
            "id": 1,
            "nama_campaign": "Promo Akhir Tahun Tani Makmur"
        },
        "klien": {
            "id": 7,
            "nama_brand": "Tani Makmur"
        }
    }
}
```


**Sudah tayang sebelumnya** - `PUT /api/posts/31/tayang` → `409 Conflict`

Response:

```json
{
    "message": "Hanya post berstatus \"dijadwalkan\" yang bisa ditandai tayang."
}
```


### 6. DELETE /api/posts/{id}

Hapus post.

**Sukses** - `DELETE /api/posts/31` → `200 OK`

Response:

```json
{
    "message": "Post berhasil dihapus."
}
```


**Id tidak ada** - `DELETE /api/posts/999` → `404 Not Found`

Response:

```json
{
    "message": "No query results for model [App\\Models\\Post] 999"
}
```


### 7. GET /api/clients

Daftar klien beserta jumlah campaign-nya, 10 per halaman.

**Sukses** - `GET /api/clients` → `200 OK`

Response (bagian `data` dipotong: ditampilkan 2 dari 10 item di halaman ini):

```json
{
    "data": [
        {
            "id": 11,
            "nama_brand": "Batik Larasati",
            "industri": "Fashion",
            "nama_kontak": "Wirda Agustina",
            "email": "halo@batiklarasati.co.id",
            "nomor_telepon": "088826117634",
            "status": "nonaktif",
            "jumlah_campaign": 1
        },
        {
            "id": 1,
            "nama_brand": "Bengkel Maju Jaya",
            "industri": "Otomotif",
            "nama_kontak": "Teguh Siregar",
            "email": "halo@bengkelmajujaya.co.id",
            "nomor_telepon": "089739537411",
            "status": "nonaktif",
            "jumlah_campaign": 0
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/clients?page=1",
        "last": "http://127.0.0.1:8000/api/clients?page=2",
        "prev": null,
        "next": "http://127.0.0.1:8000/api/clients?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 2,
        "links": "(dihilangkan agar ringkas)",
        "path": "http://127.0.0.1:8000/api/clients",
        "per_page": 10,
        "to": 10,
        "total": 12
    }
}
```


### 8. GET /api/clients/{id}

Detail satu klien.

**Sukses** - `GET /api/clients/1` → `200 OK`

Response:

```json
{
    "data": {
        "id": 1,
        "nama_brand": "Bengkel Maju Jaya",
        "industri": "Otomotif",
        "nama_kontak": "Teguh Siregar",
        "email": "halo@bengkelmajujaya.co.id",
        "nomor_telepon": "089739537411",
        "status": "nonaktif",
        "jumlah_campaign": 0
    }
}
```


**Id tidak ada** - `GET /api/clients/999` → `404 Not Found`

Response:

```json
{
    "message": "No query results for model [App\\Models\\Client] 999"
}
```


### 9. GET /api/campaigns

Daftar campaign beserta klien, PIC, dan post-nya. Query opsional: `status` (`draft`/`berjalan`/`selesai`), `page`.

**Sukses** - `GET /api/campaigns` → `200 OK`

Response (bagian `data` dipotong: ditampilkan 1 dari 8 item di halaman ini):

```json
{
    "data": [
        {
            "id": 7,
            "nama_campaign": "Testimoni Pelanggan Travel Jelajah Negeri",
            "tujuan": "Meningkatkan interaksi dan penjualan Travel Jelajah Negeri lewat media sosial.",
            "tanggal_mulai": "2026-11-12",
            "tanggal_selesai": "2026-12-08",
            "status": "berjalan",
            "klien": {
                "id": 12,
                "nama_brand": "Travel Jelajah Negeri"
            },
            "pic": {
                "id": 1,
                "name": "Prayogo Adhi Satrio"
            },
            "posts": []
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/campaigns?page=1",
        "last": "http://127.0.0.1:8000/api/campaigns?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": "(dihilangkan agar ringkas)",
        "path": "http://127.0.0.1:8000/api/campaigns",
        "per_page": 10,
        "to": 8,
        "total": 8
    }
}
```


### 10. GET /api/campaigns/{id}

Detail satu campaign.

**Sukses** - `GET /api/campaigns/1` → `200 OK`

Response:

```json
{
    "data": {
        "id": 1,
        "nama_campaign": "Promo Akhir Tahun Tani Makmur",
        "tujuan": "Meningkatkan interaksi dan penjualan Tani Makmur lewat media sosial.",
        "tanggal_mulai": "2026-10-20",
        "tanggal_selesai": "2026-12-03",
        "status": "berjalan",
        "klien": {
            "id": 7,
            "nama_brand": "Tani Makmur"
        },
        "pic": {
            "id": 3,
            "name": "Ahmad Fauzi"
        },
        "posts": [
            {
                "id": 5,
                "judul": "Kolaborasi dengan kreator",
                "platform": "TikTok",
                "tanggal_tayang": "2026-11-21",
                "status": "dijadwalkan"
            },
            {
                "id": 12,
                "judul": "Pengumuman giveaway",
                "platform": "YouTube",
                "tanggal_tayang": "2026-11-09",
                "status": "tayang"
            },
            {
                "id": 17,
                "judul": "Ucapan hari besar",
                "platform": "LinkedIn",
                "tanggal_tayang": "2026-10-29",
                "status": "tayang"
            },
            {
                "id": 18,
                "judul": "Tanya jawab dengan followers",
                "platform": "LinkedIn",
                "tanggal_tayang": "2026-10-30",
                "status": "tayang"
            },
            {
                "id": 27,
                "judul": "Rekap kegiatan bulan ini",
                "platform": "YouTube",
                "tanggal_tayang": "2026-11-02",
                "status": "tayang"
            },
            {
                "id": 28,
                "judul": "Tips penggunaan produk",
                "platform": "LinkedIn",
                "tanggal_tayang": "2026-10-27",
                "status": "tayang"
            }
        ]
    }
}
```


### 11. POST /api/campaigns

Buat campaign. `user_id` (PIC) wajib dikirim karena API tidak memakai session login.

**Sukses** - `POST /api/campaigns` → `201 Created`

Body:

```json
{
    "client_id": 1,
    "user_id": 2,
    "nama_campaign": "Harbolnas 12.12",
    "tujuan": "Mendorong penjualan saat Harbolnas.",
    "tanggal_mulai": "2026-12-01",
    "tanggal_selesai": "2026-12-12"
}
```

Response:

```json
{
    "data": {
        "id": 9,
        "nama_campaign": "Harbolnas 12.12",
        "tujuan": "Mendorong penjualan saat Harbolnas.",
        "tanggal_mulai": "2026-12-01",
        "tanggal_selesai": "2026-12-12",
        "status": "draft",
        "klien": {
            "id": 1,
            "nama_brand": "Bengkel Maju Jaya"
        },
        "pic": {
            "id": 2,
            "name": "Siti Rahmawati"
        },
        "posts": []
    }
}
```


**Data tidak valid** - `POST /api/campaigns` → `422 Unprocessable Entity`

Body:

```json
{
    "client_id": 1,
    "tanggal_mulai": "2026-12-10",
    "tanggal_selesai": "2026-12-01"
}
```

Response:

```json
{
    "message": "PIC (user_id) wajib diisi. (and 2 more errors)",
    "errors": {
        "user_id": [
            "PIC (user_id) wajib diisi."
        ],
        "nama_campaign": [
            "Nama campaign wajib diisi."
        ],
        "tanggal_selesai": [
            "Tanggal selesai tidak boleh sebelum tanggal mulai."
        ]
    }
}
```


### 12. GET /api/stats

Ringkasan angka untuk Dashboard.

**Sukses** - `GET /api/stats` → `200 OK`

Response:

```json
{
    "total_klien": 12,
    "campaign_berjalan": 4,
    "post_dijadwalkan": 2,
    "post_tayang": 26
}
```

