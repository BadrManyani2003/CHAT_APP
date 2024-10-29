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
      v-if="isEditModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
      <div class="bg-white rounded-lg shadow dark:bg-gray-700 w-full max-w-md">
        <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Modifier le Profil</h3>
          <button type="button" class="text-gray-400 hover:text-gray-900 dark:hover:text-white" @click="closeEditModal">
            <span class="sr-only">Fermer le modal</span>
            &times;
          </button>
        </div>
        <div class="p-6">
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
            <div>
              <label for="imgprofile" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image de Profil</label>
              <input type="file" @change="handleImageUpload" id="imgprofile" accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" />
            </div>
            <div class="flex justify-end">
              <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Sauvegarder</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation pour supprimer le compte -->
    <div 
      v-if="isDeleteModalOpen" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
      <div class="bg-white rounded-lg shadow dark:bg-gray-700 w-full max-w-md text-center p-6">
        <h3 class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-5">Êtes-vous sûr de vouloir supprimer ce compte ?</h3>
        <button 
          @click="confirmDelete" 
          class="text-white bg-red-600 hover:bg-red-800 font-medium rounded-lg text-sm px-5 py-2.5 mr-2"
        >
          Supprimer
        </button>
        <button 
          @click="closeDeleteModal" 
          class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5"
        >
          Annuler
        </button>
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
const formData = ref({
  name: user.value.name || '',
  email: user.value.email || '',
  address: user.value.address || '',
  gender: user.value.gender || '',
  imgprofile: null,
});

onMounted(async () => {
  await userStore.loadUser();
  user.value = userStore.user;
});

const openEditModal = () => {
  formData.value = { 
    name: user.value.name, 
    email: user.value.email, 
    address: user.value.address, 
    gender: user.value.gender,
    imgprofile: null, 
  };
  isEditModalOpen.value = true;
};

const closeEditModal = () => {
  isEditModalOpen.value = false;
};
const updateProfile = async () => {
  try {
    const data = new FormData();
    data.append("name", formData.value.name);
    data.append("email", formData.value.email);
    data.append("address", formData.value.address);
    data.append("gender", formData.value.gender);

    if (formData.value.imgprofile instanceof File) {
      data.append("imgprofile", formData.value.imgprofile);
    }

    // Log pour vérifier le contenu du FormData
    for (const pair of data.entries()) {
      console.log(`${pair[0]}: ${pair[1]}`);
    }

    await userStore.updateUser(user.value.id, data);
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
/* Styles here */
</style>
