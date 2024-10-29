import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
  },
});

export const setAuthToken = (token) => {
  if (token) {
    apiClient.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  } else {
    delete apiClient.defaults.headers.common['Authorization'];
  }
};

export default {
  login(credentials) {
    return apiClient.post('/login', credentials);
  },
  logout() {
    return apiClient.post('/logout');
  },
  updateUser(id, data) {
    return apiClient.put(`/users/${id}`, data);
  },
  deleteUser(id) {
    return apiClient.delete(`/users/${id}`);
  },
  setAuthToken,
};


  // Publications
  getPublications() {
    return apiClient.get('/publications');
  },

  getPublication(id) {
    return apiClient.get(`/publications/${id}`);
  },

  createPublication(data) {
    return apiClient.post('/publications', data);
  },

  updatePublication(id, data) {
    return apiClient.put(`/publications/${id}`, data);
  },

  deletePublication(id) {
    return apiClient.delete(`/publications/${id}`);
  },

  // Commentaires
  getComments() {
    return apiClient.get('/comments');
  },

  getComment(id) {
    return apiClient.get(`/comments/${id}`);
  },

  createComment(data) {
    return apiClient.post('/comments', data);
  },

  updateComment(id, data) {
    return apiClient.put(`/comments/${id}`, data);
  },

  deleteComment(id) {
    return apiClient.delete(`/comments/${id}`);
  },

  // Likes
  getLikes() {
    return apiClient.get('/likes');
  },

  createLike(data) {
    return apiClient.post('/likes', data);
  },

  deleteLike(id) {
    return apiClient.delete(`/likes/${id}`);
  },

  // Dislikes
  getDislikes() {
    return apiClient.get('/dislikes');
  },

  createDislike(data) {
    return apiClient.post('/dislikes', data);
  },

  deleteDislike(id) {
    return apiClient.delete(`/dislikes/${id}`);
  },

  // Messages
  getMessages() {
    return apiClient.get('/messages');
  },

  getMessage(id) {
    return apiClient.get(`/messages/${id}`);
  },

  createMessage(data) {
    return apiClient.post('/messages', data);
  },

  updateMessage(id, data) {
    return apiClient.put(`/messages/${id}`, data);
  },

  deleteMessage(id) {
    return apiClient.delete(`/messages/${id}`);
  },

  // Configuration du token
  setAuthToken
};

