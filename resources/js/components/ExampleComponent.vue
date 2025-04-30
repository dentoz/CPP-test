<template>
    <div>
      <h1>Hello from Vue 3!</h1>
      <p>Counter: {{ counter }}</p>
      <button @click="counter++">Increase</button>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const counter = ref(0);
  </script>
  
  <style scoped>
  h1 {
    color: blue;
  }
  </style>
  