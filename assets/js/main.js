// Waktu & Hari
function updateClock() {
  const now = new Date();
  const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
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
  const time = now.toLocaleTimeString("id-ID");
  document.getElementById(
    "jam-hari"
  ).textContent = `${day}, ${date} ${month} ${year} - ${time}`;
}
setInterval(updateClock, 1000);
updateClock();

// Pause marquee saat hover link pengumuman
document.addEventListener("DOMContentLoaded", function () {
  const marquee = document.querySelector("#pengumuman-slider .marquee");
  if (!marquee) return;
  marquee.addEventListener("mouseover", function (e) {
    if (e.target.tagName === "A") {
      marquee.classList.add("paused");
    }
  });
  marquee.addEventListener("mouseout", function (e) {
    if (e.target.tagName === "A") {
      marquee.classList.remove("paused");
    }
  });
});

// Partikel
particlesJS("particles-js", {
  particles: {
    number: { value: 100 },
    color: { value: "#ffc107" },
    shape: { type: "circle" },
    opacity: { value: 0.5 },
    size: { value: 3 },
    line_linked: {
      enable: true,
      distance: 150,
      color: "#ffc107",
      opacity: 0.5,
      width: 1,
    },
    move: {
      enable: true,
      speed: 1,
    },
  },
  interactivity: {
    events: {
      onhover: { enable: true, mode: "repulse" },
      onclick: { enable: true, mode: "push" },
    },
  },
  retina_detect: true,
});

// GSAP Animasi
window.addEventListener("DOMContentLoaded", () => {
  gsap.from("#jam-hari", { opacity: 0, y: -10, duration: 0.8 });
  gsap.from("#judul", { opacity: 0, y: -20, duration: 1 });
  gsap.from("#subjudul", { opacity: 0, y: -10, delay: 0.3, duration: 1 });
  gsap.from("#tombol-container a", {
    opacity: 0,
    y: 30,
    duration: 0.8,
    stagger: 0.2,
    delay: 0.6,
  });
});
