<template>
    <div class="dashboard-container">
       <!-- Hamburger Menu for Mobile View -->
    <button class="hamburger" @click="toggleSidebar">
      <span class="hamburger-bar"></span>
      <span class="hamburger-bar"></span>
      <span class="hamburger-bar"></span>
    </button> 
      <!-- Sidebar -->
<aside :class="['sidebar', { 'sidebar-open': sidebarOpen, 'sidebar-hidden': !sidebarOpen }]">
        <h2 class="sidebar-title">Admin Dashboard</h2>
        <ul class="sidebar-menu">
          <li><a href="#" @click="viewAnnouncements">Manage Announcements</a></li>
          <li><a href="#" @click="viewProfile">Profile</a></li>
          <li><button @click="logout">Logout</button></li>
        </ul>
      </aside>
  
      <!-- Main Content -->
      <div class="content-container">
        <div v-if="view === 'announcements'">
          <h1 class="page-title">Manage Announcements</h1>
          
          <!-- Announcement Form -->
          <form @submit.prevent="addOrUpdateAnnouncement">
            <input v-model="form.title" type="text" placeholder="Title" required />
            <textarea v-model="form.context" placeholder="Content" required></textarea>
            <select v-model="form.status" required>
              <option value="On Going">On Going</option>
              <option value="Done">Done</option>
              <option value="Pending">Pending</option>
            </select>
            <button type="submit">{{ form.id !== null ? 'Update' : 'Add' }} Announcement</button>
          </form>
  
          
          <input v-model="searchQuery" type="text" placeholder="Search announcements..." />
  
         
          <div class="announcement-list">
            <div
              class="announcement-card"
              v-for="announcement in filteredAnnouncements"
              :key="announcement.id"
            >
              <h3>{{ announcement.title }}</h3>
              <p>{{ announcement.context }}</p>
              <p><strong>Status:</strong> {{ announcement.status }}</p>
              <button @click="editAnnouncement(announcement)">Edit</button>
              <button @click="confirmDelete(announcement.id)">Delete</button>
            </div>
          </div>
        </div>
  
        <div v-if="view === 'profile'">
          <h1 class="page-title">Profile</h1>
          <div class="profile-details">
            <p><strong>Name:</strong> {{ admin.name }}</p>
            <p><strong>Email:</strong> {{ admin.email }}</p>
            <button @click="showChangePasswordModal = true">Change Password</button>
          </div>

          <ChangePasswordModal 
            v-if="showChangePasswordModal" 
            @close="showChangePasswordModal = false" 
            @changePassword="changePassword"
          />
        </div>
      </div>
    </div>
    <NotificationsModal :isVisible="isModalVisible" :message="modalMessage" @close="isModalVisible = false" />
    <ConfirmationModal 
        :isVisible="isConfirmationVisible" 
        :onConfirm="handleDelete" 
        @close="isConfirmationVisible = false" 
    />
    <ConfirmationModal 
        v-if="isLogoutConfirmationVisible" 
        :isVisible="isLogoutConfirmationVisible" 
        :onConfirm="confirmLogout" 
        :onCancel="cancelLogout" 
        message="Are you sure you want to logout?" 
        @close="isLogoutConfirmationVisible = false" 
    />
    <div v-if="isLoading" class="loader">Loading...</div> 
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useRouter } from 'vue-router';
  import axios from 'axios';
  import ChangePasswordModal from './ChangePasswordModal.vue'; 
  import NotificationsModal from './NotificationsModal.vue'; 
  import ConfirmationModal from './ConfirmationModal.vue'; 

  const router = useRouter();

  const view = ref('announcements');
  const sidebarOpen = ref(false);  
  const announcements = ref([]);
  const form = ref({ id: null, title: '', context: '', status: 'On Going' });
  const searchQuery = ref('');
  
  const filteredAnnouncements = computed(() => {
    return announcements.value.filter(announcement =>
      announcement && announcement.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  });
  
  
  const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value; 
};
  const addOrUpdateAnnouncement = async () => {
    isLoading.value = true; 
    try {
        if (form.value.id !== null) {
            await axios.put(`http://127.0.0.1:8000/api/announcement/${form.value.id}`, form.value, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`, 
                },
            });
            await fetchAnnouncements();
            announcements.value = [...announcements.value]; 
     
            modalMessage.value = 'Announcement updated successfully!'; 
        } else {
           
            const response = await axios.post('http://127.0.0.1:8000/api/announcement', form.value, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`, 
                },
            });
            announcements.value.push(response.data.announcement); 
            await fetchAnnouncements(); 
            announcements.value = [...announcements.value];
            modalMessage.value = 'Announcement added successfully!'; 
        }
        resetForm(); 
        isModalVisible.value = true; 
    } catch (error) {
        console.error('Failed to add/update announcement:', error.response ? error.response.data : error.message);
        modalMessage.value = 'Failed to add/update announcement: ' + (error.response ? error.response.data.message : error.message);
        isModalVisible.value = true; 
    } finally {
        isLoading.value = false; 
    }
};
  
  const editAnnouncement = (announcement) => {
    form.value = { ...announcement };
  };
  
  const isConfirmationVisible = ref(false);
  let announcementToDelete = ref(null); 

  const confirmDelete = (id) => {
    announcementToDelete.value = id; 
    isConfirmationVisible.value = true; 
  };

  const handleDelete = async () => {
    isLoading.value = true; 
    try {
        await axios.delete(`http://127.0.0.1:8000/api/announcement/${announcementToDelete.value}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        });
        announcements.value = announcements.value.filter(announcement => announcement.id !== announcementToDelete.value);
        modalMessage.value = 'Announcement deleted successfully!';
        isModalVisible.value = true
        await fetchAnnouncements(); 
            announcements.value = [...announcements.value];
    } catch (error) {
        console.error('Failed to delete announcement:', error.response ? error.response.data : error.message);
        modalMessage.value = 'Failed to delete announcement: ' + (error.response ? error.response.data.message : error.message);
        isModalVisible.value = true; 
    } finally {
        isLoading.value = false; 
        isConfirmationVisible.value = false; 
    }
  };
  
  const resetForm = () => {
    form.value = { id: null, title: '', context: '', status: 'On Going' };
  };
  
  const viewAnnouncements = () => {
    view.value = 'announcements';
  };
  
  const viewProfile = () => {
    view.value = 'profile';
  };
  
  const viewHistory = () => {
   
  };
  
  const isLogoutConfirmationVisible = ref(false); 

  const logout = async () => {
    isLogoutConfirmationVisible.value = true; 
  };

 
  const confirmLogout = async () => {
    isLoading.value = true;
    try {
        await axios.post('http://127.0.0.1:8000/api/logout', {}, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`, 
            },
        });
    } catch (error) {
        console.error('Logout failed:', error.response ? error.response.data : error.message);
    } finally {
        localStorage.removeItem('token'); 
        router.push({ name: 'Login' }); 
        isLoading.value = false; 
    }
  };

  // Add a method to cancel logout
  const cancelLogout = () => {
    isLogoutConfirmationVisible.value = false; //
  };

  const admin = ref({
    name: localStorage.getItem('fname'),
    email: localStorage.getItem('email'),
    password : localStorage.getItem('password')
  });

  const showChangePasswordModal = ref(false);

  const isModalVisible = ref(false);
  const modalMessage = ref('');
  const isLoading = ref(false); 

  const changePassword = async (newPassword) => {
    isLoading.value = true; 
    try {
        
        const response = await axios.post('http://127.0.0.1:8000/api/change-password', {
            password: newPassword,
        }, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`, 
            },
        });
        modalMessage.value = 'Password changed successfully!'; 
        isModalVisible.value = true; 
    } catch (error) {
        modalMessage.value = 'Failed to change password: ' + (error.response ? error.response.data.message : error.message);
        isModalVisible.value = true; 
    } finally {
        isLoading.value = false; 
        showChangePasswordModal.value = false; 
    }
  };

  const fetchAnnouncements = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/announcement', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`, 
            },
        });
        announcements.value = response.data; 
    } catch (error) {
        console.error('Failed to fetch announcements:', error);
    }
  };

  onMounted(() => {
    fetchAnnouncements();
  });
  
  </script>
  
  <style scoped>
  body{
    overflow:hidden;
  }
  .dashboard-container {
    display: flex;
    min-height: 100vh;
    background-color: #fff; 
    overflow: hidden;
  }
  
  
  .sidebar {
    width: 250px;
    background-color: #800000; 
    padding: 20px;
    box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
  }
  announcement-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}
