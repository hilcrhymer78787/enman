<template>
    <div>
        <AnalyticsForm class="mb-5" />
        <PieGraphArea v-if="works" :works="works" :subttl="works.span" />
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { mapState } from "vuex";
export default Vue.extend({
    computed: {
        ...mapState({
            works: (state: any) => state.analytics.works,
        }),
    },
    mounted() {
        this.$store.dispatch("analytics/setWorks");
    },
});
</script>