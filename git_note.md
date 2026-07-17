Panduan Dasar Git

• `git init`

1. Menambahkan Perubahan (Add)
   Sebelum menyimpan perubahan, kita perlu memasukkannya ke dalam staging area.

• Menambahkan file spesifik: `git add <nama_file>`

• Menambahkan semua perubahan di direktori: `git add .`

2. Menyimpan Perubahan (Commit)
   Setelah file berada di staging area, simpan perubahan tersebut ke dalam riwayat Git dengan pesan yang jelas.

• `git commit -m "Pesan commit yang deskriptif"`

3. Manajemen Branch (Cabang)
   Branch digunakan untuk mengembangkan fitur baru tanpa mengganggu kode utama (`main`/`master`).

• Mengecek Branch:

• Melihat daftar branch lokal: `git branch`

• Melihat semua branch (lokal dan remote): `git branch -a`

• Membuat Branch Baru:

• `git branch <nama_branch_baru>`

• Berpindah ke Branch Lain:

• `git checkout <nama_branch>` (atau `git switch <nama_branch>`)

• Membuat dan Langsung Berpindah ke Branch Baru:

• `git checkout -b <nama_branch_baru>` (atau `git switch -c <nama_branch_baru>`)

4. Alur Kerja ke Repository Remote (GitHub/GitLab)
   • Menghubungkan repository lokal ke remote (jika belum): `git remote add origin <URL_repository>`

• Mengirim perubahan dari branch lokal ke remote: `git push origin <nama_branch>`

5. Deployment (Penyebaran Aplikasi)
   Proses deploy tergantung pada platform yang digunakan:

• Platform Cloud/PaaS (seperti Vercel, Netlify, Render): biasanya otomatis melakukan deploy setiap kali ada `git push` ke branch utama (`main`).

• GitHub Pages: Bisa diatur lewat menu Settings -> Pages pada repository, lalu pilih branch yang ingin di-deploy.

• Server Sendiri (VPS): Biasanya melibatkan proses `git pull` langsung di dalam server untuk memperbarui kode.

Untuk mengecek repository mana yang terhubung dengan folder lokal:

• `git remote -v` (Melihat URL fetch dan push)

• `git remote show origin` (Melihat informasi detail branch remote)

• `git remote set-url origin <URL_baru>` (Mengubah URL repository)

------------------------

Alur Git: Pull di Branch Baru & Jaga Branch Main
Berikut adalah langkah-langkah untuk membuat branch baru dari branch lokal saat ini, menarik (pull) pembaruan di branch baru tersebut, dan menjaga branch `main` tetap bersih hanya untuk kebutuhan merge.

1. Salin Branch Lokal Saat Ini ke Branch Baru
Jika kamu sedang berada di branch lokal yang ingin kamu duplikat (misal dari `main` atau branch kerja saat ini) dan ingin membuat branch baru dari sana:

```

# Membuat dan langsung pindah ke branch baru

git checkout -b nama_branch_baru

```

Sekarang kamu berada di `nama_branch_baru` yang berisi salinan persis dari kode lokal terakhirmu.

2. Perbarui Branch Baru dengan Kode Terbaru dari Repo
Untuk memperbarui branch baru yang baru saja dibuat dengan perubahan terbaru dari branch remote (misalnya mengambil pembaruan dari `main` di repo):

```

git pull origin main

```

Jika ada bentrokan kode antara perubahan lokalmu dan repo, kamu bisa menyelesaikannya (resolve conflict) di branch baru ini tanpa mengganggu branch `main` lokal.

3. Menjaga Branch Main Lokal Hanya untuk Merge
Agar branch `main` lokal kamu tetap bersih dan hanya digunakan untuk kebutuhan menggabungkan (merge) atau memantau repo pusat, gunakan alur ini:

```

# Pindah kembali ke branch main lokal

git checkout main

# Perbarui main lokal agar sama persis dengan remote repo

git pull origin main

# Gabungkan branch baru yang sudah selesai dikerjakan ke main

git merge nama_branch_baru

```