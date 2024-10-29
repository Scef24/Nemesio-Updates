<template>
  <div class="modalEvent" v-if="isVisible">
    <div class="modal-content">
      <span class="close" @click="close">&times;</span>
      <h2>Events on {{ date }}</h2>
      <ul>
        <li v-for="event in events" :key="event.id">{{ event.title }}</li>
      </ul>
      <!-- <button @click="close">Close</button> -->
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isVisible: Boolean,
    events: Array,
    date: String,
  },
  methods: {
    close() {
      this.$emit('close');
    },
  },
};
</script>

<style scoped>
.modalEvent {
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  z-index: 1000;
}

.modal-content {
  background-color: #fff;
  padding: 30px;
  border-radius: 12px;
  width: 350px;
  text-align: center;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  position: relative;
}

.close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 18px;
  cursor: pointer;
  color: #333;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  margin: 10px 0;
  font-size: 16px;
}

button {
  padding: 10px 20px;
  background-color: #800000;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #660000;
}
</style>
