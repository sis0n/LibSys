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

    const courseSelect = document.getElementById('course');
    const allInputs = profileForm.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="number"], select');
    const editableInputs = Array.from(allInputs).filter(input => input.id !== 'studentNumber');

    const MAX_FILE_SIZE = 1 * 1024 * 1024;
    let originalProfileData = {};
    let isEditing = false;
    
    // ==========================================================
    // SWEETALERT UTILITY FUNCTIONS (NAAYOS ANG TOAST BORDER)
    // ==========================================================

    const showProfileToast = (iconClass, title, text, theme, duration = 3000) => {
        const themeMap = {
            // Gumamit tayo ng full class name para maiwasan ang error sa Tailwind JIT compiler
            'warning': { color: 'text-orange-600', bg: 'bg-orange-100', border: 'border-orange-400', icon: 'ph-warning' },
            'error': { color: 'text-red-600', bg: 'bg-red-100', border: 'border-red-400', icon: 'ph-x-circle' },
            'success': { color: 'text-green-600', bg: 'bg-green-100', border: 'border-green-400', icon: 'ph-check-circle' },
        };
        const selectedTheme = themeMap[theme];

        Swal.fire({
            toast: true,
            position: "bottom-end",
            showConfirmButton: false,
            timer: duration,
            width: "360px",
            background: "transparent",
            html: `
                <div class="flex flex-col text-left">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full ${selectedTheme.bg} ${selectedTheme.color}">
                            <i class="ph ${selectedTheme.icon} text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-[15px] font-semibold ${selectedTheme.color}">${title}</h3>
                            <p class="text-[13px] text-gray-700 mt-0.5">${text}</p>
                        </div>
                    </div>
                </div>
            `,
            customClass: {
                // FIXED: Inayos ang border class application
                popup: `!rounded-xl !shadow-md !border-2 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] backdrop-blur-sm ${selectedTheme.border}`,
            },
        });
    };
    
    const showFinalModal = (isSuccess, title, message) => {
        const duration = 3000;
        let timerInterval;

        const theme = isSuccess ? {
            bg: 'bg-green-50',
            border: 'border-green-300',
            text: 'text-green-700',
            iconBg: 'bg-green-100',
            iconColor: 'text-green-600',
            iconClass: 'ph-check-circle',
            progressBarColor: 'bg-green-500',
        } : {
            bg: 'bg-red-50',
            border: 'border-red-300',
            text: 'text-red-700',
            iconBg: 'bg-red-100',
            iconColor: 'text-red-600',
            iconClass: 'ph-x-circle',
            progressBarColor: 'bg-red-500',
        };

        Swal.fire({
            showConfirmButton: false, 
            showCancelButton: false,
            buttonsStyling: false,
            
            backdrop: `rgba(0,0,0,0.3) backdrop-filter: blur(6px)`,
            timer: duration, 
            
            didOpen: () => {
                const progressBar = Swal.getHtmlContainer().querySelector("#progress-bar");
                let width = 100;
                timerInterval = setInterval(() => {
                    width -= 100 / (duration / 100); 
                    if (progressBar) {
                        progressBar.style.width = width + "%";
                    }
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            },

            html: `
                <div class="w-[450px] ${theme.bg} border-2 ${theme.border} rounded-2xl p-8 shadow-xl text-center">
                    <div class="flex items-center justify-center w-16 h-16 rounded-full ${theme.iconBg} mx-auto mb-4">
                        <i class="ph ${theme.iconClass} ${theme.iconColor} text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold ${theme.text}">${title}</h3>
                    <p class="text-base ${theme.text} mt-3 mb-4">
                        ${message}
                    </p>
                    <div class="w-full bg-gray-200 h-2 rounded mt-4 overflow-hidden">
                        <div id="progress-bar" class="${theme.progressBarColor} h-2 w-full transition-all duration-100 ease-linear"></div>
                    </div>
                </div>
            `,
            customClass: {
                popup: "!block !bg-transparent !shadow-none !p-0 !border-0 !w-auto !min-w-0 !max-w-none",
            },
        });
    };

    // ==========================================================

    async function loadCourseOptions(currentCourseId = null) {
        if (!courseSelect) return;

        courseSelect.innerHTML = '<option value="">Loading Courses...</option>';
        courseSelect.disabled = true;

        try {
            const res = await fetch('api/data/getAllCourses');
            const data = await res.json();

            courseSelect.innerHTML = '';
            const defaultOption = new Option('Select a Course', '');
            courseSelect.add(defaultOption);

            if (data.success && Array.isArray(data.courses)) {
                data.courses.forEach(course => {
                    const option = new Option(`${course.course_code} - ${course.course_title}`, String(course.course_id));
                    courseSelect.add(option);
                });
            }

            if (currentCourseId) {
                courseSelect.value = String(currentCourseId);
            }
            if (!isEditing) courseSelect.disabled = true;

        } catch (err) {
            console.error("Error fetching course options:", err);
            courseSelect.innerHTML = `<option value="${currentCourseId || ''}">Error loading courses</option>`;
            courseSelect.disabled = true;
        }
    }

    async function loadProfile() {
        try {
            const res = await fetch('api/student/myprofile/get');
            if (!res.ok) {
                const errData = await res.json().catch(() => null);
                throw new Error(errData?.message || `Failed to fetch profile. Status: ${res.status}`);
            }

            const data = await res.json();
            if (data.success && data.profile) {
                const profile = data.profile;
                originalProfileData = profile;

                const fullName = profile.full_name || [profile.first_name, profile.middle_name, profile.last_name, profile.suffix].filter(Boolean).join(' ') || 'Student Name';
                profileName.textContent = fullName;
                profileStudentId.textContent = profile.student_number || 'Student ID';

                document.getElementById('firstName').value = profile.first_name || '';
                document.getElementById('middleName').value = profile.middle_name || '';
                document.getElementById('lastName').value = profile.last_name || '';
                document.getElementById('suffix').value = profile.suffix || '';
                document.getElementById('studentNumber').value = profile.student_number || '';
                document.getElementById('yearLevel').value = profile.year_level || '';
                document.getElementById('section').value = profile.section || '';
                document.getElementById('email').value = profile.email || '';
                document.getElementById('contact').value = profile.contact || '';

                const currentCourseId = String(profile.course_id) || '';
                const courseDisplayName = profile.course_code ? `${profile.course_code} - ${profile.course_title}` : 'Select a Course';

                if (!isEditing && courseSelect) {
                    courseSelect.innerHTML = `<option value="${currentCourseId}">${courseDisplayName}</option>`;
                    courseSelect.value = currentCourseId;
                    courseSelect.disabled = true;
                } else if (isEditing) {
                    loadCourseOptions(currentCourseId);
                }

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
            // Updated to use consistent toast design
            if (typeof Swal !== 'undefined') showProfileToast('ph-x-circle', 'Error', 'Could not load your profile. ' + err.message, 'error', 5000);
            else alert('Could not load your profile. ' + err.message);
        }
    }

    function toggleEdit(shouldEdit) {
        isEditing = shouldEdit;

        if (shouldEdit) {
            loadCourseOptions(originalProfileData.course_id);

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
            // Restore original profile data (if user cancels)
            loadProfile();

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
            const value = formData.get(field);
            if (!value || value.trim() === '' || (field === 'course' && (value === '0' || value === ''))) {
                missingFields.push(field);
            }
        }

        if (missingFields.length > 0) {
            // Updated to use consistent toast design
            showProfileToast('ph-warning', 'Missing Info', `Please fill in all required fields. Middle Name and Suffix are optional. (Missing: ${missingFields.join(', ')})`, 'warning');
            return;
        }

        const contact = formData.get('contact');
        if (!/^\d{11}$/.test(contact)) {
            // Updated to use consistent toast design
            showProfileToast('ph-warning', 'Invalid Contact', 'Contact number must be numeric and 11 digits.', 'warning');
            return;
        }

        const email = formData.get('email');
        if (!/^[^--Ÿ -퟿豈-﷏ﷰ-￯]+@[^--Ÿ -퟿豈-﷏ﷰ-￯]+\.[^--Ÿ -퟿豈-﷏ﷰ-￯]+$/.test(email)) {
            // Updated to use consistent toast design
            showProfileToast('ph-warning', 'Invalid Email', 'Please enter a valid email address.', 'warning');
            return;
        }

        const profileImage = formData.get('profile_image');
        const hasProfilePic = profilePreview.src && !profilePreview.classList.contains('hidden');
        if (!hasProfilePic && (!profileImage || profileImage.size === 0)) {
            // Updated to use consistent toast design
            showProfileToast('ph-warning', 'Missing Profile Picture', 'Profile picture is required.', 'warning');
            return;
        }

        const regForm = formData.get('reg_form');
        const hasRegForm = viewRegForm.href && !viewRegForm.classList.contains('hidden');
        if (!hasRegForm && (!regForm || regForm.size === 0)) {
            // Updated to use consistent toast design
            showProfileToast('ph-warning', 'Missing Registration Form', 'Registration form is required.', 'warning');
            return;
        }
        
        // --- 🟠 CONFIRM CHANGES MODAL (NAAYOS ANG SIZE AT BORDER) ---
        const confirm = await Swal.fire({
            title: 'Confirm Changes',
            text: "Are you sure? You can only do this once.",
            icon: 'warning', // Orange warning icon
            showCancelButton: true,
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'Cancel',

            // I-set sa false para magamit ang custom button classes
            buttonsStyling: false, 
            
            customClass: {
                // Orange Border, Shadow, at Background Gradient (Same as your Confirmation Modals)
                // Reduced padding (p-4) and fixed size (w-[350px] max-w-md)
                popup: 
                    "!rounded-xl !shadow-md !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] !border-2 !border-orange-400 shadow-[0_0_8px_#ffb34770] w-[350px] !max-w-md",
                
                // Custom Orange Confirm Button
                confirmButton: 
                    "!bg-orange-600 !text-white !px-5 !py-2.5 !rounded-lg hover:!bg-orange-700 !mx-2",
                
                // Custom Gray Cancel Button
                cancelButton: 
                    "!bg-gray-200 !text-gray-800 !px-5 !py-2.5 !rounded-lg hover:!bg-gray-300 !mx-2",
                
                // Inayos ang title at text size para maging mas compact
                title: "!text-xl !font-semibold",
                htmlContainer: "!text-base"
            },
            // I-align ang buttons sa gitna
            didOpen: (popup) => {
                const actions = popup.querySelector('.swal2-actions');
                if (actions) actions.style.marginTop = '1rem';
            }
        });

        if (!confirm.isConfirmed) return;
        
        // 🟠 Loading Animation (Aligned to Orange theme)
        Swal.fire({
            background: "transparent",
            html: `
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-orange-200 border-t-orange-600"></div>
                    <p class="text-gray-700 text-[14px]">Saving profile...<br><span class="text-sm text-gray-500">Just a moment.</span></p>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ffb34770]",
            },
        });

        if (croppedBlob) {
            formData.append('profile_image', croppedBlob, 'profile.png');
        }

        const selectedCourseValue = formData.get('course');
        formData.delete('course');
        formData.append('course_id', selectedCourseValue);

        try {
            const res = await fetch('api/student/myprofile/update', {
                method: 'POST',
                body: formData
            });
            
            Swal.close(); // Close loading animation

            const result = await res.json();
            const message = result.message || 'Unknown response.';

            if (result.success) {
                // 🟢 Success Modal - Custom size/style (Same as Checkout Successful)
                showFinalModal(true, 'Saved!', 'Your profile has been successfully updated.');

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
                // 🔴 Error Modal - Custom size/style (Same as Checkout Failed)
                showFinalModal(false, 'Error', message);
                throw new Error(message);
            }
        } catch (err) {
            Swal.close();
            console.error("Save profile error:", err);
            // Fallback for network/generic error (using custom modal style)
            if (err.message && !err.message.includes('Unknown response')) {
                 showFinalModal(false, 'Network Error', 'Could not save profile due to connection issue.');
            } else {
                 showFinalModal(false, 'Error', 'Failed to save profile. Please check server response.');
            }
        }
    });

    uploadInput?.addEventListener('change', (e) => {
        const file = e.target.files[0]; if (!file) return;
        if (file.size > MAX_FILE_SIZE) {
            // Updated to use consistent toast design
            showProfileToast('ph-warning', 'File Too Large', 'Image size must be less than 1MB. Please choose a smaller file.', 'warning', 5000);
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
            // Updated to use consistent toast design
            showProfileToast('ph-warning', 'Invalid File Type', 'Please upload a valid PDF file.', 'warning', 5000);
            regFormUpload.value = ''; return;
        }
        const fileURL = URL.createObjectURL(file);
        viewRegForm.href = fileURL;
        viewRegForm.classList.remove('hidden');
        uploadBtn.classList.add('hidden');
    });

    function openModal(modal) { if (modal) { modal.classList.remove("hidden"); document.body.classList.add("overflow-hidden"); } }
    function closeModal(modal) { if (modal) { modal.classList.add("hidden"); document.body.classList.remove("overflow-hidden"); } }

    isEditing = false;
    loadProfile();

});