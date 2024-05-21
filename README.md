# vanity-top

Vanity Top is a toplist web interface for VanityEmpire, where players can view global and monthly toplists sorted by kills.

# Clone the repository

First, clone the repository to your local machine:

```
git clone https://github.com/MATEQH/vanity-top.git
```

# Install Dependencies for Backend

Navigate to the backend directory and install the necessary dependencies:

```
cd vanity-top/backend
composer install
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

### Backend

Edit the config.php file:

> /backend/config.php

```
<?php

define("MONGO_URI", "INSERT_YOUR_MONGO_URI_HERE");

?>
```

### Frontend

Edit the App.jsx file:

> /frontend/src/App.jsx

```
// Example
const response = await axios.get("https://mateqh.site/vanity-api/");
```

# Change the base in frontend/vite.config.js

```
// Example
export default {
  // The base URL is the foundation for all relative URLs in the application.
  // For example, if you set this to "/vanity", the application URL will be localhost/vanity.
  base: "/vanity",
  // Additional configurations here...
};
```

# Run Frontend Application

To start the frontend development server, run:

```
npm run dev
```

# Backend Setup

Move the backend files to your web server and ensure the server has PHP support and the required MongoDB PHP extension installed.

# Build Frontend Application

Alternatively, you can build the frontend for production:

```
npm run build
```

If you built the frontend, you can move all files from the build directory to your web server, which must support PHP. If you are running the frontend in development mode, you only need to move the backend files to your web server.

# Deployment

Ensure your web server is properly configured to serve the built frontend files and the backend PHP files. The web server should handle API requests routed to the backend.

# Project Structure

- frontend/: Contains the React frontend application.
- backend/: Contains the PHP backend application.
- /public: Contains public assets and the built frontend files after running npm run build.
