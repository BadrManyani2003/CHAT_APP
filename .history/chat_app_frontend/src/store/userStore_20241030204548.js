import { defineStore } from 'pinia';
import api from './api';

export const useUserStore = defineStore('userStore', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    accessToken: localStorage.getItem('accessToken') || null,
    imgprofile: localStorage.getItem('imgprofile') || null,
    isAuthenticated: !!localStorage.getItem('accessToken'), // Détermine l'authentification
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
        if (response.data.user && response.data.access_token) {
          this.setUserData(response.data.user, response.data.access_token);
        } else {
          throw new Error('Données manquantes dans la réponse de connexion.');
        }
      } catch (error) {
        console.error('Erreur de connexion:', error);
        throw error;
      }
    },

    setUserData(user, token) {
      this.user = user;
      this.accessToken = token;
      this.imgprofile = user.imgprofile;
      this.isAuthenticated = true;

      // Sauvegarde dans localStorage
      localStorage.setItem('user', JSON.stringify(user));
      localStorage.setItem('accessToken', token);
      localStorage.setItem('imgprofile', user.imgprofile);

      api.setAuthToken(token); // Définit le token pour les requêtes futures
    },

    async logout() {
      try {
        await api.logout();
        this.clearUserData();
      } catch (error) {
        console.error('Erreur lors de la déconnexion:', error);
      }
    },
    loadUser() {
      // Cette méthode peut être supprimée si vous ne voulez pas stocker l'utilisateur
    },

    clearUserData() {
      this.user = null;
      this.accessToken = null;
      this.imgprofile = null;
      this.isAuthenticated = false;

      // Supprime les données de localStorage
      localStorage.removeItem('user');
      localStorage.removeItem('accessToken');
      localStorage.removeItem('imgprofile');

      api.setAuthToken(null); // Retirer le token des requêtes futures
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
        localStorage.setItem('user', JSON.stringify(this.user)); // Met à jour localStorage
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
  },
});
