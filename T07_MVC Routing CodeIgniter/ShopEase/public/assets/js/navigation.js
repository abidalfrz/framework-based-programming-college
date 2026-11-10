(() => {
  const button = document.querySelector('.menu-toggle');
  const menu = document.getElementById('main-menu');
  if (!button || !menu) return;
  document.documentElement.dataset.menuReady = 'true';
  const setOpen = (open) => {
    button.setAttribute('aria-expanded', String(open));
    menu.classList.toggle('is-open', open);
  };
  button.addEventListener('click', () => setOpen(button.getAttribute('aria-expanded') !== 'true'));
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
      setOpen(false);
      button.focus();
    }
  });
  menu.addEventListener('click', (event) => { if (event.target.closest('a')) setOpen(false); });
})();
