# Presentation Creator

## Overview
This PHP-based project enables users to create and manage presentations consisting of static pages with slides containing various content.

## Features
- **Dynamic Slide Creation**: Users can create slides with custom content.
- **Interactive Pagination**: Easy navigation through slides.
- **Content Management**: Add, edit, and delete slide content.
- **File Integration**: Attach media files to slides.
- **RESTful API**: Backend implemented with PHP, handling various requests like slide and file retrieval.

## Installation

### Using Docker Compose
1. Ensure Docker and Docker Compose are installed on your system.
2. Clone the repository or download the source code.
3. Navigate to the project directory.
4. Run the command `docker compose up -d` to build and start the containers in detached mode.
5. Once the containers are up and running, the application should be accessible on the configured ports.

### Using Kubernetes
1. Ensure you have Kubernetes set up and configured on your system or cluster.
2. Clone the repository or download the source code.
3. Navigate to the `./deployment` directory where the Kubernetes YAML configuration files are located.
4. Apply the configurations using `kubectl apply -f [filename]` for each YAML file in the directory.
5. Check the status of the pods and services to ensure everything is running correctly.

## Usage
1. Start your PHP server.
2. Open the project in a web browser.
3. Use the provided interface to create and manage presentations.

## API Endpoints
- `signin`: User authentication.
- `signup`: Register a new user.
- `validate`: Validate user data.
- `getslides`: Retrieve slides for a presentation.
- `getfiles`: Fetch attached files for a slide.
- `deletepresentation`: Remove an existing presentation.
- `createPresentation`: Create a new presentation.
- `editslide`: Edit an existing slide.
- `search`: Search functionality.

## Technologies Used
- PHP
- HTML/CSS
- JavaScript
