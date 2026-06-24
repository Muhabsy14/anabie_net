// Toggle sidebar untuk tampilan mobile (RWD)
document.addEventListener('DOMContentLoaded', function () {
  var btn = document.querySelector('.menu-btn');
  var sidebar = document.querySelector('.sidebar');
  var backdrop = document.querySelector('.backdrop');

  function toggle() {
    if (!sidebar) return;
    sidebar.classList.toggle('open');
    document.body.classList.toggle('nav-open');
  }
  function close() {
    if (!sidebar) return;
    sidebar.classList.remove('open');
    document.body.classList.remove('nav-open');
  }

  if (btn) btn.addEventListener('click', toggle);
  if (backdrop) backdrop.addEventListener('click', close);

  // Konfirmasi tombol hapus
  document.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (!confirm(f.getAttribute('data-confirm'))) e.preventDefault();
    });
  });
});
