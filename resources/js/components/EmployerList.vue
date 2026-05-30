<template>
  <div class="container py-4">

    <!-- ================= STATS ================= -->
    <div class="row mb-4">

      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>Total Applicants</h6>
          <h3>{{ applicants.length }}</h3>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>Accepted</h6>
          <h3>{{ acceptedCount }}</h3>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>Expired</h6>
          <h3>{{ expiredCount }}</h3>
        </div>
      </div>

    </div>

    <!-- ================= HEADER ================= -->
    <div class="d-flex justify-content-between align-items-center mb-3">

      <h4>Employer Approval</h4>

      <div class="d-flex">

        <!-- DESKTOP -->
        <div class="d-none d-md-flex">
          <button class="btn btn-primary me-2" @click="setPending">
            Pending
          </button>

          <button class="btn btn-success" @click="setAccepted">
            Accepted
          </button>
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
            <th>Position</th>
            <th>Requirements</th>
            <th>Countdown</th>
            <th>Status</th>
            <th width="180">Action</th>
          </tr>
        </thead>

        <tbody>

          <tr v-for="(applicant, index) in filteredApplicants"
              :key="applicant.id">

            <td>{{ index + 1 }}</td>

            <td>{{ applicant.name }}</td>

            <td>{{ applicant.position }}</td>

            <!-- CHECKLIST -->
            <td>

              <div class="checklist">

                <div>
                  <input type="checkbox" v-model="applicant.requirements.resume">
                  Resume
                </div>

                <div>
                  <input type="checkbox" v-model="applicant.requirements.valid_id">
                  Valid ID
                </div>

                <div>
                  <input type="checkbox" v-model="applicant.requirements.nbi">
                  NBI
                </div>

                <div>
                  <input type="checkbox" v-model="applicant.requirements.interview">
                  Interview
                </div>

              </div>

            </td>

            <!-- LIVE COUNTDOWN -->
            <td>

              <div v-if="!isExpired(applicant)">
                <span class="badge bg-warning text-dark">
                  {{ applicant.remaining }}
                </span>
              </div>

              <div v-else>
                <span class="badge bg-danger">
                  Expired
                </span>
              </div>

            </td>

            <!-- STATUS -->
            <td>

              <span class="badge bg-success" v-if="applicant.accepted">
                Accepted
              </span>

              <span class="badge bg-danger" v-else-if="isExpired(applicant)">
                Expired
              </span>

              <span class="badge bg-secondary" v-else>
                Pending
              </span>

            </td>

            <!-- ACTION -->
            <td>

              <button
                class="btn btn-success btn-sm"
                :disabled="!isComplete(applicant) || isExpired(applicant) || applicant.accepted"
                @click="acceptApplicant(applicant)"
              >
                Accept
              </button>

            </td>

          </tr>

        </tbody>

      </table>

    </div>

  </div>
</template>

<script>
export default {

  data() {
    return {

      showAccepted: false,

      applicants: [

        {
          id: 1,
          name: "John Michael",
          position: "Web Developer",

          accepted: false,

          expires_at: new Date(Date.now() + 3600 * 1000),

          remaining: "",

          requirements: {
            resume: true,
            valid_id: true,
            nbi: false,
            interview: true
          }
        },

        {
          id: 2,
          name: "Diane Masmela",
          position: "UI Designer",

          accepted: false,

          expires_at: new Date(Date.now() + 7200 * 1000),

          remaining: "",

          requirements: {
            resume: true,
            valid_id: true,
            nbi: true,
            interview: true
          }
        }

      ]

    };
  },

  mounted() {

    this.updateCountdown();

    setInterval(() => {
      this.updateCountdown();
    }, 1000);

  },

  computed: {

    acceptedCount() {
      return this.applicants.filter(a => a.accepted).length;
    },

    expiredCount() {
      return this.applicants.filter(a => this.isExpired(a)).length;
    },

    filteredApplicants() {

      if (this.showAccepted) {
        return this.applicants.filter(a => a.accepted);
      }

      return this.applicants.filter(a => !a.accepted);
    }

  },

  methods: {

    setPending() {
      this.showAccepted = false;
    },

    setAccepted() {
      this.showAccepted = true;
    },

    isComplete(applicant) {

      return Object.values(applicant.requirements)
        .every(value => value === true);

    },

    isExpired(applicant) {

      return new Date() > new Date(applicant.expires_at);

    },

    acceptApplicant(applicant) {

      if (!this.isComplete(applicant)) {
        return alert("Complete all requirements first.");
      }

      if (this.isExpired(applicant)) {
        return alert("Application expired.");
      }

      applicant.accepted = true;

      alert("Applicant accepted successfully.");

    },

    updateCountdown() {

      this.applicants.forEach(applicant => {

        const now = new Date().getTime();

        const distance =
          new Date(applicant.expires_at).getTime() - now;

        if (distance <= 0) {

          applicant.remaining = "Expired";
          return;
        }

        const hours =
          Math.floor((distance % (1000 * 60 * 60 * 24))
          / (1000 * 60 * 60));

        const minutes =
          Math.floor((distance % (1000 * 60 * 60))
          / (1000 * 60));

        const seconds =
          Math.floor((distance % (1000 * 60))
          / 1000);

        applicant.remaining =
          `${hours}h ${minutes}m ${seconds}s`;

      });

    }

  }

};
</script>

<style scoped>

.card {
  border-radius: 12px;
}

.table th,
.table td {
  vertical-align: middle;
}

.checklist div {
  font-size: 14px;
  margin-bottom: 4px;
}

.btn:disabled {
  cursor: not-allowed;
}

.badge {
  font-size: 13px;
  padding: 7px 10px;
}

</style>