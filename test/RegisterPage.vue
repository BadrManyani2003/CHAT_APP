<template>
  <div class="max-w-md mx-auto my-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-center mb-4">Register</h2>
    
    <form @submit.prevent="registerUser">
      <div class="mb-4">
        <label class="block text-gray-700">Name</label>
        <input
          v-model="credentials.name"
          type="text"
          class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          required
        />
      </div>
      
      <div class="mb-4">
        <label class="block text-gray-700">Email</label>
        <input
          v-model="credentials.email"
          type="email"
          class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          required
        />
      </div>
      
      <div class="mb-4">
        <label class="block text-gray-700">Password</label>
        <input
          v-model="credentials.password"
          type="password"
          class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          required
        />
      </div>
      
      <div class="mb-4">
        <label class="block text-gray-700">Confirm Password</label>
        <input
          v-model="credentials.password_confirmation"
          type="password"
          class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          required
        />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">Profile Image</label>
        <input
          @change="handleFileUpload"
          type="file"
          accept="image/*"
          class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          required
        />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">Birthday</label>
        <input
          v-model="credentials.birthday"
          type="date"
          class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          required
        />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">Gender</label>
        <select v-model="credentials.gender" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">Address</label>
        <input
          v-model="credentials.address"
          type="text"
          class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          required
        />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">Role</label>
        <input
          v-model="credentials.role"
          type="text"
          class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          placeholder="user"
        />
      </div>

      <button
        type="submit"
        class="w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-600"
      >
        Register
      </button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      credentials: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        birthday: '',
        gender: '',
        address: '',
        role: 'user',
        imgprofile: null,
      },
    };
  },
  methods: {
    handleFileUpload(event) {
      this.credentials.imgprofile = event.target.files[0];
      console.log("Selected file:", this.credentials.imgprofile);
    },
    async registerUser() {
      const formData = new FormData();
      Object.entries(this.credentials).forEach(([key, value]) => {
        formData.append(key, value);
      });

      console.log([...formData]);

      try {
        const response = await axios.post('http://localhost:8000/api/register', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });
        console.log('User registered:', response.data);
        this.$router.push('/login');
      } catch (error) {
        console.error('Registration failed:', error);
      }
    },
  },
};
</script>

<style scoped>
/* Ajoutez ici votre style personnalisé */
</style>
