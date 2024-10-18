// src/services/axios.js
import axios from 'axios';

const csrfToken =  localStorage.getItem('token');

const instance = axios.create({
  baseURL: 'http://localhost:8000/api/', // Replace with your backend URL
  withCredentials: true,
  maxContentLength: Infinity,
  maxBodyLength: Infinity,
});

instance.interceptors.request.use((config) => {
    // Include the CSRF token in request headers if available
    if (csrfToken) {
            // config.headers['X-CSRF-TOKEN'] = csrfToken.split('=')[1];
            config.headers['accept'] = 'application/json,text/html';
            // config.headers['Authorization'] = `Bearer ${csrfToken.split('=')[1]}`;
            config.headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
        }

        // Modify request config if needed
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

instance.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default instance;
