(() => {
  const STORAGE_KEY = "theme";
  const body = document.body;
  const toggle = document.getElementById("theme-toggle");
  if (!body || !toggle) return;

  const setTheme = (theme) => {
    if (theme === "light") {
      body.classList.add("theme-light");
      toggle.textContent = "🌙 Modo oscuro";
    } else {
      body.classList.remove("theme-light");
      toggle.textContent = "☀️ Modo claro";
    }
  };

  const saved = localStorage.getItem(STORAGE_KEY);
  const initialTheme = saved === "light" ? "light" : "dark";
  setTheme(initialTheme);

  toggle.addEventListener("click", () => {
    const nextTheme = body.classList.contains("theme-light") ? "dark" : "light";
    localStorage.setItem(STORAGE_KEY, nextTheme);
    setTheme(nextTheme);
  });
})();
