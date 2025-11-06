window.addEventListener("DOMContentLoaded", () => {
  // --- DOM Elements ---
  const profileForm = document.getElementById("profileForm");
  const editProfileBtn = document.getElementById("editProfileBtn");
  const saveProfileBtn = document.getElementById("saveProfileBtn");
  const cancelProfileBtn = document.getElementById("cancelProfileBtn");
  const formActions = document.getElementById("formActions");
  const profileLockedInfo = document.getElementById("profileLockedInfo");

  const fields = ['lastName', 'firstName', 'middleName', 'suffix', 'email'];
  const inputElements = {};
  fields.forEach(id => inputElements[id] = document.getElementById(id));

  const successMessage = document.createElement('div');
  successMessage.className = 'hidden fixed bottom-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
  document.body.appendChild(successMessage);

  const errorMessage = document.createElement('div');
  errorMessage.className = 'hidden fixed bottom-5 right-5 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
  document.body.appendChild(errorMessage);

  let originalFormState = {};

  function showMessage(element, message) {
    element.textContent = message;
    element.classList.remove('hidden');
    setTimeout(() => {
      element.classList.add('hidden');
    }, 3000);
  }

  function toggleEditMode(isEditing) {
    if (isEditing) {
      fields.forEach(id => {
        if(inputElements[id]) {
            inputElements[id].disabled = false;
            inputElements[id].classList.remove('bg-gray-50', 'border-gray-200');
            inputElements[id].classList.add('bg-white');
        }
      });
      formActions.classList.remove('hidden');
      editProfileBtn.classList.add('hidden');
    } else {
      fields.forEach(id => {
        if(inputElements[id]) {
            inputElements[id].disabled = true;
            inputElements[id].classList.add('bg-gray-50', 'border-gray-200');
            inputElements[id].classList.remove('bg-white');
            inputElements[id].value = originalFormState[id] || '';
        }
      });
      formActions.classList.add('hidden');
      editProfileBtn.classList.remove('hidden');
    }
  }

  async function loadProfile() {
    try {
      const res = await fetch('api/superadmin/myProfile/get'); 
      const data = await res.json();

      if (!data.success) {
        showMessage(errorMessage, data.message);
        return;
      }

      const profile = data.profile;
      
      const profileName = `${profile.first_name || ''} ${profile.last_name || ''}`.trim();
      document.getElementById('profileName').textContent = profileName || profile.username;
      document.getElementById('profileStudentId').textContent = profile.username;
      
      const dataMap = {
          'firstName': profile.first_name,
          'lastName': profile.last_name,
          'middleName': profile.middle_name,
          'suffix': profile.suffix,
          'email': profile.email,
          'contact': profile.contact 
      };
      
      fields.forEach(id => {
          const value = dataMap[id] || '';
          if(inputElements[id]) {
              inputElements[id].value = value;
          }
          originalFormState[id] = value; 
      });


      if (profile.allow_edit === 1) {
        editProfileBtn.classList.remove('hidden');
      } else {
        profileLockedInfo.textContent = "Profile editing is locked.";
        profileLockedInfo.classList.remove('hidden');
      }

    } catch (err) {
      showMessage(errorMessage, 'Failed to load profile data.');
      console.error(err);
    }
  }

  editProfileBtn.addEventListener('click', () => toggleEditMode(true));
  cancelProfileBtn.addEventListener('click', () => toggleEditMode(false));

  profileForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    saveProfileBtn.disabled = true;
    saveProfileBtn.textContent = 'Saving...';

    const payload = {
        'first_name': document.getElementById("firstName").value,
        'last_name': document.getElementById("lastName").value,
        'middle_name': document.getElementById("middleName").value,
        'suffix': document.getElementById("suffix").value,
        'email': document.getElementById("email").value
    };

    try {
      const res = await fetch('api/superadmin/myProfile/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload),
      });

      const data = await res.json();

      if (data.success) {
        showMessage(successMessage, data.message);
        
        const newName = `${payload.first_name} ${payload.last_name}`;
        document.getElementById('profileName').textContent = newName.trim();
        
        const sessionUserName = document.querySelector('#session-user-name');
        if(sessionUserName) sessionUserName.textContent = newName.trim();
        
        originalFormState = {
            'lastName': payload.last_name,
            'firstName': payload.first_name,
            'middleName': payload.middle_name,
            'suffix': payload.suffix,
            'email': payload.email
        };
        
        toggleEditMode(false); 
        
      } else {
        showMessage(errorMessage, data.message);
      }

    } catch (err) {
      showMessage(errorMessage, 'An error occurred. Please try again.');
      console.error(err);
    } finally {
      saveProfileBtn.disabled = false;
      saveProfileBtn.textContent = 'Save Changes';
    }
  });

  loadProfile();
});