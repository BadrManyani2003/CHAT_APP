<template>
  <section class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 max-w-lg w-full mx-auto p-6 mt-10">
      <div class="flex flex-col items-center">
        <div class="relative">
          <img 
            class="rounded-full w-36 h-36 mb-4 border-4 border-green-600" 
            :src="user.imgprofile || 'default-profile.png'" 
            alt="Image de Profil" 
          />
          <button 
            @click="openImageUploadModal"
            class="absolute bottom-1 right-1 bg-blue-500 text-white p-1 rounded-full hover:bg-blue-600"
          >
            <i class="fas fa-camera"></i>
          </button>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ user.name }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.birthday || 'Date de naissance non spécifiée' }}</p>
        
        <div class="mt-4 space-y-2 w-full text-center">
          <p v-if="user.email" class="text-gray-700 dark:text-gray-300">Email: <span class="font-semibold">{{ user.email }}</span></p>
          <p v-if="user.address" class="text-gray-700 dark:text-gray-300">Adresse: <span class="font-semibold">{{ user.address }}</span></p>
          <p v-if="user.gender" class="text-gray-700 dark:text-gray-300">Genre: <span class="font-semibold">{{ user.gender }}</span></p>
        </div>

        <div class="mt-6">
          <button 
            @click="openEditModal" 
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
          >
            Modifier le Profil
          </button>
          <button 
            @click="openDeleteModal"
            class="ml-2 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
          >
            Supprimer le Compte
          </button>
        </div>
      </div>
    </div>

    <!-- Modal pour modifier l'image de profil -->
    <div v-if="isImageUploadModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow dark:bg-gray-700 w-full max-w-md p-6">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Modifier l'image de profil</h3>
        <input type="file" @change="handleImageUpload" class="block w-full mb-4" accept="image/*" />
        <div class="flex justify-end">
          <button @click="closeImageUploadModal" class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">Annuler</button>
        </div>
      </div>
    </div>

    <!-- Modal d'édition de profil -->
    <div v-if="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow dark:bg-gray-700 w-full max-w-md">
        <form @submit.prevent="updateProfile" class="space-y-4 p-6">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Modifier le Profil</h3>
          <input type="text" v-model="formData.name" placeholder="Nom" required class="input-field" />
          <input type="email" v-model="formData.email" placeholder="Email" required class="input-field" />
          <input type="text" v-model="formData.address" placeholder="Adresse" class="input-field" />
          <select v-model="formData.gender" class="input-field">
            <option value="">Sélectionner un genre</option>
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
            <option value="Autre">Autre</option>
          </select>
          <button type="submit" class="save-button">Sauvegarder</button>
        </form>
      </div>
    </div>

    <!-- Modal de confirmation pour supprimer le compte -->
    <div v-if="isDeleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow dark:bg-gray-700 w-full max-w-md p-6 text-center">
        <h3 class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-5">Êtes-vous sûr de vouloir supprimer ce compte ?</h3>
        <button @click="confirmDelete" class="text-white bg-red-600 hover:bg-red-800 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">Supprimer</button>
        <button @click="closeDeleteModal" class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">Annuler</button>
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
const isImageUploadModalOpen = ref(false);
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

const openImageUploadModal = () => {
  isImageUploadModalOpen.value = true;
};

const closeImageUploadModal = () => {
  isImageUploadModalOpen.value = false;
};

const handleImageUpload = async (event) => {
  const file = event.target.files[0];
  if (file) {
    const formData = new FormData();
    formData.append('image', file);
    try {
      await userStore.updateUserImage(user.value.id, formData);
      user.value = userStore.user;
      closeImageUploadModal();
    } catch (error) {
      console.error("Erreur lors de la mise à jour de l'image de profil:", error);
    }
  }
};
</script>

<style scoped>
.input-field {
  background: #f9f9f9;
  border: 1px solid #ddd;
  padding: 10px;
  border-radius: 5px;
  width: 100%;
}
.save-button {
  background: #4f46e5;
  color: #fff;
  padding: 10px;
  border-radius: 5px;
  width: 100%;
  text-align: center;
}
</style>
