<template>
  <section class="py-10 my-auto dark:bg-gray-900">
    <div class="lg:w-[80%] md:w-[90%] xs:w-[96%] mx-auto flex gap-4">
      <div class="lg:w-[88%] md:w-[80%] sm:w-[88%] xs:w-full mx-auto shadow-2xl p-4 rounded-xl h-fit self-center dark:bg-gray-800/40">
        <h1 class="lg:text-3xl md:text-2xl sm:text-xl xs:text-xl font-serif font-extrabold mb-2 dark:text-white">Profile</h1>
        <h2 class="text-grey text-sm mb-4 dark:text-gray-400">Update Your Profile</h2>

        <form @submit.prevent="updateProfile" class="space-y-4">
          <!-- Profile Image Upload -->
          <div class="flex justify-center mb-6">
            <div class="relative">
              <img
                :src="formData.imgprofile"
                alt="Profile Image"
                class="rounded-full border-4 border-green-600 w-36 h-36 object-cover mb-4"
              />
              <input type="file" id="upload_profile" @change="onProfileImageChange" hidden required />
              <label for="upload_profile" class="absolute inset-0 flex items-center justify-center cursor-pointer bg-gray-200 rounded-full hover:bg-gray-300">
                <span class="text-blue-700">Change Profile Image</span>
              </label>
            </div>
          </div>

          <!-- Cover Image Upload -->
          <div class="mb-4">
            <input type="file" id="upload_cover" @change="onCoverImageChange" hidden required />
            <label for="upload_cover" class="flex justify-center items-center cursor-pointer bg-gray-200 p-2 rounded-lg hover:bg-gray-300">
              <span class="text-blue-700">Change Cover Image</span>
            </label>
          </div>

          <h2 class="text-center mt-1 font-semibold dark:text-gray-300">Upload Profile and Cover Image</h2>
          
          <div class="flex gap-4 justify-between">
            <div class="w-full mb-4">
              <label for="name" class="mb-2 dark:text-gray-300">Name</label>
              <input type="text" v-model="formData.name" id="name" class="mt-2 p-4 w-full border-2 rounded-lg dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800" placeholder="Full Name" required />
            </div>

            <div class="w-full mb-4">
              <label for="email" class="mb-2 dark:text-gray-300">Email</label>
              <input type="email" v-model="formData.email" id="email" class="mt-2 p-4 w-full border-2 rounded-lg dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800" placeholder="Email" required />
            </div>
          </div>

          <div class="flex gap-4 justify-between">
            <div class="w-full mb-4">
              <label for="address" class="mb-2 dark:text-gray-300">Address</label>
              <input type="text" v-model="formData.address" id="address" class="mt-2 p-4 w-full border-2 rounded-lg dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800" placeholder="Address" />
            </div>

            <div class="w-full mb-4">
              <label for="gender" class="mb-2 dark:text-gray-300">Gender</label>
              <select v-model="formData.gender" id="gender" class="w-full text-grey border-2 rounded-lg p-4 dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800">
                <option disabled value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>

          <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white rounded-lg px-4 py-2 hover:bg-blue-600">Save Changes</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modals -->
    <transition name="fade">
      <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow dark:bg-gray-700 w-full max-w-md text-center p-6">
          <h3 class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-5">Profile updated successfully!</h3>
          <button @click="closeModal" class="bg-blue-600 text-white font-medium rounded-lg text-sm px-5 py-2.5">Close</button>
        </div>
      </div>
    </transition>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { useUserStore } from '../store/userStore';

const userStore = useUserStore();
const formData = ref({ 
  name: '',
  email: '',
  address: '',
  gender: '',
  imgprofile: 'default_profile_image.jpg', // Placeholder image
  coverImage: null 
});

const isModalOpen = ref(false);

// Load user data
const loadUserData = async () => {
  await userStore.loadUser();
  Object.assign(formData.value, userStore.user);
};

// Handle profile image change
const onProfileImageChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    formData.value.imgprofile = URL.createObjectURL(file); // Preview image
  }
};

// Handle cover image change
const onCoverImageChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    formData.value.coverImage = URL.createObjectURL(file); // Preview image
  }
};

// Update user profile
const updateProfile = async () => {
  try {
    await userStore.updateUser(userStore.user.id, formData.value);
    isModalOpen.value = true; // Show success modal
  } catch (error) {
    console.error("Error updating profile:", error);
  }
};

// Close modal
const closeModal = () => {
  isModalOpen.value = false;
};

loadUserData(); // Load initial user data
</script>

<style scoped>
/* Add any additional styles here */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
