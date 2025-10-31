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

  const profileForm = document.getElementById('profileForm');
  const editProfileBtn = document.getElementById('editProfileBtn');
  const cancelProfileBtn = document.getElementById('cancelProfileBtn');
  const formActions = document.getElementById('formActions');
  const profileName = document.getElementById('profileName');
  const profileStaffId = document.getElementById('profileFacultyId'); // keep ID same in HTML or rename
  const uploadLabel = document.getElementById('uploadLabel');

  const allInputs = profileForm.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="number"]');
  const editableInputs = Array.from(allInputs).filter(input => input.id !== 'facultyId');

  const MAX_FILE_SIZE = 1 * 1024 * 1024;
  let originalProfileData = {};

  // --- Load Staff Profile ---
  async function loadProfile() {
    try {
      const res = await fetch('api/staff/myprofile/get');
      if (!res.ok) throw new Error(`Failed to fetch profile. Status: ${res.status}`);
      const data = await res.json();

      if (data.success && data.profile) {
        const profile = data.profile;
        originalProfileData = profile;

        const fullName = profile.full_name ||
          [profile.first_name, profile.middle_name, profile.last_name, profile.suffix]
            .filter(Boolean).join(' ') || 'Staff Name';

        profileName.textContent = fullName;
        profileStaffId.textContent = profile.employee_id || 'Staff ID';

        document.getElementById('firstName').value = profile.first_name || '';
        document.getElementById('middleName').value = profile.middle_name || '';
        document.getElementById('lastName').value = profile.last_name || '';
        document.getElementById('suffix').value = profile.suffix || '';
        document.getElementById('position').value = profile.position || '';
        document.getElementById('email').value = profile.email || '';
        document.getElementById('contact').value = profile.contact || '';
        document.getElementById('facultyId').value = profile.employee_id || '';

        if (profile.profile_picture) {
          profilePreview.src = profile.profile_picture;
          profilePreview.classList.remove('hidden');
          if (profileIcon) profileIcon.style.display = 'none';
        } else {
          profilePreview.classList.add('hidden');
          if (profileIcon) profileIcon.style.display = 'flex';
        }

        editProfileBtn.classList.remove('hidden');
        uploadLabel.classList.add('hidden');
      } else {
        throw new Error(data.message || 'Could not parse profile data.');
      }
    } catch (err) {
      console.error("Load profile error:", err);
      alert('Could not load your profile. ' + err.message);
    }
  }

  // --- Toggle Edit Mode ---
  function toggleEdit(isEditing) {
    editableInputs.forEach(input => {
      input.disabled = !isEditing;
      input.classList.toggle('bg-white', isEditing);
      input.classList.toggle('border-gray-300', isEditing);
      input.classList.toggle('focus:border-orange-500', isEditing);
      input.classList.toggle('focus:ring-orange-500', isEditing);
      input.classList.toggle('bg-gray-50', !isEditing);
      input.classList.toggle('border-gray-200', !isEditing);
    });

    formActions.classList.toggle('hidden', !isEditing);
    editProfileBtn.classList.toggle('hidden', isEditing);
    uploadLabel.classList.toggle('hidden', !isEditing);

    if (!isEditing) loadProfile(); // Revert changes on cancel
  }

  editProfileBtn?.addEventListener('click', () => toggleEdit(true));
  cancelProfileBtn?.addEventListener('click', () => toggleEdit(false));

  // --- Submit Profile Form ---
  profileForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(profileForm);

    // Validate required fields
    const requiredFields = ['first_name', 'last_name', 'position', 'email', 'contact'];
    const missingFields = requiredFields.filter(f => !formData.get(f) || formData.get(f).trim() === '');
    if (missingFields.length) {
      alert(`Please fill in all required fields: ${missingFields.join(', ')}`);
      return;
    }

    const contact = formData.get('contact');
    if (!/^\d{11}$/.test(contact)) {
      alert('Contact number must be numeric and 11 digits.');
      return;
    }

    const email = formData.get('email');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      alert('Please enter a valid email address.');
      return;
    }

    if (croppedBlob) formData.append('profile_image', croppedBlob, 'profile.png');

    try {
      const res = await fetch('api/staff/myprofile/update', { method: 'POST', body: formData });
      const result = await res.json();

      if (result.success) {
        alert('Profile updated successfully!');
        croppedBlob = null;
        uploadInput.value = "";
        loadProfile();
        toggleEdit(false);
      } else {
        throw new Error(result.message || 'Failed to save profile.');
      }
    } catch (err) {
      console.error("Save profile error:", err);
      alert('Could not save profile. ' + err.message);
    }
  });

  // --- Profile Picture Upload & Crop ---
  uploadInput?.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    if (file.size > MAX_FILE_SIZE) {
      alert("Image size must be less than 1MB.");
      uploadInput.value = "";
      return;
    }
    const reader = new FileReader();
    reader.onload = () => {
      cropImage.src = reader.result;
      cropModal.classList.remove('hidden');
      setTimeout(() => {
        if (cropper) cropper.destroy();
        cropper = new Cropper(cropImage, { aspectRatio: 1, viewMode: 1, dragMode: 'move', background: false, autoCropArea: 1, responsive: true, guides: true });
      }, 100);
    };
    reader.readAsDataURL(file);
  });

  zoomIn?.addEventListener('click', () => cropper?.zoom(0.1));
  zoomOut?.addEventListener('click', () => cropper?.zoom(-0.1));
  resetCrop?.addEventListener('click', () => cropper?.reset());

  cancelCrop?.addEventListener('click', () => {
    cropModal.classList.add('hidden');
    cropper?.destroy();
    uploadInput.value = "";
    croppedBlob = null;
  });

  saveCrop?.addEventListener('click', () => {
    const canvas = cropper.getCroppedCanvas({ width: 200, height: 200 });
    const circleCanvas = document.createElement('canvas');
    circleCanvas.width = 200; circleCanvas.height = 200;
    const ctx = circleCanvas.getContext('2d');
    ctx.beginPath(); ctx.arc(100, 100, 100, 0, Math.PI * 2); ctx.closePath(); ctx.clip();
    ctx.drawImage(canvas, 0, 0, 200, 200);
    profilePreview.src = circleCanvas.toDataURL('image/png');
    profilePreview.classList.remove('hidden');
    if (profileIcon) profileIcon.style.display = 'none';
    circleCanvas.toBlob(blob => { croppedBlob = blob; }, 'image/png');
    cropModal.classList.add('hidden');
    cropper.destroy();
  });

  loadProfile();
});
