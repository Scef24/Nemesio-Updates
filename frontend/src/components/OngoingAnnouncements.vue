<template>
  <div class="ongoing-announcements-container">
    <h1 class="page-title">Ongoing Announcements</h1>
    <div v-if="isLoading" class="loader">Loading...</div>
    <div v-else>
      <ul class="announcement-list">
        <li v-for="announcement in ongoingAnnouncements" :key="announcement.id" class="announcement-item">
          <h3>{{ announcement.title }}</h3>
          <p>{{ announcement.context }}</p>
          <p><strong>Status:</strong> {{ announcement.status }}</p>
          <p><strong>Date:</strong> {{ announcement.created_at }}</p>
          <button @click="editAnnouncement(announcement)">Edit</button>
          <button @click="confirmDelete(announcement.id)">Delete</button>
        </li>
      </ul>
      <div v-if="ongoingAnnouncements.length === 0">No ongoing announcements available.</div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const ongoingAnnouncements = ref([]);
const isLoading = ref(true);

const fetchOngoingAnnouncements = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/announcement?status=On Going', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if needed
      },
    });
    ongoingAnnouncements.value = response.data; // Assuming the response is an array of ongoing announcements
  } catch (error) {
    console.error('Failed to fetch ongoing announcements:', error);
  } finally {
    isLoading.value = false; // Stop loading
  }
};

const editAnnouncement = (announcement) => {
  // Logic to edit the announcement (e.g., open a modal or navigate to an edit page)
  console.log('Edit announcement:', announcement);
};

const confirmDelete = (id) => {
  // Logic to confirm deletion of the announcement
  console.log('Confirm delete for announcement ID:', id);
};

onMounted(() => {
  fetchOngoingAnnouncements(); // Fetch ongoing announcements when the component is mounted
});
</script>

<style scoped>
.ongoing-announcements-container {
  padding: 20px;
}

.page-title {
  font-size: 32px;
  color: #800000; /* Maroon text for page title */
  margin-bottom: 20px;
}

.announcement-list {
  list-style: none;
  padding: 0;
}

.announcement-item {
  background-color: #f9f9f9;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-bottom: 10px;
}

.loader {
  text-align: center;
  font-size: 20px;
  color: #333;
}
</style>
