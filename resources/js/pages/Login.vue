<template>
  <div class="login-wrapper">
    <v-card>
      <v-card-title>Sign in to start your session</v-card-title>
      <v-card-text>
        <v-btn color="red" class="text-white" @click="googleLogin">
          <v-icon start>mdi-google</v-icon>
          Sign in with Google
        </v-btn>
      </v-card-text>
    </v-card>

    <v-dialog v-if="shouldShowSnackbar" v-model="dialog" max-width="500">
      <form @submit.prevent="onSubmit">
        <v-card>
          <v-card-title>
            <p class="mb-5">To continue please fill your nick name</p>
            <v-spacer></v-spacer>
            <v-avatar size="95" class="mb-5">
              <img :src="prefetchedData.avatar" alt="User Avatar" />
            </v-avatar>
            <h3>{{ prefetchedData.name }}</h3>
          </v-card-title>
          <v-card-text>
            <v-text-field label="Email" v-model="prefetchedData.email" readonly></v-text-field>
            <v-text-field label="Nickname" v-model="nickname.value.value"
              :error-messages="nickname.errorMessage.value"></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue-darken-1" type="submit" :disabled="loading">
              <v-progress-circular v-if="loading" class="mr-2" indeterminate></v-progress-circular>
              Submit
            </v-btn>
          </v-card-actions>
        </v-card>
      </form>
    </v-dialog>
  </div>
</template>

<script>
import { inject, ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router';
import { useField, useForm } from 'vee-validate'
import * as yup from 'yup'
import { messaging, getToken } from "../firebase";

export default {
  setup() {
    const schema = yup.object().shape({
      nickname: yup.string().required('nick name is required').min(3, 'nick name must be at least 3 characters')
    })
    const prefetchedData = inject('prefetchedData', null);
    const dialog = ref(false)
    const loading = ref(false)

    const route = useRoute();
    const router = useRouter();

    const { handleSubmit, values, errors } = useForm({ validationSchema: schema });

    const nickname = useField('nickname', schema);

    const query = route.query;
    const verified = query.verified || false;

    if (Object.keys(prefetchedData).length > 0 && verified) {
      localStorage.setItem('token', JSON.stringify(prefetchedData));
    }

    if (Object.keys(prefetchedData).length > 0 && !prefetchedData?.exists && verified) {
      router.push('/dashboard');
    }

    if (Object.keys(prefetchedData).length > 0 && prefetchedData?.exists && verified) {
      dialog.value = true;
    }

    const csrfToken = document.cookie
      .split('; ')
      .find(row => row.startsWith('XSRF-TOKEN'))
      ?.split('=')[1];

    const requestFcmToken = async () => {
      try {
        const currentToken = await getToken(messaging, {
          vapidKey: 'BFGUFtTocmXLsGiyGcIwY7a8cEBX5gx0LWrwzT4STbB9tvzDpVlJy9EKafpwmJt1dqmS9sT0zPjeB-01670bPR0',
          serviceWorkerRegistration: await window.navigator.serviceWorker.ready,
        });

        if (!currentToken) {
          console.warn('No registration token available. Request permission to generate one.');
        } 
        return currentToken;
      } catch (err) {
        console.error('An error occurred while retrieving token. ', err);
      }
    };

    const onSubmit = handleSubmit(async (values) => {
      loading.value = true;
      const fcmToken = await requestFcmToken();
      const response = await fetch('/api/user/availability', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'authorization': `Bearer ${prefetchedData.token}`,
          // 'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          ...values,
          ...prefetchedData,
          fcmToken
        }),
        credentials: 'include'
      });

      loading.value = false;
      const data = await response.json();
      if (!response.ok) {
        return alert(data.message);
      }

      return router.push('/dashboard');
    });

    const googleLogin = async () => {
      await fetch('/sanctum/csrf-cookie', {
        method: 'GET',
        credentials: 'include'
      });

      window.location.href = '/auth/redirect/google';
    };

    const shouldShowSnackbar = computed(() => Object.keys(prefetchedData).length > 0);

    return { prefetchedData, googleLogin, dialog, shouldShowSnackbar, onSubmit, nickname, loading };
  }
}
</script>

<style scoped>
.login-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.v-card {
  width: 500px;
  padding: 1.5rem 1rem;
}

.v-card-title {
  font-size: 1rem;
  margin-bottom: 1rem;
  text-align: center;
}

.v-btn {
  text-transform: capitalize;
  width: 100%;
}
</style>