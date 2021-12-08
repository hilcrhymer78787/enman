<template>
    <div>
        <v-card>
            <v-toolbar color="teal" height="70px" dark class="d-block" style="box-shadow:none;">
                <v-toolbar-title>毎日タスク</v-toolbar-title>
                <v-spacer></v-spacer>
                <span class="pt-1 mr-3">{{date}}</span>
                <v-btn v-if="mode == 'today'" @click="openTaskDialog()" light height="35px" width="35px" fab elevation="0">
                    <v-icon color="teal">mdi-plus</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text id="scrollArea" :style="mode != 'today' ? 'height: 60vh;overflow-y:scroll;':''" class="pa-0">
                <div id="scrollAreaInner">
                    <vuedraggable @change="dragged()" :options="{animation: 200,  delay: 50 }" v-model="displayTasks">
                        <div v-for="(task,taskIndex) in displayTasks" :key="taskIndex">
                            <swiper @slideChangeTransitionStart="hoge()" :options="swiperOption">
                                <swiper-slide class="swiper_btn" v-if="mode == 'today'">
                                    <v-btn @click="deleteTask(task)" :loading="deleteTaskLoading" color="error" dark class="pa-0 mr-3">削除</v-btn>
                                    <v-btn @click="openTaskDialog(task)" color="orange" dark class="pa-0">編集</v-btn>
                                </swiper-slide>
                                <swiper-slide>
                                    <v-list-item class="pl-2 pr-0" style="height:60px;overflow:hidden;">
                                        <v-list-item-avatar @click="onFocusTask(task)">
                                            <v-icon v-if="task.works.length == 0">mdi-account</v-icon>
                                            <v-img v-if="task.works.length == 1 && task.works[0].work_user_img.slice( 0, 4 ) == 'http'" :src="task.works[0].work_user_img" aspect-ratio="1" class="rounded-circle"></v-img>
                                            <v-img v-else-if="task.works.length == 1" :src="backUrl+'/storage/'+task.works[0].work_user_img" aspect-ratio="1" class="rounded-circle"></v-img>
                                            <v-img v-if="task.works.length >= 2 && loginInfo.room_img.slice( 0, 4 ) == 'http'" :src="loginInfo.room_img" aspect-ratio="1" class="rounded-circle"></v-img>
                                            <v-img v-else-if="task.works.length >= 2" :src="backUrl+'/storage/'+loginInfo.room_img" aspect-ratio="1" class="rounded-circle"></v-img>
                                        </v-list-item-avatar>
                                        <v-list-item-content @click="onFocusTask(task)">
                                            <v-list-item-title>{{task.task_name}}</v-list-item-title>
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
                                </swiper-slide>
                            </swiper>
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
            <CreateWorks @onCloseModal="dialog = false" :date="date" :mode="mode" @getTasks="$emit('getTasks')" @getWorks="$emit('getWorks')" :focusTask="focusTask" v-if="dialog" />
        </v-dialog>

        <v-dialog v-model="taskDialog" scrollable>
            <CreateTasks @onCloseTaskDialog="taskDialog = false" :focusTask="focusTask" v-if="taskDialog" />
        </v-dialog>

    </div>
</template>

<script>
import vuedraggable from "vuedraggable";
import { mapState } from "vuex";
export default {
    props: ["tasks", "date", "mode"],
    components: {
        vuedraggable: vuedraggable,
    },
    data() {
        return {
            displayTasks: [],
            backUrl: process.env.API_BASE_URL,
            swiperOption: {
                initialSlide: 1,
                slidesPerView: "auto",
            },
            deleteTaskLoading: false,
            taskDialog: false,
            dialog: false,
            focusTask: null,
            loadings: [],
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        dragged() {
            this.$axios.post(`/api/task/sortset`, { tasks: this.displayTasks });
        },
        hoge() {
            return;
        },
        onFocusTask(task) {
            this.dialog = true;
            this.focusTask = task;
        },
        async onClickCheckBoxBlank(task, taskIndex) {
            this.$set(this.loadings, taskIndex, true);
            await this.$axios.post(`/api/work/create`, {
                date: this.date,
                task_id: task.task_id,
                works: [
                    {
                        work_user_id: this.loginInfo.id,
                        work_minute: task.task_default_minute,
                    },
                ],
            });
            if (this.mode == "today") {
                await this.$store.dispatch("setTodayTasks");
            } else {
                await this.$emit("getTasks", this.date);
                this.$emit("getWorks");
            }

            this.$set(this.loadings, taskIndex, false);
        },
        async onClickCheckBoxMarked(task, taskIndex) {
            if (
                !confirm(
                    `${this.date}、「${task.task_name}」の稼働情報を全て削除しますか？`
                )
            ) {
                return;
            }
            this.$set(this.loadings, taskIndex, true);
            const date = this.date;
            const task_id = task.task_id;
            await this.$axios.delete(
                `/api/work/delete?date=${date}&task_id=${task_id}`
            );
            if (this.mode == "today") {
                await this.$store.dispatch("setTodayTasks");
            } else {
                await this.$emit("getTasks", this.date);
                this.$emit("getWorks");
            }
            this.$set(this.loadings, taskIndex, false);
        },
        async deleteTask(task) {
            if (
                !confirm(
                    `「${task.task_name}」に関するデータを全て削除しますか？`
                )
            ) {
                return;
            }
            this.deleteTaskLoading = true;
            const task_id = task.task_id;
            await this.$axios.delete(`/api/task/delete?task_id=${task_id}`);
            await this.$store.dispatch("setTodayTasks");
            this.deleteTaskLoading = false;
        },
        openTaskDialog(task) {
            if (task) {
                this.focusTask = task;
            } else {
                this.focusTask = null;
            }
            this.taskDialog = true;
        },
    },
    async mounted() {
        await this.$store.dispatch("setTodayTasks");
        this.displayTasks = this.tasks;
        this.$nextTick(() => {
            const scrollArea = document.querySelector("#scrollArea");
            const scrollAreaInner = document.querySelector("#scrollAreaInner");
            if(!scrollArea){
                return
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
};
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
::v-deep {
    .swiper_btn {
        width: 180px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(128, 128, 128, 0.1);
    }
}
</style>