.announcement-card {
  background-color: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  width: 300px;
}
  .sidebar-title {
    color: white; 
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
  }
  
  .sidebar-menu {
    list-style: none;
    padding: 0;
  }
  
  .sidebar-menu li {
    margin-bottom: 15px;
  }
  
  .sidebar-menu a {
    color: white; /* White text for sidebar links */
    font-size: 18px;
    text-decoration: none;
    display: block;
    transition: color 0.3s ease;
  }
  
  .sidebar-menu a:hover {
    color: #ffe6f2;
  }
  
  /* Content Area Styles */
  .content-container {
    flex: 1;
    padding: 20px;
    overflow-y: auto; /* Allow vertical scrolling if needed */
  }
  
  .page-title {
    font-size: 32px;
    color: #800000; /* Maroon text for page title */
    margin-bottom: 30px;
    margin-top:30px;
  }
  
  form {
    margin-bottom: 30px;
  }
  
  input, textarea, select {
    color:black;
    display: block;
    width: 100%;
    margin-bottom: 15px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color:white; /* White background for inputs */
  }
  
  button {
    padding: 10px 20px;
    background-color: #800000; /* Maroon button */
    color: white; /* White text for buttons */
    border: none;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
  }
  
  button:hover {
    background-color: #660000; /* Darker maroon on hover */
  }
  
  .announcement-list {
    display: flex; /* Use flexbox for layout */
    flex-wrap: wrap; /* Allow items to wrap to the next line */
    gap: 20px; /* Space between announcement cards */
    max-height: 400px; /* Set a maximum height */
    overflow-y: auto; /* Enable vertical scrolling */
    border: 1px solid #ccc; /* Optional: Add a border */
    border-radius: 8px; /* Optional: Rounded corners */
    padding: 10px; /* Optional: Padding inside the list */
    margin-top: 20px; /* Optional: Space above the list */
  }
  
  .announcement-card {
    flex: 1 1 calc(30% - 20px); /* Adjust width to fit 3 cards per row with gap */
    margin-bottom: 15px; /* Space between announcement cards */
    padding: 10px; /* Padding inside each card */
    background-color: #f9f9f9; /* Light background for cards */
    border: 1px solid #ddd; /* Border for cards */
    border-radius: 5px; /* Rounded corners for cards */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: Add shadow for depth */
  }
  
  .announcement-card button {
    margin-top: 10px;
    margin-right: 10px;
  }
  
  .profile-details {
  background-color: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  max-width: 400px; /* Set a max width */
  width: 100%; /* Allow it to be responsive */
  margin: 0 auto; /* Center the profile details */
}
  
  /* Hamburger Menu for Mobile View */
  .hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    position: fixed;
    top: 20px;
    left: 20px;
    background: none;
    border: none;
    cursor: pointer;
    z-index: 1000;
    color:black;
  }
  .hamburger:hover{
    background:none;
  }
  .hamburger-bar {
    width: 30px;
    height: 3px;
    background-color: #111011;
  }
  .hamburger:focus {
  outline: none; /* Remove the default focus outline */
}
  /* Responsive Styles */
  @media (max-width: 768px) {
    .dashboard-container {
      flex-direction: column;
    }
  
    .hamburger {
    display: flex; 
    top: 5px; 
    left: 5px; 
    
  }
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width:100px;
      height: 100%;
      transform: translateX(-100%);
      z-index: 999;
      transition: transform 0.3s ease;
    }
  
    .sidebar-hidden {
      transform: translateX(-100%);
    }
  
    .sidebar-open {
      transform: translateX(0);
    
    }
  
    .content-container {
      padding-top: 60px; /* Space for the hamburger menu */
    }
    .sidebar-title {
    font-size: 20px; /* Adjusted size for mobile view */
  }

  .sidebar-menu a {
    font-size: 16px; /* Adjusted size for mobile view */
  }
  .profile-details {
    padding: 15px; /* Adjust padding for mobile */
  }

  .profile-details p {
    font-size: 14px; /* Adjust font size for mobile */
  }
  .page-title {
    font-size: 24px; /* Adjusted size for mobile view */
  }
  }
  .loader {
    /* Add your loader styles here */
    text-align: center;
    font-size: 20px;
    color: #333;
  }
  </style>
    