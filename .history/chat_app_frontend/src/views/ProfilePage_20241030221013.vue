<template>
  <div class="container mx-auto p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Left Section: Profile Info -->
      <div class="bg-white rounded-lg shadow-md p-6 flex items-center my-10">
        <img
          class="w-24 h-24 rounded-full mr-4"
          :src="profileImage"
          alt="Profile Image"
        />
        <div>
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ user.name }}</h2>
          <p class="text-gray-600 dark:text-gray-400">{{ user.birthday }}</p>
        </div>
      </div>

      <!-- Right Section: Actions -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Profile Details</h3>
        <div class="mb-4">
          <p class="text-gray-600 dark:text-gray-400">Email: <span class="font-medium">{{ user.email }}</span></p>
          <p class="text-gray-600 dark:text-gray-400">Phone: <span class="font-medium">{{ user.phone }}</span></p>
          <p class="text-gray-600 dark:text-gray-400">Address: <span class="font-medium">{{ user.address }}</span></p>
        </div>
        <div class="flex space-x-4">
          <button
            @click="editProfile"
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
            @click="deleteProfile"
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
  </div>
</template>

<script>
import { computed } from 'vue';
import { useUserStore } from '@/store/userStore'; // Adjust path as necessary

export default {
  setup() {
    const userStore = useUserStore();

    // Accessing the logged-in user
    const user = computed(() => userStore.user);

    // Computed property for profile image
    const profileImage = computed(() => {
      return user.value.imgprofile
        ? `http://localhost:8000/storage/${user.value.imgprofile}`
        : 'http://localhost:8000/storage/default/default.jfif';
    });

    const editProfile = () => {
      // Implement the edit profile logic (e.g., redirect to edit page)
      console.log('Edit Profile');
    };

    const deleteProfile = () => {
      // Implement the delete profile logic (e.g., confirmation and API call)
      console.log('Delete Profile');
    };

    return { user, profileImage, editProfile, deleteProfile };
  },
};
</script>

<style scoped>
.container {
  max-width: 800px; /* Limit the width of the container */
}
</style>
