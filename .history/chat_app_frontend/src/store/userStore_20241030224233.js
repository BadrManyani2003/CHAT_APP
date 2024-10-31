<template>
  <div class="container mx-auto p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
        <img
          class="w-40 h-40 rounded-full mb-4"
          :src="profileImage"
          alt="Profile Image"
        />
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ user.name }}</h2>
        <p class="text-gray-600 dark:text-gray-400">{{ user.birthday }}</p>
      </div>

      <!-- Profile Details Section -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Profile Details</h3>
        <div class="mb-4">
          <p class="text-gray-600 dark:text-gray-400">Email: <span class="font-medium">{{ user.email }}</span></p>
          <p class="text-gray-600 dark:text-gray-400">Phone: <span class="font-medium">{{ user.phone }}</span></p>
          <p class="text-gray-600 dark:text-gray-400">Address: <span class="font-medium">{{ user.address }}</span></p>
        </div>
        <div class="flex space-x-4">
          <button
            @click="toggleEditModal"
            class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150"
            aria-label="Modify Profile"
          >
            <svg
              class="w-5 h-5 mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 4l4 4-8 8-4-4 8-8zm0 0L8 12m8-8l-8 8m8 8l4 4m-4-4l-4-4"
              />
            </svg>
            Modify
          </button>
          <button
            @click="toggleDeleteModal"
            class="flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150"
            aria-label="Delete Profile"
          >
            <svg
              class="w-5 h-5 mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 4l4 4-4 4m4-4H4m4 4l-4 4m0-4l4 4"
              />
            </svg>
            Delete
          </button>
        </div>
      </div>
    </div>

    <!-- Edit Profile Modal -->
    <modal v-if="isEditModalOpen" @close="closeEditModal">
      <template #header>
        <h3 class="text-xl font-semibold">Modifier le Profil</h3>
      </template>
      <template #body>
        <form @submit.prevent="updateProfile">
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Name</label>
            <input v-model="editUserData.name" type="text" class="w-full border rounded p-2" required />
          </div>
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
            <input v-model="editUserData.email" type="email" class="w-full border rounded p-2" required />
          </div>
          <div class="mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Changes</button>
          </div>
        </form>
      </template>
    </modal>

    <!-- Delete Profile Modal -->
    <modal v-if="isDeleteModalOpen" @close="closeDeleteModal">
      <template #header>
        <h3 class="text-xl font-semibold">Confirmer la Suppression</h3>
      </template>
      <template #body>
        <p>Êtes-vous sûr de vouloir supprimer votre profil ? Cette action est irréversible.</p>
        <div class="flex justify-end mt-4">
          <button @click="confirmDelete" class="bg-red-600 text-white px-4 py-2 rounded">Confirmer</button>
          <button @click="closeDeleteModal" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded">Annuler</button>
        </div>
      </template>
    </modal>
  </div>
</template>

<script>
import { computed, ref } from 'vue';
import { useUserStore } from '@/store/userStore'; // Adjust path as necessary

export default {
  setup() {
    const userStore = useUserStore();
    const isEditModalOpen = ref(false);
    const isDeleteModalOpen = ref(false);
    const editUserData = ref({ ...userStore.user }); // Copy the user data for editing

    const user = computed(() => userStore.user);

    const profileImage = computed(() => {
      return user.value.imgprofile
        ? `http://localhost:8000/storage/${user.value.imgprofile}`
        : 'http://localhost:8000/storage/default/default.jfif';
    });

    const toggleEditModal = () => {
      editUserData.value = { ...user.value }; // Reset form data
      isEditModalOpen.value = !isEditModalOpen.value;
    };

    const toggleDeleteModal = () => {
      isDeleteModalOpen.value = !isDeleteModalOpen.value;
    };

    const closeEditModal = () => {
      isEditModalOpen.value = false;
    };

    const closeDeleteModal = () => {
      isDeleteModalOpen.value = false;
    };

    const updateProfile = async () => {
      try {
        await userStore.updateUser(editUserData.value);
        closeEditModal();
      } catch (error) {
        console.error('Error updating profile:', error);
      }
    };

    const confirmDelete = async () => {
      try {
        await userStore.deleteUser();
        console.log('Profile Deleted');
        closeDeleteModal();
      } catch (error) {
        console.error('Error deleting profile:', error);
      }
    };

    return {
      user,
      profileImage,
      toggleEditModal,
      closeEditModal,
      isEditModalOpen,
      isDeleteModalOpen,
      toggleDeleteModal,
      closeDeleteModal,
      updateProfile,
      confirmDelete,
      editUserData,
    };
  },
};
</script>

<style scoped>
.container {
  max-width: 800px; /* Limit the width of the container */
}
</style>
