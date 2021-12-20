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
        onResize() {
            setTimeout(() => {
                this.reMount();
            }, 200);
        },
    },
    watch: {
        propsDatas() {
            this.reMount();
        },
    },
    mounted() {
        window.addEventListener("resize", this.onResize);
    },
    beforeDestroy() {
        window.removeEventListener("resize", this.onResize);
    },
};
</script>

<style>
</style>