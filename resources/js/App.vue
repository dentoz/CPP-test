<template>
  <div>
    <router-view></router-view> <!-- All pages will be shown here -->
  </div>
</template>

<script>
import { provide } from 'vue'
import { onMessage, messaging } from "./firebase";
import { showInviteToast } from './utils/inviteToast';
import { useToast } from "vue-toastification";

export default {
  props: {
    prefetchedData: Object
  },
  setup(props) {
    provide('prefetchedData', props.prefetchedData);
    const toast = useToast();
    
    onMessage(messaging, (payload) => {
      console.log('📥 FCM message received in foreground:', payload);
      if (payload.data.type === 'INVITE_REQUESTED') {
        showInviteToast(payload.data);
      }

      if (payload.data.type === 'INVITATION_ACCEPTED') {
        toast.success(`${payload.data.from_user} accepted your invitation`, {
          timeout: false,
          closeOnClick: false,
          draggable: false,
          position: 'top-right',
          onClick: () => { }, // prevent dismiss
        })
      }

      if (payload.data.type === 'INVITATION_REJECTED') {
        toast.warning(`${payload.data.from_user} rejected your invitation`, {
          timeout: false,
          closeOnClick: false,
          draggable: false,
          position: 'top-right',
          onClick: () => { }, // prevent dismiss
        })
      }
    })
  }
}
</script>