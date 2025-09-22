<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="icon" type="image/png" href="images/logo.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Plant Disease Detection - AI Powered Diagnosis</title>
    <link rel="stylesheet" href="assets/css/disease.css">
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  </head>
  <body>
    <div class="container">
      <header class="header">
        <div class="header-content">
          <a href="main.php" class="back-button"> ← Back to Main </a>
          <div>
            <h1>🌱 Detect Your Crop's Disease here!!</h1>
            <p>AI-Powered Plant Health Diagnosis System</p>
          </div>
        </div>
      </header>

      <div class="main-content">
        <div class="analysis-section">
          <h2>🔬Disease Analysis</h2>
          <div id="modelStatus" class="status-indicator status-success">
            Upload an image to begin analysis
          </div>

          <div class="upload-container">
            <div class="upload-zone">
              <div class="upload-area" id="uploadArea">
                <div class="upload-icon">📸</div>
                <div class="upload-text">
                  <strong>Drop your plant image here</strong><br />
                  or click to browse files<br />
                  <small style="opacity: 0.7">Supports JPG, PNG, WebP formats</small>
                </div>
              </div>
              <input type="file" id="fileInput" class="file-input" accept="image/*"/>
              <center>
                <button class="btn" id="analyzeBtn" disabled>🔍 Analyze Plant Health</button>
              </center>
            </div>

            <div class="results-zone">
              <div class="preview-container" id="previewContainer">
                <div class="no-results">📷 Upload an image to get started</div>
              </div>

              <div class="loading" id="loading" style="display:none;">
                <div class="spinner"></div>
                <p>🧠 AI is analyzing your plant...</p>
              </div>

              <div id="results"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      const fileInput = document.getElementById("fileInput");
      const analyzeBtn = document.getElementById("analyzeBtn");
      const resultsDiv = document.getElementById("results");
      const loadingDiv = document.getElementById("loading");
      const previewContainer = document.getElementById("previewContainer");

      let selectedFile = null;

      fileInput.addEventListener("change", (e) => {
        selectedFile = e.target.files[0];
        if (selectedFile) {
          analyzeBtn.disabled = false;
          previewContainer.innerHTML = `<img src="${URL.createObjectURL(selectedFile)}" 
            alt="Preview" style="max-width:200px; border-radius:8px; margin-top:10px;">`;
        }
      });

      analyzeBtn.addEventListener("click", async () => {
        if (!selectedFile) return;

        const formData = new FormData();
        formData.append("file", selectedFile);

        resultsDiv.innerHTML = "";
        loadingDiv.style.display = "block";

        try {
          const response = await fetch("http://localhost:8000/predict", {
            method: "POST",
            body: formData
          });

          const data = await response.json();
          loadingDiv.style.display = "none";

          if (data.error) {
            resultsDiv.innerHTML = `<p style="color:red;">Error: ${data.error}</p>`;
            return;
          }

          resultsDiv.innerHTML = `
            <h3>Predicted Disease: <span style="color:#2e7d32;">${data.predicted_class}</span></h3>
            <p>Confidence: ${(data.predicted_probability * 100).toFixed(2)}%</p>
            <p>Severity: ${data.severity}</p>
            <h4>Recommendations:</h4>
            <ul>${data.recommendations.map(r => `<li>${r}</li>`).join("")}</ul>
          `;
        } catch (err) {
          loadingDiv.style.display = "none";
          resultsDiv.innerHTML = `<p style="color:red;">Failed to connect to AI server. Make sure FastAPI is running.</p>`;
        }
      });
    </script>

    <script src="assets/js/theme.js"></script>
  </body>
</html>
