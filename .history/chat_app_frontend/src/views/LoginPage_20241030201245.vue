<template>
  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center mt-10 px-6 py-8 mx-auto md:h-screen lg:py-0">
      <div class="w-full max-w-md bg-white rounded-lg shadow dark:border dark:bg-gray-800 dark:border-gray-700 my-10">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Se connecter à votre compte
          </h1>
          <!-- Alert d'erreur -->
          <div v-if="errorMessage" class="alert alert-danger mb-4">
            <p class="text-sm text-red-600 dark:text-red-400">{{ errorMessage }}</p>
          </div>
          <form class="space-y-4 md:space-y-6" @submit.prevent="handleLogin">
            <div>
              <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Votre email</label>
              <input
                type="email"
                v-model="email"
                id="email"
                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="name@company.com"
                required
              />
            </div>
            <div>
              <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mot de passe</label>
              <input
                type="password"
                v-model="password"
                id="password"
                placeholder="••••••••"
                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required
              />
            </div>
            <button
              type="submit"
              class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
            >
              Se connecter
            </button>
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
              Vous n'avez pas de compte ? <router-link to="/register" class="font-medium text-primary-600 hover:underline dark:text-primary-500">S'inscrire</router-link>
            </p>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { ref } from 'vue';
import { useUserStore } from '../store/userStore';
import { useRouter } from 'vue-router';

export default {
  setup() {
    const userStore = useUserStore();
    const email = ref('');
    const password = ref('');
    const errorMessage = ref(null);
    const router = useRouter();

    const handleLogin = async () => {
      errorMessage.value = null;
      try {
        await userStore.login({ email: email.value, password: password.value });
        
        // Vérifiez si les données de l'utilisateur sont bien stockées
        if (userStore.user && userStore.accessToken) {
          router.push('/'); // Redirection vers le tableau de bord après connexion
        } else {
          throw new Error("Les informations de connexion sont manquantes.");
        }
      } catch (err) {
        console.error('Login failed:', err);
        errorMessage.value = 'Échec de la connexion, veuillez vérifier vos informations.';
      }
    };

    return { email, password, handleLogin, errorMessage };
  },
};
</script>

<style scoped>
/* Ajoutez des styles supplémentaires si nécessaire */
</style>
