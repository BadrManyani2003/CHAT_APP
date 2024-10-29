<template>
  <section class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 max-w-md w-full mx-auto p-6 mt-10">
      <div class="flex flex-col items-center">
        <img 
          class="rounded-full w-36 h-36 mb-4 border-4 border-green-600" 
          :src="user.imgprofile" 
          alt="Image de Profil" 
        />
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ user.name }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.birthday || 'Date de naissance non spécifiée' }}</p>
        
        <div class="mt-4 space-y-2">
          <p class="text-gray-700 dark:text-gray-300" v-if="user.email">Email : <span class="font-semibold">{{ user.email }}</span></p>
          <p class="text-gray-700 dark:text-gray-300" v-if="user.address">Adresse : <span class="font-semibold">{{ user.address }}</span></p>
          <p class="text-gray-700 dark:text-gray-300" v-if="user.gender">Genre : <span class="font-semibold">{{ user.gender }}</span></p>
        </div>
        
        <div class="mt-6">
          <button 
            @click="openEditModal" 
            class="btn btn-primary"
          >
            Modifier le Profil
          </button>
          <button 
            @click="openDeleteModal"
            class="btn btn-danger ml-2"
          >
            Supprimer le Compte
          </button>
        </div>
      </div>
    </div>

    <!-- Modal d'édition de profil -->
    <div 
      v-if="isEditModalOpen"
      tabindex="-1"
      class="modal modal-open"
    >
      <div class="modal-box relative">
        <button 
          @click="closeEditModal"
          class="btn btn-sm btn-circle absolute right-2 top-2"
        >
          ✕
        </button>
        <h3 class="text-lg font-bold">Modifier le Profil</h3>
        <form @submit.prevent="updateProfile" class="space-y-4 mt-4">
          <div>
            <label for="name" class="block mb-2 text-sm font-medium">Nom</label>
            <input type="text" v-model="formData.name" id="name" class="input input-bordered w-full" required />
          </div>
          <div>
            <label for="email" class="block mb-2 text-sm font-medium">Email</label>
            <input type="email" v-model="formData.email" id="email" class="input input-bordered w-full" required />
          </div>
          <div>
            <label for="address" class="block mb-2 text-sm font-medium">Adresse</label>
            <input type="text" v-model="formData.address" id="address" class="input input-bordered w-full" />
          </div>
          <div>
            <label for="gender" class="block mb-2 text-sm font-medium">Genre</label>
            <select v-model="formData.gender" id="gender" class="select select-bordered w-full">
              <option value="">Sélectionner un genre</option>
              <option value="Homme">Homme</option>
              <option value="Femme">Femme</option>
              <option value="Autre">Autre</option>
            </select>
          </div>
          <div class="flex justify-end">
            <button type="submit" class="btn btn-primary">Sauvegarder</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal de confirmation pour supprimer le compte -->
    <div v-if="isDeleteModalOpen" tabindex="-1" class="modal modal-open">
      <div class="modal-box text-center">
        <button 
          @click="closeDeleteModal"
          class="btn btn-sm btn-circle absolute right-2 top-2"
        >
          ✕
        </button>
        <h3 class="font-bold text-lg">Êtes-vous sûr de vouloir supprimer ce compte ?</h3>
        <div class="mt-4">
          <button @click="confirmDelete" class="btn btn-danger">Supprimer</button>
          <button @click="closeDeleteModal" class="btn btn-secondary ml-2">Annuler</button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useUserStore } from '../store/userStore'; 

const userStore = useUserStore();
const user = ref(userStore.user);
const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const formData = ref({ ...user.value });

onMounted(async () => {
  await userStore.loadUser();
  user.value = userStore.user;
});

const openEditModal = () => {
  formData.value = { ...user.value };
  isEditModalOpen.value = true;
};

const closeEditModal = () => {
  isEditModalOpen.value = false;
};

const updateProfile = async () => {
  try {
    await userStore.updateUser(user.value.id, formData.value);
    user.value = userStore.user;
    closeEditModal();
  } catch (error) {
    console.error("Erreur lors de la mise à jour du profil:", error);
  }
};

const openDeleteModal = () => {
  isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
  isDeleteModalOpen.value = false;
};

const confirmDelete = async () => {
  try {
    await userStore.deleteProfile();
    closeDeleteModal();
  } catch (error) {
    console.error("Erreur lors de la suppression du compte:", error);
  }
};
</script>

<style scoped>
/* Custom styles if needed */
</style>
