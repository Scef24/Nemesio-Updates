<template>
    <div class="modal">
        <div class="modal-content">
            <span class="close" @click="closeModal">&times;</span>
            <h2>Change Password</h2>
            <input type="password" v-model="newPassword" placeholder="New Password" required />
            <input type="password" v-model="confirmPassword" placeholder="Confirm Password" required />
            <button @click="submitChangePassword">Change Password</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const emit = defineEmits(['close', 'changePassword']);
const newPassword = ref('');
const confirmPassword = ref('');

const closeModal = () => {
    emit('close');
};

const submitChangePassword = () => {
    if (newPassword.value !== confirmPassword.value) {
        alert("Passwords do not match!");
        return;
    }
    emit('changePassword', newPassword.value); // Emit the new password to the parent component
    closeModal();
};
</script>

<style scoped>
.modal {
    display: flex;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 90%; /* Use percentage for responsiveness */
    max-width: 400px; /* Max width for larger screens */
    text-align: center;
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow */
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

input[type="password"] {
    width: 80%; /* Full width */
    padding: 12px; /* Increased padding for comfort */
    margin: 10px 0; /* Margin for spacing */
    border: 1px solid #ccc; /* Light border */
    border-radius: 4px; /* Rounded corners */
    background-color: white; /* Set background color to white */
    color: #333; /* Text color */
    box-shadow: none; /* Remove inner shadow */
    transition: border-color 0.3s; /* Smooth transition */
    font-size: 16px; /* Font size to match login input */
}

input[type="password"]:focus {
    border-color: #007bff; /* Change border color on focus */
    outline: none; /* Remove default outline */
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Focus shadow */
}

button {
    background-color:maroon; /* Button color */
    color: white; /* Text color */
    padding: 12px 15px; /* Increased padding */
    border: none; /* No border */
    border-radius: 4px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor */
    transition: background-color 0.3s; /* Smooth transition */
    font-size: 16px; /* Font size to match login button */
}

button:hover {
    background-color: #2b1912; /* Darker shade on hover */
}

/* Responsive Styles */
@media (max-width: 600px) {
    .modal-content {
        padding: 15px; /* Reduce padding on smaller screens */
    }

    input[type="password"], button {
        font-size: 14px; /* Smaller font size for mobile */
        padding: 10px; /* Adjust padding for mobile */
    }
}
</style>