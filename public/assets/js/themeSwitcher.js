(function () {
   const STORAGE_KEY = "theme";
   const darkMediaQuery = window.matchMedia("(prefers-color-scheme: dark)");

   const getBrowserTheme = () => (darkMediaQuery.matches ? "dark" : "light");

   const getActiveTheme = () => {
      const savedTheme = localStorage.getItem(STORAGE_KEY);
      if (savedTheme === "dark" || savedTheme === "light") {
         return savedTheme;
      }
      return getBrowserTheme();
   };

   const updateThemeImages = (theme) => {
      const attrName = theme === "dark" ? "data-img-theme-dark" : "data-img-theme-light";
      document.querySelectorAll(`img[${attrName}]`).forEach((img) => {
         const targetSrc = img.getAttribute(attrName);
         if (targetSrc && img.getAttribute("src") !== targetSrc) {
            img.setAttribute("src", targetSrc);
         }
      });
   };

   const updateToggleButtons = (theme) => {
      const isDark = theme === "dark";
      document.querySelectorAll("[data-theme-toggle]").forEach((btn) => {
         btn.setAttribute("aria-pressed", isDark ? "true" : "false");
         btn.setAttribute(
            "aria-label",
            isDark ? "Aktifkan tema terang" : "Aktifkan tema gelap"
         );
         btn.setAttribute(
            "title",
            isDark ? "Ganti ke Tema Terang" : "Ganti ke Tema Gelap"
         );

         const icon = btn.querySelector("[data-theme-icon]");
         if (icon) {
            icon.classList.remove("bi-sun-fill", "bi-moon-stars-fill", "bi-circle-half");
            icon.classList.add(isDark ? "bi-moon-stars-fill" : "bi-sun-fill");
         }
      });
   };

   const applyTheme = (theme, persist = false) => {
      const resolvedTheme = theme === "dark" ? "dark" : "light";
      document.documentElement.setAttribute("data-bs-theme", resolvedTheme);

      if (persist) {
         localStorage.setItem(STORAGE_KEY, resolvedTheme);
      }

      updateToggleButtons(resolvedTheme);
      updateThemeImages(resolvedTheme);
   };

   // Terapkan langsung saat script dieksekusi di <head> agar tidak terjadi flash (FOUC)
   applyTheme(getActiveTheme(), false);

   document.addEventListener("DOMContentLoaded", function () {
      const currentTheme = document.documentElement.getAttribute("data-bs-theme") || getActiveTheme();
      updateToggleButtons(currentTheme);
      updateThemeImages(currentTheme);

      document.querySelectorAll("[data-theme-toggle]").forEach((btn) => {
         btn.addEventListener("click", function (e) {
            e.preventDefault();
            const active = document.documentElement.getAttribute("data-bs-theme") || getActiveTheme();
            const nextTheme = active === "dark" ? "light" : "dark";
            applyTheme(nextTheme, true);
         });
      });
   });

   const handleSystemThemeChange = (e) => {
      const savedTheme = localStorage.getItem(STORAGE_KEY);
      if (savedTheme !== "dark" && savedTheme !== "light") {
         applyTheme(e.matches ? "dark" : "light", false);
      }
   };

   if (typeof darkMediaQuery.addEventListener === "function") {
      darkMediaQuery.addEventListener("change", handleSystemThemeChange);
   } else if (typeof darkMediaQuery.addListener === "function") {
      darkMediaQuery.addListener(handleSystemThemeChange);
   }
})();
