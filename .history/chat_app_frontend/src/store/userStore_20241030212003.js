// src/store/userStore.js
import { defineStore } from 'pinia';
import api from './api';

export const useUserStore = defineStore('userStore', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    accessToken: localStorage.getItem('accessToken') || null,
    imgprofile: localStorage.getItem('imgprofile') || null,
    isAuthenticated: !!localStorage.getItem('accessToken'),
    loginError: null, // Pour stocker l'erreur de connexion
  }),

  actions: {
    async register(credentials) {
      const formData = new FormData();
      Object.entries(credentials).forEach(([key, value]) => {
        formData.append(key, value);
      });

      try {
        const response = await api.register(formData);
        this.setUserData(response.data.user, response.data.access_token);
      } catch (error) {
        console.error('Erreur lors de l’inscription:', error.response ? error.response.data : error);
        throw error;
      }
    },

    async login(credentials) {
      try {
        const response = await api.login(credentials);
        if (response.data.user && response.data.token) {
          this.setUserData(response.data.user, response.data.token);
        } else {
          throw new Error('Données manquantes dans la réponse de connexion.');
        }
      } catch (error) {
        console.error('Erreur de connexion:', error);
        throw error; // Propager l'erreur pour qu'elle soit gérée dans le composant
      }
    }

    setUserData(user, token) {
      this.user = user;
      this.accessToken = token;
      this.imgprofile = user.imgprofile;
      this.isAuthenticated = true;

      localStorage.setItem('user', JSON.stringify(user));
      localStorage.setItem('accessToken', token);
      localStorage.setItem('imgprofile', user.imgprofile);

      api.setAuthToken(token);
    },

    async logout() {
      try {
        await api.logout();
        this.clearUserData();
      } catch (error) {
        console.error('Erreur lors de la déconnexion:', error);
      }
    },

    clearUserData() {
      this.user = null;
      this.accessToken = null;
      this.imgprofile = null;
      this.isAuthenticated = false;

      localStorage.removeItem('user');
      localStorage.removeItem('accessToken');
      localStorage.removeItem('imgprofile');

      api.setAuthToken(null);
    },

    loadUser() {
      // Peut être utilisée pour recharger l'état utilisateur à partir de localStorage si nécessaire
      const user = JSON.parse(localStorage.getItem('user'));
      const accessToken = localStorage.getItem('accessToken');
      if (user && accessToken) {
        this.user = user;
        this.accessToken = accessToken;
        this.isAuthenticated = true;
        api.setAuthToken(accessToken);
      }
    },
  },
});
