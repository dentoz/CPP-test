<template>
    <v-navigation-drawer
        v-model="isDrawerShow"
        :location="$vuetify.display.mobile ? 'bottom' : undefined"
        temporary
      >
        testttt
      </v-navigation-drawer>
</template>

<script setup>
import { defineProps, watch, ref } from 'vue'

const props = defineProps({
    showSidebar: {
        type: Boolean,
        default: false
    },
})

const isDrawerShow = ref(props.showSidebar);

watch(() => props.showSidebar, (newVal) => {
    isDrawerShow.value = newVal;
});

</script>

<style scoped>
.v-navigation-drawer {
    top: 0 !important;
    background-color: #343a40;
    color: #f8f9fa;
    z-index: 99999 !important;
    height: 100vh !important;
}
</style>