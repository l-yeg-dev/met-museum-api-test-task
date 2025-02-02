Metropolitan Museum Art Search

Overview

This project is a full-stack Laravel 10 + Vue 3 application that allows users to search for artworks from The Metropolitan Museum of Art's public API.

Features

Fetch Departments: Retrieve a list of available art departments.

Search Artworks: Search for artworks by title within a selected department.

Display Artwork Details: Show artwork title, artist name, date, and an image preview.

Optimized API Calls: Uses caching for improved performance.

Error Handling & Logging: Graceful API failure handling with logs.

SOLID Principles Applied: Clean architecture with DTOs and a Service layer.

Tech Stack

Backend: Laravel 10, PHP 8.3, Laravel HTTP Client, Caching, DTOs

Frontend: Vue 3, Vite, Axios, Tailwind CSS

Setup Instructions

1️⃣ Clone the Repository

git clone https://github.com/your-repo/metmuseum-api.git
cd metmuseum-api

2️⃣ Install Dependencies

Laravel Backend

composer install
cp .env.example .env
php artisan key:generate

Vue Frontend

cd frontend
npm install

3️⃣ Configure Environment

Open .env and set up the Laravel environment:

APP_URL=http://127.0.0.1:8000

4️⃣ Run the Backend Server

php artisan serve

5️⃣ Run the Frontend

cd frontend
npm run dev

API Endpoints

Departments

GET /api/departments

Fetches available art departments.

Search Artworks

GET /api/search?departmentId=1&searchTerm=cylinder

Search artworks by title within a department.

Get Artwork Details

GET /api/object/{id}

Fetches detailed information about a specific artwork.
