<template>
    <div>
      <p>{{ message }}</p>
      <div class="mt-2 space-x-2">
        <button @click="accept" class="px-3 py-1 bg-green-500 text-white rounded">Join</button>
        <button @click="reject" class="px-3 py-1 bg-red-500 text-white rounded">Reject</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { useToast } from 'vue-toastification';
  const props = defineProps(['message', 'onAccept', 'onReject']);
  const toast = useToast();
  
  function accept() {
    toast.clear(); // close all toasts
    props.onAccept();
  }
  
  function reject() {
    toast.clear();
    props.onReject();
  }
  </script>
  