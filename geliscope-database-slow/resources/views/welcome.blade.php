<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GeliScope API Hub - Backend Engine</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen pb-16">
    <!-- Top Navigation / Header -->
    <header class="border-b border-slate-800 bg-slate-900/60 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-500 flex items-center justify-center font-bold text-lg shadow-lg shadow-indigo-500/30">
                    GS
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white flex items-center gap-2">
                        GeliScope <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 font-semibold">v1.0 API Engine</span>
                    </h1>
                    <p class="text-xs text-slate-400">SDG Problem 4: Quality & Inclusive Education Platform</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span class="w-2 h-2 mr-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    Backend Online (Port 8000)
                </span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 mt-8 space-y-10">
        <!-- Architecture Overview Banner -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-900/50 border border-slate-800 relative overflow-hidden group hover:border-indigo-500/50 transition">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition"></div>
                <div class="text-indigo-400 font-semibold text-sm mb-1 uppercase tracking-wider">Primary Database</div>
                <h3 class="text-2xl font-bold text-white flex items-center justify-between">
                    MySQL Relational
                    <span class="text-sm px-2.5 py-0.5 rounded-md bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Active</span>
                </h3>
                <p class="text-sm text-slate-400 mt-2">14 tables populated with Kenyan schools, students, teachers, parents, exams, and leaderboard scores.</p>
            </div>

            <div class="p-6 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-900/50 border border-slate-800 relative overflow-hidden group hover:border-purple-500/50 transition">
                <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition"></div>
                <div class="text-purple-400 font-semibold text-sm mb-1 uppercase tracking-wider">Secondary Database</div>
                <h3 class="text-2xl font-bold text-white flex items-center justify-between">
                    MongoDB Analytics Bridge
                    <span class="text-sm px-2.5 py-0.5 rounded-md bg-purple-500/20 text-purple-300 border border-purple-500/30">Ready</span>
                </h3>
                <p class="text-sm text-slate-400 mt-2">6 models configured for unstructured Bloom's taxonomy AI evaluations, OCR paper scans, and parent reports.</p>
            </div>

            <div class="p-6 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-900/50 border border-slate-800 relative overflow-hidden group hover:border-emerald-500/50 transition">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition"></div>
                <div class="text-emerald-400 font-semibold text-sm mb-1 uppercase tracking-wider">RESTful API Endpoints</div>
                <h3 class="text-2xl font-bold text-white flex items-center justify-between">
                    34 Live Routes
                    <span class="text-sm px-2.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">JSON v1</span>
                </h3>
                <p class="text-sm text-slate-400 mt-2">All controllers wired up under `/api/v1/*` with full relationship autoloading and pagination.</p>
            </div>
        </section>

        <!-- Live API Explorer Quick-Links -->
        <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 md:p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-white">Interactive API Explorer</h2>
                    <p class="text-sm text-slate-400">Click any card below to open the real JSON endpoint directly in your browser or frontend app.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="/api/v1/schools" target="_blank" class="p-5 rounded-xl bg-slate-950/80 border border-slate-800/80 hover:border-indigo-500/60 hover:bg-slate-900 transition block group">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">GET</span>
                        <span class="text-xs text-slate-500 group-hover:text-slate-300 transition">View JSON →</span>
                    </div>
                    <h4 class="font-semibold text-white mt-3 group-hover:text-indigo-400 transition">/api/v1/schools</h4>
                    <p class="text-xs text-slate-400 mt-1">List schools with class, student, and teacher counts.</p>
                </a>

                <a href="/api/v1/schools/1" target="_blank" class="p-5 rounded-xl bg-slate-950/80 border border-slate-800/80 hover:border-indigo-500/60 hover:bg-slate-900 transition block group">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">GET</span>
                        <span class="text-xs text-slate-500 group-hover:text-slate-300 transition">View JSON →</span>
                    </div>
                    <h4 class="font-semibold text-white mt-3 group-hover:text-indigo-400 transition">/api/v1/schools/1</h4>
                    <p class="text-xs text-slate-400 mt-1">School #1 with nested classes, students, and teachers.</p>
                </a>

                <a href="/api/v1/users?role=student" target="_blank" class="p-5 rounded-xl bg-slate-950/80 border border-slate-800/80 hover:border-emerald-500/60 hover:bg-slate-900 transition block group">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">GET</span>
                        <span class="text-xs text-slate-500 group-hover:text-slate-300 transition">View JSON →</span>
                    </div>
                    <h4 class="font-semibold text-white mt-3 group-hover:text-emerald-400 transition">/api/v1/users?role=student</h4>
                    <p class="text-xs text-slate-400 mt-1">Filter all registered student accounts.</p>
                </a>

                <a href="/api/v1/users?role=teacher" target="_blank" class="p-5 rounded-xl bg-slate-950/80 border border-slate-800/80 hover:border-emerald-500/60 hover:bg-slate-900 transition block group">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">GET</span>
                        <span class="text-xs text-slate-500 group-hover:text-slate-300 transition">View JSON →</span>
                    </div>
                    <h4 class="font-semibold text-white mt-3 group-hover:text-emerald-400 transition">/api/v1/users?role=teacher</h4>
                    <p class="text-xs text-slate-400 mt-1">Filter all registered teacher accounts.</p>
                </a>

                <a href="/api/v1/exams" target="_blank" class="p-5 rounded-xl bg-slate-950/80 border border-slate-800/80 hover:border-amber-500/60 hover:bg-slate-900 transition block group">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">GET</span>
                        <span class="text-xs text-slate-500 group-hover:text-slate-300 transition">View JSON →</span>
                    </div>
                    <h4 class="font-semibold text-white mt-3 group-hover:text-amber-400 transition">/api/v1/exams</h4>
                    <p class="text-xs text-slate-400 mt-1">List all mid-term and end-of-term exams.</p>
                </a>

                <a href="/api/v1/exams/1" target="_blank" class="p-5 rounded-xl bg-slate-950/80 border border-slate-800/80 hover:border-amber-500/60 hover:bg-slate-900 transition block group">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">GET</span>
                        <span class="text-xs text-slate-500 group-hover:text-slate-300 transition">View JSON →</span>
                    </div>
                    <h4 class="font-semibold text-white mt-3 group-hover:text-amber-400 transition">/api/v1/exams/1</h4>
                    <p class="text-xs text-slate-400 mt-1">Exam #1 with student grades & scores loaded.</p>
                </a>

                <a href="/api/v1/leaderboard?subject_id=1" target="_blank" class="p-5 rounded-xl bg-slate-950/80 border border-slate-800/80 hover:border-purple-500/60 hover:bg-slate-900 transition block group">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-purple-500/20 text-purple-400 border border-purple-500/30">GET</span>
                        <span class="text-xs text-slate-500 group-hover:text-slate-300 transition">View JSON →</span>
                    </div>
                    <h4 class="font-semibold text-white mt-3 group-hover:text-purple-400 transition">/api/v1/leaderboard?subject_id=1</h4>
                    <p class="text-xs text-slate-400 mt-1">Student ranking leaderboard for Mathematics.</p>
                </a>

                <a href="/api/v1/parents/1" target="_blank" class="p-5 rounded-xl bg-slate-950/80 border border-slate-800/80 hover:border-rose-500/60 hover:bg-slate-900 transition block group">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">GET</span>
                        <span class="text-xs text-slate-500 group-hover:text-slate-300 transition">View JSON →</span>
                    </div>
                    <h4 class="font-semibold text-white mt-3 group-hover:text-rose-400 transition">/api/v1/parents/1</h4>
                    <p class="text-xs text-slate-400 mt-1">Parent #1 profile with linked students & classes.</p>
                </a>

                <a href="/api/v1/parents/1/students/1/teachers" target="_blank" class="p-5 rounded-xl bg-slate-950/80 border border-slate-800/80 hover:border-rose-500/60 hover:bg-slate-900 transition block group">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">GET</span>
                        <span class="text-xs text-slate-500 group-hover:text-slate-300 transition">View JSON →</span>
                    </div>
                    <h4 class="font-semibold text-white mt-3 group-hover:text-rose-400 transition">/api/v1/parents/1/students/1/teachers</h4>
                    <p class="text-xs text-slate-400 mt-1">List teachers teaching Child #1 for parent access.</p>
                </a>
            </div>
        </section>

        <!-- Comprehensive Endpoint Reference Table -->
        <section class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 md:p-8">
            <h3 class="text-lg font-bold text-white mb-4">Full API Route Directory (`/api/v1/*`)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 font-semibold">
                            <th class="py-3 px-4">Method</th>
                            <th class="py-3 px-4">Endpoint Path</th>
                            <th class="py-3 px-4">Controller Action</th>
                            <th class="py-3 px-4">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        <tr>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-xs bg-indigo-500/20 text-indigo-400 font-mono font-bold">GET</span> / <span class="px-2 py-0.5 rounded text-xs bg-emerald-500/20 text-emerald-400 font-mono font-bold">POST</span></td>
                            <td class="py-3 px-4 font-mono text-xs text-indigo-300">/api/v1/users</td>
                            <td class="py-3 px-4 text-xs">UserController@index / store</td>
                            <td class="py-3 px-4 text-xs text-slate-400">List users (filter by `?role=`) or create user + Student/Teacher/Parent profile.</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-xs bg-indigo-500/20 text-indigo-400 font-mono font-bold">GET</span> / <span class="px-2 py-0.5 rounded text-xs bg-emerald-500/20 text-emerald-400 font-mono font-bold">POST</span></td>
                            <td class="py-3 px-4 font-mono text-xs text-indigo-300">/api/v1/schools</td>
                            <td class="py-3 px-4 text-xs">SchoolController@index / store</td>
                            <td class="py-3 px-4 text-xs text-slate-400">Manage schools and retrieve class hierarchy.</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-xs bg-indigo-500/20 text-indigo-400 font-mono font-bold">GET</span> / <span class="px-2 py-0.5 rounded text-xs bg-emerald-500/20 text-emerald-400 font-mono font-bold">POST</span></td>
                            <td class="py-3 px-4 font-mono text-xs text-indigo-300">/api/v1/schools/{id}/classes</td>
                            <td class="py-3 px-4 text-xs">SchoolController@classes / storeClass</td>
                            <td class="py-3 px-4 text-xs text-slate-400">List or create classes within a specific school.</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-xs bg-indigo-500/20 text-indigo-400 font-mono font-bold">GET</span> / <span class="px-2 py-0.5 rounded text-xs bg-emerald-500/20 text-emerald-400 font-mono font-bold">POST</span></td>
                            <td class="py-3 px-4 font-mono text-xs text-indigo-300">/api/v1/exams</td>
                            <td class="py-3 px-4 text-xs">ExamController@index / store</td>
                            <td class="py-3 px-4 text-xs text-slate-400">Filter exams by `class_id` or `subject_id` and create new exams.</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-xs bg-emerald-500/20 text-emerald-400 font-mono font-bold">POST</span></td>
                            <td class="py-3 px-4 font-mono text-xs text-indigo-300">/api/v1/exams/{examId}/results</td>
                            <td class="py-3 px-4 text-xs">ExamController@storeResult</td>
                            <td class="py-3 px-4 text-xs text-slate-400">Submit a student's total score and grade for an exam.</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-xs bg-emerald-500/20 text-emerald-400 font-mono font-bold">POST</span></td>
                            <td class="py-3 px-4 font-mono text-xs text-indigo-300">/api/v1/exam-results/{id}/question-scores</td>
                            <td class="py-3 px-4 text-xs">ExamController@storeQuestionScores</td>
                            <td class="py-3 px-4 text-xs text-slate-400">Bulk submit detailed question-by-question marks for a result.</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-xs bg-indigo-500/20 text-indigo-400 font-mono font-bold">GET</span> / <span class="px-2 py-0.5 rounded text-xs bg-emerald-500/20 text-emerald-400 font-mono font-bold">POST</span></td>
                            <td class="py-3 px-4 font-mono text-xs text-purple-300">/api/v1/analysis/exam/{student}/{exam}</td>
                            <td class="py-3 px-4 text-xs">AnalysisController@examAnalysis / store</td>
                            <td class="py-3 px-4 text-xs text-slate-400">Retrieve or store unstructured Bloom's taxonomy insights from MongoDB.</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-xs bg-indigo-500/20 text-indigo-400 font-mono font-bold">GET</span> / <span class="px-2 py-0.5 rounded text-xs bg-emerald-500/20 text-emerald-400 font-mono font-bold">POST</span></td>
                            <td class="py-3 px-4 font-mono text-xs text-purple-300">/api/v1/analysis/skills/{studentId}</td>
                            <td class="py-3 px-4 text-xs">AnalysisController@skillProfile / store</td>
                            <td class="py-3 px-4 text-xs text-slate-400">Manage student skill sets (`logical_deduction`) and suggested career paths.</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-xs bg-indigo-500/20 text-indigo-400 font-mono font-bold">GET</span></td>
                            <td class="py-3 px-4 font-mono text-xs text-indigo-300">/api/v1/leaderboard</td>
                            <td class="py-3 px-4 text-xs">LeaderboardController@index</td>
                            <td class="py-3 px-4 text-xs text-slate-400">Get student rankings sorted by `rank` ASC (`?subject_id=1&term=1`).</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-xs bg-indigo-500/20 text-indigo-400 font-mono font-bold">GET</span> / <span class="px-2 py-0.5 rounded text-xs bg-emerald-500/20 text-emerald-400 font-mono font-bold">POST</span></td>
                            <td class="py-3 px-4 font-mono text-xs text-indigo-300">/api/v1/parents/{id}/students</td>
                            <td class="py-3 px-4 text-xs">ParentController@linkStudent / unlink</td>
                            <td class="py-3 px-4 text-xs text-slate-400">Link or detach children from a parent account.</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-xs bg-indigo-500/20 text-indigo-400 font-mono font-bold">GET</span></td>
                            <td class="py-3 px-4 font-mono text-xs text-indigo-300">/api/v1/parents/{pid}/students/{sid}/report</td>
                            <td class="py-3 px-4 text-xs">ParentController@studentReport</td>
                            <td class="py-3 px-4 text-xs text-slate-400">Fetch termly AI performance report generated for the parent.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
