<template>
  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0 my-3">
      <div class="w-full max-w-md bg-white rounded-lg shadow dark:border dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Modifier le Profil
          </h1>
          <div v-if="errorMessage" class="alert alert-danger mb-4">
            <p class="text-sm text-red-600 dark:text-red-400">{{ errorMessage }}</p>
          </div>
          <form @submit.prevent="submitForm" class="space-y-4 md:space-y-6">
            <div>
              <label for="titre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Titre</label>
              <input
                type="text"
                v-model="titre"
                id="titre"
                class="input"
                required
              />
            </div>
            <div>
              <label for="img" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image de profil</label>
              <input
                type="file"
                @change="onFileChange"
                id="img"
                class="input"
                accept="image/*"
                required
              />
            </div>
            <button
              type="submit"
              class="btn btn-primary w-full"
            >
              Soumettre
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import api from '@/store/api'; // Assurez-vous d'importer l'API

export default {
  data() {
    return {
      titre: '',
      img: null,
      errorMessage: ''
    };
  },
  methods: {
    onFileChange(event) {
      this.img = event.target.files[0];
    },
    async submitForm() {
      const formData = new FormData();
      formData.append('titre', this.titre);
      formData.append('img', this.img);

      try {
        const response = await api.post('/api/imgs', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        console.log('Image ajoutée:', response.data);
        // Vous pouvez rediriger ou mettre à jour l'état après succès
      } catch (error) {
        this.errorMessage = error.response?.data?.message || 'Erreur lors de l\'ajout de l\'image';
        console.error('Erreur lors de l\'ajout de l\'image:', error);
      }
    }
  }
};
</script>

<style scoped>
.input {
  background-color: #f8f9fa;
  border: 1px solid #d1d5db;
  color: #374151;
  padding: 0.625rem;
  border-radius: 0.375rem;
  width: 100%;
}

.btn {
  background-color: #34d399;
  color: white;
  padding: 0.625rem;
  border-radius: 0.375rem;
  transition: background-color 0.3s;
}

.btn:hover {
  background-color: #059669;
}
</style>
