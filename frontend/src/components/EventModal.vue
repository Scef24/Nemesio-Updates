<template>
  <div class="modalEvent" v-if="isVisible">
    <div class="modal-content">
      <span class="close" @click="close">&times;</span>
      <h2>Add Event</h2>
      <input v-model="title" type="text" placeholder="Event Title" />
      <button @click="submit">Add Event</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isVisible: Boolean,
  },
  data() {
    return {
      title: '',
    };
  },
  methods: {
    close() {
      this.$emit('close');
    },
    submit() {
      if (this.title) {
        this.$emit('add-event', this.title);
        this.title = '';
        this.close();
      }
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

input[type="text"] {
  width: 100%;
  padding: 10px;
  margin-top: 15px;
  margin-bottom: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
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
