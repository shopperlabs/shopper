import axios from "axios";

const baseURL = (document.querySelector('meta[name="dashboard-url"]') as Element).getAttribute('content');
const fetchClient = () => {
  const defaultOptions = {
    baseURL: `${baseURL}`,
    headers: {
      'Content-Type': 'application/json',
      "X-Requested-With": "XMLHttpRequest"
    }
  };

  // Create instance
  const instance = axios.create(defaultOptions);

  // Set the AUTH token for any request
  instance.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    config.headers.Authorization = token ? `Bearer ${token}` : '';

    return config;
  });

  return instance;
};

export default fetchClient();
