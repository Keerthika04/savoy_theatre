// function toggleMenu() {
//     var navLinks = document.querySelector('.nav_links');
//     navLinks.classList.toggle('open');
//   }

window.addEventListener('scroll', () => {
  let navbar = document.getElementById('navbar');
  if (window.scrollY > 0) {
      navbar.style.backgroundColor = 'var(--darkBg)';
  } else {
      navbar.style.backgroundColor = 'transparent';
  }
});

window.addEventListener('scroll', () => {
  let movie_navbar = document.getElementById('nav_movie');
  let viewportHeight = window.innerHeight;
  let size = 0.65 * viewportHeight;

  if (window.scrollY > size) {
    movie_navbar.classList.add('visible');
  } else {
      movie_navbar.classList.remove('visible');
  }
});


document.addEventListener('DOMContentLoaded', function () {
  var mySwiper = new Swiper('.swiper-container', {
      loop: true,
      autoplay: {
          delay: 5000, 
      },

      pagination: {
          el: '.swiper-pagination',
      },
  });
});
