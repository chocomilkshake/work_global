<template>
  <section class="py-5 bg-light">
    <div class="container">
      <div class="row align-items-start g-5">

        <!-- LEFT -->
        <div class="col-lg-5 d-flex align-items-start">
          <div class="hero-content">

            <h1 class="fw-bold">
              <img :src="heroImg" class="img-fluid mt-4" alt="Register">
              Start your journey with
              <span class="text-primary">Work Global</span>
            </h1>

            <p class="text-muted mt-3">
              Create your profile and get matched with global employers.
            </p>

          </div>
        </div>

        <!-- RIGHT -->
        <div class="col-lg-7">

          <div class="card border-0 shadow-sm rounded-4 p-4">

            <h3 class="fw-bold mb-4">
              Applicant Registration
            </h3>

            <form @submit.prevent="submitForm">

              <!-- PROFILE -->
              <div class="profile-card">
                <div class="profile-cover"></div>

                <div class="profile-body">

                  <div class="profile-avatar-wrapper">

                    <img :src="profilePreview" class="profile-avatar">

                    <label for="profileImageInput" class="profile-camera">
                      <i class="fa fa-camera"></i>
                    </label>

                    <input type="file" id="profileImageInput" hidden accept="image/*" @change="onProfileChange">

                  </div>

                  <h5 class="mt-3">
                    Add Profile Photo
                  </h5>

                  <button type="button" class="btn btn-outline-danger btn-sm" @click="removeProfile">
                    Remove
                  </button>

                </div>
              </div>

              <br>

              <!-- NAME -->
              <div class="row g-3">

                <div class="col-md-4">
                  <label>First Name *</label>
                  <input v-model="form.first_name" class="form-control" required>
                </div>

                <div class="col-md-4">
                  <label>Middle Name</label>
                  <input v-model="form.middle_name" class="form-control">
                </div>

                <div class="col-md-4">
                  <label>Last Name *</label>
                  <input v-model="form.last_name" class="form-control" required>
                </div>

              </div>

              <hr class="my-4">

              <!-- ADDRESS -->
              <h6 class="fw-bold">
                Address Information
              </h6>

              <div class="row g-3">

                <div class="col-md-6">
                  <label>Street No *</label>
                  <input v-model="form.street_no" class="form-control" required>
                </div>

                <div class="col-md-6">
                  <label>Full Address *</label>
                  <input v-model="form.full_address" class="form-control" required>
                </div>

                <div class="col-md-4">
                  <label>Region</label>
                  <input v-model="form.region" class="form-control">
                </div>

                <div class="col-md-4">
                  <label>City</label>
                  <input v-model="form.city" class="form-control">
                </div>

                <div class="col-md-4">
                  <label>Barangay</label>
                  <input v-model="form.barangay" class="form-control">
                </div>

              </div>

              <hr class="my-4">

              <!-- CONTACT -->
              <h6 class="fw-bold">
                Contact Details
              </h6>

              <div class="row g-3">

                <div class="col-md-6">
                  <label>Contact Number</label>
                  <input v-model="form.contact_number" class="form-control">
                </div>

                <div class="col-md-6">
                  <label>Email</label>
                  <input v-model="form.email" type="email" class="form-control">
                </div>

              </div>

              <hr class="my-4">

              <!-- ACCOUNT -->
              <h6 class="fw-bold">
                Account Information
              </h6>

              <div class="row g-3">

                <div class="col-md-6">
                  <label>Username *</label>
                  <input v-model="form.username" class="form-control" required>
                </div>

                <div class="col-md-6">

                  <label>Password *</label>

                  <input v-model="form.password" type="password" class="form-control" required
                    @input="checkPasswordStrength">

                  <!-- meter -->
                  <div class="progress mt-2" style="height:8px">
                    <div class="progress-bar" :class="strengthClass" :style="{
                      width: strengthPercent + '%'
                    }">
                    </div>
                  </div>

                  <small :class="strengthTextClass">
                    {{ passwordStrength }}
                  </small>

                </div>

                <div class="col-md-12">

                  <label>
                    Re-enter Password *
                  </label>

                  <input v-model="form.password_confirmation" type="password" class="form-control" required>

                  <small v-if="form.password_confirmation && !passwordMatch" class="text-danger">
                    Passwords do not match
                  </small>

                  <small v-if="form.password_confirmation && passwordMatch" class="text-success">
                    Password matched ✓
                  </small>

                </div>

              </div>

              <hr class="my-4">

              <!-- RESUME -->
              <h6 class="fw-bold">
                Resume
              </h6>

              <input type="file" class="form-control" @change="onResumeChange">

              <small>
                PDF,DOC,DOCX
              </small>

              <button type="submit" class="btn btn-primary w-100 mt-4">
                Create Account
              </button>

            </form>

          </div>

        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import axios from 'axios'

