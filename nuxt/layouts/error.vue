<template>
    <v-app dark>
        <h1 v-if="error.statusCode === 404">
            {{ pageNotFound }}
        </h1>
        <h1 v-else>
            {{ otherError }}
        </h1>
        <NuxtLink to="/">
            Home page
        </NuxtLink>
    </v-app>
</template>

<script lang="ts">
import Vue from "vue";
export default Vue.extend({
    layout: "empty",
    props: {
        error: {
            type: Object,
            default: null,
        },
    },
    data() {
        return {
            pageNotFound: "404 Not Found" as string,
            otherError: "An error occurred" as string,
        };
    },
});
</script>

<style scoped>
h1 {
    font-size: 20px;
}
</style>
