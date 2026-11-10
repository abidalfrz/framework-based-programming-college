(() => {
  const carousel = document.querySelector('.featured-carousel');
  if (!carousel) return;
  const slides = [...carousel.querySelectorAll('.featured-slide')];
  if (slides.length < 2) return;

  const track = carousel.querySelector('.featured-track');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  let index = 0;
  let timer;

  const show = (next) => {
    index = (next + slides.length) % slides.length;
    track.style.transform = `translateX(-${index * 100}%)`;
    slides.forEach((slide, i) => {
      slide.inert = i !== index;
      slide.setAttribute('aria-hidden', String(i !== index));
    });
  };
  const stop = () => clearInterval(timer);
  const start = () => {
    stop();
    if (!reducedMotion.matches && !document.hidden && !carousel.matches(':hover') && !carousel.contains(document.activeElement)) {
      timer = setInterval(() => show(index + 1), 5000);
    }
  };
  carousel.addEventListener('mouseenter', stop);
  carousel.addEventListener('mouseleave', start);
  carousel.addEventListener('focusin', stop);
  carousel.addEventListener('focusout', (event) => { if (!carousel.contains(event.relatedTarget)) start(); });
  carousel.querySelector('.featured-arrow-prev').addEventListener('click', () => show(index - 1));
  carousel.querySelector('.featured-arrow-next').addEventListener('click', () => show(index + 1));
  document.addEventListener('visibilitychange', start);
  reducedMotion.addEventListener('change', start);
  carousel.classList.add('is-ready');
  carousel.querySelectorAll('.featured-arrow').forEach((arrow) => { arrow.hidden = false; });
  show(0);
  start();
})();
