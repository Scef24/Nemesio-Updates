<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">
      <h2>Edit Announcement</h2>
      
      <!-- Edit Form -->
      <form @submit.prevent="updateAnnouncement">
        <input v-model="form.title" type="text" placeholder="Title" required />
        <textarea v-model="form.context" placeholder="Content" required></textarea>
        <select v-model="form.status" required>
          <option value="On Going">On Going</option>
          <option value="Done">Done</option>
          <option value="Pending">Pending</option>
        </select>

        <button type="submit">Update</button>
      </form>
      
      <button @click="closeModal">Cancel</button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  isVisible: Boolean,
  announcement: Object,
  onClose: Function,
});

const form = ref({ ...props.announcement });

// Watch for changes in the announcement prop to update the form
watch(() => props.announcement, (newAnnouncement) => {
  form.value = { ...newAnnouncement };
  console.log('Form updated with new announcement:', form.value); // Debugging
});

const updateAnnouncement = async () => {
  console.log('Submitting form data:', form.value); // Debugging

  try {
    await axios.put(`http://127.0.0.1:8000/api/announcement/${form.value.id}`, {
      title: form.value.title,
      context: form.value.context,
      status: form.value.status,
    }, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    });

    // Close the modal and notify parent about the update
    props.onClose(true);
  } catch (error) {
    console.error('Failed to update announcement:', error);
  }
};

const closeModal = () => {
  props.onClose(false);
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.modal-content {
  background: #fff;
  padding: 30px;
  border-radius: 10px;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
  transition: transform 0.3s ease-in-out;
  transform: scale(1);
  color: black;
  box-sizing: border-box;
}

.modal-content h2 {
  margin-bottom: 25px;
  font-size: 1.8em;
  text-align: center;
  color: #800000; /* Dark red color */
}

.modal-content form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.modal-content input,
.modal-content textarea,
.modal-content select {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1em;
  background-color: transparent; /* Remove background */
  transition: border-color 0.3s ease;
  color: #800000;
  box-sizing: border-box;
}

.modal-content input:focus,
.modal-content textarea:focus,
.modal-content select:focus {
  border-color: #800000; /* Dark red color */
  outline: none;
  color: #800000;
}

.modal-content button {
  padding: 12px;
  border: none;
  border-radius: 5px;
  background-color: #800000; /* Dark red color */
  color: #fff;
  font-size: 1em;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: 10px;
}

.modal-content button:hover {
  background-color: #800000; /* Darker red */
}

.modal-content button:active {
  background-color: #800000; /* Even darker red */
}

@media (max-width: 600px) {
  .modal-content {
    padding: 20px;
    max-width: 90%;
  }
  .modal-overlay{
    margin-left: -20px;
  }
  .modal-content h2 {
    font-size: 1.5em;
  }

  .modal-content input,
  .modal-content textarea,
  .modal-content select,
  .modal-content button {
    font-size: 0.9em;
  }
}
</style>
