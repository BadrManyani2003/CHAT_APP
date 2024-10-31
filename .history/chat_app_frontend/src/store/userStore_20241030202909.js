import { defineStore } from 'pinia';
import api from './api';

export const useUserStore = defineStore('userStore', {
  state: () => ({
    user: null,
    accessToken: null,
    imgprofile: null,
    isAuthenticated: false, // Ajout du champ isAuthenticated
  }),

  actions: {
    async register(credentials) {
      const formData = new FormData();
      Object.entries(credentials).forEach(([key, value]) => {
        formData.append(key, value);
      });

      console.log("FormData contents:", [...formData]);

      try {
        const response = await api.register(formData);
        this.user = response.data.user;
        this.accessToken = response.data.token;
        this.imgprofile = response.data.imgprofile;
        this.isAuthenticated = true; // Utilisateur authentifié après l'inscription
        api.setAuthToken(this.accessToken);
      } catch (error) {
        console.error('Erreur lors de l’inscription:', error.response ? error.response.data : error);
        throw error;
      }
    },

    async login(credentials) {
      try {
        const response = await api.login(credentials);
        if (response.data.user && response.data.token) {
          this.user = response.data.user;
          this.accessToken = response.data.token;
          this.imgprofile = response.data.imgprofile;
          this.isAuthenticated = true; // Utilisateur authentifié après la connexion

          // Configurer le token pour les requêtes futures
          api.setAuthToken(this.accessToken);
        } else {
          throw new Error('Données manquantes dans la réponse de connexion.');
        }
      } catch (error) {
        console.error('Erreur de connexion:', error);
        throw error;
      }
    },
    loadUser() {
      // Cette méthode peut être supprimée si vous ne voulez pas stocker l'utilisateur
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
      this.imgprofile = null;
      this.isAuthenticated = false; // Réinitialisation lors de la déconnexion
      api.setAuthToken(null); // Retirer le token des requêtes futures
    },
  },
});