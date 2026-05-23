document.addEventListener('DOMContentLoaded', function () {

    const profileInput = document.getElementById('profileImageInput');
    const profilePreview = document.getElementById('profilePreview');
    const removeBtn = document.getElementById('profileRemoveBtn');

    const defaultImage = profilePreview.dataset.default;

    profileInput.addEventListener('change', function () {

        const file = this.files[0];

        if (file) {

            const reader = new FileReader();

            reader.onload = function (e) {

                profilePreview.src = e.target.result;

            }

            reader.readAsDataURL(file);

        }

    });

    removeBtn.addEventListener('click', function () {

        profilePreview.src = defaultImage;
        profileInput.value = '';

    });

});