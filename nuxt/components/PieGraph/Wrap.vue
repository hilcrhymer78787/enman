<template>
    <PieGraph :mode="mode" :propsDatas="propsDatas" />
</template>

<script>
export default {
    props: ["mode", "propsDatas"],
    data() {
        return {
            isShowPieGraph: false,
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
    },
    watch: {
        propsDatas() {
            this.reMount();
        },
    },
    mounted() {
        window.addEventListener("resize", this.reMount);
    },
    beforeDestroy() {
        window.removeEventListener("resize", this.reMount);
    },
};
</script>

<style>
</style>