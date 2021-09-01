<template>
    <div>
        <!-- <pre>{{$store.state.everydayTasks}}</pre> -->
        <v-card v-for="(task,taskIndex) in everydayTasks" :key="taskIndex" :class="task.works.length ? 'blue' : 'grey'" class="lighten-5 mb-4 px-2 pb-1 pt-2">
            <v-card-title class="d-flex align-start pa-0">
                <span style="width:calc(100% - 25px);">{{task.name}}</span>
                <v-icon v-if="task.works.length" @click="onClickCheckboxMarked(taskIndex)" color="blue">mdi-checkbox-marked-outline</v-icon>
                <v-icon v-else @click="onClickCheckboxBlank(taskIndex)">mdi-checkbox-blank-outline</v-icon>
            </v-card-title>
            <v-card v-for="(work, workIndex) in task.works" :key="workIndex" class="px-2 mb-2">
                <v-card-actions class="pa-0 ma-0 d-flex">
                    <v-select class="pa-0 mr-2" v-model="work.user_id" :items="$store.state.users" item-text="name" item-value="id" style="width:120px;"></v-select>
                    <v-select class="pa-0" v-model="work.minute" :items="$MINUTE" item-text="txt" item-value="val" style="width:70px;"></v-select>
                </v-card-actions>
            </v-card>
        </v-card>

        <!-- <v-btn @click="dialog = true" style="bottom:70px;" fixed right fab dark>
            <v-icon dark>mdi-plus</v-icon>
        </v-btn>

        <v-dialog v-model="dialog" scrollable>
            <v-card>
                <v-card-title dark>add new task</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <IndexForm />
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="dialog = false">Close</v-btn>
                    <v-btn @click="dialog = false">Add</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog> -->
        <pre>{{$data}}</pre>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            tasks: [],
            dialog: false,
        };
    },
    computed: {
        ...mapState(["everydayTasks"]),
    },
    methods: {
        onClickCheckboxBlank(taskIndex) {
            let works = this.tasks[taskIndex].works;
            let obj = {};
            this.$set(obj, "user_id", 1);
            this.$set(obj, "minute", 5);
            works.push(obj);
        },
        onClickCheckboxMarked(taskIndex) {
            let works = this.tasks[taskIndex].works;
            works.splice(0, works.length);
        },
    },
    mounted() {
        this.everydayTasks.forEach((task, i) => {
            let obj = {};
            // タスクセット
            Object.keys(task).forEach((key) => {
                this.$set(obj, key, task[key]);
            });
            let array = [];
            if (task.works.length) {
                task.works.forEach((work) => {
                    let workObj = {};
                    Object.keys(work).forEach((key) => {
                        this.$set(workObj, key, work[key]);
                    });
                    array.push(workObj);
                });
            }
            this.$set(obj, "works", array);
            this.tasks.push(obj);
        });
    },
};
</script>

<style lang="scss" scoped>
::v-deep {
    .v-text-field__details {
        display: none !important;
    }
}
</style>