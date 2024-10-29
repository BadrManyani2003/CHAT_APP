import { defineStore } from 'pinia';
import api from './api'; // Importez les fonctions d'API depuis api.js

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

    loadUser() {
      // Récupérez les informations de l'utilisateur depuis localStorage
      const storedUser = localStorage.getItem('user');
      const storedToken = localStorage.getItem('access_token');

      if (storedUser && storedToken) {
        try {
          this.user = JSON.parse(storedUser);
          this.accessToken = storedToken;

          // Configurez le token d'authentification
          api.setAuthToken(storedToken);
        } catch (error) {
          console.error('Erreur de chargement des données utilisateur:', error);
          this.user = null;
          this.accessToken = null;
        }
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
        const response = await api.put(`/users/${id}`, updatedData); // Appel à l'API avec les nouvelles données
        this.user = response.data; // Mettre à jour l'état de l'utilisateur avec les données mises à jour
        localStorage.setItem('user', JSON.stringify(response.data)); // Mettre à jour le localStorage
        return response.data;
      } catch (error) {
        console.error('Erreur lors de la mise à jour du profil utilisateur :', error);
        throw error; // Relancer l'erreur pour la gestion dans le composant
      }
    }
  },

    async deleteProfile() {
      try {
        await api.deleteUser(this.user.id); // Appeler l'API pour supprimer l'utilisateur
        this.user = null; // Réinitialiser l'état de l'utilisateur après la suppression
        localStorage.removeItem('access_token');
        localStorage.removeItem('user');
        api.setAuthToken(null); // Supprimer le token d'authentification
      } catch (error) {
        console.error('Erreur lors de la suppression du profil :', error);
      }
    },

});


import { defineStore } from 'pinia';
import api from '../api'; // Assurez-vous de bien l'importer

export const useUserStore = defineStore('userStore', {
  state: () => ({
    user: null,
    accessToken: null,
  }),

  actions: {
    
    async loadUser() {
      const storedUser = localStorage.getItem('user');
      const storedToken = localStorage.getItem('access_token');

      if (storedUser && storedToken) {
        this.user = JSON.parse(storedUser);
        this.accessToken = storedToken;
        api.setAuthToken(storedToken);
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
