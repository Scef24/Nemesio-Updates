// src/config/axiosConfig.js
import axios from 'axios';

const axiosInstance = axios.create({
    baseURL: 'http://127.0.0.1:8000/api', // Laravel API base URL
    timeout: 5000,
    headers: {
        'Content-Type': 'application/json',
        // Include any necessary headers, e.g., Authorization
    },
});

// Interceptors can be added as needed
axiosInstance.interceptors.request.use(config => {
    // Add token or modify config if needed
    return config;
}, error => {
    return Promise.reject(error);
});

axiosInstance.interceptors.response.use(response => {
    return response;
}, error => {
    return Promise.reject(error);
});

export default axiosInstance;