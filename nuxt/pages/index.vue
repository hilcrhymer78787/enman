<template>

</template>

<script lang="ts">
import moment from "moment";
export default {
    middleware({ redirect }: any) {
        let year = moment().year();
        let month = moment().month() + 1;
        let day = moment().date();
        redirect(`/task?year=${year}&month=${month}&day=${day}`);
    },
};
</script>

<style>
</style>