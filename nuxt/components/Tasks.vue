<template>
    <div>
        <v-card>
            <v-card-title>
                <span>タスク</span>
                <v-spacer></v-spacer>
                <span class="pt-1 mr-3">{{date}}</span>
                <v-btn v-if="mode == 'today'" @click="openTaskDialog()" light height="35px" width="35px" fab elevation="0">
                    <v-icon color="teal">mdi-plus</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text id="scrollArea" :style="mode != 'today' ? 'height: 60vh;overflow-y:scroll;':''" class="pa-0">
                <div id="scrollAreaInner">
                    <vuedraggable @change="dragged()" :options="{animation: 200,  delay: 50 }" v-model="displayTasks">
                        <div v-for="(task,taskIndex) in displayTasks" :key="taskIndex">
                            <v-list-item class="pl-2 pr-0" style="height:60px;overflow:hidden;cursor:move;">
                                <v-list-item-avatar @click="onFocusTask(task)">
                                    <v-icon v-if="task.works.length == 0">mdi-account</v-icon>
                                    <PartsImg v-if="task.works.length == 1" :src="task.works[0].work_user_img" />
                                    <PartsImg v-if="task.works.length >= 2" :src="loginInfo.room_img" />
                                </v-list-item-avatar>
                                <v-list-item-content @click="onFocusTask(task)">
                                    <v-list-item-title>{{task.name}}</v-list-item-title>
                                    <v-list-item-subtitle style="font-size:12px;">
                                        <span>想定:{{task.task_default_minute}}分</span>
                                        <span v-if="task.works.length">稼働:{{task.minute}}分</span>
                                        <span v-if="task.works.length == 1">担当:{{task.works[0].work_user_name}}</span>
                                        <span v-if="task.works.length >= 2">担当:複数人</span>
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                                <v-btn :loading="loadings[taskIndex]" class="check" icon>
                                    <v-icon @click="onClickCheckBoxMarked(task,taskIndex)" style="font-size: 25px;" v-if="task.works.length">mdi-checkbox-marked-outline</v-icon>
                                    <v-icon @click="onClickCheckBoxBlank(task,taskIndex)" style="font-size: 25px;" v-else>mdi-checkbox-blank-outline</v-icon>
                                </v-btn>
                            </v-list-item>
                            <v-divider v-if="taskIndex + 1 != displayTasks.length || mode != 'today'"></v-divider>
                        </div>
                        <div class="pa-5 text-center" v-if="!displayTasks.length">現在登録されているタスクはありません</div>
                    </vuedraggable>
                </div>
            </v-card-text>
            <v-divider v-if="mode != 'today'"></v-divider>
            <v-card-actions v-if="mode != 'today'">
                <v-spacer></v-spacer>
                <v-btn @click="$emit('onCloseDialog')">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-dialog v-model="dialog" scrollable>
            <CreateWorks @openTaskDialog="openTaskDialog" @onCloseModal="dialog = false" :date="date" :mode="mode" @fetchData="$emit('fetchData')" :focusTask="focusTask" v-if="dialog" />
        </v-dialog>

        <v-dialog v-model="taskDialog" scrollable>
            <CreateTasks @onCloseTaskDialog="onCloseTaskDialog" :focusTask="focusTask" v-if="taskDialog" />
        </v-dialog>

    </div>
</template>

<script lang="ts">
import Vue from "vue";
import vuedraggable from "vuedraggable";
import { mapState } from "vuex";
import { PropOptions } from "vue";
import { AxiosRequestConfig, AxiosResponse, AxiosError } from "axios";
import { apiUserBearerAuthenticationResponseType } from "@/types/api/user/bearerAuthentication/response";
import { apiTaskReadResponseTaskType } from "@/types/api/task/read/response";
import { apiWorkCreateRequestType } from "@/types/api/work/create/request";
import { apiWorkDeleteRequestType } from "@/types/api/work/delete/request";
import {
    apiTaskSortsetRequestType,
    apiTaskSortsetRequestTaskType,
} from "@/types/api/task/sortset/request";
import moment from "moment";

