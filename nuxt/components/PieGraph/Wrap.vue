<template>
    <PieGraph v-if="isShowPieGraph" :mode="mode" :propsDatas="propsDatas" />
</template>

<script>
export default {
    props: ["mode", "propsDatas"],
    data() {
        return {
            isShowPieGraph: true,
            timeoutId:"",
        };
    },
    methods: {
        reMount() {
            // 円グラフ再描画
            this.isShowPieGraph = false;
            this.$nextTick(() => {
                this.isShowPieGraph = true;
            });
        },
        onResize() {
            clearTimeout(this.timeoutId)
            this.timeoutId = setTimeout(() => {
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
};
</script>

<style>
</style>