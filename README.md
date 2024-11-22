# Laravel Application with Docker

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Overview

This is a Laravel-based web application, designed to provide a modern, robust framework for web development. The application is containerized using Docker, and includes automated setup for database migrations and seeding on launch.

### Key Features

- **Laravel Framework**: A powerful PHP framework with elegant syntax, designed for web application development.
- **Database Migrations and Seeding**: On launch, the application will automatically run database migrations and seed test data.
- **Dockerized Environment**: The application is containerized using Docker, allowing for easy setup and deployment.
- **Seamless Development Setup**: Just build and start the Docker container to get up and running with the application.

## Project Setup

### Requirements

- **Docker**: Make sure you have Docker installed on your machine. You can download it from [here](https://www.docker.com/get-started).
- **Docker Compose** (optional, but recommended): If you plan to manage the application with multiple services (e.g., database), Docker Compose is highly recommended.

### Steps to Set Up

1. **Clone the Repository**  
   Clone this project to your local machine:

   ```bash
   git clone https://github.com/DomantasBaras/task-manager.git
   cd task-manager
