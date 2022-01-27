<template>
    <Tasks mode="today" @fetchData="fetchData" :tasks="todayTasks" />
</template>
<script lang="ts">
import Vue from "vue";
import moment from "moment";
import { mapState } from "vuex";
export default Vue.extend({
    middleware({ redirect, route }: any) {
        if (!route.query.year || !route.query.month || !route.query.day) {
            let year = moment().year();
            let month = moment().month() + 1;
            let day = moment().date();
            redirect(`/?year=${year}&month=${month}&day=${day}`);
        }
    },
    computed: {
        ...mapState(["todayTasks"]),
    },
    methods: {
        fetchData(): void {
            this.$store.dispatch("setTodayTasks");
        },
    },
});
</script>

<style>
</style>