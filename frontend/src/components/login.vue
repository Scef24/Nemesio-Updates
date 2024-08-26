<template>
  <div class="login-container">
    <div class="logo-section">
      <img src="/logo.png" alt="Logo" class="logo" />
      <h3 class="logo-text"><i>Nemesio Epifania Taneo Memorial High School</i></h3>
    </div>
    <div class="login-box">
      <h2 class="title">Login</h2>
      <form @submit.prevent="login">
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" v-model="email" required />
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" v-model="password" required />
        </div>
        <button type="submit" class="login-button">Login</button>
      </form>
      <button class="header-button" @click="handleButtonClick">Announcements</button>
      <Loader :isLoading="isLoading" />
      <NotificationModal :isVisible="showNotification" :message="notificationMessage" @close="showNotification = false" />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "../config/axiosConfig";
import Loader from './Loader.vue';
import NotificationModal from './NotificationsModal.vue';

const email = ref("");
const password = ref("");
const router = useRouter();
const isLoading = ref(false);
const showNotification = ref(false);
const notificationMessage = ref('');

const login = async () => {
  isLoading.value = true; // Show loader
  try {
    const response = await axios.post("/login", {
      email: email.value,
      password: password.value,
    });
    console.log("Login successful:", response.data);
    
    // Store the token in localStorage
    localStorage.setItem('token', response.data.token);

    // Redirect to the admin dashboard
    router.push({ name: 'AdminDashboard' });
  } catch (error) {
    if (error.response) {
      notificationMessage.value = 'Invalid credentials. Please try again.';
    } else {
      notificationMessage.value = 'An error occurred. Please try again.';
    }
    showNotification.value = true; // Show notification modal
  } finally {
    isLoading.value = false; // Hide loader
  }
};

const handleButtonClick = () => {
  router.push({name: 'Home'})
};
</script>

<style scoped>
html,
body {
  height: 100%;
  margin: 0;
}

.header-title {
  margin: 0;
  font-size: 1.5em;
  text-align: center;
  font-family: "Georgia", serif;
}

.header-button {
  background-color: white;
  color: maroon;
  border: none;
  border-radius: 5px;
  padding: 10px 15px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.header-button:hover {
  background-color: #f2f2f2;
}

.login-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  min-height: 100vh;
  background-color: white;
  padding: 20px;
  margin-top: 0;
}

.logo-text {
  margin-top: 10px;
  color: maroon;
  font-size: 1.2em;
  font-weight: normal;
  font-family: "Georgia", serif;
}

.logo-section {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-bottom: 10px;
}

.logo {
  max-width: 80%;
  height: auto;
  transition: max-width 0.3s ease;
}

.login-box {
  flex: 0 0 300px;
  background-color: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.title {
  color: maroon;
  margin-bottom: 20px;
}

.input-group {
  margin-bottom: 15px;
  text-align: left;
}

.input-group label {
  color: maroon;
  font-weight: bold;
  margin-bottom: 5px;
  display: block;
}

.input-group input {
  width: 90%;
  padding: 10px;
  border: 1px solid maroon;
  border-radius: 6px;
  background-color: #f9f9f9;
  font-size: 16px;
  color: black;
}

.input-group input:focus {
  outline: none;
  border-color: maroon;
}

.login-button {
  width: 100%;
  padding: 12px;
  background-color: maroon;
  color: white;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.login-button:hover {
  background-color: #800000;
}

/* Responsive Design */
@media (max-width: 768px) {
  .logo {
    margin-top: 5px;
    max-width: 60%;
  }

  .login-container {
    flex-direction: column;
    align-items: center;
    margin-top: 0%;
  }

  .logo-section {
    margin-bottom: 0px;
  }
}

@media (max-width: 480px) {
  .logo {
    max-width: 50%;
    margin-bottom: 0;
    margin-top: 5px;
  }
  .logo-text {
    display: none;
  }
  .login-box {
    width: 70%;
    padding: 15px;
    margin-top: 0;
  }
  .logo-section {
    margin-top: 0;
    padding: 0;
  }
}
</style>