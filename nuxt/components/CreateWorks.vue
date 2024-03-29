<template>
    <v-card>
        <v-card-title class="py-4 pl-4 pr-3">
            <span>{{task.name}}</span>
            <v-spacer></v-spacer>
            <v-btn v-if="$route.name=='task'" @click="$emit('openTaskDialog',focusTask)" light height="35px" width="35px" fab elevation="0">
                <v-icon color="teal">mdi-cog-outline</v-icon>
            </v-btn>
        </v-card-title>
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
import Vue from "vue";
import { PropOptions } from "vue";
import { mapState } from "vuex";
import { AxiosRequestConfig, AxiosResponse, AxiosError } from "axios";
import {
    apiTaskReadResponseTaskWorkType,
    apiTaskReadResponseTaskType,
} from "@/types/api/task/read/response";
import { apiWorkDeleteRequestType } from "@/types/api/work/delete/request";
import { apiUserBearerAuthenticationResponseType } from "@/types/api/user/bearerAuthentication/response";
import { vformType } from "@/types/vuetify/vform";
import { apiWorkCreateRequestType } from "@/types/api/work/create/request";
export type selectType = {
    val: number;
    txt: string;
};
export default Vue.extend({
    props: {
        focusTask: Object as PropOptions<apiTaskReadResponseTaskType>,
        date: String,
        mode: String,
    },
    data() {
        return {
            deleteLoading: false as boolean,
            saveLoading: false as boolean,
            noError: false as boolean,
            task: {} as apiTaskReadResponseTaskType,
        };
    },
    computed: {
        ...mapState({
            loginInfo: (state: any): apiUserBearerAuthenticationResponseType =>
                state.loginInfo,
        }),
    },
    methods: {
        users(userId: number): object[] {
            let outputData: selectType[] = [];
            this.loginInfo.room_joined_users.forEach((user) => {
                const userDuplicateJudge =
                    this.task.works.filter(
                        (work: apiTaskReadResponseTaskWorkType) =>
                            work.work_user_id == user.id
                    ).length == 0;

                if (userDuplicateJudge || userId == user.id) {
                    outputData.push({ val: user.id, txt: user.name });
                }
            });
            return outputData;
        },
        addWork() {
            const form = this.$refs.form as vformType;
            form.validate();
            this.$nextTick(() => {
                if (!this.noError) {
                    return;
                }
                this.task.works.push({
                    work_user_id: 0,
                    work_minute: 0,
                } as apiTaskReadResponseTaskWorkType);
            });
        },
        removeWork(workIndex: number) {
            if (this.task.works.length == 1) {
                return;
            }
            this.task.works.splice(workIndex, 1);
        },
        async onClickSave() {
            const form = this.$refs.form as vformType;
            form.validate();
            if (!this.noError) {
                return;
            }
            this.saveLoading = true;
            let apiParam: apiWorkCreateRequestType = {
                date: this.date,
                task_id: this.task.task_id,
                works: [],
            };
            this.task.works.forEach((work) => {
                apiParam.works.push({
                    work_user_id: work.work_user_id,
                    work_minute: work.work_minute,
                });
            });
            const requestConfig: AxiosRequestConfig = {
                url: `/api/work/create`,
                method: "POST",
                data: apiParam,
            };
            await this.$axios(requestConfig)
                .then((res: AxiosResponse) => {
                    this.$emit("fetchData");
                })
                .finally(() => {
                    this.saveLoading = false;
                    this.$emit("onCloseModal");
                });
        },
        async onClickDelete() {
            if (
                !confirm(
                    `${this.date}、「${this.task.name}」の稼働情報を全て削除しますか？`
                )
            ) {
                return;
            }
            this.deleteLoading = true;
            let apiParam: apiWorkDeleteRequestType = {
                date: this.date,
                task_id: this.task.task_id,
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
                    this.deleteLoading = false;
                    this.$emit("onCloseModal");
                });
        },
    },
    mounted() {
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
            let obj = {};
            this.$set(obj, "work_user_id", this.loginInfo.id);
            this.$set(obj, "work_minute", this.focusTask.task_default_minute);
            this.task.works.push(obj as apiTaskReadResponseTaskWorkType);
        }
    },
});
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