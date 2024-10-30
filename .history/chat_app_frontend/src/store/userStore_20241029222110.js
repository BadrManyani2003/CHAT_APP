import { defineStore } from 'pinia';
import api from './api';

export const useUserStore = defineStore('userStore', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    accessToken: localStorage.getItem('access_token') || null,
  }),

  actions: {
    async login(credentials) {
      try {
        const response = await api.login(credentials);
        this.user = response.data.user;
        this.accessToken = response.data.access_token;

        // Stocker les informations dans localStorage
        localStorage.setItem('access_token', response.data.access_token);
        localStorage.setItem('user', JSON.stringify(response.data.user));

        // Configuration du token
        api.setAuthToken(response.data.access_token);
      } catch (error) {
        console.error('Erreur de connexion:', error);
        throw error;
      }
    },

    loadUser() {
      const storedUser = localStorage.getItem('user');
      const storedToken = localStorage.getItem('access_token');

      if (storedUser && storedToken) {
        this.user = JSON.parse(storedUser);
        this.accessToken = storedToken;
        api.setAuthToken(storedToken);
      }
    },

    async logout() {
      try {
        await api.logout();
        this.clearUserData();
      } catch (error) {
        console.error('Erreur lors de la déconnexion:', error);
      }
    },

    async fetchUser(id) {
      try {
        const response = await api.getUser(id);
        this.user = response.data;
      } catch (error) {
        console.error('Erreur lors de la récupération de l’utilisateur :', error);
      }
    },

    async updateUser(id, updatedData) {
      try {
        const response = await api.updateUser(id, updatedData);
        this.user = response.data;
        localStorage.setItem('user', JSON.stringify(response.data));
        return response.data;
      } catch (error) {
        console.error('Erreur lors de la mise à jour du profil utilisateur :', error);
        throw error;
      }
    },

    async deleteProfile() {
      try {
        await api.deleteUser(this.user.id);
        this.clearUserData();
      } catch (error) {
        console.error('Erreur lors de la suppression du profil :', error);
      }
    },

    clearUserData() {
      this.user = null;
      this.accessToken = null;
      localStorage.removeItem('access_token');
      localStorage.removeItem('user');
      api.setAuthToken(null);
    },
  }
});
