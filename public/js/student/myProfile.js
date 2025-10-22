document.addEventListener('DOMContentLoaded', () => {
    const uploadInput = document.getElementById('uploadProfile');
    const profilePreview = document.getElementById('profilePreview');
    const cropModal = document.getElementById('cropModal');
    const cropImage = document.getElementById('cropImage');
    const cancelCrop = document.getElementById('cancelCrop');
    const saveCrop = document.getElementById('saveCrop');
    const zoomIn = document.getElementById('zoomIn');
    const zoomOut = document.getElementById('zoomOut');
    const resetCrop = document.getElementById('resetCrop');
    let cropper;

    // ==========================================================
    // Registration Form Elements
    // ==========================================================
    const regFormUpload = document.getElementById('regFormUpload');
    const viewRegForm = document.getElementById('viewRegForm');
    const saveBtn = document.getElementById('saveRegForm');
    const removeBtn = document.getElementById('removeRegForm');
    const uploadBtn = document.getElementById('uploadBtn');

    // ==========================================================
    // Profile Picture Upload and Crop (with 1MB file size limit)
    // ==========================================================
    const MAX_FILE_SIZE = 1 * 1024 * 1024;

    uploadInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > MAX_FILE_SIZE) {
            alert("Image size must be less than 1MB. Please choose a smaller file.");
            uploadInput.value = "";
            return;
        }

        const reader = new FileReader();
        reader.onload = () => {
            cropImage.src = reader.result;
            cropModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                cropper = new Cropper(cropImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    background: false,
                    autoCropArea: 1,
                    responsive: true,
                    guides: true,
                });
            }, 100);
        };
        reader.readAsDataURL(file);
    });

    zoomIn.addEventListener('click', () => cropper && cropper.zoom(0.1));
    zoomOut.addEventListener('click', () => cropper && cropper.zoom(-0.1));
    resetCrop.addEventListener('click', () => cropper && cropper.reset());

    cancelCrop.addEventListener('click', () => {
        cropModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if (cropper) cropper.destroy();
        uploadInput.value = "";
    });

    saveCrop.addEventListener('click', () => {
        const canvas = cropper.getCroppedCanvas({
            width: 200,
            height: 200,
        });

        // Create circular mask
        const circleCanvas = document.createElement('canvas');
        circleCanvas.width = 200;
        circleCanvas.height = 200;
        const circleCtx = circleCanvas.getContext('2d');

        circleCtx.beginPath();
        circleCtx.arc(100, 100, 100, 0, Math.PI * 2);
        circleCtx.closePath();
        circleCtx.clip();
        circleCtx.drawImage(canvas, 0, 0, 200, 200);

        profilePreview.src = circleCanvas.toDataURL('image/png');
        profilePreview.classList.remove('hidden');
        profilePreview.previousElementSibling.style.display = 'none';

        cropModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        cropper.destroy();
    });

    // ==========================================================
    // Registration Form Upload with Save/Remove/View
    // ==========================================================
    regFormUpload.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        if (file.type !== 'application/pdf') {
            alert('Please upload a valid PDF file.');
            regFormUpload.value = '';
            return;
        }

        const fileURL = URL.createObjectURL(file);
        viewRegForm.href = fileURL;
        viewRegForm.classList.remove('hidden');
        saveBtn.classList.remove('hidden');
        removeBtn.classList.remove('hidden');
        uploadBtn.classList.add('hidden');
    });

    removeBtn.addEventListener('click', () => {
        if (confirm('Remove uploaded registration form?')) {
            regFormUpload.value = '';
            viewRegForm.classList.add('hidden');
            saveBtn.classList.add('hidden');
            removeBtn.classList.add('hidden');
            uploadBtn.classList.remove('hidden');
            viewRegForm.href = '#';
        }
    });

    saveBtn.addEventListener('click', () => {
        // pang test lang ng id
        alert('Registration form saved successfully!');
    });
});