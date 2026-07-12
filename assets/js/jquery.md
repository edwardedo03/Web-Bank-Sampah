1. DOM Selection & Manipulation

• `$()` : Memilih elemen (ID, Class, Tag).

• `.text()` / `.html()` : Mengubah isi teks atau struktur HTML.

• `.val()` : Mengambil/mengubah nilai input form.

• `.append()` / `.prepend()` : Menyisipkan konten di dalam elemen.

2. CSS & Atribut

• `.addClass()` / `.removeClass()` / `.toggleClass()` : Manipulasi class CSS.

• `.css()` : Mengubah style langsung.

• `.attr()` : Mengambil/mengubah atribut (seperti `src`, `href`).

3. Event Handling (Interaksi)

• `$(document).ready()` : Wajib, memastikan HTML kelar di-load.

• `.click()` / `.hover()` / `.keyup()` / `.submit()` : Menangani aksi user.

• `.on()` : Menempelkan event untuk elemen dinamis.

4. Effects & Animation

• `.hide()` / `.show()` / `.toggle()` : Muncul/sembunyi.

• `.fadeIn()` / `.fadeOut()` : Efek memudar.

• `.slideUp()` / `.slideDown()` : Efek menggulung.

• `.animate()` : Animasi custom properti CSS.

5. AJAX (Ambil Data API)

• `$.ajax()` : Request asinkronus lengkap ke server/API.

• `$.get()` / `$.post()` : Shortcut request data tanpa reload halaman.

6. DOM Traversal

• `.parent()` / `.children()` / `.siblings()` : Navigasi ke elemen atas, bawah, atau sejajar.

Catatan: jQuery Core tidak punya komponen UI siap pakai (seperti carousel/modal) dan tidak ada auto data-binding.
