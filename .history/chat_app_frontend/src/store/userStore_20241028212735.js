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
