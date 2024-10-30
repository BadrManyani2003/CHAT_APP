<template>
  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0 my-3">
      <div class="w-full max-w-md bg-white rounded-lg shadow dark:border dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Créer un compte
          </h1>
          <!-- Alert d'erreur -->
          <div v-if="errorMessage" class="alert alert-danger mb-4">
            <p class="text-sm text-red-600 dark:text-red-400">{{ errorMessage }}</p>
          </div>
          <form class="space-y-4 md:space-y-6" @submit.prevent="submitForm">
            <div>
              <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
              <input
                type="text"
                v-model="name"
                id="name"
                class="input"
                required
              />
            </div>
            <div>
              <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Votre email</label>
              <input
                type="email"
                v-model="email"
                id="email"
                class="input"
                placeholder="name@company.com"
                required
              />
            </div>
            <div>
              <label for="imgprofile" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image de profil</label>
              <input
                type="file"
                @change="onFileChange"
                id="imgprofile"
                class="input"
                accept="image/*"
                required
              />
            </div>
            <div>
              <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mot de passe</label>
              <input
                type="password"
                v-model="password"
                id="password"
                class="input"
                required
              />
            </div>
            <div>
              <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmer le mot de passe</label>
              <input
                type="password"
                v-model="password_confirmation"
                id="password_confirmation"
                class="input"
                required
              />
            </div>
            <button type="submit" class="btn btn-primary w-full">S'inscrire</button>
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
              Vous avez déjà un compte ? <router-link to="/login" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Se connecter</router-link>
            </p>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import api from '@/store/api'; // Assurez-vous d'importer votre API

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      imgprofile: null,
      errorMessage: '',
    };
  },
  methods: {
    onFileChange(event) {
      this.imgprofile = event.target.files[0];
    },
    async submitForm() {
      const formData = new FormData();
      formData.append('name', this.name);
      formData.append('email', this.email);
      formData.append('password', this.password);
      formData.append('password_confirmation', this.password_confirmation);
      
      if (this.imgprofile) {
        formData.append('imgprofile', this.imgprofile);
      } else {
        this.errorMessage = "Veuillez sélectionner une image de profil.";
        return;
      }

      try {
        const response = await api.register(formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
        console.log('Inscription réussie:', response.data);
        this.$router.push('/login');
      } catch (error) {
        this.errorMessage = error.response?.data?.message || 'Erreur lors de l\'inscription';
        console.error(this.errorMessage);
      }
    },
  },
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
