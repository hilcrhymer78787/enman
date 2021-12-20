<template>
    <div>
        <AnalyticsForm class="mb-5"/>
        <PieGraphArea v-if="analyticsWorks" :works="analyticsWorks" :subttl="analyticsWorks.span" />
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    computed: {
        ...mapState(["analyticsWorks"]),
    },
    mounted() {
        this.$store.dispatch("setAnalyticsWorks");
    },
};
</script>