<template>
  <div class="container py-4">

    <!-- ================= STATS ================= -->
    <div class="row mb-4">

      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>Total Users</h6>
          <h3>{{ users.length }}</h3>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>Admins</h6>
          <h3>{{ adminCount }}</h3>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>In Trash</h6>
          <h3>{{ trashedUsers.length }}</h3>
        </div>
      </div>

    </div>

    <!-- ================= HEADER ================= -->
    <div class="d-flex justify-content-between align-items-center mb-3">

      <h4>User Management</h4>

      <div class="d-flex">

        <!-- ADD USER -->
        <button class="btn btn-success me-2" @click="showAddModal = true">
          + Add User
        </button>

        <!-- DESKTOP -->
        <div class="d-none d-md-flex">
          <button class="btn btn-primary me-2" @click="setActive">Active</button>
          <button class="btn btn-warning" @click="setTrash">Trash</button>
        </div>

        <!-- MOBILE -->
        <div class="d-md-none position-relative ms-2">
          <button class="btn btn-dark" @click="toggleMenu = !toggleMenu">☰</button>

          <div v-if="toggleMenu" class="mobile-menu shadow">
            <button class="dropdown-item" @click="setActive">Active</button>
            <button class="dropdown-item" @click="setTrash">Trash</button>
          </div>
        </div>

      </div>
    </div>

    <!-- ================= TABLE ================= -->
    <div class="card shadow-sm">

      <table class="table table-hover mb-0">

        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>

          <tr v-for="(user, index) in paginatedUsers" :key="user.id">

            <td>{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.username }}</td>
            <td>{{ user.email }}</td>

            <td>
              <span class="badge bg-info" v-if="user.role === 'admin'">Admin</span>
              <span class="badge bg-secondary" v-else>User</span>
            </td>

            <td>
              <span class="badge bg-success" v-if="!user.deleted_at">Active</span>
              <span class="badge bg-danger" v-else>Trash</span>
            </td>

            <td>

              <div v-if="!showTrash">

                <button class="btn btn-sm btn-info me-1" @click="viewUser(user)">View</button>
                <button class="btn btn-sm btn-success me-1" @click="editUser(user)">Edit</button>
                <button class="btn btn-sm btn-danger" @click="deleteUser(user)">Delete</button>

              </div>

              <div v-else>

                <button class="btn btn-sm btn-primary me-1" @click="restoreUser(user)">Restore</button>
                <button class="btn btn-sm btn-danger" @click="forceDelete(user)">Delete</button>

              </div>

            </td>

          </tr>

        </tbody>

      </table>

    </div>

    <!-- ================= PAGINATION ================= -->
    <div class="d-flex justify-content-between align-items-center mt-3">
      <div>
        <small class="text-muted">
          Showing {{ (currentPage - 1) * itemsPerPage + 1 }} to {{ Math.min(currentPage * itemsPerPage, users.length) }} of {{ users.length }} users
        </small>
      </div>
      <nav aria-label="Page navigation">
        <ul class="pagination mb-0">
          <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <button class="page-link" @click="currentPage = 1" :disabled="currentPage === 1">First</button>
          </li>
          <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <button class="page-link" @click="currentPage--" :disabled="currentPage === 1">Previous</button>
          </li>

          <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: currentPage === page }">
            <button class="page-link" @click="currentPage = page">{{ page }}</button>
          </li>

          <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <button class="page-link" @click="currentPage++" :disabled="currentPage === totalPages">Next</button>
          </li>
          <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <button class="page-link" @click="currentPage = totalPages" :disabled="currentPage === totalPages">Last</button>
          </li>
        </ul>
      </nav>
    </div>

    <!-- ================= ADD USER MODAL ================= -->
    <div v-if="showAddModal" class="modal-backdrop-custom">

      <div class="modal-card">

        <h5 class="mb-3">Add User Account</h5>

        <!-- NAME -->
        <input v-model="form.name" class="form-control mb-2" placeholder="Name">

        <!-- USERNAME -->
        <input v-model="form.username" class="form-control mb-2" placeholder="Username">

        <!-- EMAIL -->
        <input v-model="form.email" class="form-control mb-2" placeholder="Email">

        <!-- PASSWORD -->
        <input type="password" v-model="form.password" class="form-control mb-2" placeholder="Password">

        <!-- PASSWORD STRENGTH -->
        <div class="progress mb-1" style="height:6px;">
          <div class="progress-bar"
               :class="{
                 'bg-danger': passwordStrength === 'Weak',
                 'bg-warning': passwordStrength === 'Medium',
                 'bg-success': passwordStrength === 'Strong'
               }"
               :style="{
                 width: passwordStrength === 'Weak' ? '33%' :
                        passwordStrength === 'Medium' ? '66%' : '100%'
               }">
          </div>
        </div>

        <small class="mb-2 d-block">
          Strength: {{ passwordStrength }}
        </small>

        <!-- CONFIRM PASSWORD -->
        <input type="password"
               v-model="form.confirm_password"
               class="form-control mb-2"
               placeholder="Confirm Password">

        <small v-if="form.confirm_password && form.password !== form.confirm_password"
               class="text-danger">
          Password does not match
        </small>

        <!-- ROLE -->
        <select v-model="form.role" class="form-control mb-3">
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>

        <!-- BUTTONS -->
        <div class="d-flex justify-content-end">

          <button class="btn btn-secondary me-2" @click="closeModal">
            Cancel
          </button>

          <button class="btn btn-success"
                  :disabled="!isFormValid"
                  @click="addUser">
            Save
          </button>

        </div>

      </div>

    </div>

  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      users: [],
      showTrash: false,
      toggleMenu: false,
      showAddModal: false,
      currentPage: 1,
      itemsPerPage: 10,

      form: {
        name: "",
        username: "",
        email: "",
        password: "",
        confirm_password: "",
        role: "user"
      }
    };
  },

  mounted() {
    this.fetchUsers();
  },

  watch: {
    showTrash() {
      this.fetchUsers();
    }
  },

  computed: {

    adminCount() {
      return this.users.filter(u => u.role === 'admin' && !u.deleted_at).length;
    },

    trashedUsers() {
      return this.users.filter(u => u.deleted_at);
    },

    paginatedUsers() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.users.slice(start, end);
    },

    totalPages() {
      return Math.ceil(this.users.length / this.itemsPerPage);
    },

    passwordStrength() {
      const p = this.form.password;
      let score = 0;

      if (p.length >= 6) score++;
      if (p.length >= 10) score++;
      if (/[A-Z]/.test(p)) score++;
      if (/[0-9]/.test(p)) score++;
      if (/[^A-Za-z0-9]/.test(p)) score++;

      if (score <= 2) return "Weak";
      if (score <= 4) return "Medium";
      return "Strong";
    },

    isFormValid() {
      return (
        this.form.name &&
        this.form.username &&
        this.form.email &&
        this.form.password &&
        this.form.confirm_password &&
        this.form.password === this.form.confirm_password &&
        this.passwordStrength !== "Weak"
      );
    }

  },

  methods: {

    async fetchUsers() {
      const url = this.showTrash
        ? '/user-account/trash'
        : '/user-account';

      const res = await axios.get(url);
      this.users = res.data;
      this.currentPage = 1;
      this.toggleMenu = false;
    },

    setActive() {
      this.showTrash = false;
      this.currentPage = 1;
    },

    setTrash() {
      this.showTrash = true;
      this.currentPage = 1;
    },

    async addUser() {
      await axios.post('/user-account', this.form);

      alert("User created successfully");

      this.closeModal();
      this.fetchUsers();
    },

    closeModal() {
      this.showAddModal = false;

      this.form = {
        name: "",
        username: "",
        email: "",
        password: "",
        confirm_password: "",
        role: "user"
      };
    },

    async deleteUser(user) {
      if (!confirm("Move to trash?")) return;
      await axios.delete(`/user-account/${user.id}`);
      this.fetchUsers();
    },

    async restoreUser(user) {
      if (!confirm("Restore user?")) return;
      await axios.post(`/user-account/${user.id}/restore`);
      this.fetchUsers();
    },

    async forceDelete(user) {
      if (!confirm("Permanently delete?")) return;
      await axios.delete(`/user-account/${user.id}/force`);
      this.fetchUsers();
    },

    editUser(user) {
      alert("Edit: " + user.name);
    },

    viewUser(user) {
      alert("View: " + user.name);
    }

  }
};
</script>

<style scoped>
.card { border-radius: 10px; }

.modal-backdrop-custom {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-card {
  background: white;
  padding: 20px;
  width: 420px;
  border-radius: 10px;
}

.mobile-menu {
  position: absolute;
  right: 0;
  top: 45px;
  background: white;
  width: 150px;
  border-radius: 8px;
  overflow: hidden;
  z-index: 999;
}

.mobile-menu .dropdown-item {
  padding: 10px;
  width: 100%;
  text-align: left;
  border: none;
  background: white;
}
</style>