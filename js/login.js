document.addEventListener("DOMContentLoaded", () => {
  const jobSeekerBtn = document.getElementById("jobSeekerBtn");
  const companyBtn = document.getElementById("companyBtn");

  jobSeekerBtn.addEventListener("click", () => {
      jobSeekerBtn.classList.add("active");
      companyBtn.classList.remove("active");
  });

  companyBtn.addEventListener("click", () => {
      companyBtn.classList.add("active");
      jobSeekerBtn.classList.remove("active");
  });
});
