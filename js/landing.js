const jobs = [
  { title: "Software Engineer (3 Year Exp.)", location: "📍 Vancouver, Canada", type: "⏳ Full Time", salary: "💰 CAD 150,000" },
  { title: "Frontend Developer (2 Year Exp.)", location: "📍 Toronto, Canada", type: "⏳ Full Time", salary: "💰 CAD 120,000" },
  { title: "Backend Developer (5 Year Exp.)", location: "📍 New York, USA", type: "⏳ Full Time", salary: "💰 USD 140,000" },
  { title: "UI/UX Designer (3 Year Exp.)", location: "📍 California, USA", type: "⏳ Full Time", salary: "💰 USD 100,000" },
  { title: "Technical Consultant (Entry Level)", location: "📍 Seoul, Korea", type: "⏳ Full Time", salary: "💰 KRW 100,000,000" }
];

const jobList = document.getElementById("job-list");
const searchInput = document.getElementById("search-input");
const loadMoreBtn = document.getElementById("load-more-btn");

let displayedJobs = 3;

function renderJobs(jobArray) {
  jobList.innerHTML = "";

  jobArray.slice(0, displayedJobs).forEach(job => {
      const jobCard = document.createElement("div");
      jobCard.classList.add("job-card");
      const jobIcon = job.title.trim().charAt(0).toUpperCase();

      jobCard.innerHTML = `
          <div class="job-icon">${jobIcon}</div>
          <div class="job-details">
              <h4>${job.title}</h4>
              <p>${job.location} | ${job.type} | ${job.salary}</p>
          </div>
          <button class="btn-view">View Details</button>
      `;
      jobList.appendChild(jobCard);
  });

  loadMoreBtn.style.display = displayedJobs >= jobArray.length ? "none" : "block";
}

function searchJobs() {
  const searchText = searchInput.value.toLowerCase();
  const filteredJobs = jobs.filter(job => job.title.toLowerCase().includes(searchText));
  displayedJobs = 3;
  renderJobs(filteredJobs);
}

function loadMoreJobs() {
  displayedJobs += 3;
  renderJobs(jobs);
}

document.addEventListener("DOMContentLoaded", () => renderJobs(jobs));
loadMoreBtn.addEventListener("click", loadMoreJobs);
searchInput.addEventListener("input", searchJobs);
