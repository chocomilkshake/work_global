<template>
  <div class="container py-4">

    <div class="row mb-4">

      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>Total Employers</h6>
          <h3>{{ employers.length }}</h3>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>Approved</h6>
          <h3>{{ approvedCount }}</h3>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>Expired</h6>
          <h3>{{ expiredCount }}</h3>
        </div>
      </div>

    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">

      <h4>Employer Approval</h4>

      <div>
        <button class="btn btn-primary me-2" @click="setPending">Pending</button>
        <button class="btn btn-success" @click="setApproved">Approved</button>
      </div>

    </div>

    <div class="card shadow-sm">

      <div class="table-responsive">

        <table class="table table-hover mb-0">

          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Employer</th>
              <th>Company</th>
              <th>Documents</th>
              <th>Countdown</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>

            <tr v-for="(employer, index) in filteredEmployers" :key="employer.id">

              <td>{{ index + 1 }}</td>
              <td>{{ employer.name }}</td>
              <td>{{ employer.company }}</td>

              <td style="min-width:320px">

                <div class="mb-2">
                  <label class="form-label small">Business Permit</label>

                  <div v-if="employer.documents.business_permit">
                    <span class="badge bg-success me-2">Uploaded</span>
                    {{ employer.documents.business_permit.name }}

                    <a
                      :href="employer.documents.business_permit.url"
                      target="_blank"
                      class="btn btn-sm btn-outline-primary ms-2"
                    >View</a>

                    <button
                      class="btn btn-sm btn-danger ms-2"
                      @click="removeFile(employer, 'business_permit')"
                    >Remove</button>
                  </div>

                  <input
                    v-else
                    type="file"
                    class="form-control form-control-sm"
                    @change="uploadFile($event, employer, 'business_permit')"
                  >
                </div>

                <div class="mb-2">
                  <label class="form-label small">Mayor's Permit</label>

                  <div v-if="employer.documents.mayors_permit">
                    <span class="badge bg-success me-2">Uploaded</span>
                    {{ employer.documents.mayors_permit.name }}

                    <a :href="employer.documents.mayors_permit.url" target="_blank" class="btn btn-sm btn-outline-primary ms-2">View</a>

                    <button class="btn btn-sm btn-danger ms-2" @click="removeFile(employer, 'mayors_permit')">Remove</button>
                  </div>

                  <input
                    v-else
                    type="file"
                    class="form-control form-control-sm"
                    @change="uploadFile($event, employer, 'mayors_permit')"
                  >
                </div>

                <div class="mb-2">
                  <label class="form-label small">DTI / SEC</label>

                  <div v-if="employer.documents.dti_sec">
                    <span class="badge bg-success me-2">Uploaded</span>
                    {{ employer.documents.dti_sec.name }}

                    <a :href="employer.documents.dti_sec.url" target="_blank" class="btn btn-sm btn-outline-primary ms-2">View</a>

                    <button class="btn btn-sm btn-danger ms-2" @click="removeFile(employer, 'dti_sec')">Remove</button>
                  </div>

                  <input
                    v-else
                    type="file"
                    class="form-control form-control-sm"
                    @change="uploadFile($event, employer, 'dti_sec')"
                  >
                </div>

                <div class="mb-2">
                  <label class="form-label small">Valid ID</label>

                  <div v-if="employer.documents.valid_id">
                    <span class="badge bg-success me-2">Uploaded</span>
                    {{ employer.documents.valid_id.name }}

                    <a :href="employer.documents.valid_id.url" target="_blank" class="btn btn-sm btn-outline-primary ms-2">View</a>

                    <button class="btn btn-sm btn-danger ms-2" @click="removeFile(employer, 'valid_id')">Remove</button>
                  </div>

                  <input
                    v-else
                    type="file"
                    class="form-control form-control-sm"
                    @change="uploadFile($event, employer, 'valid_id')"
                  >
                </div>

                <div>
                  <label class="form-label small">Proof of Billing</label>

                  <div v-if="employer.documents.proof_of_billing">
                    <span class="badge bg-success me-2">Uploaded</span>
                    {{ employer.documents.proof_of_billing.name }}

                    <a :href="employer.documents.proof_of_billing.url" target="_blank" class="btn btn-sm btn-outline-primary ms-2">View</a>

                    <button class="btn btn-sm btn-danger ms-2" @click="removeFile(employer, 'proof_of_billing')">Remove</button>
                  </div>

                  <input
                    v-else
                    type="file"
                    class="form-control form-control-sm"
                    @change="uploadFile($event, employer, 'proof_of_billing')"
                  >
                </div>

              </td>

              <td>
                <span v-if="employer.approved && !isExpired(employer)" class="badge bg-warning text-dark">
                  {{ employer.remaining }}
                </span>
                <span v-else-if="isExpired(employer)" class="badge bg-danger">Expired</span>
                <span v-else class="badge bg-secondary">N/A</span>
              </td>

              <td>
                <span v-if="employer.approved" class="badge bg-success">Approved</span>
                <span v-else-if="isExpired(employer)" class="badge bg-danger">Expired</span>
                <span v-else class="badge bg-secondary">Pending</span>
              </td>

              <td>
                <button
                  class="btn btn-success btn-sm"
                  :disabled="!isComplete(employer) || isExpired(employer) || employer.approved"
                  @click="approveEmployer(employer)"
                >
                  Approve
                </button>
              </td>

            </tr>

          </tbody>

        </table>

      </div>

    </div>

  </div>
