<template>
    <v-card>
        <v-card-title>{{task.name}}</v-card-title>
        <v-divider></v-divider>
        <v-card-text class="pa-3" style="min-height:35vh;">
            <v-form ref="form" v-model="noError">
                <v-card v-for="(work,workIndex) in task.works" :key="workIndex" class="d-flex align-center mb-4" style="height:70px;overflow: hidden;">
                    <v-select class="pt-3 pl-3" style="width:46%;" label="担当者" :items="users(work.work_user_id)" v-model="work.work_user_id" item-value="val" item-text="txt" :rules="[v => !!v || '担当者は必須です']" dense></v-select>
                    <v-spacer></v-spacer>
                    <v-select class="pt-3" style="width:30%;" label="稼働時間" :items="$MINUTE" v-model="work.work_minute" item-value="val" item-text="txt" :rules="[v => !!v || '稼働時間は必須です']" dense></v-select>
                    <v-spacer></v-spacer>
                    <v-btn @click="removeWork(workIndex)" class="close_wrap" v-ripple style="width:12%;" icon>
                        <v-icon color="white">mdi-close</v-icon>
                    </v-btn>
                </v-card>
                <v-btn @click="addWork()" icon class="d-block mx-auto">
                    <v-icon style="font-size:35px;">mdi-plus</v-icon>
                </v-btn>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn color="error" :loading="deleteLoading" @click="onClickDelete()">削除</v-btn>
            <v-spacer></v-spacer>
            <v-btn class="mr-2" @click="$emit('onCloseModal')">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-btn color="teal" :loading="saveLoading" dark @click="onClickSave()">登録</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script lang="ts">
import { PropOptions } from "vue";
import { mapState } from "vuex";
import { workType } from '@/types/work'
import { taskType } from '@/types/task'
export type userType = {
    id: number;
    name: string;
};
export type selectType = {
    val: number;
    txt: string;
};
export default {
    props: {
        focusTask: Object as PropOptions<taskType>,
        date: String,
        mode: String,
    },
    data() {
        return {
            deleteLoading: false as boolean,
            saveLoading: false as boolean,
            noError: false as boolean,
            task: {} as taskType,
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        users(userId: number): object[] {
            let outputData: selectType[] = [];
            this.$store.state.loginInfo.room_joined_users.forEach(
                (user: userType) => {
                    const userDuplicateJudge =
                        this.task.works.filter(
                            (work: workType) => work.work_user_id == user.id
                        ).length == 0;

                    if (userDuplicateJudge || userId == user.id) {
                        outputData.push({ val: user.id, txt: user.name });
                    }
                }
            );
            return outputData;
        },
        addWork(): void {
            this.$refs.form.validate();
            if (!this.noError) {
                return;
            }
            this.task.works.push({
                user_id: 0,
                minute: 0,
            });
        },
        removeWork(workIndex: number): void {
            if (this.task.works.length == 1) {
                return;
            }
            this.task.works.splice(workIndex, 1);
        },
        async onClickSave(): Promise<void> {
            this.$refs.form.validate();
            if (!this.noError) {
                return;
            }
            this.saveLoading = true;
            await this.$axios
                .post(`/api/work/create`, this.task)
                .then((): void => {
                    this.$emit("fetchData");
                })
                .finally((): void => {
                    this.saveLoading = false;
                    this.$emit("onCloseModal");
                });
        },
        async onClickDelete(): Promise<void> {
            if (
                !confirm(
                    `${this.date}、「${this.task.name}」の稼働情報を全て削除しますか？`
                )
            ) {
                return;
            }
            this.deleteLoading = true;
            await this.$axios
                .delete(
                    `/api/work/delete?date=${this.date}&task_id=${this.task.task_id}`
                )
                .then((): void => {
                    this.$emit("fetchData");
                })
                .finally((): void => {
                    this.deleteLoading = false;
                    this.$emit("onCloseModal");
                });
        },
    },
    mounted(): void {
        this.$set(this.task, "date", this.date);
        for (const [key, value] of Object.entries(this.focusTask)) {
            if (Array.isArray(value)) {
                let array: object[] = [];
                value.forEach((valueObj) => {
                    let obj: object = {};
                    for (const [key2, value2] of Object.entries(valueObj)) {
                        this.$set(obj, key2, value2);
                    }
                    array.push(obj);
                });
                this.$set(this.task, key, array);
            } else {
                this.$set(this.task, key, value);
            }
        }
        if (!this.focusTask.works.length) {
            let obj: object = {};
            this.$set(obj, "work_user_id", this.loginInfo["id"]);
            this.$set(obj, "work_minute", this.focusTask.task_default_minute);
            this.task.works.push(obj);
        }
    },
};
</script>
<style lang="scss" scoped>
.close_wrap {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.6);
    height: 100%;
    border-radius: 0 !important;
}
</style>