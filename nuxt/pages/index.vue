<template>
    <div class="crud">
        <ul>
            <li v-for="(task,taskIndex) in tasks" :key="taskIndex">
                <h2><input type="checkbox" v-model="task.emergency"> {{task.content}}</h2>
                <h5>作成日：{{task.created_at}}</h5>
                <h5>更新日：{{task.updated_at}}</h5>
            </li>
        </ul>
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
li{
    padding-bottom: 10px;
    margin-bottom: 10px;
    border-bottom: 1px solid black;
}
</style>