<template>
  <div>
    <router-view></router-view> <!-- All pages will be shown here -->
  </div>
</template>

<script>
import { provide } from 'vue'

export default {
  props: {
    prefetchedData: Object
  },
  setup(props) {
    provide('prefetchedData', props.prefetchedData)
  }
}
</script>