<template>
<div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h4 class="logo">User Panel</h4>

        <a href="#" @click.prevent="section='dashboard'">Dashboard</a>
        <a href="#" @click.prevent="section='profile'">Profile</a>
        <a href="#" @click.prevent="section='jobs'">Jobs</a>
        <a href="#" @click.prevent="section='applications'">Applications</a>

        <hr>

        <form method="POST" action="/logout">
            <input type="hidden" name="_token" :value="csrf">
            <button class="logout-btn">Logout</button>
        </form>
    </aside>

    <!-- MAIN -->
    <main class="main">

        <!-- TOP BAR -->
        <div class="topbar">
            <h3>{{ title }}</h3>
            <span>Welcome, {{ user.name }}</span>
        </div>

        <!-- DASHBOARD -->
        <section v-if="section === 'dashboard'" class="card-box">
            <h4>Dashboard Overview</h4>

            <div class="grid">
                <div class="card">
                    <h2>{{ stats.applied }}</h2>
                    <p>Applied Jobs</p>
                </div>

                <div class="card">
                    <h2>{{ stats.pending }}</h2>
                    <p>Pending</p>
                </div>

                <div class="card">
                    <h2>{{ stats.approved }}</h2>
                    <p>Approved</p>
                </div>
            </div>
        </section>

        <!-- PROFILE -->
        <section v-if="section === 'profile'" class="card-box">
            <h4>Profile</h4>
            <p><b>Name:</b> {{ user.name }}</p>
            <p><b>Email:</b> {{ user.email }}</p>
        </section>

        <!-- JOBS -->
        <section v-if="section === 'jobs'" class="card-box">
            <h4>Available Jobs</h4>

            <div v-for="job in jobs" :key="job.id" class="job">
                <div>
                    <h5>{{ job.title }}</h5>
                    <small>{{ job.location }}</small>
                </div>

                <button @click="apply(job)" class="btn">
                    Apply
                </button>
            </div>
        </section>

        <!-- APPLICATIONS -->
        <section v-if="section === 'applications'" class="card-box">
            <h4>My Applications</h4>

            <table>
                <tr>
                    <th>Job</th>
                    <th>Status</th>
                </tr>

                <tr v-for="app in applications" :key="app.id">
                    <td>{{ app.job }}</td>
                    <td>
                        <span :class="status(app.status)">
                            {{ app.status }}
                        </span>
                    </td>
                </tr>
            </table>
        </section>

    </main>

</div>
</template>

<script>
export default {
    data() {
        return {
            section: 'dashboard',

            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

            user: {
                name: 'John Doe',
                email: 'john@email.com'
            },

            stats: {
                applied: 5,
                pending: 2,
                approved: 3
            },

            jobs: [
                { id: 1, title: 'Housekeeper', location: 'Naic' },
                { id: 2, title: 'Factory Worker', location: 'Manila' }
            ],

            applications: [
                { id: 1, job: 'Housekeeper', status: 'Pending' },
                { id: 2, job: 'Factory Worker', status: 'Approved' }
            ]
        }
    },

    computed: {
        title() {
            return this.section.charAt(0).toUpperCase() + this.section.slice(1)
        }
    },

    methods: {
        apply(job) {
            alert('Applied to ' + job.title)
        },

        status(s) {
            return s === 'Approved' ? 'badge success' : 'badge warning'
        }
    }
}
</script>

<style>
.app {
    display: flex;
    min-height: 100vh;
    font-family: sans-serif;
}

/* Sidebar */
.sidebar {
    width: 240px;
    background: #111827;
    color: white;
    padding: 20px;
}

.sidebar a {
    display: block;
    color: #ccc;
    margin: 10px 0;
    text-decoration: none;
}

.sidebar a:hover {
    color: white;
}

.logout-btn {
    width: 100%;
    padding: 10px;
    background: red;
    color: white;
    border: none;
    margin-top: 10px;
    cursor: pointer;
}

/* Main */
.main {
    flex: 1;
    padding: 20px;
    background: #f4f6f9;
}

.topbar {
    background: white;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
}

/* Cards */
.card-box {
    background: white;
    padding: 20px;
    border-radius: 12px;
}

.grid {
    display: flex;
    gap: 10px;
}

.card {
    flex: 1;
    padding: 20px;
    background: #f9fafb;
    border-radius: 10px;
    text-align: center;
}

/* Jobs */
.job {
    display: flex;
    justify-content: space-between;
    padding: 15px;
    margin: 10px 0;
    background: #f9fafb;
    border-radius: 10px;
}

.btn {
    background: #2563eb;
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
}

/* Badge */
.badge {
    padding: 5px 10px;
    border-radius: 8px;
    color: white;
}

.success {
    background: green;
}

.warning {
    background: orange;
}
</style>