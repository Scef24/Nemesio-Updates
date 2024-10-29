<template>
  <div class="calendar-container">
    <vue-cal
      :events="events"
      @cell-click="handleCellClick"
      style="height: 400px;"
    ></vue-cal>

    <!-- Event Display Modal -->
    <EventDisplayModal
      :isVisible="isDisplayModalVisible"
      :events="eventsToDisplay"
      :date="selectedDate"
      @close="isDisplayModalVisible = false"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import VueCal from 'vue-cal';
import 'vue-cal/dist/vuecal.css';
import EventDisplayModal from './EventDisplayModal.vue'; // Import the display modal
import { format } from 'date-fns';

const events = ref([]);
const isDisplayModalVisible = ref(false);
const eventsToDisplay = ref([]);
const selectedDate = ref('');

const fetchEvents = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/events');
    events.value = response.data.map(event => ({
      start: event.start,
      end: event.end,
      title: event.title,
    }));
  } catch (error) {
    console.error('Failed to fetch events:', error);
  }
};

const handleCellClick = (date) => {
  const formattedDate = format(new Date(date), 'yyyy-MM-dd');
  const eventsOnDate = events.value.filter(event => event.start.startsWith(formattedDate));

  if (eventsOnDate.length > 0) {
    selectedDate.value = formattedDate;
    eventsToDisplay.value = eventsOnDate;
    isDisplayModalVisible.value = true; // Show modal to display events
  }
};

onMounted(() => {
  fetchEvents();
});
</script>

<style scoped>
.calendar-container {
  margin: 20px;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  max-width: 100%;
  overflow-x: auto;
}

@media (max-width: 768px) {
  .calendar-container {
    padding: 10px;
  }

  .vuecal {
    width: 100%;
    height: auto;
  }
}
</style>
