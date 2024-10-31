<template>
  <section class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <!-- Profile Display and Edit Section -->
    <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 max-w-md w-full mx-auto p-6 mt-10">
      <div class="flex flex-col items-center">
        <img 
          :src="profileImage" 
          alt="Image de Profil"
          class="rounded-full w-36 h-36 mb-4 border-4 border-green-600"
        />
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ user.name }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.birthday || 'Date de naissance non spécifiée' }}</p>

        <!-- Profile details -->
        <div class="mt-4 space-y-2">
          <p class="text-gray-700 dark:text-gray-300" v-if="user.email">Email : <span class="font-semibold">{{ user.email }}</span></p>
          <p class="text-gray-700 dark:text-gray-300" v-if="user.address">Adresse : <span class="font-semibold">{{ user.address }}</span></p>
          <p class="text-gray-700 dark:text-gray-300" v-if="user.gender">Genre : <span class="font-semibold">{{ user.gender }}</span></p>
        </div>

        <!-- Edit Button -->
        <div class="mt-6">
          <button @click="openEditModal" class="bg-blue-600 text-white px-4 py-2 rounded">Modifier le Profil</button>
        </div>
      </div>
    </div>

    <!-- Edit Profile Modal -->
    <div v-if="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow dark:bg-gray-700 w-full max-w-md">
        <div class="p-4 border-b rounded-t dark:border-gray-600 flex justify-between">
          <h3 class="text-xl font-semibold">Modifier le Profil</h3>
          <button @click="closeEditModal">&times;</button>
        </div>
        <div class="p-6">
          <form @submit.prevent="updateProfile" class="space-y-4">
            <!-- Text Inputs -->
            <div>
              <label>Nom</label>
              <input type="text" v-model="formData.name" required class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
            </div>
            <div>
              <label>Email</label>
              <input type="email" v-model="formData.email" required class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
            </div>
            <div>
              <label>Adresse</label>
              <input type="text" v-model="formData.address" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
            </div>
            <div>
              <label>Genre</label>
              <select v-model="formData.gender" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Sélectionner un genre</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
              </select>
            </div>
            <!-- File Input -->
            <div>
              <label>Image de Profil</label>
              <input type="file" @change="handleImageUpload" accept="image/*" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
            </div>
            <div class="flex justify-end">
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Sauvegarder</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useUserStore } from '../store/userStore';

const userStore = useUserStore();
const user = ref({});
const isEditModalOpen = ref(false);
const formData = ref({
  name: '',
  email: '',
  address: '',
  gender: '',
  imgprofile: null,
});

// Utiliser une computed property pour obtenir l'image de profil
const profileImage = computed(() => {
  return ''http://localhost:8000/storage/userStore.imgprofile || 'http://localhost:8000/storage/default/default.jfif'; // Image par défaut si imgprofile est vide
});

onMounted(async () => {
  await userStore.loadUser();
  user.value = userStore.user; // Initialisation de l'utilisateur
  formData.value = { 
    name: user.value.name, 
    email: user.value.email, 
    address: user.value.address, 
    gender: user.value.gender,
    imgprofile: null, 
  }; // Initialisation des données de formulaire
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

const handleImageUpload = (event) => {
  formData.value.imgprofile = event.target.files[0]; // Mise à jour de imgprofile avec le fichier sélectionné
};

// Update Profile Function
const updateProfile = async () => {
  try {
    const data = new FormData();
    data.append("name", formData.value.name);
    data.append("email", formData.value.email);
    data.append("address", formData.value.address);
    data.append("gender", formData.value.gender);

    // Assurez-vous que imgprofile est un fichier
    if (formData.value.imgprofile) {
      data.append("imgprofile", formData.value.imgprofile);
    }

    await userStore.updateUser(data); // Envoi des données mises à jour au userStore
    user.value = userStore.user; // Met à jour l'utilisateur après la mise à jour
    closeEditModal(); // Ferme la modal
  } catch (error) {
    console.error("Erreur lors de la mise à jour du profil:", error);
  }
};
</script>

<style scoped>
/* Ajoutez ici votre style personnalisé si nécessaire */
</style>