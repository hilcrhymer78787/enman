<template>
    <div class="crud">
        <pre>{{$data}}</pre>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            tasks: {},
        };
    },

    mounted() {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 1;
        const day = today.getDate();
        this.$axios
            .get(
                `/api/task/show?year=${year}&month=${month}&day=${day}&token=${this.$store.state.loginInfo.token}`
            )
            .then((res) => {
                this.tasks = res.data;
                console.log(res.data);
            });
    },
};
</script>
<style lang="scss" scoped>
.crud {
    padding: 20px;
    button {
        margin: 0 20px 50px;
    }
}
li {
    padding-bottom: 10px;
    margin-bottom: 10px;
    border-bottom: 1px solid black;
}
</style>