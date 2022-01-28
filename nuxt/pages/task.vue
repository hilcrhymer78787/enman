<template>
    <Tasks mode="today" @fetchData="fetchData" :tasks="todayTasks" />
</template>
<script lang="ts">
import Vue from "vue";
import moment from "moment";
import { mapState } from "vuex";
export default Vue.extend({
    middleware({ redirect, route }) {
        if (!route.query.year || !route.query.month || !route.query.day) {
            let year = moment().year();
            let month = moment().month() + 1;
            let day = moment().date();
            redirect(`/?year=${year}&month=${month}&day=${day}`);
        }
    },
    computed: {
        ...mapState({
            todayTasks: (state: any) => state.task.todayTasks,
        }),
    },
    methods: {
        fetchData() {
            this.$store.dispatch("task/setTodayTasks");
        },
    },
});
</script>