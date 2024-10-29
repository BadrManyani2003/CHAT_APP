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
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
          >
            Modifier le Profil
          </button>
          <button 
            @click="openDeleteModal"
            class="ml-2 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
          >
            Supprimer le Compte
          </button>
        </div>
      </div>
    </div>

    <!-- Modal d'édition de profil -->
    <div 
      id="edit-profile-modal" 
      tabindex="-1" 
      class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
      v-if="isEditModalOpen"
    >
      <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
          <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Modifier le Profil</h3>
            <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="closeEditModal">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
              </svg>
              <span class="sr-only">Fermer le modal</span>
            </button>
          </div>
          <div class="p-4 md:p-5">
            <form @submit.prevent="updateProfile" class="space-y-4">
              <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                <input type="text" v-model="formData.name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required />
              </div>
              <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" v-model="formData.email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required />
              </div>
              <div>
                <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Adresse</label>
                <input type="text" v-model="formData.address" id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" />
              </div>
              <div>
                <label for="gender" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Genre</label>
                <select v-model="formData.gender" id="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                  <option value="">Sélectionner un genre</option>
                  <option value="Homme">Homme</option>
                  <option value="Femme">Femme</option>
                  <option value="Autre">Autre</option>
                </select>
              </div>
              <div class="flex justify-end">
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Sauvegarder</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation pour supprimer le compte -->
    <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full" v-if="isDeleteModalOpen">
      <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
          <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="closeDeleteModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Fermer le modal</span>
          </button>
          <div class="p-4 md:p-5 text-center">
            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Êtes-vous sûr de vouloir supprimer ce compte ?</h3>
            <button 
              @click="confirmDelete" 
              class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2"
            >
              Supprimer
            </button>
            <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2" data-modal-hide="popup-modal" @click="closeDeleteModal">
              Annuler
            </button>
          </div>
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
  await userStore.loadUser(); // Charger les données de l'utilisateur
  user.value = userStore.user; // Mettre à jour les données du profil
});

const openEditModal = () => {
  formData.value = { ...user.value }; // Remplir le formulaire avec les données actuelles
  isEditModalOpen.value = true;
};

const closeEditModal = () => {
  isEditModalOpen.value = false;
};

const updateProfile = async () => {
  await userStore.editProfile(formData.value); // Mettre à jour le profil via le store
  user.value = userStore.user; // Mettre à jour l'utilisateur dans le composant
  closeEditModal();
};

const openDeleteModal = () => {
  isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
  isDeleteModalOpen.value = false;
};

const confirmDelete = async () => {
  await userStore.deleteProfile(); // Supprimer le compte via le store
  closeDeleteModal();
  // Optionnel : Rediriger ou effectuer d'autres actions après la suppression
};
</script>

<style scoped>
/* Ajoutez vos styles ici si nécessaire */
</style>
