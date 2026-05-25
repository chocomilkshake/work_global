<template>
  <div class="container py-4">

    <!-- ================= DASHBOARD STATS ================= -->
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

      <div>
        <button class="btn btn-success me-2" @click="openAddModal">
          + Add User
        </button>

        <button class="btn btn-primary me-2" @click="showTrash = false">
          Active
        </button>

        <button class="btn btn-warning" @click="showTrash = true">
          Trash
        </button>
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
            <th width="220">Action</th>
          </tr>
        </thead>

        <tbody>

          <tr v-for="(user, index) in filteredUsers" :key="user.id">

            <td>{{ index + 1 }}</td>
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

          <tr v-if="filteredUsers.length === 0">
            <td colspan="7" class="text-center py-4">
              No users found
            </td>
          </tr>

        </tbody>

      </table>

    </div>

    <!-- ================= ADD USER MODAL ================= -->
    <transition name="slide-up">

      <div v-if="showAddModal" class="modal-backdrop-custom">

        <div class="modal-card">

          <h5 class="mb-3">Add User Account</h5>

          <!-- NAME -->
          <div class="mb-2">
            <label>Name</label>
            <input v-model="form.name" class="form-control" />
          </div>

          <!-- USERNAME -->
          <div class="mb-2">
            <label>Username</label>
            <input v-model="form.username" class="form-control" />
          </div>

          <!-- EMAIL -->
          <div class="mb-2">
            <label>Email</label>
            <input v-model="form.email" class="form-control" />
          </div>

          <!-- PASSWORD -->
          <div class="mb-2">
            <label>Password</label>
            <input type="password" v-model="form.password" class="form-control" />

            <div class="progress mt-2" style="height: 6px;">
              <div
                class="progress-bar"
                :class="{
                  'bg-danger': passwordStrength === 'Weak',
                  'bg-warning': passwordStrength === 'Medium',
                  'bg-success': passwordStrength === 'Strong'
                }"
                :style="{
                  width:
                    passwordStrength === 'Weak'
                      ? '33%'
                      : passwordStrength === 'Medium'
                      ? '66%'
                      : '100%'
                }"
              ></div>
            </div>

            <small
              :class="{
                'text-danger': passwordStrength === 'Weak',
                'text-warning': passwordStrength === 'Medium',
                'text-success': passwordStrength === 'Strong'
              }"
            >
              Strength: {{ passwordStrength }}
            </small>
          </div>

          <!-- CONFIRM PASSWORD -->
          <div class="mb-2">
            <label>Confirm Password</label>
            <input type="password" v-model="form.confirm_password" class="form-control" />

            <small v-if="form.confirm_password && form.password !== form.confirm_password" class="text-danger">
              Password does not match
            </small>
          </div>

          <!-- ROLE -->
          <div class="mb-2">
            <label>Role</label>
            <select v-model="form.role" class="form-control">
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
          </div>

          <!-- BUTTONS -->
          <div class="d-flex justify-content-end mt-3">

            <button class="btn btn-secondary me-2" @click="closeModal">
              Cancel
            </button>

            <button
              class="btn btn-success"
              :disabled="!isFormValid"
              @click="addUser"
            >
              Save
            </button>

          </div>

        </div>

      </div>

    </transition>

  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {

      showTrash: false,
      showAddModal: false,

      form: {
        name: "",
        username: "",
        email: "",
        password: "",
        confirm_password: "",
        role: "user"
      },

      users: [
        { id: 1, name: "System Admin", username: "admin01", email: "admin@mail.com", role: "admin", deleted_at: null },
        { id: 2, name: "John Doe", username: "john_doe", email: "john@mail.com", role: "user", deleted_at: null },
        { id: 3, name: "Jane Smith", username: "jane_smith", email: "jane@mail.com", role: "user", deleted_at: "2026-01-01" }
      ]
    };
  },

  mounted() {
    window.addEventListener("keydown", this.handleEsc);
  },

  beforeUnmount() {
    window.removeEventListener("keydown", this.handleEsc);
  },

  computed: {

    filteredUsers() {
      return this.users.filter(u =>
        this.showTrash ? u.deleted_at : !u.deleted_at
      );
    },

    adminCount() {
      return this.users.filter(u => u.role === "admin" && !u.deleted_at).length;
    },

    trashedUsers() {
      return this.users.filter(u => u.deleted_at);
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
      if (score === 3 || score === 4) return "Medium";
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

    openAddModal() {
      this.showAddModal = true;
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

    handleEsc(event) {
      if (event.key === "Escape" && this.showAddModal) {
        this.closeModal();
      }
    },

    async addUser() {

      if (!this.isFormValid) return;

      try {

        await axios.post('/user-account', {
          username: this.form.username,
          name: this.form.name,
          email: this.form.email,
          password: this.form.password,
          role: this.form.role
        });

        this.users.push({
          id: Date.now(),
          name: this.form.name,
          username: this.form.username,
          email: this.form.email,
          role: this.form.role,
          deleted_at: null
        });

        alert("User created successfully");

        this.closeModal();

      } catch (error) {
        console.log(error);
        alert("Failed to create user");
      }
    },

    viewUser(user) {
      alert(`View: ${user.name}`);
    },

    editUser(user) {
      alert(`Edit: ${user.name}`);
    },

    deleteUser(user) {
      user.deleted_at = new Date().toISOString();
    },

    restoreUser(user) {
      user.deleted_at = null;
    },

    forceDelete(user) {
      this.users = this.users.filter(u => u.id !== user.id);
    }
  }
};
</script>

<style scoped>

.modal-backdrop-custom {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-card {
  background: #fff;
  padding: 20px;
  width: 420px;
  border-radius: 10px;
}

/* animation */
.slide-up-enter-from {
  opacity: 0;
  transform: translateY(80px);
}

.slide-up-enter-active {
  transition: all 0.25s ease-out;
}

.slide-up-enter-to {
  opacity: 1;
  transform: translateY(0);
}

.slide-up-leave-active {
  transition: all 0.2s ease-in;
}

.slide-up-leave-to {
  opacity: 0;
  transform: translateY(80px);
}

</style>