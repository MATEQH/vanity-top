# vanity-top
Vanity Top is a toplist web interface for VanityEmpire, where players can view global and monthly toplists sorted by kills.

# Clone the repository
First, clone the repository to your local machine:
```
git clone https://github.com/MATEQH/vanity-top.git
```

# Install Dependencies for Frontend
Navigate to the frontend directory and install the necessary dependencies:
```
cd vanity-top/frontend
npm install
```

# Configuration
## Change URLs and Mongo URI
Update the API endpoint URL in the frontend and the MongoDB URI in the backend configuration files.

### Frontend
Edit the src/App.jsx file:
```
// Example
const response = await axios.get("https://mateqh.site/vanity-api/");
```

### Backend
Edit the config.php file:
>/backend/config.php
```
<?php

define("MONGO_URI", "INSERT_YOUR_MONGO_URI_HERE");

?>
```

# Run Frontend Application
To start the frontend development server, run:
```
npm run dev
```
# Build Frontend Application
Alternatively, you can build the frontend for production:
```
npm run build
```
If you built the frontend, you can move all files from the build directory to your web server, which must support PHP. If you are running the frontend in development mode, you only need to move the backend files to your web server.

# Backend Setup
Move the backend files to your web server and ensure the server has PHP support and the required MongoDB PHP extension installed.

# Deployment
Ensure your web server is properly configured to serve the built frontend files and the backend PHP files. The web server should handle API requests routed to the backend.

# Project Structure
- frontend/: Contains the React frontend application.
- backend/: Contains the PHP backend application.
- /public: Contains public assets and the built frontend files after running npm run build.
