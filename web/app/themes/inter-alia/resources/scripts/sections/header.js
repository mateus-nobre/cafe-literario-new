document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.menu-principal');

  if (!toggle || !nav) return;

  toggle.addEventListener('click', () => {
    const isHidden = nav.classList.contains('hidden');

    if (isHidden) {
      nav.classList.remove('hidden');
      nav.classList.add('block');
      toggle.setAttribute('aria-expanded', 'true');
    } else {
      nav.classList.add('hidden');
      nav.classList.remove('block');
      toggle.setAttribute('aria-expanded', 'false');
    }
  });
});
