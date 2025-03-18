const jobList = document.getElementById("job-list");
const searchInput = document.getElementById("search-input");
const loadMoreBtn = document.getElementById("load-more-btn");

let displayedJobs = 10;
let filteredJobs = [...jobs];

function renderJobs(jobArray) {
  jobList.innerHTML = "";
  jobArray.slice(0, displayedJobs).forEach((job) => {
    const jobCard = document.createElement("div");
    jobCard.classList.add("job-card");
    const jobIcon = job.title.trim().charAt(0).toUpperCase();

    jobCard.innerHTML = `
      <div class="job-icon">${jobIcon}</div>
      <div class="job-details">
          <h4>${job.title}</h4>
          <p>📍 ${job.location} | ⏳ ${ job.type } | 💰 ${new Intl.NumberFormat().format(job.salary)}</p>
      </div>
      <button class="btn-view">View Details</button>
      `;
    jobList.appendChild(jobCard);
  });

  loadMoreBtn.style.display = displayedJobs >= jobArray.length ? "none" : "block";
}

function searchJobs() {
  const searchText = searchInput.value.toLowerCase();
  filteredJobs = jobs.filter((job) =>job.title.toLowerCase().includes(searchText));
  displayedJobs = 10;
  renderJobs(filteredJobs);
}

function loadMoreJobs() {
  displayedJobs += 10;
   renderJobs(filteredJobs);
}

document.addEventListener("DOMContentLoaded", () => renderJobs(jobs));
loadMoreBtn.addEventListener("click", loadMoreJobs);
searchInput.addEventListener("input", searchJobs);
