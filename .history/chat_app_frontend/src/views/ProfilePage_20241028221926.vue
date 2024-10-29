<template>
  <section class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="p-16">
      <div class="p-8 bg-white shadow mt-24 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-3">
          <div class="grid grid-cols-3 text-center order-last md:order-first mt-20 md:mt-0">
            <div>
              <p class="font-bold text-gray-700 text-xl">{{ user.friendsCount }}</p>
              <p class="text-gray-400">Friends</p>
            </div>
            <div>
              <p class="font-bold text-gray-700 text-xl">{{ user.photosCount }}</p>
              <p class="text-gray-400">Photos</p>
            </div>
            <div>
              <p class="font-bold text-gray-700 text-xl">{{ user.commentsCount }}</p>
              <p class="text-gray-400">Comments</p>
            </div>
          </div>
          <div class="relative">
            <div class="w-48 h-48 bg-indigo-100 mx-auto rounded-full shadow-2xl absolute inset-x-0 top-0 -mt-24 flex items-center justify-center">
              <img 
                v-if="user.imgprofile" 
                :src="user.imgprofile" 
                alt="Image de Profil" 
                class="rounded-full w-48 h-48"
              />
              <svg 
                v-else 
                xmlns="http://www.w3.org/2000/svg" 
                class="h-24 w-24 text-indigo-500" 
                viewBox="0 0 20 20" fill="currentColor"
              >
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>
          <div class="space-x-8 flex justify-between mt-32 md:mt-0 md:justify-center">
            <button 
              @click="openEditModal" 
              class="text-white py-2 px-4 uppercase rounded bg-blue-400 hover:bg-blue-500 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5"
            >
              Modifier le Profil
            </button>
            <button 
              @click="openDeleteModal" 
              class="text-white py-2 px-4 uppercase rounded bg-gray-700 hover:bg-gray-800 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5"
            >
              Supprimer le Compte
            </button>
          </div>
        </div>

        <div class="mt-20 text-center border-b pb-12">
          <h1 class="text-4xl font-medium text-gray-700">{{ user.name }}, <span class="font-light text-gray-500">{{ user.age }}</span></h1>
          <p class="font-light text-gray-600 mt-3">{{ user.location }}</p>
          <p class="mt-8 text-gray-500">{{ user.title }}</p>
          <p class="mt-2 text-gray-500">{{ user.university }}</p>
        </div>

        <div class="mt-12 flex flex-col justify-center">
          <p class="text-gray-600 text-center font-light lg:px-16">{{ user.bio }}</p>
          <button class="text-indigo-500 py-2 px-4 font-medium mt-4">Show more</button>
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
              <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Modifier l'image de profil</label>
              <input type="file" @change="handleImageUpload" accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" />
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

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = () => {
      user.value.imgprofile = reader.result; // Prévisualisation de l'image
      formData.value.imgprofile = reader.result; // Ajout de l'image au formulaire
    };
    reader.readAsDataURL(file);
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
