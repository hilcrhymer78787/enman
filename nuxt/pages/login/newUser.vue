<template>
    <v-dialog :value="true" scrollable hide-overlay persistent no-click-animation>
        <CreateUser mode="create" />
    </v-dialog>
</template>

<script lang="ts">
import Vue from "vue";
export default Vue.extend({
    layout: "login",
});
</script>