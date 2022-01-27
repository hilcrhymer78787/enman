<template>
    <div>
        <AnalyticsForm class="mb-5" />
        <PieGraphArea v-if="works" :works="works" :subttl="works.span" />
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { mapState } from "vuex";
import { apiWorkReadAnalyticsRequestType } from "@/types/api/work/read/analytics/request";
export default Vue.extend({
    computed: {
        ...mapState({
            works: (state: any) => state.analytics.works,
        }),
    },
    mounted() {
        let param: apiWorkReadAnalyticsRequestType = {
            start_date: "",
            last_date: "",
        };
        this.$store.dispatch("analytics/setWorks", param);
    },
});
</script>