</template>

<script>
import axios from "axios";

export default {

  data() {
    return {

      showApproved: false,

      employers: []

    };
  },

  async mounted() {
    await this.fetchEmployers();
    this.updateCountdown();
    setInterval(this.updateCountdown, 1000);
  },

  computed: {

    approvedCount() {
      return this.employers.filter(e => e.approved).length;
    },

    expiredCount() {
      return this.employers.filter(e => this.isExpired(e)).length;
    },

    filteredEmployers() {
      return this.showApproved
        ? this.employers.filter(e => e.approved)
        : this.employers.filter(e => !e.approved);
    }

  },

  methods: {

    setPending() {
      this.showApproved = false;
    },

    setApproved() {
      this.showApproved = true;
    },

    async fetchEmployers() {
      try {
        const res = await axios.get('/admin/employers');
        this.employers = res.data.map(employer => ({
          id: employer.id,
          name: employer.contact_person || employer.username || employer.company_name || 'Unknown',
          company: employer.company_name || 'Unknown',
          approved: String(employer.status).toLowerCase() === 'approved',
          expires_at: employer.expires_at || new Date(Date.now() + 3600000),
          remaining: '',
          documents: {
            business_permit: null,
            mayors_permit: null,
            dti_sec: null,
            valid_id: null,
            proof_of_billing: null
          }
        }));
      } catch (error) {
        console.error('Failed to load employers:', error);
      }
    },

    uploadFile(event, employer, type) {
      const file = event.target.files[0];
      if (!file) return;

      employer.documents[type] = {
        name: file.name,
        file,
        url: URL.createObjectURL(file)
      };
    },

    removeFile(employer, type) {
      employer.documents[type] = null;
    },

    isComplete(employer) {
      return Object.values(employer.documents).every(f => f !== null);
    },

    isExpired(employer) {
      return new Date() > new Date(employer.expires_at);
    },

    approveEmployer(employer) {
      if (!this.isComplete(employer)) return alert("Upload all documents first.");
      if (this.isExpired(employer)) return alert("Expired request.");

      employer.approved = true;
      alert("Employer approved successfully.");
    },

    updateCountdown() {
      this.employers.forEach(e => {
        if (!e.approved) {
          e.remaining = "";
          return;
        }

        const diff = new Date(e.expires_at) - new Date();

        if (diff <= 0) {
          e.remaining = "Expired";
          return;
        }

        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);

        e.remaining = `${h}h ${m}m ${s}s`;
      });
    }

  }

};
</script>

<style scoped>
.card { border-radius: 12px; }
.table td, .table th { vertical-align: middle; }
.badge { font-size: 13px; padding: 6px 10px; }
</style>