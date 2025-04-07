document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector(".search-input");

  if (searchInput) {
    searchInput.addEventListener("input", () => {
      const query = searchInput.value.toLowerCase();
      document.querySelectorAll(".post").forEach(post => {
        const text = post.textContent.toLowerCase();
        post.style.display = text.includes(query) ? "block" : "none";
      });
    });
  }
});
