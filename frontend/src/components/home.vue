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
        <li><a href="/about" @click="goToAbout">About</a></li>
      </ul>
    </aside>

    <!-- Button to toggle calendar visibility -->
    <button @click="toggleCalendar" class="toggle-calendar-button">
      {{ calendarVisible ? 'Show Announcements' : 'Show Calendar' }}
    </button>

    <!-- Conditional Rendering -->
    <div v-if="calendarVisible" class="calendar-container">
      <ReadOnlyCalendar />
    </div>

    <div v-else class="content-container">
      <h1 class="page-title">Announcements</h1>
      <div class="announcements-list">
        <div
          class="announcement-card"
          v-for="announcement in announcements"
          :key="announcement.id"
          @click="openModal(announcement.image)"
        >
          <img v-if="announcement.image" :src="announcement.image" alt="Announcement Image" class="announcement-image" height="200px" width="300px"/>
          <h2 class="card-title">{{ announcement.title }}</h2>
          <p class="card-content">{{ announcement.context }}</p>  
        </div>
      </div>
    </div>

    <!-- Inline Modal -->
    <div v-if="isModalVisible" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <img :src="selectedImage" alt="Announcement Image" class="modal-image" height="500px" width="600px" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import ReadOnlyCalendar from './ReadOnlyCalendar.vue';

const announcements = ref([]);
const calendarVisible = ref(false);
const isModalVisible = ref(false);
const selectedImage = ref('');
const sidebarOpen = ref(false);
let fetchInterval;

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const toggleCalendar = () => {
  calendarVisible.value = !calendarVisible.value;
};

const openModal = (imageSrc) => {
  selectedImage.value = imageSrc;
  isModalVisible.value = true;
};

const closeModal = () => {
  isModalVisible.value = false;
};

const fetchAnnouncements = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/announcements');
    announcements.value = response.data;
  } catch (error) {
    console.error('Error fetching announcements:', error);
  }
};

onMounted(() => {
  fetchAnnouncements();
  fetchInterval = setInterval(fetchAnnouncements, 5000); // Fetch every 60 seconds
});

onUnmounted(() => {
  clearInterval(fetchInterval);
});
</script>

<style scoped>
.main-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #fff;
}

/* Hamburger Menu */
.hamburger {
  display: flex;
  flex-direction: column;
  gap: 5px;
  position: fixed;
  top: 5px;
  left: 5px;
  background: none;
  border: none;
  cursor: pointer;
  z-index: 1000;
  color: black;
}

.hamburger-bar {
  width: 30px;
  height: 3px;
  background-color: #111011;
}
.hamburger:focus {
  outline: none; 
}

.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 250px;
  height: 100%;
  background-color: #800000;
  padding: 20px;
  box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
  transform: translateX(-100%);
  transition: transform 0.3s ease;
  z-index: 999;
}

.sidebar-open {
  transform: translateX(0);
}

.sidebar-hidden {
  transform: translateX(-100%);
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
  margin-left: 50px; /* Adjust based on sidebar width */
}

.page-title {
  text-align: center;
  color: #800000; /* Maroon text for page title */
  margin-bottom: 30px;
  margin-top: 30px;
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

/* Responsive Design */
@media (max-width: 768px) {
  .main-container {
    flex-direction: column;
  }

  .content-container {
    margin-left: 0;
  }

  .sidebar {
    width: 100%;
    height: auto;
    transform: translateX(-100%);
  }

  .sidebar-open {
    transform: translateX(0);
  }
}

body {
  display: flex;
  justify-content: center; /* Centers horizontally */
  align-items: center;    /* Centers vertically */
  height: 100vh;          /* Full viewport height */
  margin: 0;              /* Remove default margin */
}

h1 {
  text-align: center;     /* Center text */
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: transparent;
  padding: 0;
  border-radius: 0;
  max-width: 90vw; /* Use viewport width */
  max-height: 90vh; /* Use viewport height */
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-image {
  width: 100%;
  height: auto;
  object-fit: cover;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .main-container {
    flex-direction: column;
  }

  .content-container {
    margin-left: 0;
  }

  .sidebar {
    width: 50%;
    height: 100vh;
    transform: translateX(-100%); /* Ensure it uses translateX */
  }

  .sidebar-open {
    transform: translateX(0); /* Slide in from the left */
  }
}

/* Button to toggle calendar */
.toggle-calendar-button {
  margin-top: 5rem;
  padding: 10px 20px;
  background-color: #800000;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.toggle-calendar-button:hover {
  background-color: #a00000;
}

/* Calendar Section */
.calendar-container {
  margin-left: 0;
  background-color: #f9f9f9;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 100%;
  overflow-x: auto;
}
</style>
