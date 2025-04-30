<template>
    <div>
      <h1>Login Page</h1>
      <button @click="googleLogin">Sign in with Google</button>
    </div>
  </template>
  
  <script setup>
  const googleLogin = () => {
    window.location.href = '/auth/redirect/google';
  };
  </script>
  