export default Vue.extend({
    props: {
        mode: String,
        tasks: Array as PropOptions<apiTaskReadResponseTaskType[]>,
    },
    components: {
        vuedraggable: vuedraggable,
    },
    data() {
        return {
            displayTasks: [] as apiTaskReadResponseTaskType[],
            taskDialog: false as boolean,
            dialog: false as boolean,
            focusTask: {} as apiTaskReadResponseTaskType | null,
            loadings: [] as boolean[],
        };
    },
    computed: {
        ...mapState({
            loginInfo: (state: any): apiUserBearerAuthenticationResponseType =>
                state.loginInfo,
        }),
        year(): number {
            return Number(this.$route.query.year);
        },
        month(): number {
            return Number(this.$route.query.month);
        },
        day(): number {
            return Number(this.$route.query.day);
        },
        date(): string {
            if (this.mode == "today") {
                let year = moment().year();
                let month = moment().month() + 1;
                let day = moment().date();
                return `${year}-${month}-${day}`;
            }
            return `${this.year}-${this.month}-${this.day}`;
        },
    },
    methods: {
        dragged() {
            let tasks: apiTaskSortsetRequestTaskType[] = [];
            this.displayTasks.forEach((task) => {
                tasks.push({
                    task_id: task.task_id,
                });
            });
            let apiParam: apiTaskSortsetRequestType = {
                tasks: tasks,
            };
            const requestConfig: AxiosRequestConfig = {
                url: `/api/task/sortset`,
                method: "POST",
                data: apiParam,
            };
            this.$axios(requestConfig)
                .then((res: AxiosResponse) => {})
                .catch((err: AxiosError) => {});
        },
        onFocusTask(task: apiTaskReadResponseTaskType) {
            this.dialog = true;
            this.focusTask = task;
        },
        openTaskDialog(task: apiTaskReadResponseTaskType) {
            if (task) {
                this.focusTask = task;
            } else {
                this.focusTask = null;
            }
            this.taskDialog = true;
        },
        onCloseTaskDialog() {
            this.taskDialog = false;
            this.dialog = false;
            this.$emit("fetchData");
        },
        async onClickCheckBoxBlank(
            task: apiTaskReadResponseTaskType,
            taskIndex: number
        ) {
            this.$set(this.loadings, taskIndex, true);
            let apiParam: apiWorkCreateRequestType = {
                date: this.date,
                task_id: task.task_id,
                works: [
                    {
                        work_user_id: this.loginInfo.id,
                        work_minute: task.task_default_minute,
                    },
                ],
            };
            const requestConfig: AxiosRequestConfig = {
                url: `/api/work/create`,
                method: "POST",
                data: apiParam,
            };
            await this.$axios(requestConfig)
                .then(() => {
                    this.$emit("fetchData");
                })
                .finally(() => {
                    this.$set(this.loadings, taskIndex, false);
                });
        },
        async onClickCheckBoxMarked(
            task: apiTaskReadResponseTaskType,
            taskIndex: number
        ) {
            if (
                !confirm(
                    `${this.date}、「${task.name}」の稼働情報を全て削除しますか？`
                )
            ) {
                return;
            }
            this.$set(this.loadings, taskIndex, true);
            let apiParam: apiWorkDeleteRequestType = {
                date: this.date,
                task_id: task.task_id,
            };
            const requestConfig: AxiosRequestConfig = {
                url: `/api/work/delete`,
                method: "DELETE",
                data: apiParam,
            };
            await this.$axios(requestConfig)
                .then((res: AxiosResponse) => {
                    this.$emit("fetchData");
                })
                .finally(() => {
                    this.$set(this.loadings, taskIndex, false);
                });
        },
    },
    mounted() {
        this.displayTasks = this.tasks;
        this.$store.commit("task/setFocusTasks", []);
        this.$emit("fetchData");
        // スクロール対応
        this.$nextTick(() => {
            const scrollArea: any = document.querySelector("#scrollArea");
            const scrollAreaInner: any =
                document.querySelector("#scrollAreaInner");
            if (!scrollArea) {
                return;
            }
            scrollArea.addEventListener("scroll", () => {
                if (scrollArea.scrollTop == 0) {
                    scrollArea.scrollTo({ top: 1 });
                }
                if (
                    scrollArea.scrollTop + scrollArea.clientHeight ==
                    scrollAreaInner.clientHeight
                ) {
                    scrollArea.scrollBy({ top: -1 });
                }
            });
        });
    },
    watch: {
        tasks() {
            this.displayTasks = this.tasks;
        },
    },
});
</script>
<style lang="scss" scoped>
.check {
    border-radius: 0;
    height: 100%;
    width: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(128, 128, 128, 0.1);
}
</style>