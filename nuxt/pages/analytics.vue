<template>
    <div>
        <AnalyticsForm class="mb-5" />
        <PieGraphArea v-if="works" :works="works" :subttl="works.span" />
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    computed: {
        ...mapState({
            works: (state) => state.analytics.works,
        }),
    },
    mounted() {
        this.$store.dispatch("analytics/setWorks");
    },
};
</script>