<!-- Modal.vue -->
<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="p-4 border-b rounded-t flex justify-between">
          <slot name="header"></slot>
          <button @click="$emit('close')" class="text-gray-600 hover:text-gray-800">&times;</button>
        </div>
        <div class="p-6">
          <slot name="body"></slot>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    emits: ['close'],
  };
  </script>
  
  <style scoped>
  /* Add any modal-specific styles here */
  </style>
  