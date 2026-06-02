<template>
    <section class="py-5 d-flex align-items-center" style="min-height: 80vh;">
        <div class="container">
            <div class="row justify-content-center align-items-center">

                <div class="col-lg-6 text-center mb-4 mb-lg-0">
                    <div class="mb-4">
                        <img :src="heroImg" class="img-fluid" alt="Hero Image">
                    </div>
                    <!-- employer logo preview intentionally shown inside the form, not here -->



                    <h2 class="fw-bold text-primary">Employer Portal</h2>
                    <p class="text-muted">
                        Register your company and start finding qualified candidates for your business.
                    </p>
                </div>

                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 rounded-4 p-4">
                        <h3 class="fw-bold mb-3 text-center">Employer Registration</h3>

                        <p class="text-muted text-center mb-4">
                            Create your company account
                        </p>

                        <div class="stepper mb-4">
                            <div class="step" :class="{ active: page === 1, done: page > 1 }">1</div>
                            <div class="line"></div>
                            <div class="step" :class="{ active: page === 2, done: page > 2 }">2</div>
                            <div class="line"></div>
                            <div class="step" :class="{ active: page === 3, done: page > 3 }">3</div>
                            <div class="line"></div>
                            <div class="step" :class="{ active: page === 4, done: page > 4 }">4</div>
                            <div class="line"></div>
                            <div class="step" :class="{ active: page === 5 }">5</div>
                        </div>

                        <div v-if="errorMessage" class="alert alert-danger">
                            {{ errorMessage }}
                        </div>

                        <div v-show="page === 1" class="form-section">
                            <div class="logo-wrapper mx-auto mb-3">
                                <img v-if="logoPreview" :src="logoPreview" class="logo-preview" />
                                <div v-else class="logo-placeholder">🏢</div>
                            </div>

                            <button class="btn btn-outline-primary btn-sm mb-3" @click="$refs.logoInput.click()">
                                Upload Logo
                            </button>

                            <input ref="logoInput" type="file" accept="image/*" style="display:none" @change="handleLogo" />

                            <p class="text-muted small mb-3">Recommended: 300x300 PNG or JPG</p>

                            <input v-model="form.company_name" class="form-control mb-2" placeholder="Company Name">
                            <input v-model="form.industry" class="form-control mb-2" placeholder="Industry">

                            <select v-model="form.business_type" class="form-select mb-2">
                                <option value="">Business Type</option>
                                <option>Corporation</option>
                                <option>Sole Proprietorship</option>
                                <option>Partnership</option>
                            </select>

                            <textarea v-model="form.description" class="form-control"
                                placeholder="Company Description"></textarea>
                        </div>

                        <div v-show="page === 2" class="form-section">
                            <h5>Business Address</h5>
                            <input v-model="form.office_address" class="form-control mb-2" placeholder="Office Address">
                            <input v-model="form.city" class="form-control mb-2" placeholder="City">
                            <input v-model="form.barangay" class="form-control" placeholder="Barangay">
                        </div>

                        <div v-show="page === 3" class="form-section">
                            <h5>Contact Information</h5>
                            <input v-model="form.contact_person" class="form-control mb-2" placeholder="Contact Person">
                            <input v-model="form.mobile_number" @input="handleMobileInput" type="tel"
                                inputmode="numeric" maxlength="11" class="form-control mb-2">
                            <input v-model="form.email" class="form-control" placeholder="Email">
                        </div>

                        <div v-show="page === 4" class="form-section">
                            <h5>Account Setup</h5>
                            <input v-model="form.username" class="form-control mb-2" placeholder="Username">
                            <input type="password" v-model="form.password" class="form-control mb-2"
                                placeholder="Password">
                            <p v-if="passwordStrengthLabel" class="small mb-2">Password strength: <span
                                    :class="passwordStrengthClass">{{ passwordStrengthLabel }}</span></p>
                            <input type="password" v-model="form.password_confirmation" class="form-control"
                                placeholder="Confirm Password">
                        </div>

                        <div v-show="page === 5" class="form-section">
                            <h5>Business Documents</h5>

                            <div v-for="doc in documents" :key="doc.key" class="mb-2">
                                <label class="form-label small">{{ doc.label }}</label>

                                <input type="file" class="form-control" :accept="doc.accept"
                                    @change="handleFile($event, doc.key)" />
                            </div>

                            <p class="text-muted small mt-2">
                                Allowed: JPG, PNG, PDF, DOC, DOCX (Max 5MB each)
                            </p>
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <button class="btn btn-light" @click="previousPage" :disabled="page === 1">
                                Back
                            </button>

                            <button v-if="page < totalPages" class="btn btn-primary" @click="nextPage">
                                Next
                            </button>

                            <button v-else class="btn btn-success" @click="submitForm">
                                Submit Registration
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import axios from 'axios'

const heroImg = '/assets/img/hero-img.png'
const page = ref(1)
const totalPages = 5
const errorMessage = ref('')
const logoPreview = ref(null)

const allowedDocumentTypes = [
    'image/jpeg',
    'image/png',
    'image/jpg',
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
]
const documents = [
    { key: 'business_permit', label: 'Business Permit', accept: '.jpg,.jpeg,.png,.pdf,.doc,.docx' },
    { key: 'dti_sec', label: 'DTI / SEC', accept: '.jpg,.jpeg,.png,.pdf,.doc,.docx' },
    { key: 'bir_certificate', label: 'BIR Certificate', accept: '.jpg,.jpeg,.png,.pdf,.doc,.docx' },
    { key: 'municipal_permit', label: "Mayor's Permit", accept: '.jpg,.jpeg,.png,.pdf,.doc,.docx' },
    { key: 'valid_id', label: 'Valid ID', accept: '.jpg,.jpeg,.png,.pdf,.doc,.docx' }
]
const allowedDocumentExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']
const maxFileSize = 5 * 1024 * 1024
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
const mobilePattern = /^\d{11}$/

