<template>
    <PieGraph v-if="isShowPieGraph" :mode="mode" :propsDatas="propsDatas" />
</template>

<script lang="ts">
import Vue from "vue";
export default Vue.extend({
    props: ["mode", "propsDatas"],
    data() {
        return {
            isShowPieGraph: true as boolean,
            timeoutId: 0 as number,
        };
    },
    methods: {
        reMount(): void {
            // 円グラフ再描画
            this.isShowPieGraph = false;
            this.$nextTick(() => {
                this.isShowPieGraph = true;
            });
        },
        onResize(): void {
            clearTimeout(this.timeoutId);
            this.timeoutId = window.setTimeout(() => {
                this.reMount();
            }, 100);
        },
    },
    watch: {
        propsDatas() {
            this.reMount();
        },
    },
    mounted() {
        window.addEventListener("resize", this.onResize);
        window.addEventListener("orientationchange", this.onResize);
    },
    beforeDestroy() {
        window.removeEventListener("resize", this.onResize);
        window.addEventListener("orientationchange", this.onResize);
    },
});
</script>

<style>
</style>