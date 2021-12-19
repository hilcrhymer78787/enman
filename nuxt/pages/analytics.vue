<template>
    <div>
        <pre>{{calendars}}</pre>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            test: "",
            isShowPieGraph: true,
        };
    },
    computed: {
        ...mapState(["calendars"]),
    },
    mounted(){
        this.$store.dispatch('setCalendars')
    }
};
</script>

<style>
</style>