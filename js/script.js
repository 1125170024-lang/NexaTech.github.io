/* =========================================
   JAVASCRIPT - NEXATECH AI
   ========================================= */

document.addEventListener("DOMContentLoaded", () => {
  // 1. INITIALIZATION AOS ANIMATION
  AOS.init({
    duration: 800,
    easing: "ease-in-out",
    once: true,
    offset: 100,
  });

  // 2. STICKY NAVBAR EFFECT
  const navbar = document.getElementById("mainNavbar");

  window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  });

  // 3. DARK MODE TOGGLE
  const themeToggle = document.getElementById("themeToggle");
  const htmlElement = document.documentElement;
  const icon = themeToggle.querySelector("i");

  // Cek Local Storage
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    htmlElement.setAttribute("data-bs-theme", savedTheme);
    updateIcon(savedTheme);
  }

  themeToggle.addEventListener("click", () => {
    const currentTheme = htmlElement.getAttribute("data-bs-theme");
    const newTheme = currentTheme === "dark" ? "light" : "dark";

    htmlElement.setAttribute("data-bs-theme", newTheme);
    localStorage.setItem("theme", newTheme);
    updateIcon(newTheme);
  });

  function updateIcon(theme) {
    if (theme === "light") {
      icon.classList.remove("fa-moon");
      icon.classList.add("fa-sun");
    } else {
      icon.classList.remove("fa-sun");
      icon.classList.add("fa-moon");
    }
  }

  // 4. BACK TO TOP BUTTON
  const backToTop = document.getElementById("backToTop");

  window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
      backToTop.classList.add("show");
    } else {
      backToTop.classList.remove("show");
    }
  });

  // 5. COUNTER ANIMATION (Menggunakan Intersection Observer)
  const counters = document.querySelectorAll(".counter");
  const speed = 200;

  const animateCounter = (counter) => {
    const target = +counter.getAttribute("data-target");
    const count = +counter.innerText;
    const inc = target / speed;

    if (count < target) {
      counter.innerText = Math.ceil(count + inc);
      setTimeout(() => animateCounter(counter), 20);
    } else {
      counter.innerText = target;
    }
  };

  const counterObserver = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          observer.unobserve(entry.target); // Hanya jalankan sekali
        }
      });
    },
    { threshold: 0.5 },
  );

  counters.forEach((counter) => {
    counterObserver.observe(counter);
  });

  // 6. FORM VALIDATION (Contact Section)
  const contactForm = document.getElementById("contactForm");
  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const messageInput = document.getElementById("message");

  const nameError = document.getElementById("nameError");
  const emailError = document.getElementById("emailError");
  const messageError = document.getElementById("messageError");
  const successMessage = document.getElementById("successMessage");

  contactForm.addEventListener("submit", (e) => {
    e.preventDefault();
    let isValid = true;

    // Reset Error Styles
    [nameError, emailError, messageError].forEach((el) => (el.style.display = "none"));
    successMessage.classList.add("d-none");

    // Validasi Nama
    if (nameInput.value.trim() === "") {
      nameError.style.display = "block";
      isValid = false;
    }

    // Validasi Email (Regex Sederhana)
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailInput.value.trim())) {
      emailError.style.display = "block";
      isValid = false;
    }

    // Validasi Pesan (Minimal 10 Karakter)
    if (messageInput.value.trim().length < 10) {
      messageError.style.display = "block";
      isValid = false;
    }

    // Jika Valid
    if (isValid) {
      // Simulasi Pengiriman
      const submitBtn = contactForm.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerText;

      submitBtn.innerText = "Mengirim...";
      submitBtn.disabled = true;

      setTimeout(() => {
        successMessage.classList.remove("d-none");
        contactForm.reset();
        submitBtn.innerText = originalText;
        submitBtn.disabled = false;

        // Hilangkan pesan sukses setelah 5 detik
        setTimeout(() => {
          successMessage.classList.add("d-none");
        }, 5000);
      }, 1500);
    }
  });

  // 7. SMOOTH SCROLL UNTUK NAV LINKS
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("href");

      if (targetId === "#") return;

      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        // Tutup mobile menu jika terbuka
        const navbarCollapse = document.getElementById("navbarNav");
        if (navbarCollapse.classList.contains("show")) {
          const bsCollapse = new bootstrap.Collapse(navbarCollapse);
          bsCollapse.hide();
        }

        window.scrollTo({
          top: targetElement.offsetTop - 70, // Offset untuk sticky navbar
          behavior: "smooth",
        });
      }
    });
  });

  // 8. LIGHTBOX SEDERHANA UNTUK GALERI
  const galleryItems = document.querySelectorAll(".gallery-item");

  galleryItems.forEach((item) => {
    item.addEventListener("click", function () {
      const imgSrc = this.querySelector("img").src;
      // Membuat modal lightbox dinamis
      const lightbox = document.createElement("div");
      lightbox.style.position = "fixed";
      lightbox.style.top = "0";
      lightbox.style.left = "0";
      lightbox.style.width = "100%";
      lightbox.style.height = "100%";
      lightbox.style.backgroundColor = "rgba(0,0,0,0.9)";
      lightbox.style.zIndex = "9999";
      lightbox.style.display = "flex";
      lightbox.style.justifyContent = "center";
      lightbox.style.alignItems = "center";
      lightbox.style.cursor = "pointer";

      const img = document.createElement("img");
      img.src = imgSrc;
      img.style.maxWidth = "90%";
      img.style.maxHeight = "90%";
      img.style.borderRadius = "8px";
      img.style.boxShadow = "0 0 20px rgba(99, 102, 241, 0.5)";

      lightbox.appendChild(img);
      document.body.appendChild(lightbox);

      // Tutup lightbox saat diklik
      lightbox.addEventListener("click", () => {
        document.body.removeChild(lightbox);
      });
    });
  });
});
