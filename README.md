# CADS Agile Software Development

## Deskripsi Project

Project ini adalah aplikasi sederhana berbasis PHP untuk manajemen task dengan pendekatan Agile (Kanban).
Task dapat dikelola dengan status:

- To Do
- Doing (in-progress)
- Done

Aplikasi menampilkan board seperti Trello, mendukung CRUD task, dan menggunakan version control serta pipeline CI untuk menjaga kualitas kode.

## Tujuan

- Menerapkan konsep Agile secara sederhana pada project PHP.
- Menunjukkan alur kerja Git Flow dari feature branch sampai merge.
- Menyediakan automation test dasar melalui GitHub Actions.

## Fitur Utama

- Create task
- Read task
- Update task
- Delete task
- Kanban board (To Do, Doing, Done)
- Drag and drop card antar status

## Struktur File Inti

- index.php: Tampilan board Kanban.
- create_frontend.php / create_backend.php: Form dan proses Create.
- read.php: Endpoint Read data task (JSON).
- update_frontend.php / update_backend.php: Form dan proses Update.
- delete_frontend.php / delete_backend.php: Form dan proses Delete.
- task_service.php: Logic bisnis CRUD task.
- storage.php: Logic baca/tulis penyimpanan.
- tasks.json: Penyimpanan data task.
- test.php: Automated test sederhana.

## Tech Stack

- PHP 8+
- JSON file storage (tanpa database)
- Git + GitHub
- GitHub Actions (CI)

## Pembagian Jobdesk Tim

- Create: Cindy
- Read: Akeyla
- Update: Desta
- Delete: Salwa

## Version Control dan Branching

Strategi branch yang digunakan:

- main: branch stabil/produksi
- develop: branch integrasi pengembangan
- feature/<nama-fitur>: branch pengerjaan fitur

Contoh branch fitur:

- feature/create
- feature/read
- feature/update
- feature/delete

## Git Flow Sampai Merge

1. Update branch develop
2. Buat branch feature dari develop
3. Implementasi fitur dan commit bertahap
4. Push branch feature ke remote
5. Buat Pull Request ke develop
6. Jalankan pipeline CI otomatis
7. Review dan perbaikan jika ada feedback
8. Merge PR ke develop
9. (Opsional rilis) PR dari develop ke main

Contoh command singkat:

```bash
git checkout develop
git pull origin develop
git checkout -b feature/read
git add .
git commit -m "feat: implement read task"
git push -u origin feature/read
```

## Pipeline CI (Automation Test)

Pipeline berada di `.github/workflows/php-ci.yml` dengan proses:

1. Trigger saat push dan pull request ke branch main/master/develop
2. Setup PHP 8.2
3. Lint seluruh file PHP (`php -l`)
4. Menjalankan test otomatis lewat `php test.php`

Pipeline ini memastikan kode minimal lolos syntax check dan test dasar sebelum merge.

## Tools Agile yang Digunakan

- Kanban board di aplikasi (To Do, Doing, Done)
- GitHub Pull Request untuk kolaborasi dan review
- GitHub Actions untuk CI automation

## Cara Menjalankan Project

1. Clone repository
2. Jalankan di web server lokal (contoh: Laragon)
3. Buka halaman utama:

```text
http://localhost/CADS_AgileSoftwareDevelopment/index.php
```

## Cara Menjalankan Test

Jalankan perintah berikut di root project:

```bash
php test.php
```

Jika semua lulus, output menampilkan jumlah test passed dan exit code 0.

## Demo Singkat untuk Presentasi

1. Buka board dan tunjukkan kolom To Do, Doing, Done.
2. Tambah task baru (Create).
3. Tampilkan data task (Read).
4. Ubah task/status (Update), termasuk drag and drop.
5. Hapus task (Delete).
6. Tunjukkan riwayat branch/PR/merge di GitHub.
7. Tunjukkan status pipeline CI pada PR sebelum merge.

## Catatan

Project ini dibuat untuk Agile Software Development dengan implementasi PHP sederhana.