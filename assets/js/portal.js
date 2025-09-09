document.addEventListener("DOMContentLoaded", function () {
  // Particles.js
  particlesJS("particles-js", {
    particles: {
      number: {
        value: 80,
        density: {
          enable: true,
          value_area: 800,
        },
      },
      color: {
        value: "#6c757d",
      },
      shape: {
        type: "circle",
      },
      opacity: {
        value: 0.5,
        random: false,
      },
      size: {
        value: 3,
        random: true,
      },
      line_linked: {
        enable: true,
        distance: 150,
        color: "#6c757d",
        opacity: 0.4,
        width: 1,
      },
      move: {
        enable: true,
        speed: 2,
        direction: "none",
        random: false,
        straight: false,
        out_mode: "out",
        bounce: false,
      },
    },
    interactivity: {
      detect_on: "canvas",
      events: {
        onhover: {
          enable: true,
          mode: "repulse",
        },
        onclick: {
          enable: true,
          mode: "push",
        },
        resize: true,
      },
      modes: {
        repulse: {
          distance: 100,
          duration: 0.4,
        },
        push: {
          particles_nb: 4,
        },
      },
    },
    retina_detect: true,
  });

  // Clock
  function updateClock() {
    const now = new Date();
    const days = [
      "Minggu",
      "Senin",
      "Selasa",
      "Rabu",
      "Kamis",
      "Jumat",
      "Sabtu",
    ];
    const months = [
      "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Agustus",
      "September",
      "Oktober",
      "November",
      "Desember",
    ];
    const day = days[now.getDay()];
    const date = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();
    const time = now.toLocaleTimeString("id-ID", {
      hour12: false,
    });

    document.getElementById(
      "jam-hari"
    ).innerHTML = `${day}, ${date} ${month} ${year} | ${time}`;
  }
  setInterval(updateClock, 1000);
  updateClock();

  // GSAP Animations
  gsap.from("#judul", {
    duration: 1,
    y: -50,
    opacity: 0,
    ease: "power3.out",
    delay: 0.2,
  });
  gsap.from("#subjudul", {
    duration: 1,
    y: -30,
    opacity: 0,
    delay: 0.4,
    ease: "power3.out",
  });
  // gsap.from(".card", {
  //     duration: 0.8,
  //     y: 50,
  //     opacity: 0,
  //     stagger: 0.08,
  //     delay: 0.6,
  //     ease: "power3.out"
  // });
});
