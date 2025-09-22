const uploadArea = document.getElementById("uploadArea");
const fileInput = document.getElementById("fileInput");
const analyzeBtn = document.getElementById("analyzeBtn");
const previewContainer = document.getElementById("previewContainer");
const loading = document.getElementById("loading");
const resultsDiv = document.getElementById("results");

let selectedFile = null;

// File upload handling
uploadArea.addEventListener("click", () => fileInput.click());

uploadArea.addEventListener("dragover", (e) => {
  e.preventDefault();
  uploadArea.classList.add("dragover");
});

uploadArea.addEventListener("dragleave", () => {
  uploadArea.classList.remove("dragover");
});

uploadArea.addEventListener("drop", (e) => {
  e.preventDefault();
  uploadArea.classList.remove("dragover");
  uploadArea.classList.add("drag-animation");

  setTimeout(() => {
    uploadArea.classList.remove("drag-animation");
  }, 600);

  const files = e.dataTransfer.files;
  if (files.length > 0) {
    handleFileSelect(files[0]);
  }
});

fileInput.addEventListener("change", (e) => {
  if (e.target.files.length > 0) {
    handleFileSelect(e.target.files[0]);
  }
});

function handleFileSelect(file) {
  try {
    validateImage(file);
    selectedFile = file;

    previewContainer.innerHTML = '<div class="spinner"></div>';

    const reader = new FileReader();
    reader.onload = (e) => {
      previewContainer.innerHTML = `<img src="${e.target.result}" alt="Plant preview" class="preview-image">`;
      analyzeBtn.disabled = false;
      resultsDiv.innerHTML = "";

      document.getElementById("modelStatus").innerHTML =
        "🔍 Image loaded - Ready for analysis";
      document.getElementById("modelStatus").className =
        "status-indicator status-warning";
    };

    reader.onerror = () => {
      previewContainer.innerHTML =
        '<div class="no-results">❌ Error loading image</div>';
      selectedFile = null;
    };

    reader.readAsDataURL(file);
  } catch (error) {
    alert(error.message);
    previewContainer.innerHTML =
      '<div class="no-results">📷 Upload an image to get started</div>';
    selectedFile = null;
    analyzeBtn.disabled = true;
  }
}

analyzeBtn.addEventListener("click", async () => {
  if (!selectedFile) return;

  loading.style.display = "block";
  resultsDiv.innerHTML = "";
  analyzeBtn.disabled = true;

  const formData = new FormData();
  formData.append("image", selectedFile);

  try {
    const response = await fetch("http://localhost:5000/predict", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) throw new Error("Server error");

    const result = await response.json();

    loading.style.display = "none";
    displayResults(result);
  } catch (error) {
    console.error("Analysis error:", error);
    loading.style.display = "none";
    resultsDiv.innerHTML = `
      <div class="result-card" style="border-left-color: #dc3545;">
        <div class="disease-name" style="color: #dc3545;">❌ Analysis Failed</div>
        <div class="description">Unable to analyze the image. Please try again later.</div>
      </div>
    `;
  }

  analyzeBtn.disabled = false;
});

function displayResults(result) {
  const { disease, confidence, info } = result;

  const severityClass = info.severity;
  const severityEmoji = {
    low: "✅",
    medium: "⚠️",
    high: "🚨",
  };

  resultsDiv.innerHTML = `
    <div class="result-card">
      <div class="disease-name">${severityEmoji[severityClass]} ${info.name}</div>
      <div class="confidence">Confidence: ${confidence}%</div>
      <div class="severity ${severityClass}">
        Severity: ${info.severity.charAt(0).toUpperCase() + info.severity.slice(1)}
      </div>
      <div class="description">${info.description}</div>
      <div class="treatment">
        <h4>🌿 Treatment Recommendations</h4>
        <p>${info.treatment}</p>
      </div>
    </div>
  `;
}

function openModal(modalId) {
  document.getElementById(modalId).style.display = "block";
  document.body.style.overflow = "hidden";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
  document.body.style.overflow = "auto";
}

document.querySelectorAll(".close").forEach((closeBtn) => {
  closeBtn.addEventListener("click", (e) => {
    const modal = e.target.closest(".modal");
    modal.style.display = "none";
    document.body.style.overflow = "auto";
  });
});

document.querySelectorAll(".modal").forEach((modal) => {
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
      document.body.style.overflow = "auto";
    }
  });
});

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    document.querySelectorAll(".modal").forEach((modal) => {
      if (modal.style.display === "block") {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {
  console.log("🌱 Plant Disease Detection System Initialized");
  document.querySelector(".header").style.opacity = "0";
  document.querySelector(".header").style.transform = "translateY(-20px)";

  setTimeout(() => {
    document.querySelector(".header").style.transition = "all 0.6s ease";
    document.querySelector(".header").style.opacity = "1";
    document.querySelector(".header").style.transform = "translateY(0)";
  }, 100);
});

function validateImage(file) {
  const validTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
  const maxSize = 10 * 1024 * 1024; 

  if (!validTypes.includes(file.type)) {
    throw new Error("Invalid file type. Please upload JPG, PNG, or WebP images only.");
  }

  if (file.size > maxSize) {
    throw new Error("File too large. Please upload images smaller than 10MB.");
  }

  return true;
}
