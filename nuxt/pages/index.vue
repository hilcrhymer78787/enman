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
    methods: {
        Get() {
            axios.get("http://localhost:8000/api/task/read").then((res) => {
                this.tasks = res.data;
            });
        },
    },
    mounted() {
        this.Get();
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
</style>