document.addEventListener("DOMContentLoaded", () => {
  if (window.location.search.includes("error")) {
    history.replaceState({}, document.title, window.location.pathname);
  }
});
