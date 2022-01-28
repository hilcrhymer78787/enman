<template>
    <v-img v-if="src.slice( 0, 4 ) == 'http'" :src="src" aspect-ratio="1" class="rounded-circle"></v-img>
    <v-img v-else :src="backUrl+'/storage/'+src" aspect-ratio="1" class="rounded-circle"></v-img>
</template>

<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
    props: {
        src: String,
    },
    data() {
        return {
            backUrl: process.env.API_BASE_URL,
        };
    },
});
</script>