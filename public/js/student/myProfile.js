document.addEventListener('DOMContentLoaded', () => {
    const courses = {
        "BSA": "Bachelor of Science in Accountancy",
        "BSAIS": "Bachelor of Science in Accounting Information Systems",
        "BSBA FMGT": "BSBA Major in Financial Management",
        "BSBA HRM": "BSBA Major in Human Resource Management",
        "BSBA MKTG": "BSBA Major in Marketing Management",
        "BS ENTREP": "Bachelor of Science in Entrepreneurship",
        "BS CRIM": "Bachelor of Science in Criminology",
        "BSISM": "Bachelor of Science in Industrial Security Management",
        "BECED": "Bachelor of Early Childhood Education",
        "BSE ENG": "Bachelor of Secondary Education Major in English",
        "BSE ENG-CHI": "Bachelor of Secondary Education Major in English with Additional Chinese Language and Pedagogy Courses",
        "BSE SCI": "Bachelor of Secondary Education Major in Science",
        "BTLED HE": "Bachelor of Technology and Livelihood Education Major in Home Economics",
        "CPE": "Certificate in Professional Education",
        "BS CPE": "Bachelor of Science in Computer Engineering",
        "BS ECE": "Bachelor of Science in Electronics Engineering",
        "BS EE": "Bachelor of Science in Electrical Engineering",
        "BS IE": "Bachelor of Science in Industrial Engineering",
        "ABBS": "Bachelor of Arts in Behavioral Sciences",
        "BA COMM": "Bachelor of Arts in Communication",
        "BA POS": "Bachelor of Arts in Political Science",
        "BS MATH": "Bachelor of Science in Mathematics",
        "BS PSY": "Bachelor of Science in Psychology",
        "BSIS": "Bachelor of Science in Information Systems",
        "BSIT": "Bachelor of Science in Information Technology",
        "BSCS": "Bachelor of Science in Computer Science",
        "BSEMC": "Bachelor of Science in Entertainment and Multimedia Computing",
        "BSOAD": "Bachelor of Science in Office Administration",
        "BSSW": "Bachelor of Science in Social Work",
        "BSTM": "Bachelor of Science in Tourism Management",
        "BSHM": "Bachelor of Science in Hospitality Management",
        "DPA": "Doctor in Public Administration",
        "BPA": "Bachelor of Public Administration",
        "BPA ECGE": "Bachelor of Public Administration – Evening Class for Govt. Employees",
        "MAED": "Master of Arts in Education Major in Educational Management",
        "MAT-EG": "Master of Arts in Teaching in the Early Grades",
        "MATS": "Master of Arts in Teaching Science",
        "MBA": "Master in Business Administration",
        "LAW": "Bachelor of Laws / Juris Doctor"
    };

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

    const allInputs = profileForm.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="number"], select');
    const editableInputs = Array.from(allInputs).filter(input => input.id !== 'studentNumber');

    const MAX_FILE_SIZE = 1 * 1024 * 1024;
    let originalProfileData = {};

    function populateCourses() {
        const select = document.getElementById('course');
        if (!select) return;
        const currentValue = select.value;
        select.innerHTML = '';
        const defaultOption = new Option('Select a Course', '');
        select.add(defaultOption);

        for (const [abbr, name] of Object.entries(courses)) {
            const option = new Option(`${abbr} - ${name}`, abbr);
            select.add(option);
        }
        select.value = currentValue;
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

                console.log("profile_updated:", profile.profile_updated);
                console.log("allow_edit:", profile.allow_edit);
                console.log("Edit button element:", editProfileBtn);

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
            if (typeof Swal !== 'undefined') Swal.fire('Error', 'Could not load your profile. ' + err.message, 'error');
            else alert('Could not load your profile. ' + err.message);
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
            Swal.fire('Missing Info', `Please fill in all required fields. Middle Name and Suffix are optional. (Missing: ${missingFields.join(', ')})`, 'warning');
            return;
        }

        const contact = formData.get('contact');
        if (!/^\d{11}$/.test(contact)) {
            Swal.fire('Invalid Contact', 'Contact number must be numeric and 11 digits.', 'warning');
            return;
        }

        const email = formData.get('email');
        if (!/^[^ -- -퟿豈-﷏ﷰ-￯]+@[^ -- -퟿豈-﷏ﷰ-￯]+\.[^ -- -퟿豈-﷏ﷰ-￯]+$/.test(email)) {
            Swal.fire('Invalid Email', 'Please enter a valid email address.', 'warning');
            return;
        }

        const profileImage = formData.get('profile_image');
        const hasProfilePic = profilePreview.src && !profilePreview.classList.contains('hidden');
        if (!hasProfilePic && (!profileImage || profileImage.size === 0)) {
            Swal.fire('Missing Profile Picture', 'Profile picture is required.', 'warning');
            return;
        }

        const regForm = formData.get('reg_form');
        const hasRegForm = viewRegForm.href && !viewRegForm.classList.contains('hidden');
        if (!hasRegForm && (!regForm || regForm.size === 0)) {
            Swal.fire('Missing Registration Form', 'Registration form is required.', 'warning');
            return;
        }

        const confirm = await Swal.fire({
            title: 'Confirm Changes',
            text: "Are you sure? You can only do this once.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!'
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
                await Swal.fire('Saved!', 'Your profile has been updated.', 'success');
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
            Swal.fire('Error', 'Could not save profile. ' + err.message, 'error');
        }
    });

    uploadInput?.addEventListener('change', (e) => {
        const file = e.target.files[0]; if (!file) return;
        if (file.size > MAX_FILE_SIZE) {
            alert("Image size must be less than 1MB. Please choose a smaller file.");
            uploadInput.value = ""; return;
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
            alert('Please upload a valid PDF file.');
            regFormUpload.value = ''; return;
        }
        const fileURL = URL.createObjectURL(file);
        viewRegForm.href = fileURL;
        viewRegForm.classList.remove('hidden');
        uploadBtn.classList.add('hidden');
    });

    function openModal(modal) { if (modal) { modal.classList.remove("hidden"); document.body.classList.add("overflow-hidden"); } }
    function closeModal(modal) { if (modal) { modal.classList.add("hidden"); document.body.classList.remove("overflow-hidden"); } }

    populateCourses();
    loadProfile();

});