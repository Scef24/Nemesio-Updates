<template>
    <div class="main-container">
      <!-- Hamburger Menu for Mobile View -->
      <button class="hamburger" @click="toggleSidebar">
        <span class="hamburger-bar"></span>
        <span class="hamburger-bar"></span>
        <span class="hamburger-bar"></span>
      </button>
  
      <!-- Sidebar -->
      <aside class="sidebar" :class="{ 'sidebar-hidden': !sidebarOpen, 'sidebar-open': sidebarOpen }">

        <h2 class="sidebar-title">Menu</h2>
        <ul class="sidebar-menu">
          <li><a href="/login" @click="goToLogin">Login</a></li>
        </ul>
      </aside>
  
      <!-- Announcements Section -->
      <div class="content-container">
        <h1 class="page-title">Announcements</h1>
        <div class="announcements-list">
          <div
            class="announcement-card"
            v-for="announcement in announcements"
            :key="announcement.id"
          >
            <h2 class="card-title">{{ announcement.title }}</h2>
            <p class="card-content">{{ announcement.context }}</p>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';

  const announcements = ref([]); // Initialize announcements as an empty array
  const sidebarOpen = ref(false); // Sidebar state

  const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
  };
  
  const goToHistory = () => {
    console.log("Navigating to History");
    sidebarOpen.value = false; // Close sidebar after clicking
  };
  
  const goToLogin = () => {
    console.log("Navigating to Login");
    sidebarOpen.value = false; // Close sidebar after clicking
  };

  // Fetch announcements from the backend API
  const fetchAnnouncements = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/announcements'); // Replace with your API URL
        console.log(response.data)
        announcements.value = response.data; // Set announcements to the fetched data
    } catch (error) {
        console.error('Error fetching announcements:', error);
    }
  };

  // Fetch announcements when the component is mounted
  onMounted(() => {
    fetchAnnouncements();
  });
  </script>
  



<style scoped>
.main-container {
  display: flex;
  min-height: 100vh;
  background-color: #fff; /* White background */
  
}

/* Hamburger Menu */
.hamburger {
  display: none;
  flex-direction: column;
  gap: 5px;
  position: fixed;
  top: 10px;
  left: 20px;
  background: none;
  border: none;
  cursor: pointer;
  z-index: 1000;
}

.hamburger-bar {
  width: 30px;
  height: 3px;
  background-color: #020202;
}
.hamburger:focus {
  outline: none; /* Remove the default focus outline */
}

.sidebar {
  width: 130px;
  background-color: #800000; /* Maroon sidebar */
  padding: 20px;
  box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
  color: white; /* White text for sidebar */
}

.sidebar-title {
  color: white; /* White text for sidebar title */
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
  color: #ffe6f2; /* Lighter pink on hover */
}

/* Announcements Section */
.content-container {
  flex: 1;
  padding: 20px;
  transition: margin-left 0.3s ease;
}

.page-title {
  text-align: center;
  color: #800000; /* Maroon text for page title */
  margin-bottom: 30px;
  margin-top:30px;
  font-size: 32px;
  font-weight: bold;
}

.announcements-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}

.announcement-card {
  background-color: white; /* White background for announcement cards */
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  width: 300px;
  text-align: left;
  transition: transform 0.3s ease;
  border: 1px solid #800000; /* Maroon border for announcement cards */
}

.announcement-card:hover {
  transform: translateY(-5px);
}

.card-title {
  color: #800000; /* Maroon card title */
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 10px;
}

.card-content {
  color: #333; /* Dark text for card content */
}
.sidebar-hidden {
  transform: translateX(-100%);
}

.sidebar-open {
  transform: translateX(0);
}


/* Responsive Design */
@media (max-width: 768px) {
  .main-container {
    flex-direction: column;
  }

  .hamburger {
    display: flex;
  }
  .sidebar {
    width: 100px; /* Adjust this value for mobile view */
  }
  

  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    transform: translateX(-100%);
  }

  .sidebar-hidden {
    transform: translateX(-100%);
  }

  .sidebar-open {
    transform: translateX(0);
  }

  .content-container {
    margin-left: 0;
  }
}
@media (min-width: 769px) {
  .hamburger {
    display: flex; /* Show hamburger in desktop view */
  }
}

/* Show sidebar when open on mobile */
.sidebar-hidden {
  transform: translateX(-100%);
}

.sidebar-open {
  transform: translateX(0);
}
@media (max-width: 768px) {
  .hamburger {
    display: flex; /* Show hamburger in mobile view */
    top: 5px; /* Adjust top position as needed */
    left: 5px; /* Move hamburger closer to the left side */
  }
}
</style>