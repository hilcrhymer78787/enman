<template>
    <v-dialog :value="true" scrollable hide-overlay persistent no-click-animation>
        <CreateUser mode="create" />
    </v-dialog>
</template>

<script>
export default {
    layout:'login',
};
</script>

<style>
</style>