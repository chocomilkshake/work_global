const jobCards = document.querySelectorAll('.job-card');

const detailTitle = document.getElementById('detailTitle');
const detailCompany = document.getElementById('detailCompany');
const detailDescription = document.getElementById('detailDescription');
const detailLogo = document.getElementById('detailLogo');

jobCards.forEach(card => {

    card.addEventListener('click', () => {

        jobCards.forEach(item => {
            item.classList.remove('active');
        });

        card.classList.add('active');

        detailTitle.innerText = card.dataset.title;

        detailCompany.innerText =
            `${card.dataset.company} • ${card.dataset.location}`;

        detailDescription.innerText =
            card.dataset.description;

        detailLogo.innerHTML =
            `<i class="fa ${card.dataset.logo}"></i>`;
    });

});