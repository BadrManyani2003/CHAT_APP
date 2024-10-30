import { defineStore } from 'pinia';
import api from './api'; // Assurez-vous de bien l'importer

export const useUserStore = defineStore('userStore', {
  state: () => ({
    user: null,
    accessToken: null,
  }),

  actions: {
    async login(credentials) {
      try {
        const response = await api.login(credentials);
        this.user = response.data.user;
        this.accessToken = response.data.access_token;

        // Stockez le token et les informations de l'utilisateur dans localStorage
        localStorage.setItem('access_token', response.data.access_token);
        localStorage.setItem('user', JSON.stringify(response.data.user));

        // Configurez le token d'authentification pour les requêtes futures
        api.setAuthToken(response.data.access_token);
      } catch (error) {
        console.error('Erreur de connexion:', error);
        throw error;
      }
    },
    async loadUser() {
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
        await api.logout(); // Déconnectez l'utilisateur via l'API
        this.user = null;
        this.accessToken = null;
        localStorage.removeItem('access_token');
        localStorage.removeItem('user');
        api.setAuthToken(null);
      } catch (error) {
        console.error('Erreur lors de la déconnexion:', error);
      }
    },

    async fetchUser(id) {
      try {
        const response = await api.getUser(id);
        this.user = response.data; // Mettez à jour l'utilisateur dans le store
      } catch (error) {
        console.error('Erreur lors de la récupération de l’utilisateur :', error);
      }
    },
    async updateUser(id, updatedData) {
      try {
        const response = await api.updateUser(id, updatedData); // API call
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
        this.user = null;
        localStorage.removeItem('access_token');
        localStorage.removeItem('user');
        api.setAuthToken(null);
      } catch (error) {
        console.error('Erreur lors de la suppression du profil :', error);
      }
    },
  }
});
