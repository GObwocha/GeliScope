# GeliScope

**GeliScope** is an AI Vision Assessment Engine built to empower inclusive and high-quality education. It leverages artificial intelligence and computer vision to streamline exam marking, analyze student performance, and generate comprehensive cognitive and skill profiles. By providing actionable insights, GeliScope bridges the gap between students, teachers, and parents to foster a data-driven educational environment.

## 🚀 Key Features

- **AI-Powered Assessment & Vision Processing:** Automates exam marking and analysis using advanced vision algorithms.
- **Comprehensive Analytics:** Generates detailed cognitive and skill profiles for individual students and entire classes.
- **Role-Based Dashboards:** 
  - **Teachers/Tutors:** Manage classes, track class performance, generate diagnoses, and view comprehensive student profiles.
  - **Parents:** Link with students to view personalized reports, track progress, and connect with teachers.
  - **Students:** Access exam archives, track personal performance, and explore AI-driven career suggestions.
- **Leaderboards:** Encourages healthy academic competition and tracks top performers across subjects and schools.
- **Upload Centre:** Easy management and archival of exams and past papers.

## 🛠 Tech Stack

GeliScope is built with a modern, decoupled architecture:

### Frontend (`geliscope-frontend`)
- **Framework:** [Vue 3](https://vuejs.org/) with [Vite](https://vitejs.dev/)
- **UI Library:** [Vuetify](https://vuetifyjs.com/) for modern, responsive UI components
- **State Management:** [Pinia](https://pinia.vuejs.org/)
- **Data Visualization:** [Chart.js](https://www.chartjs.org/) & `vue-chartjs`
- **Authentication:** JWT & Integrated Google OAuth (`vue3-google-login`)

### Backend (`geliscope-database`)
- **Framework:** [Laravel](https://laravel.com/) (PHP)
- **Database:** MongoDB (via Laravel integration)
- **API:** RESTful API architecture built with Laravel Sanctum for API token authentication.

## 📂 Project Structure

```text
GeliScope/
├── geliscope-frontend/     # Vue.js frontend SPA
├── geliscope-database/     # Laravel backend API
└── README.md               # Project documentation
```

## ⚙️ Getting Started

### Prerequisites
- [Node.js](https://nodejs.org/) (v20+ recommended)
- [PHP](https://www.php.net/) (v8.1+)
- [Composer](https://getcomposer.org/)
- MongoDB Server

### Running the Backend (API)
1. Navigate to the backend directory:
   ```bash
   cd geliscope-database
   ```
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Set up your environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Configure your `.env` to connect to your MongoDB database.
5. Run the local development server:
   ```bash
   php artisan serve
   ```

### Running the Frontend
1. Navigate to the frontend directory:
   ```bash
   cd geliscope-frontend
   ```
2. Install Node dependencies:
   ```bash
   npm install
   ```
3. Start the Vite development server:
   ```bash
   npm run dev
   ```

## 📄 License
This project is proprietary. All Rights Reserved.