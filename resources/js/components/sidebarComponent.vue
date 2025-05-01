<template>
    <v-navigation-drawer
        v-model="isDrawerShow"
        :location="$vuetify.display.mobile ? 'bottom' : undefined"
        temporary
      >
        <v-list>
            <v-list-item-group v-for="(item, index) in items" :key="index">
                <v-list-item :href="item.link" :class="{'d-flex': true, 'align-center': true, active: item.active}">
                    <template v-slot:prepend>
                        <v-icon :icon="item.icon"></v-icon>
                    </template>
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item>
            </v-list-item-group>
        </v-list>
      </v-navigation-drawer>
</template>

<script setup>
import { defineProps, watch, ref, onMounted  } from 'vue'
import { useRoute } from 'vue-router'

const props = defineProps({
    showSidebar: {
        type: Boolean,
        default: false
    },
})

const route = useRoute()
const isDrawerShow = ref(props.showSidebar);
const items = ref([
    { title: 'Dashboard', icon: 'mdi-view-dashboard', link: '/dashboard', active: true },
    { title: 'Chat Room', icon: 'mdi-account-multiple', link: '/chatroom', active: false },
]);

watch(() => props.showSidebar, (newVal) => {
    isDrawerShow.value = newVal;
});

onMounted(async() => {
    items.value.map(item => {
        if (typeof item.link !== 'undefined') {
            if (item.link == route.path) {
                item.active = true
            } else {
                item.active = false
            }
        }
        return item
    })
})

</script>

<style scoped>
.v-navigation-drawer {
    top: 0 !important;
    background-color: #343a40;
    color: #f8f9fa;
    z-index: 99999 !important;
    height: 100vh !important;
}

.v-list {
    margin-top: 65px;
}
.v-list-item.active {
    background-color: #3c4247;
}
</style>