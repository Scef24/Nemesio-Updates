<template>
    <div class="super-admin-container">
        <div class="header">
            <h1>Super Admin Panel</h1>
            <button @click="logout" class="logout-button">Logout</button>
        </div>
        <button @click="showRegisterModal = true" class="open-modal-button">Register New User</button>
        <button @click="showBlockedUsersModal = true" class="open-modal-button">Blocked Users</button>
        
        <!-- Register User Modal -->
        <div v-if="showRegisterModal" class="modal-overlay">
            <div class="modal">
                <h2>Register New User</h2>
                <form @submit.prevent="registerUser">
          <div class="input-group">
            <label for="register-fname">First Name</label>
            <input type="text" id="register-fname" v-model="newUser.fname" required />
          </div>
          <div class="input-group">
            <label for="register-lname">Last Name</label>
            <input type="text" id="register-lname" v-model="newUser.lname" required />
          </div>
          <div class="input-group">
            <label for="register-email">Email</label>
            <input type="email" id="register-email" v-model="newUser.email" required />
          </div>
          <div class="input-group">
            <label for="register-password">Password</label>
            <input type="password" id="register-password" v-model="newUser.password" required />
          </div>
          <button type="submit" class="register-button">Register</button>
          <button type="button" class="close-modal-button" @click="showRegisterModal = false">Close</button>
        </form>
            </div>
        </div>

        <!-- Blocked Users Modal -->
        <div v-if="showBlockedUsersModal" class="modal-overlay">
            <div class="modal">
                <h2>Blocked Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in blockedUsers" :key="user.id">
                            <td>{{ user.id }}</td>
                            <td>{{ user.fname }} {{ user.lname }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                <button @click="unblockUser(user.id)">Unblock</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="close-modal-button" @click="showBlockedUsersModal = false">Close</button>
            </div>
        </div>

        <div class="user-list-section">
            <h2>Registered Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.fname }} {{ user.lname }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            <button @click="deleteUser(user.id)">Delete</button>
                            <button @click="blockUser(user.id)">Block</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const users = ref([]);
const blockedUsers = ref([]);
const showRegisterModal = ref(false);
const showBlockedUsersModal = ref(false);
const newUser = ref({
    fname: '',
    lname: '',
    email: '',
    password: '',
    role: 'user' // Default role
});
const router = useRouter();

const fetchUsers = async () => {
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get('http://127.0.0.1:8000/api/users', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        users.value = response.data.filter(user => !user.is_blocked);
        blockedUsers.value = response.data.filter(user => user.is_blocked);
       
    } catch (error) {
        console.error(error);
        alert('Error fetching users');
    }
};

const registerUser = async () => {
    try {
        const token = localStorage.getItem('token');
        await axios.post('http://127.0.0.1:8000/api/register', newUser.value, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        fetchUsers();
        newUser.value = { fname: '', lname: '', email: '', password: '', role: 'user' }; // Reset form
        showRegisterModal.value = false; // Close modal
        alert('User Registered successfully')
    } catch (error) {
        console.error(error);
        alert('Error registering user');
    }
};

const deleteUser = async (id) => {
    try {
        const token = localStorage.getItem('token');
        await axios.delete(`http://127.0.0.1:8000/api/users/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        fetchUsers();
    } catch (error) {
        console.error(error);
        alert('Error deleting user');
    }
};

const blockUser = async (id) => {
    try {
        const token = localStorage.getItem('token');
        await axios.post(`http://127.0.0.1:8000/api/users/${id}/block`, {}, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        alert("User Blocked")
        fetchUsers();
    } catch (error) {
        console.error(error);
        alert('Error blocking user');
    }
};

const unblockUser = async (id) => {
    try {
        const token = localStorage.getItem('token');
        await axios.post(`http://127.0.0.1:8000/api/users/${id}/unblock`, {}, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        alert('User unblocked!')
        fetchUsers();
    } catch (error) {
        console.error(error);
        alert('Error unblocking user');
    }
};

const logout = () => {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('fname');
    localStorage.removeItem('email');
    localStorage.removeItem('user_role');
    router.push({ name: 'Login' });
};

onMounted(fetchUsers);
</script>

<style scoped>
.super-admin-container {
    padding: 20px;
    background-color: #f9f9f9;
    color: #333;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  background-color: maroon;
  padding: 10px;
  color: white;
  width:84.4%;
}

h1 {
    margin: 0;
}

.register-section, .user-list-section {
    margin-bottom: 30px;
    color:black;
}

.register-section h2, .user-list-section h2 {
    color: maroon;
    margin-bottom: 15px;
}

.input-group {
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
    color: maroon;
    font-weight: bold;
}

.input-group input, .input-group select {
    width: 80%;
    padding: 10px;
    border: 1px solid maroon;
    border-radius: 6px;
    background-color: #fff;
    color:black;
}
.open-modal-button{
    margin-top: 5rem;
    display:flex;
} 

.register-button, .close-modal-button, .logout-button {
    padding: 10px 20px;
    background-color: maroon;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.register-button:hover, .close-modal-button:hover, .open-modal-button:hover, .logout-button:hover {
    background-color: #800000;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: white;
}

table th, table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

table th {
    background-color: maroon;
    color: white;
}

table td button {
    margin-right: 5px;
    padding: 5px 10px;
    background-color: maroon;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

table td button:hover {
    background-color: #800000;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.modal h2 {
    margin-top: 0;
    color: maroon;
}
</style>