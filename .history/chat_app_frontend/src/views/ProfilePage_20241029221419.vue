<template>
  <section class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <!-- Profile Display and Edit Section -->
    <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 max-w-md w-full mx-auto p-6 mt-10">
      <div class="flex flex-col items-center">
        <img 
          :src="user.imgprofile || defaultProfileImage" 
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

        <!-- Edit and Delete Buttons -->
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
            <div><label>Nom</label><input type="text" v-model="formData.name" required /></div>
            <div><label>Email</label><input type="email" v-model="formData.email" required /></div>
            <div><label>Adresse</label><input type="text" v-model="formData.address" /></div>
            <div><label>Genre</label>
              <select v-model="formData.gender">
                <option value="">Sélectionner un genre</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
              </select>
            </div>
            <!-- File Input -->
            <div>
              <label>Image de Profil</label>
              <input type="file" @change="handleImageUpload" accept="image/*" />
            </div>
            <div class="flex justify-end"><button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Sauvegarder</button></div>
          </form>
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
const formData = ref({
  name: user.value.name || '',
  email: user.value.email || '',
  address: user.value.address || '',
  gender: user.value.gender || '',
  imgprofile: null,
});
const defaultProfileImage = '/storage/default/default.jfif';

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

// Handle file input
const handleImageUpload = (event) => {
  formData.value.imgprofile = event.target.files[0];
};

// Update Profile Function
const updateProfile = async () => {
  try {
    const data = new FormData();
    data.append("name", formData.value.name);
    data.append("email", formData.value.email);
    data.append("address", formData.value.address);
    data.append("gender", formData.value.gender);

    // Assurez-vous que `imgprofile` est un fichier
    if (formData.value.imgprofile instanceof File) {
      data.append("imgprofile", formData.value.imgprofile);
    }

    await userStore.updateUser(user.value.id, data);
    user.value = userStore.user;
    closeEditModal();
  } catch (error) {
    console.error("Erreur lors de la mise à jour du profil:", error);
  }
};


  await userStore.updateUser(user.value.id, data);
  user.value = userStore.user;
  closeEditModal();
};
</script>

<style scoped>
/* Add styling as needed */
</style>
