<!-- src/views/LoginPage.vue -->
<template>
  <div class="login-container">
    <h1>Connexion</h1>
    <form @submit.prevent="handleLogin">
      <input v-model="credentials.email" placeholder="Email" required />
      <input v-model="credentials.password" type="password" placeholder="Mot de passe" required />
      <button type="submit">Se connecter</button>
      <p v-if="userStore.loginError" class="error">{{ userStore.loginError }}</p> <!-- Affiche l'erreur -->
    </form>
  </div>
</template>

<script>
import { useUserStore } from '@/store/userStore';
import { ref } from 'vue';

export default {
  setup() {
    const userStore = useUserStore();
    const credentials = ref({ email: '', password: '' });

    const handleLogin = async () => {
      try {
        await userStore.login(credentials.value);
      } catch (error) {
        console.error('Erreur lors de la connexion:', error);
      }
    };

    return {
      userStore,
      credentials,
      handleLogin,
    };
  },
};
</script>

<style>
.login-container {
  max-width: 400px;
  margin: auto;
  padding: 1em;
  border: 1px solid #ddd;
  border-radius: 8px;
}
.error {
  color: red;
  font-size: 0.9em;
}
</style>
