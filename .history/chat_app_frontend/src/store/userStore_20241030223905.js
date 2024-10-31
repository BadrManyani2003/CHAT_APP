<template>
  <div class="container mx-auto p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Left Section: Profile Info -->
      <div class="bg-white rounded-lg shadow-md p-6 items-center ">
        <img
          class="w-40 h-40 rounded-full mr-4 mb-10"
          :src="profileImage"
          alt="Profile Image"
        />
        <div>
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ user.name }}</h2>
          <p class="text-gray-600 dark:text-gray-400">{{ user.birthday }}</p>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Profile Details</h3>
        <div class="mb-4">
          <p class="text-gray-600 dark:text-gray-400">Email: <span class="font-medium">{{ user.email }}</span></p>
          <p class="text-gray-600 dark:text-gray-400">Phone: <span class="font-medium">{{ user.phone }}</span></p>
          <p class="text-gray-600 dark:text-gray-400">Address: <span class="font-medium">{{ user.address }}</span></p>
        </div>
        <div class="flex space-x-4">
          <button
            @click="openEditModal"
            class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150"
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
            @click="openDeleteModal"
            class="flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-150"
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
    <div v-if="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow dark:bg-gray-700 w-full max-w-md">
        <div class="p-4 border-b rounded-t dark:border-gray-600 flex justify-between">
          <h3 class="text-xl font-semibold">Modifier le Profil</h3>
          <button @click="closeEditModal" class="text-gray-600 hover:text-gray-800 dark:hover:text-gray-300">&times;</button>
        </div>
        <div class="p-6">
          <form @submit.prevent="updateProfile">
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-700">Name</label>
              <input v-model="editUserData.name" type="text" class="w-full border rounded p-2" />
            </div>
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
              <input v-model="editUserData.email" type="email" class="w-full border rounded p-2" />
            </div>
            <div class="mt-4">
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Profile Modal -->
    <div v-if="isDeleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow dark:bg-gray-700 w-full max-w-md">
        <div class="p-4 border-b rounded-t dark:border-gray-600 flex justify-between">
          <h3 class="text-xl font-semibold">Confirmer la Suppression</h3>
          <button @click="closeDeleteModal" class="text-gray-600 hover:text-gray-800 dark:hover:text-gray-300">&times;</button>
        </div>
        <div class="p-6">
          <p>Êtes-vous sûr de vouloir supprimer votre profil ? Cette action est irréversible.</p>
          <div class="flex justify-end mt-4">
            <button @click="confirmDelete" class="bg-red-600 text-white px-4 py-2 rounded">Confirmer</button>
            <button @click="closeDeleteModal" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded">Annuler</button>
          </div>
        </div>
      </div>
    </div>
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

    // Accessing the logged-in user
    const user = computed(() => userStore.user);

    // Computed property for profile image
    const profileImage = computed(() => {
      return user.value.imgprofile
        ? `http://localhost:8000/storage/${user.value.imgprofile}`
        : 'http://localhost:8000/storage/default/default.jfif';
    });

    const openEditModal = () => {
      editUserData.value = { ...user.value }; // Reset form data
      isEditModalOpen.value = true;
    };

    const closeEditModal = () => {
      isEditModalOpen.value = false;
    };

    const openDeleteModal = () => {
      isDeleteModalOpen.value = true;
    };

    const closeDeleteModal = () => {
      isDeleteModalOpen.value = false;
    };

    const updateProfile = async () => {
      try {
        await userStore.updateUser(editUserData.value); // Update user via store
        closeEditModal();
      } catch (error) {
        console.error('Error updating profile:', error);
      }
    };

    const confirmDelete = async () => {
      try {
        await userStore.deleteUser(); // Delete user via store
        // Redirect to login or home page after deletion
        console.log('Profile Deleted');
        closeDeleteModal();
      } catch (error) {
        console.error('Error deleting profile:', error);
      }
    };

    return {
      user,
      profileImage,
      openEditModal,
      closeEditModal,
      isEditModalOpen,
      isDeleteModalOpen,
      openDeleteModal,
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
