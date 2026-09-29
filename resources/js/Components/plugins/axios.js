import axios from "axios";

const axiosInstance = axios.create({
    baseURL: '/api'
})

const isPublicRequest = (url = '') => (
    url.startsWith('/public/') ||
    url === '/newsletter/subscribe' ||
    url.startsWith('/newsletter/unsubscribe/') ||
    url === '/contacts'
);

axiosInstance.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    const expiresAt = Number(localStorage.getItem('token_expires_at') || 0);
    const isLoginRequest = config.url?.endsWith('/login');
    const publicRequest = isPublicRequest(config.url);

    if (token && !isLoginRequest && (!expiresAt || expiresAt <= Date.now())) {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('token_expires_at');
    }

    if (token && !publicRequest) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
})

// Response interceptor to handle 401 errors
axiosInstance.interceptors.response.use(
    response => response,
    error => {
        const isLoginRequest = error.config?.url?.endsWith('/login');
        const publicRequest = isPublicRequest(error.config?.url);

        if (error.response && error.response.status === 401 && !isLoginRequest && !publicRequest) {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            localStorage.removeItem('token_expires_at');
            window.location.href = '/admin/login?unauthorized=true';
        }
        return Promise.reject(error);
    }
)

export const getData = async (url) => {
    try {
        const response = await axiosInstance.get(url);
        return response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
}

export const postData = async (url, data) => {
    try {
        const response = await axiosInstance.post(url, data);
        return response.data;
    } catch (error) {
        console.error('Error posting data:', error);
        throw error;
    }
}

export const putData = async (url, data) => {
    try {
        const response = await axiosInstance.put(url, data);
        return response.data;
    } catch (error) {
        console.error('Error updating data:', error);
        throw error;
    }
}

export const patchData = async (url, data = {}) => {
    try {
        const response = await axiosInstance.patch(url, data);
        return response.data;
    } catch (error) {
        console.error('Error patching data:', error);
        throw error;
    }
}

export const deleteData = async (url) => {
    try {
        const response = await axiosInstance.delete(url);
        return response.data;
    } catch (error) {
        console.error('Error deleting data:', error);
        throw error;
    }
}

export default axiosInstance;