const dropdown = document.getElementById('dropdown');
const menuBtn = document.getElementById('menuBtn');

function toggleMenu() {
  if (!dropdown || !menuBtn) return;
  const isOpen = dropdown.style.display === 'block';
  dropdown.style.display = isOpen ? 'none' : 'block';
  menuBtn.classList.toggle('active', !isOpen);
}

if (dropdown && menuBtn) {
  window.addEventListener('click', function (e) {
    if (!menuBtn.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.style.display = 'none';
      menuBtn.classList.remove('active');
    }
  });
}

const slides = document.querySelectorAll('.carousel img');
let slideIndex = 0;

if (slides.length > 1) {
  setInterval(() => {
    slides[slideIndex].classList.remove('active');
    slideIndex = (slideIndex + 1) % slides.length;
    slides[slideIndex].classList.add('active');
  }, 4000);
}
