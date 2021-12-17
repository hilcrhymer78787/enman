<template>
    <div>
        <pre>{{test}}</pre>
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
        ...mapState(["works"]),
    },
};
</script>

<style>
</style>