document.addEventListener('DOMContentLoaded', () => {
    const uploadInput = document.getElementById('uploadProfile');
    const profilePreview = document.getElementById('profilePreview');
    const profileIcon = document.getElementById('profileIcon');
    const cropModal = document.getElementById('cropModal');
    const cropImage = document.getElementById('cropImage');
    const cancelCrop = document.getElementById('cancelCrop');
    const saveCrop = document.getElementById('saveCrop');
    const zoomIn = document.getElementById('zoomIn');
    const zoomOut = document.getElementById('zoomOut');
    const resetCrop = document.getElementById('resetCrop');
    let cropper;
    let croppedBlob = null;

    const regFormUpload = document.getElementById('regFormUpload');
    const viewRegForm = document.getElementById('viewRegForm');
    const uploadBtn = document.getElementById('uploadBtn');

    const profileForm = document.getElementById('profileForm');
    const editProfileBtn = document.getElementById('editProfileBtn');
    const cancelProfileBtn = document.getElementById('cancelProfileBtn');
    const formActions = document.getElementById('formActions');
    const profileName = document.getElementById('profileName');
    const profileStudentId = document.getElementById('profileStudentId');
    const uploadLabel = document.getElementById('uploadLabel');
    const profileLockedInfo = document.getElementById('profileLockedInfo');

    const allInputs = profileForm.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="number"]');
    const editableInputs = Array.from(allInputs).filter(input => input.id !== 'studentNumber');

    const MAX_FILE_SIZE = 1 * 1024 * 1024;
    let originalProfileData = {};

    // 🔸 Common SweetAlert Style
    function showAlert({ icon = 'info', title = '', text = '', html = '', confirmText = 'OK' }) {
        Swal.fire({
            background: "transparent",
            html: html || `
                <div class="flex flex-col items-center text-center">
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-orange-100 text-orange-600 mb-3">
                        <i class="ph ${icon === 'error' ? 'ph-warning' : icon === 'success' ? 'ph-check-circle' : icon === 'warning' ? 'ph-warning' : 'ph-info'} text-3xl"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">${title}</h2>
                    <p class="text-sm text-gray-600 mt-1">${text}</p>
                </div>
            `,
            confirmButtonText: confirmText,
            customClass: {
                popup: "!rounded-xl !shadow-lg !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] !border-2 !border-orange-400 shadow-[0_0_8px_#ffb34770]",
                confirmButton: "!bg-orange-600 !text-white !px-5 !py-2.5 !rounded-lg hover:!bg-orange-700",
            },
        });
    }

    async function loadProfile() {
        try {
            const res = await fetch('/libsys/public/student/myprofile/get');
            if (!res.ok) {
                const errData = await res.json().catch(() => null);
                throw new Error(errData?.message || `Failed to fetch profile. Status: ${res.status}`);
            }

            const data = await res.json();
            if (data.success && data.profile) {
                const profile = data.profile;
                originalProfileData = profile;

                const fullName = profile.full_name ||
                    [profile.first_name, profile.middle_name, profile.last_name, profile.suffix]
                        .filter(Boolean).join(' ') || 'Student Name';
                profileName.textContent = fullName;
                profileStudentId.textContent = profile.student_number || 'Student ID';

                document.getElementById('firstName').value = profile.first_name || '';
                document.getElementById('middleName').value = profile.middle_name || '';
                document.getElementById('lastName').value = profile.last_name || '';
                document.getElementById('suffix').value = profile.suffix || '';
                document.getElementById('studentNumber').value = profile.student_number || '';
                document.getElementById('course').value = profile.course || '';
                document.getElementById('yearLevel').value = profile.year_level || '';
                document.getElementById('section').value = profile.section || '';
                document.getElementById('email').value = profile.email || '';
                document.getElementById('contact').value = profile.contact || '';

                if (profile.profile_picture) {
                    profilePreview.src = profile.profile_picture;
                    profilePreview.classList.remove('hidden');
                    if (profileIcon) profileIcon.style.display = 'none';
                } else {
                    profilePreview.classList.add('hidden');
                    if (profileIcon) profileIcon.style.display = 'flex';
                }

                if (profile.registration_form) {
                    viewRegForm.href = profile.registration_form;
                    viewRegForm.classList.remove('hidden');
                    uploadBtn.classList.add('hidden');
                } else {
                    viewRegForm.classList.add('hidden');
                }

                if (profile.profile_updated == 0 || profile.can_edit_profile == 1) {
                    editProfileBtn.classList.remove('hidden');
                    profileLockedInfo.classList.add('hidden');
                } else {
                    editProfileBtn.classList.add('hidden');
                    profileLockedInfo.classList.remove('hidden');
                }

            } else {
                throw new Error(data.message || 'Could not parse profile data.');
            }
        } catch (err) {
            console.error("Load profile error:", err);
            showAlert({
                icon: 'error',
                title: 'Error Loading Profile',
                text: `Could not load your profile.<br>${err.message}`
            });
        }
    }

    function toggleEdit(isEditing) {
        if (isEditing) {
            editableInputs.forEach(input => {
                input.disabled = false;
                input.classList.remove('bg-gray-50', 'border-gray-200');
                input.classList.add('bg-white', 'border-gray-300', 'focus:border-orange-500', 'focus:ring-orange-500');
            });
            formActions.classList.remove('hidden');
            editProfileBtn.classList.add('hidden');
            uploadLabel.classList.remove('hidden');
            uploadBtn.classList.remove('hidden');
            regFormUpload.disabled = false;
            viewRegForm.classList.add('hidden');
        } else {
            editableInputs.forEach(input => {
                input.disabled = true;
                input.classList.add('bg-gray-50', 'border-gray-200');
                input.classList.remove('bg-white', 'border-gray-300', 'focus:border-orange-500', 'focus:ring-orange-500');
            });

            formActions.classList.add('hidden');
            regFormUpload.disabled = true;

            if (originalProfileData.profile_updated == 1) {
                editProfileBtn.classList.add('hidden');
                uploadLabel.classList.add('hidden');
                uploadBtn.classList.add('hidden');
                profileLockedInfo.classList.remove('hidden');
            } else {
                editProfileBtn.classList.remove('hidden');
                uploadLabel.classList.add('hidden');
                uploadBtn.classList.add('hidden');
            }

            if (originalProfileData.registration_form) {
                viewRegForm.classList.remove('hidden');
            }

            loadProfile();
        }
    }

    editProfileBtn?.addEventListener('click', () => toggleEdit(true));
    cancelProfileBtn?.addEventListener('click', () => toggleEdit(false));

    profileForm?.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(profileForm);
        const requiredFields = ['first_name', 'last_name', 'course', 'year_level', 'section', 'email', 'contact'];
        let missingFields = [];

        for (const field of requiredFields) {
            if (!formData.get(field) || formData.get(field).trim() === '') {
                missingFields.push(field);
            }
        }

        if (missingFields.length > 0) {
            showAlert({
                icon: 'warning',
                title: 'Missing Info',
                text: `Please fill in all required fields. Missing: ${missingFields.join(', ')}`
            });
            return;
        }

        const contact = formData.get('contact');
        if (!/^\d{11}$/.test(contact)) {
            showAlert({ icon: 'warning', title: 'Invalid Contact', text: 'Contact number must be numeric and 11 digits.' });
            return;
        }

        const email = formData.get('email');
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showAlert({ icon: 'warning', title: 'Invalid Email', text: 'Please enter a valid email address.' });
            return;
        }

        const profileImage = formData.get('profile_image');
        const hasProfilePic = profilePreview.src && !profilePreview.classList.contains('hidden');
        if (!hasProfilePic && (!profileImage || profileImage.size === 0)) {
            showAlert({ icon: 'warning', title: 'Missing Profile Picture', text: 'Profile picture is required.' });
            return;
        }

        const regForm = formData.get('reg_form');
        const hasRegForm = viewRegForm.href && !viewRegForm.classList.contains('hidden');
        if (!hasRegForm && (!regForm || regForm.size === 0)) {
            showAlert({ icon: 'warning', title: 'Missing Registration Form', text: 'Registration form is required.' });
            return;
        }

      const confirm = await Swal.fire({
  background: "transparent",
  html: `
    <div class="flex flex-col text-center">
      <div class="flex justify-center mb-3">
        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-orange-100 text-orange-600">
          <i class="ph ph-warning text-2xl"></i>
        </div>
      </div>
      <h3 class="text-[17px] font-semibold text-orange-700">Confirm Changes</h3>
      <p class="text-[14px] text-gray-700 mt-1">
        Are you sure you want to save these changes?<br>
        You can only do this once.
      </p>
    </div>
  `,
  showCancelButton: true,
  confirmButtonText: "Yes, save it!",
  cancelButtonText: "Cancel",
  customClass: {
    popup:
      "!rounded-xl !shadow-md !p-6 !w-[21rem] !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] !border-2 !border-orange-400 shadow-[0_0_8px_#ffb34770]",
    confirmButton:
      "!bg-orange-600 !text-white !px-5 !py-2.5 !rounded-lg hover:!bg-orange-700",
    cancelButton:
      "!bg-gray-200 !text-gray-800 !px-5 !py-2.5 !rounded-lg hover:!bg-gray-300",
  },
});
        if (!confirm.isConfirmed) return;

        if (croppedBlob) {
            formData.append('profile_image', croppedBlob, 'profile.png');
        }

        try {
            const res = await fetch('/libsys/public/student/myprofile/update', {
                method: 'POST',
                body: formData
            });
            const result = await res.json();

            if (result.success) {
                await Swal.fire({
  background: "transparent",
  html: `
    <div class="flex flex-col text-center">
      <div class="flex justify-center mb-3">
        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-orange-100 text-orange-600">
          <i class="ph ph-check-circle text-2xl"></i>
        </div>
      </div>
      <h3 class="text-[17px] font-semibold text-orange-700">Saved!</h3>
      <p class="text-[14px] text-gray-700 mt-1">
        Your profile has been updated.
      </p>
    </div>
  `,
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true,
  customClass: {
    popup:
      "!rounded-xl !shadow-md !p-6 !w-[20rem] !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] !border-2 !border-orange-400 shadow-[0_0_8px_#ffb34770]",
    timerProgressBar: "!bg-gradient-to-r !from-orange-400 !to-orange-500",
  },
  didOpen: () => {
    const progressBar = Swal.getTimerProgressBar();
    if (progressBar) {
      progressBar.style.height = "5px";
      progressBar.style.borderRadius = "0 0 12px 12px";
      progressBar.style.background = "linear-gradient(to right, #ff9f43, #ff6b00)";
      progressBar.style.boxShadow = "0 0 4px #ffb347aa";
    }
  }
});
                originalProfileData.profile_updated = 1;

                const headerFullname = document.getElementById('headerFullname');
                if (headerFullname) {
                    const newFullName = [
                        formData.get('first_name'),
                        formData.get('middle_name'),
                        formData.get('last_name'),
                        formData.get('suffix')
                    ].filter(Boolean).join(' ');
                    headerFullname.textContent = newFullName;
                }

                const headerAvatarContainer = document.getElementById('headerAvatarContainer');
                if (headerAvatarContainer && croppedBlob) {
                    headerAvatarContainer.innerHTML = '';
                    const newImg = document.createElement('img');
                    newImg.id = 'headerProfilePic';
                    newImg.src = URL.createObjectURL(croppedBlob);
                    newImg.alt = 'Profile';
                    newImg.className = 'w-9 h-9 rounded-full object-cover border border-orange-200';
                    headerAvatarContainer.appendChild(newImg);
                }

                loadProfile();
                toggleEdit(false);
            } else {
                throw new Error(result.message || 'Failed to save profile.');
            }
        } catch (err) {
            console.error("Save profile error:", err);
            showAlert({ icon: 'error', title: 'Error', text: 'Could not save profile.<br>' + err.message });
        }
    });

    uploadInput?.addEventListener('change', (e) => {
        const file = e.target.files[0]; if (!file) return;
        if (file.size > MAX_FILE_SIZE) {
            showAlert({ icon: 'warning', title: 'File Too Large', text: 'Image size must be less than 1MB.' });
            uploadInput.value = "";
            return;
        }
        const reader = new FileReader();
        reader.onload = () => {
            if (cropImage) cropImage.src = reader.result;
            openModal(cropModal);
            setTimeout(() => {
                cropper = new Cropper(cropImage, {
                    aspectRatio: 1, viewMode: 1, dragMode: 'move',
                    background: false, autoCropArea: 1, responsive: true, guides: true,
                });
            }, 100);
        };
        reader.readAsDataURL(file);
    });

    zoomIn?.addEventListener('click', () => cropper && cropper.zoom(0.1));
    zoomOut?.addEventListener('click', () => cropper && cropper.zoom(-0.1));
    resetCrop?.addEventListener('click', () => cropper && cropper.reset());

    cancelCrop?.addEventListener('click', () => {
        closeModal(cropModal);
        if (cropper) cropper.destroy();
        uploadInput.value = "";
    });

    saveCrop?.addEventListener('click', () => {
        const canvas = cropper.getCroppedCanvas({ width: 200, height: 200 });
        const circleCanvas = document.createElement('canvas');
        circleCanvas.width = 200; circleCanvas.height = 200;
        const circleCtx = circleCanvas.getContext('2d');
        circleCtx.beginPath(); circleCtx.arc(100, 100, 100, 0, Math.PI * 2);
        circleCtx.closePath(); circleCtx.clip();
        circleCtx.drawImage(canvas, 0, 0, 200, 200);

        profilePreview.src = circleCanvas.toDataURL('image/png');
        profilePreview.classList.remove('hidden');
        if (profileIcon) profileIcon.style.display = 'none';

        circleCanvas.toBlob((blob) => {
            croppedBlob = blob;
        }, 'image/png');

        closeModal(cropModal);
        cropper.destroy();
    });

    regFormUpload?.addEventListener('change', (e) => {
        const file = e.target.files[0]; if (!file) return;
        if (file.type !== 'application/pdf') {
            showAlert({ icon: 'warning', title: 'Invalid File', text: 'Please upload a valid PDF file.' });
            regFormUpload.value = '';
            return;
        }
        const fileURL = URL.createObjectURL(file);
        viewRegForm.href = fileURL;
        viewRegForm.classList.remove('hidden');
        uploadBtn.classList.add('hidden');
    });

    function openModal(modal) { if (modal) { modal.classList.remove("hidden"); document.body.classList.add("overflow-hidden"); } }
    function closeModal(modal) { if (modal) { modal.classList.add("hidden"); document.body.classList.remove("overflow-hidden"); } }

    loadProfile();
});
