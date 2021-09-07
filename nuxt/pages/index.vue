<template>
    <Tasks mode="today" :date="date" :tasks="$store.state.todayTasks" />
</template>
<script>
import moment from "moment";
export default {
    computed: {
        date() {
            return moment(new Date()).format("YYYY-MM-DD");
        },
    },
    mounted() {
        this.$store.dispatch("setTodayTasks");
    },
};
</script>

<style>
</style>