const form = reactive({
    company_name: '',
    industry: '',
    business_type: '',
    description: '',

    office_address: '',
    city: '',
    barangay: '',

    contact_person: '',
    mobile_number: '',
    email: '',

    username: '',
    password: '',
    password_confirmation: '',

    company_logo: null,
    business_permit: null,
    dti_sec: null,
    bir_certificate: null,
    municipal_permit: null,
    valid_id: null
})

const getPasswordStrength = (password) => {
    if (!password || password.length < 8) {
        return 'weak'
    }

    const hasLower = /[a-z]/.test(password)
    const hasUpper = /[A-Z]/.test(password)
    const hasDigit = /\d/.test(password)
    const hasSymbol = /[^A-Za-z0-9]/.test(password)

    if (hasLower && hasUpper && hasDigit && hasSymbol) {
        return 'strong'
    }

    if ((hasLower || hasUpper) && hasDigit) {
        return 'normal'
    }

    return 'weak'
}

const passwordStrengthLabel = computed(() => {
    const strength = getPasswordStrength(form.password)
    return form.password ? strength : ''
})

const passwordStrengthClass = computed(() => {
    if (passwordStrengthLabel.value === 'strong') return 'text-success'
    if (passwordStrengthLabel.value === 'normal') return 'text-warning'
    return 'text-danger'
})

/* ================= LOGO ================= */
const handleLogo = (e) => {
    const file = e.target.files[0]
    if (!file) return

    if (!file.type.startsWith('image/')) {
        errorMessage.value = 'Logo must be an image file'
        return
    }

    form.company_logo = file
    logoPreview.value = URL.createObjectURL(file)
}

const handleMobileInput = (e) => {
    form.mobile_number = e.target.value.replace(/\D/g, '').slice(0, 11)
}

/* ================= FILES ================= */
const handleFile = (e, field) => {
    const file = e.target.files[0]
    if (!file) return

    const extension = file.name.split('.').pop().toLowerCase()
    const typeAllowed = allowedDocumentTypes.includes(file.type)
    const extensionAllowed = allowedDocumentExtensions.includes(extension)

    if (!typeAllowed && !extensionAllowed) {
        errorMessage.value = 'Documents must be JPG, PNG, PDF, DOC, or DOCX'
        e.target.value = ''
        return
    }

    if (file.size > maxFileSize) {
        errorMessage.value = 'Each document must be 5MB or smaller'
        e.target.value = ''
        return
    }

    form[field] = file
    errorMessage.value = ''
}

/* ================= VALIDATION ================= */
const validateStep = () => {
    errorMessage.value = ''

    if (page.value === 1) {
        if (!form.company_name || !form.industry || !form.business_type || !form.description) {
            errorMessage.value = 'Please complete Company Information'
            return false
        }
    }

    if (page.value === 2) {
        if (!form.office_address || !form.city || !form.barangay) {
            errorMessage.value = 'Please complete Address'
            return false
        }
    }

    if (page.value === 3) {
        if (!form.contact_person || !form.mobile_number || !form.email) {
            errorMessage.value = 'Please complete Contact Info'
            return false
        }

        if (!mobilePattern.test(form.mobile_number)) {
            errorMessage.value = 'Mobile Number must be 11 digits only'
            return false
        }

        if (!emailPattern.test(form.email)) {
            errorMessage.value = 'Please enter a valid email address'
            return false
        }
    }

    if (page.value === 4) {
        if (!form.username || !form.password || !form.password_confirmation) {
            errorMessage.value = 'Please complete Account Setup'
            return false
        }

        if (form.password !== form.password_confirmation) {
            errorMessage.value = 'Password does not match'
            return false
        }

        if (getPasswordStrength(form.password) === 'weak') {
            errorMessage.value = 'Password is too weak. Use letters, numbers, and symbols with at least 8 characters.'
            return false
        }
    }

    if (page.value === 5) {
        if (!form.business_permit || !form.dti_sec || !form.bir_certificate) {
            errorMessage.value = 'Please upload all required documents'
            return false
        }
    }

    return true
}

/* ================= NAV ================= */
const nextPage = () => {
    if (!validateStep()) return
    page.value++
}

const previousPage = () => {
    if (page.value > 1) page.value--
}

/* ================= SUBMIT ================= */
const submitForm = async () => {
    if (!validateStep()) return

    const formData = new FormData()

    Object.entries(form).forEach(([key, value]) => {
        if (value !== null && value !== '') {
            formData.append(key, value)
        }
    })

    try {
        const res = await axios.post('/employer/register', formData)
        // on success redirect to employer login page
        window.location.href = '/employer_login'
    } catch (err) {
        errorMessage.value = err.response?.data?.message || 'Registration failed. Please try again.'
    }
}
</script>

<style scoped>
.stepper {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}

.step {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
}

.step.active {
    background: #0d6efd;
    color: white;
}

.step.done {
    background: #198754;
    color: white;
}

.line {
    width: 50px;
    height: 2px;
    background: #dee2e6;
}

.logo-wrapper {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 2px dashed #ccc;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    background: #f8f9fa;
}

.logo-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.logo-placeholder {
    font-size: 28px;
}

.form-section {
    padding: 10px 5px;
}
</style>