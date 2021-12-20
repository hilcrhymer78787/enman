<template>
    <div>
        <PieGraphArea v-if="analyticsWorks && isShowPieGraph" :works="analyticsWorks" subttl="全期間" />
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            isShowPieGraph: true,
            param: {
                start_date: "",
                last_date: "",
            },
        };
    },
    computed: {
        ...mapState(["analyticsWorks"]),
    },
    mounted() {
        this.$store.dispatch("setAnalyticsWorks", this.param);
    },
};
</script>

<style>
</style>