const heroImg = '/assets/img/hero-img.png'

const profilePreview = ref('/assets/img/people.png')

const profile_image = ref(null)
const resume = ref(null)

const passwordStrength = ref('Weak')
const strengthPercent = ref(10)

const form = reactive({
  first_name: '',
  middle_name: '',
  last_name: '',
  street_no: '',
  full_address: '',
  region: '',
  city: '',
  barangay: '',
  contact_number: '',
  email: '',
  username: '',
  password: '',
  password_confirmation: ''
})

/* ================= PROFILE IMAGE ================= */
const onProfileChange = (e) => {
  const file = e.target.files[0]
  if (!file) return

  profile_image.value = file
  profilePreview.value = URL.createObjectURL(file)
}

const removeProfile = () => {
  profile_image.value = null
  profilePreview.value = '/assets/img/people.png'
}

/* ================= RESUME ================= */
const onResumeChange = (e) => {
  resume.value = e.target.files[0]
}

/* ================= PASSWORD STRENGTH ================= */
const checkPasswordStrength = () => {
  let score = 0
  const pass = form.password

  if (pass.length >= 8) score++
  if (/[A-Z]/.test(pass)) score++
  if (/[0-9]/.test(pass)) score++
  if (/[!@#$%^&*]/.test(pass)) score++

  switch (score) {
    case 1:
      passwordStrength.value = 'Weak'
      strengthPercent.value = 25
      break
    case 2:
      passwordStrength.value = 'Normal'
      strengthPercent.value = 50
      break
    case 3:
      passwordStrength.value = 'Strong'
      strengthPercent.value = 75
      break
    case 4:
      passwordStrength.value = 'Very Strong'
      strengthPercent.value = 100
      break
    default:
      passwordStrength.value = 'Weak'
      strengthPercent.value = 10
  }
}

/* ================= COMPUTED ================= */
const passwordMatch = computed(() => {
  return form.password.trim() === form.password_confirmation.trim()
})

const strengthClass = computed(() => ({
  'bg-danger': passwordStrength.value === 'Weak',
  'bg-warning': passwordStrength.value === 'Normal',
  'bg-success': ['Strong', 'Very Strong'].includes(passwordStrength.value)
}))

const strengthTextClass = computed(() => ({
  'text-danger': passwordStrength.value === 'Weak',
  'text-warning': passwordStrength.value === 'Normal',
  'text-success': ['Strong', 'Very Strong'].includes(passwordStrength.value)
}))

/* ================= SUBMIT ================= */
const submitForm = async () => {
  try {
    if (!passwordMatch.value) {
      alert('Passwords do not match')
      return
    }

    if (!['Strong', 'Very Strong'].includes(passwordStrength.value)) {
      alert('Password must be Strong or Very Strong')
      return
    }

    const formData = new FormData()

    // ✅ manually append (SAFE METHOD)
    formData.append('first_name', form.first_name)
    formData.append('middle_name', form.middle_name)
    formData.append('last_name', form.last_name)
    formData.append('street_no', form.street_no)
    formData.append('full_address', form.full_address)
    formData.append('region', form.region)
    formData.append('city', form.city)
    formData.append('barangay', form.barangay)
    formData.append('contact_number', form.contact_number)
    formData.append('email', form.email)
    formData.append('username', form.username)
    formData.append('password', form.password)
    formData.append('password_confirmation', form.password_confirmation)

    if (profile_image.value) {
      formData.append('profile_image', profile_image.value)
    }

    if (resume.value) {
      formData.append('resume', resume.value)
    }

    const response = await axios.post('/applicant/store', formData)

    console.log('SUCCESS:', response.data)

    window.location.href = '/login'

  } catch (error) {
    console.log('❌ VALIDATION ERROR:', error.response?.data)

    if (error.response?.status === 422) {
      const errors = error.response.data.errors

      let message = 'Validation Error:\n'
      for (const key in errors) {
        message += `${errors[key][0]}\n`
      }

      alert(message)
    } else {
      alert('Something went wrong. Check console.')
    }
  }
}
</script>