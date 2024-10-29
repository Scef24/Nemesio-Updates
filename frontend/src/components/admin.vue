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
          <li><a href="#" @click="viewCalendar">Manage Calendar</a></li>
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
    <!-- Input for Title -->
    <input v-model="form.title" type="text" placeholder="Title" required />

    <!-- Textarea for Content -->
    <textarea v-model="form.context" placeholder="Content" required></textarea>

    <!-- Dropdown for Status -->
    <select v-model="form.status" required>
      <option value="On Going">On Going</option>
      <option value="Done">Done</option>
      <option value="Pending">Pending</option>
    </select>

    <!-- File Input for Image Upload -->
    <input type="file" @change="handleImageUpload" ref="fileInputRef" />

    <!-- Submit Button: Text dynamically changes based on form.id -->
    <button type="submit">{{ form.id !== null ? 'Update' : 'Add' }} Announcement</button>
</form>

  
          
          <input v-model="searchQuery" type="text" placeholder="Search announcements..." />
  
         
          <div class="announcement-list">
            <div
    class="announcement-card"
    v-for="announcement in filteredAnnouncements"
    :key="announcement.id">
    <h3>{{ announcement.title }}</h3>
    <p>{{ announcement.context }}</p>
    
    <!-- Display Image if Available -->
    <img v-if="announcement.image" :src="announcement.image" alt="Announcement Image" class="announcement-image" />
    
    <p><strong>Status:</strong> {{ announcement.status }}</p>
    <button @click="openEditModal(announcement)">Edit</button>
    <button @click="confirmDelete(announcement.id)">Delete</button>
  </div>  
        </div>
        </div>
        <div v-if="view === 'calendar'">
          <h1 class="page-title">Manage Calendar</h1>
          <vue-cal
            :events="calendarEvents"
            @cell-click="handleCellClick"
            @event-click="editEvent"
            style="height: 400px;"
          ></vue-cal>
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
        :message="'Are you sure you want to delete this announcement?'" 
        @close="isConfirmationVisible = false"
    /> 
        
    <ConfirmationModal 
        v-if="isLogoutConfirmationVisible" 
        :isVisible="isLogoutConfirmationVisible" 
        :onConfirm="confirmLogout" 
        :onCancel="cancelLogout" 
        :message="'Are you sure you want to logout?'"
        @close="isLogoutConfirmationVisible = false" 
    />
    <EditAnnouncementModal
          v-if="isEditModalVisible"
          :isVisible="isEditModalVisible"
          :announcement="selectedAnnouncement"
          @close="handleEditModalClose"
        />
    <div v-if="isLoading" class="loader">Loading...</div> 
    <EventModal
      :isVisible="isModalVisible"
      @close="isModalVisible = false"
      @add-event="addEvent"
    />
    <EventDisplayModal
      :isVisible="isDisplayModalVisible"
      :events="eventsToDisplay"
      :date="selectedDate"
      @close="isDisplayModalVisible = false"
    />
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useRouter } from 'vue-router';
  import axios from 'axios';
  import { format } from 'date-fns';
  import ChangePasswordModal from './ChangePasswordModal.vue'; 
  import NotificationsModal from './NotificationsModal.vue'; 
  import ConfirmationModal from './ConfirmationModal.vue'; 
  import EditAnnouncementModal from './EditAnnouncementModal.vue';
  import VueCal from 'vue-cal';
  import 'vue-cal/dist/vuecal.css';
  import EventModal from './EventModal.vue'; // Import the modal component
  import EventDisplayModal from './EventDisplayModal.vue'; // Import the display modal

  const router = useRouter();

  const view = ref('announcements');
  const sidebarOpen = ref(false);  
  const announcements = ref([]);
  const form = ref({ id: null, title: '', context: '', status: 'On Going', image: null });
  const searchQuery = ref('');
  const isEditModalVisible = ref(false);
  const selectedAnnouncement = ref(null);
  const calendarEvents = ref([]);

  const openEditModal = (announcement) => {
    console.log("clickd")
    selectedAnnouncement.value = { ...announcement };
    isEditModalVisible.value = true;
  };

  const handleEditModalClose = (wasUpdated) => {
    isEditModalVisible.value = false;
    if (wasUpdated) {
      fetchAnnouncements(); // Refresh the list if an update was made
    }
  };

  const filteredAnnouncements = computed(() => {
    return announcements.value.filter(announcement =>
      announcement && announcement.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  });

  const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
      form.value.image = file;
    }
  };
  const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value; 
};
const addOrUpdateAnnouncement = async () => {
    isLoading.value = true; // Start loading
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('context', form.value.context);
    formData.append('status', form.value.status);

    // Append image to FormData only if an image is selected
    if (form.value.image) {
        formData.append('image', form.value.image);
    }

    try {
        if (form.value.id !== null) {
            await axios.put(`http://127.0.0.1:8000/api/announcement/${form.value.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
            });
            await fetchAnnouncements();
            announcements.value = [...announcements.value]; 
     
            modalMessage.value = 'Announcement updated successfully!'; 
        } else {
            const response = await axios.post('http://127.0.0.1:8000/api/announcement', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
            });
            announcements.value.push(response.data.announcement);
            modalMessage.value = 'Announcement added successfully!';
        }
        await fetchAnnouncements(); // Refresh the list
        announcements.value = [...announcements.value]; // Ensure UI updates
        resetForm(); // Clear the form
        isModalVisible.value = true; // Show success modal
    } catch (error) {
        console.error('Failed to add/update announcement:', error.response ? error.response.data : error.message);
        modalMessage.value = 'Failed to add/update announcement: ' + (error.response ? error.response.data.message : error.message);
        isModalVisible.value = true; // Show error modal
    } finally {
        isLoading.value = false; // End loading
    }
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
    form.value = { id: null, title: '', context: '', status: 'On Going', image: null };
    // Reset the file input using the ref
    if (fileInputRef.value) {
        fileInputRef.value.value = '';
    }
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
  
  const fileInputRef = ref(null); // Define the file input reference
  
  // Function to switch views
  const viewCalendar = () => {
    view.value = 'calendar';
    fetchCalendarEvents(); // Fetch events when switching to calendar view
  };

  // Fetch calendar events
  const fetchCalendarEvents = async () => {
    try {
      const response = await axios.get('http://127.0.0.1:8000/api/events', {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
      console.log('Fetched events:', response.data);
      calendarEvents.value = response.data.map(event => ({
        start: event.start,
        end: event.end,
        title: event.title,
      }));
      console.log('Mapped events:', calendarEvents.value);
    } catch (error) {
      console.error('Failed to fetch calendar events:', error);
    }
  };

  // Function to handle cell clicks
  const handleCellClick = (date) => {
    const formattedDate = format(new Date(date), 'yyyy-MM-dd');
    const eventsOnDate = calendarEvents.value.filter(event => event.start.startsWith(formattedDate));

    if (eventsOnDate.length > 0) {
      selectedDate.value = formattedDate;
      eventsToDisplay.value = eventsOnDate;
      isDisplayModalVisible.value = true; // Show modal to display events
    } else {
      selectedDate.value = formattedDate;
      isModalVisible.value = true; // Show modal to add event
    }
  };

  const selectedDate = ref('');

  const addEvent = (title) => {
    const newEvent = { start: selectedDate.value, end: selectedDate.value, title };
    calendarEvents.value.push(newEvent);
    saveEvent(newEvent);
  };

  // Edit an existing event
  const editEvent = (event) => {
    const newTitle = prompt('Edit event title:', event.title);
    if (newTitle) {
      event.title = newTitle;
      updateEvent(event);
    }
  };

  // Save a new event to the backend
  const saveEvent = async (event) => {
    try {
      await axios.post('http://127.0.0.1:8000/api/events', event, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
    } catch (error) {
      console.error('Failed to save event:', error);
    }
  };

  // Update an existing event on the backend
  const updateEvent = async (event) => {
    try {
      await axios.put(`http://127.0.0.1:8000/api/events/${event.id}`, event, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
    } catch (error) {
      console.error('Failed to update event:', error);
    }
  };

  const isDisplayModalVisible = ref(false);
  const eventsToDisplay = ref([]);

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
  .announcement-image {
    width: 100%; /* Make the image take the full width of its container */
    height: auto; /* Maintain the aspect ratio */
    max-height: 200px; /* Optional: Set a maximum height */
    object-fit: cover; /* Ensure the image covers the container without distortion */
    border-radius: 5px; /* Optional: Add rounded corners */
    margin-bottom: 10px; /* Space between the image and other content */
}
  </style>
    









