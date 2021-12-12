<template>
    <Tasks mode="today" :date="date" :tasks="$store.state.todayTasks" />
</template>
<script lang="ts">
import moment from "moment";
export default {
    computed: {
        date() {
            return moment(new Date()).format("YYYY-MM-DD");
        },
    },
};
</script>

<style>
</style>