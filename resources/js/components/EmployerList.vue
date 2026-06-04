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
        <button class="btn btn-secondary me-2" @click="fetchEmployers">Refresh</button>
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

            <tr v-for="(employer, index) in paginatedEmployers" :key="employer.id">

              <td>
                <div class="d-flex align-items-center">
                  <span class="me-2">{{ index + 1 }}</span>
                  <img v-if="employer.company_logo" :src="employer.company_logo" alt="Logo" class="table-logo rounded-circle" />
                </div>
              </td>

              <td>{{ employer.name }}</td>
              <td>{{ employer.company }}</td>

              <td style="min-width:320px">
                <div class="document-dropdown">
                  <button class="btn btn-sm btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" @click="toggleDocuments(employer.id)">
                    <span>Documents</span>
                    <span>{{ showDocuments[employer.id] ? '▲' : '▼' }}</span>
                  </button>

                  <div v-if="showDocuments[employer.id]" class="mt-3 document-card-list">
                    <div v-for="doc in documentList" :key="doc.key" class="document-row d-flex justify-content-between align-items-center px-3 py-3 mb-2 border rounded bg-white shadow-sm">
                      <div class="me-3 flex-grow-1">
                        <div class="fw-semibold mb-1">{{ doc.label }}</div>
                        <div class="text-muted document-filename">{{ employer.documents[doc.key]?.name || 'No file uploaded' }}</div>
                      </div>
                      <div class="d-flex align-items-center">
                        <a v-if="employer.documents[doc.key]" :href="employer.documents[doc.key].url" target="_blank" class="btn btn-primary btn-sm me-2">View</a>
                        <button v-if="employer.documents[doc.key] && !employer.approved" type="button" class="btn btn-danger btn-sm" @click="rejectDocument(employer, doc.key)">Reject</button>
                        <span v-else-if="!employer.documents[doc.key]" class="badge bg-secondary">Missing</span>
                      </div>
                    </div>
                  </div>
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
                <button
                  class="btn btn-danger btn-sm ms-2"
                  :disabled="employer.approved"
                  @click="rejectEmployer(employer)"
                >
                  Reject
                </button>
              </td>

            </tr>

          </tbody>

        </table>

        <div class="d-flex justify-content-between align-items-center p-3 border-top bg-white">
          <div>
            Showing {{ (currentPage - 1) * itemsPerPage + 1 }} to {{ Math.min(currentPage * itemsPerPage, filteredEmployers.length) }} of {{ filteredEmployers.length }} employers
          </div>
          <nav>
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

      employers: [],

      showDocuments: {},
      documentList: [
        { key: 'business_permit', label: 'Business Permit' },
        { key: 'dti_sec', label: 'DTI / SEC' },
        { key: 'bir_certificate', label: 'BIR Certificate' },
        { key: 'municipal_permit', label: "Mayor's Permit" },
        { key: 'valid_id', label: 'Valid ID' }
      ],

      currentPage: 1,
      itemsPerPage: 5,

      countdownTimer: null,
      refreshTimer: null

    };
  },

  async mounted() {
    await this.fetchEmployers();
    this.updateCountdown();
    this.countdownTimer = setInterval(this.updateCountdown, 1000);
    this.refreshTimer = setInterval(this.fetchEmployers, 10000); // refresh data every 10 seconds
  },

  watch: {
    showApproved() {
      this.currentPage = 1;
    },
    filteredEmployers() {
      if (this.currentPage > this.totalPages) {
        this.currentPage = this.totalPages;
      }
    }
  },

  beforeUnmount() {
    clearInterval(this.countdownTimer);
    clearInterval(this.refreshTimer);
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
    },

    paginatedEmployers() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredEmployers.slice(start, end);
    },

    totalPages() {
      return Math.max(1, Math.ceil(this.filteredEmployers.length / this.itemsPerPage));
    }

  },

  methods: {

    setPending() {
      this.showApproved = false;
      this.currentPage = 1;
    },

    setApproved() {
      this.showApproved = true;
      this.currentPage = 1;
    },

    async fetchEmployers() {
      try {
        const res = await axios.get('/admin/employers');
        this.employers = res.data.map(employer => ({
          id: employer.id,
          name: employer.contact_person || employer.username || employer.company_name || 'Unknown',
          company: employer.company_name || 'Unknown',
          company_logo: employer.company_logo || null,
          approved: String(employer.status).toLowerCase() === 'approved',
          expires_at: employer.expires_at || new Date(Date.now() + 3600000),
          remaining: '',
          documents: {
            business_permit: employer.documents?.business_permit || null,
            municipal_permit: employer.documents?.municipal_permit || null,
            dti_sec: employer.documents?.dti_sec || null,
            bir_certificate: employer.documents?.bir_certificate || null,
            valid_id: employer.documents?.valid_id || null,
          }
        }));
        this.currentPage = 1;
      } catch (error) {
        console.error('Failed to load employers:', error);
      }
    },

    isComplete(employer) {
      return [
        employer.documents.business_permit,
        employer.documents.municipal_permit,
        employer.documents.dti_sec,
        employer.documents.bir_certificate,
        employer.documents.valid_id,
      ].every(f => f !== null);
    },

    isExpired(employer) {
      return new Date() > new Date(employer.expires_at);
    },

    toggleDocuments(employerId) {
      this.showDocuments = {
        ...this.showDocuments,
        [employerId]: !this.showDocuments[employerId]
      };
    },

    async approveEmployer(employer) {
      if (!this.isComplete(employer)) return alert("Upload all documents first.");
      if (this.isExpired(employer)) return alert("Expired request.");

      try {
        await axios.post(`/admin/employer/${employer.id}/approve`);
        alert("Employer approved successfully.");
        await this.fetchEmployers();
      } catch (error) {
        alert(error.response?.data?.message || 'Failed to approve employer.');
      }
    },

    async rejectEmployer(employer) {
      if (!confirm('Reject this employer registration and request corrected files?')) return;

      try {
        await axios.post(`/admin/employer/${employer.id}/reject`);
        alert("Employer rejected successfully.");
        await this.fetchEmployers();
      } catch (error) {
        alert(error.response?.data?.message || 'Failed to reject employer.');
      }
    },

    async rejectDocument(employer, documentKey) {
      if (!confirm('Reject this specific document? This will clear the file and set the employer back to pending.')) return;

      try {
        await axios.post(`/admin/employer/${employer.id}/document/reject`, {
          document_key: documentKey
        });
        alert('Document rejected and employer reset to pending.');
        await this.fetchEmployers();
      } catch (error) {
        alert(error.response?.data?.message || 'Failed to reject document.');
      }
    },

    updateCountdown() {
      this.employers.forEach(e => {
        if (!e.approved || !e.expires_at || isNaN(new Date(e.expires_at).getTime())) {
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
.table-logo { width: 34px; height: 34px; object-fit: cover; }
.document-row { min-height: 70px; }
.document-dropdown button { min-width: 120px; }
.document-card-list { max-height: 360px; overflow-y: auto; }
.document-filename { max-width: 240px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; }
.text-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
</style>