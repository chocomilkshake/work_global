const jobCards = Array.from(document.querySelectorAll('.job-card'));
const jobSearchInput = document.getElementById('jobSearchInput');

const detailTitle = document.getElementById('detailTitle');
const detailCompany = document.getElementById('detailCompany');
const detailDescription = document.getElementById('detailDescription');
const detailLogo = document.getElementById('detailLogo');

const updateDetailPanel = card => {
    if (!card) return;

    jobCards.forEach(item => item.classList.remove('active'));
    card.classList.add('active');

    detailTitle.innerText = card.dataset.title || '';
    detailCompany.innerText = `${card.dataset.company || ''} • ${card.dataset.location || ''}`;
    detailDescription.innerText = card.dataset.description || '';

    if (card.dataset.image) {
        detailLogo.innerHTML = `<img src="${card.dataset.image}" alt="${card.dataset.company || 'Company logo'}">`;
    } else {
        detailLogo.innerHTML = `<span>${(card.dataset.title || '').charAt(0).toUpperCase()}</span>`;
    }
};

const filterJobs = () => {
    const query = jobSearchInput?.value.trim().toLowerCase() || '';
    let firstVisibleCard = null;

    jobCards.forEach(card => {
        const matches = query === '' || [
            card.dataset.title,
            card.dataset.company,
            card.dataset.location,
            card.dataset.description,
        ].some(field => field?.includes(query));

        card.style.display = matches ? '' : 'none';
        if (matches && !firstVisibleCard) {
            firstVisibleCard = card;
        }
    });

    if (firstVisibleCard) {
        updateDetailPanel(firstVisibleCard);
    }
};

jobCards.forEach(card => {
    card.addEventListener('click', () => updateDetailPanel(card));
});

jobSearchInput?.addEventListener('input', filterJobs);
