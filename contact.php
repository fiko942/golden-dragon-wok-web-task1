<?php
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$csrfToken = $_SESSION['csrf_token'];
$captchaA = random_int(1, 9);
$captchaB = random_int(1, 9);
$_SESSION['captcha_answer'] = $captchaA + $captchaB;
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak &amp; Karier | Golden Dragon Wok</title>
  <meta name="description" content="Hubungi Golden Dragon Wok untuk reservasi, kemitraan, atau ajukan lamaran kerja secara online.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Noto+Serif:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/custom.css">
  <script>
    window.tailwind = {
      config: {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              primary: '#9A1111',
              accent: '#C8A951',
              base: '#FAFAFA'
            },
            fontFamily: {
              heading: ['\'Noto Serif\'', 'serif'],
              body: ['Inter', 'system-ui', 'sans-serif']
            }
          }
        }
      }
    };
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-body bg-base text-neutral-900 dark:bg-neutral-900 dark:text-neutral-100">
  <header data-header class="sticky top-0 z-50 transition-all bg-white/90 dark:bg-neutral-900/90">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5 lg:px-8">
      <a href="index.html#home" class="text-lg font-heading tracking-widest text-[#C8A951]" aria-label="Golden Dragon Wok beranda">Golden Dragon Wok</a>
      <nav aria-label="Navigasi utama" class="hidden items-center gap-8 text-sm font-medium md:flex">
        <a href="index.html#home" class="hover:text-[#C8A951] focus-visible:outline focus-visible:outline-[#C8A951]">Home</a>
        <a href="index.html#about" class="hover:text-[#C8A951] focus-visible:outline focus-visible:outline-[#C8A951]">Tentang</a>
        <a href="menu.html" class="hover:text-[#C8A951] focus-visible:outline focus-visible:outline-[#C8A951]">Menu</a>
        <a href="team.html" class="hover:text-[#C8A951] focus-visible:outline focus-visible:outline-[#C8A951]">Tim</a>
        <a href="contact.php" class="text-[#C8A951] focus-visible:outline focus-visible:outline-[#C8A951]">Contact &amp; Jobs</a>
      </nav>
      <div class="flex items-center gap-4">
        <a href="https://wa.me/6285733666741?text=Halo%20Golden%20Dragon%20Wok" class="inline-flex items-center rounded-full border border-[#C8A951] px-4 py-2 text-sm font-semibold text-[#9A1111] transition hover:bg-[#C8A951] hover:text-neutral-900 focus-visible:outline focus-visible:outline-[#C8A951]">Chat WhatsApp</a>
        <button type="button" data-theme-toggle class="inline-flex items-center justify-center rounded-full border border-neutral-200 p-2 text-neutral-700 transition hover:border-[#C8A951] hover:text-[#C8A951] focus-visible:outline focus-visible:outline-[#C8A951]" aria-pressed="false">
          <img src="/assets/icons/sun.svg" data-icon="sun" class="toggle-icon" alt="Ikon matahari">
          <img src="/assets/icons/moon.svg" data-icon="moon" class="toggle-icon hidden" alt="Ikon bulan">
        </button>
      </div>
    </div>
  </header>

  <main class="bg-base py-16 dark:bg-neutral-900" id="contact">
    <section class="mx-auto max-w-6xl px-6 lg:px-8">
      <header class="mb-12 space-y-4 text-center">
        <p class="text-sm uppercase tracking-[0.3em] text-[#C8A951]">Hubungi Kami</p>
        <h1 class="font-heading text-4xl font-semibold">Contact &amp; Careers</h1>
        <p class="text-neutral-600 dark:text-neutral-300">Reservasi, kerja sama, atau peluang karier—kami siap membantu setiap langkah Anda.</p>
      </header>

      <div class="grid gap-12 lg:grid-cols-2">
        <article class="space-y-6 rounded-3xl border border-neutral-200/70 bg-white/80 p-8 shadow-gdw-soft dark:border-neutral-700 dark:bg-neutral-800/80">
          <h2 class="font-heading text-2xl font-semibold text-neutral-900 dark:text-neutral-100">Informasi Kontak</h2>
          <p class="text-neutral-600 dark:text-neutral-300">Jl. Wijaya Kusuma No. 88, Malang 65123<br>Senin–Minggu • 10:00 – 22:00 WIB</p>
          <div class="grid gap-4 text-sm text-neutral-700 dark:text-neutral-300">
            <p><strong>Email Reservasi:</strong> <a href="mailto:reservations@gdwok.com" class="text-[#9A1111] hover:text-[#C8A951]">reservations@gdwok.com</a></p>
            <p><strong>Email HR:</strong> <a href="mailto:hr@gdwok.com" class="text-[#9A1111] hover:text-[#C8A951]">hr@gdwok.com</a></p>
            <p><strong>WhatsApp:</strong> <a href="https://wa.me/6281234567890?text=Halo%20Golden%20Dragon%20Wok" class="text-[#9A1111] hover:text-[#C8A951]">+62 812-3456-7890</a></p>
          </div>
          <div class="overflow-hidden rounded-2xl shadow-lg">
            <iframe title="Lokasi Golden Dragon Wok" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.6754031774033!2d112.63263299999999!3d-7.1622503999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6295942bc4fb5%3A0x2b2a8886a4f38ebb!2sMalang!5e0!3m2!1sid!2sid!4v1700000000000" width="100%" height="240" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
          <div>
            <h3 class="font-semibold text-neutral-900 dark:text-neutral-100">Reservasi</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">Reservasi dilakukan melalui WhatsApp atau email dengan menyertakan tanggal, jam, dan jumlah tamu. Konfirmasi akan kami kirimkan dalam 1 jam kerja.</p>
          </div>
        </article>

        <section class="space-y-6 rounded-3xl border border-neutral-200/70 bg-white/80 p-8 shadow-gdw-soft dark:border-neutral-700 dark:bg-neutral-800/80">
          <div class="space-y-2">
            <h2 class="font-heading text-2xl font-semibold text-neutral-900 dark:text-neutral-100">Apply for a Role</h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">Isi formulir berikut untuk bergabung dengan Golden Dragon Wok. Kami mencari individu yang berorientasi layanan dan mencintai dunia kuliner.</p>
            <p class="text-xs font-medium uppercase tracking-widest text-[#9A1111]">Catatan: gunakan email Gmail aktif untuk menerima ringkasan aplikasi otomatis.</p>
          </div>

          <div data-alert class="hidden rounded-lg border bg-green-100 p-4 text-sm text-green-900" role="status" aria-live="polite">
            <span data-alert-message>Pesan akan tampil di sini.</span>
          </div>

          <form id="job-form" class="space-y-5" method="post" action="/php/applyJob.php" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
            <div data-field-wrap class="space-y-1">
              <label for="position" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Posisi yang Dilamar</label>
              <select id="position" name="position" required data-validate="" class="w-full rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm focus:border-[#C8A951] focus:outline-none focus:ring-2 focus:ring-[#C8A951]/40 dark:bg-neutral-900 dark:text-neutral-100">
                <option value="">Pilih posisi</option>
                <option value="Server">Server</option>
                <option value="Cook">Cook</option>
                <option value="Barista">Barista</option>
                <option value="Cashier">Cashier</option>
                <option value="Dishwasher">Dishwasher</option>
              </select>
              <p data-hint class="text-xs text-neutral-500"></p>
            </div>

            <div data-field-wrap class="space-y-1">
              <label for="fullname" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Nama Lengkap</label>
              <input id="fullname" name="fullname" type="text" required data-validate="" class="w-full rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm focus:border-[#C8A951] focus:outline-none focus:ring-2 focus:ring-[#C8A951]/40 dark:bg-neutral-900 dark:text-neutral-100" placeholder="Nama sesuai KTP">
              <p data-hint class="text-xs text-neutral-500"></p>
            </div>

            <div data-field-wrap class="space-y-1">
              <label for="email" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Email</label>
              <input id="email" name="email" type="email" required data-validate="email" class="w-full rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm focus:border-[#C8A951] focus:outline-none focus:ring-2 focus:ring-[#C8A951]/40 dark:bg-neutral-900 dark:text-neutral-100" placeholder="nama@gmail.com">
              <p data-hint class="text-xs text-neutral-500">Gunakan alamat Gmail aktif.</p>
            </div>

            <div data-field-wrap class="space-y-1">
              <label for="phone" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">No HP / WhatsApp</label>
              <input id="phone" name="phone" type="tel" required data-validate="phone" class="w-full rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm focus:border-[#C8A951] focus:outline-none focus:ring-2 focus:ring-[#C8A951]/40 dark:bg-neutral-900 dark:text-neutral-100" placeholder="Contoh: +628123456789">
              <p data-hint class="text-xs text-neutral-500"></p>
            </div>

            <div data-field-wrap class="space-y-1">
              <label for="experience" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Pengalaman Kerja</label>
              <textarea id="experience" name="experience" rows="4" minlength="20" required data-validate="minlength" class="w-full rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm focus:border-[#C8A951] focus:outline-none focus:ring-2 focus:ring-[#C8A951]/40 dark:bg-neutral-900 dark:text-neutral-100" placeholder="Ceritakan pengalaman relevan minimal 20 karakter"></textarea>
              <p data-hint class="text-xs text-neutral-500"></p>
            </div>

            <div data-field-wrap class="space-y-1">
              <label for="cv" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Upload CV (opsional)</label>
              <input id="cv" name="cv" type="file" accept="application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" data-validate="file" class="w-full rounded-lg border border-dashed border-neutral-300 bg-white px-4 py-2 text-sm focus:border-[#C8A951] focus:outline-none focus:ring-2 focus:ring-[#C8A951]/40 dark:bg-neutral-900 dark:text-neutral-100">
              <p data-hint class="text-xs text-neutral-500">Format PDF atau DOCX, maksimal 2MB.</p>
            </div>

            <div data-field-wrap class="space-y-2">
              <div class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Captcha</div>
              <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-lg bg-neutral-100 px-3 py-2 font-semibold text-neutral-800 dark:bg-neutral-900 dark:text-neutral-100"><?php echo $captchaA; ?> + <?php echo $captchaB; ?> = ?</span>
                <input type="number" name="captcha_answer" required data-captcha-input data-answer="<?php echo $_SESSION['captcha_answer']; ?>" data-validate="" class="w-32 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm focus:border-[#C8A951] focus:outline-none focus:ring-2 focus:ring-[#C8A951]/40 dark:bg-neutral-900 dark:text-neutral-100" aria-label="Jawab captcha">
              </div>
              <p data-hint class="text-xs text-neutral-500"></p>
            </div>

            <div data-field-wrap class="space-y-2">
              <label class="flex items-start gap-3 text-sm text-neutral-700 dark:text-neutral-200">
                <input type="checkbox" name="agree" value="1" required data-validate="checkbox" class="mt-1 h-4 w-4 rounded border-neutral-300 text-[#9A1111] focus:ring-[#C8A951]">
                <span>Saya menyetujui penggunaan data pribadi saya untuk proses rekrutmen Golden Dragon Wok.</span>
              </label>
              <p data-hint class="text-xs text-neutral-500"></p>
            </div>

            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#9A1111] px-6 py-3 font-semibold text-neutral-100 transition hover:bg-[#b31a1a] focus-visible:outline focus-visible:outline-[#C8A951]">
              <span>Submit Application</span>
              <span data-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-neutral-100 border-t-transparent"></span>
            </button>
          </form>
        </section>
      </div>
    </section>
  </main>

  <footer class="bg-neutral-900 py-12 text-neutral-100 bg-gdw-footer">
    <div class="mx-auto grid max-w-6xl gap-10 px-6 md:grid-cols-4 lg:px-8">
      <div class="space-y-3">
        <h3 class="font-heading text-xl text-[#C8A951]">Golden Dragon Wok</h3>
        <p class="text-sm text-neutral-300">Jl. Wijaya Kusuma No. 88<br>Malang 65123</p>
        <p class="text-sm text-neutral-300">Senin–Minggu • 10:00 – 22:00 WIB</p>
      </div>
      <div class="space-y-2 text-sm">
        <h4 class="font-semibold text-neutral-200">Reservasi</h4>
        <a href="mailto:wijifikoteren@streampeg.com" class="block hover:text-[#C8A951]">wijifikoteren@streampeg.com</a>
        <a href="https://wa.me/6285733666741?text=Halo%20Golden%20Dragon%20Wok" class="block hover:text-[#C8A951]">+62 857-3366-6741</a>
      </div>
      <div class="space-y-2 text-sm">
        <h4 class="font-semibold text-neutral-200">Ikuti Kami</h4>
        <a href="#" class="block hover:text-[#C8A951]">Instagram</a>
        <a href="#" class="block hover:text-[#C8A951]">TikTok</a>
        <a href="#" class="block hover:text-[#C8A951]">Tripadvisor</a>
      </div>
      <div class="space-y-3">
        <h4 class="font-semibold text-neutral-200">Halaman Cepat</h4>
        <a href="index.html#signature" class="block text-sm hover:text-[#C8A951]">Signature Dishes</a>
        <a href="menu.html" class="block text-sm hover:text-[#C8A951]">Menu Lengkap</a>
      </div>
    </div>
    <div class="mt-10 border-t border-white/10 pt-6 text-center text-xs text-neutral-400">© <span data-year></span> Golden Dragon Wok. All rights reserved.</div>
  </footer>

  <button type="button" data-back-to-top class="back-to-top fixed bottom-6 right-6 flex h-12 w-12 items-center justify-center rounded-full bg-[#9A1111] text-white shadow-lg focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-white" aria-label="Kembali ke atas" title="Kembali ke atas">
    ↑
  </button>

  <script src="/js/jquery-3.7.1.min.js"></script>
  <script src="/js/app.js" defer></script>
  <script src="/js/main.jquery.js" defer></script>
</body>
</html>
