<template>
    <v-card>
        <v-card-title>
            <span v-if="this.focusTask">「{{this.focusTask.name}}」を編集</span>
            <span v-else>新しいタスクを追加</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text class="pa-3" style="min-height:30vh;">
            <v-form ref="form" v-model="noError">
                <v-text-field validate-on-blur v-model="form.task_name" :rules="[v => !!v || 'タスクの名前は必須です']" label="タスクの名前" prepend-icon="mdi-format-title" required></v-text-field>
                <v-select v-model="form.task_default_minute" :items="$MINUTE" :rules="[v => !!v || '想定時間は必須です']" label="想定時間" prepend-icon="mdi-clock-outline" required item-value="val" item-text="txt"></v-select>
            </v-form>
        </v-card-text>

        <v-divider></v-divider>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="mr-2" @click="$emit('onCloseTaskDialog')">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-btn color="teal" :loading="loading" dark @click="submit()">登録</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script lang="ts">
import { mapState } from "vuex";
import Vue, { PropOptions } from "vue";
import { AxiosRequestConfig, AxiosResponse, AxiosError } from "axios";
import { apiTaskReadResponseTaskType } from "@/types/api/task/read/response";
import { apiTaskCreateRequestType } from "@/types/api/task/create/request";
import { apiUserBearerAuthenticationResponseType } from "@/types/api/user/bearerAuthentication/response";
import { vformType } from "@/types/vuetify/vform";
export default Vue.extend({
    props: {
        focusTask: Object as PropOptions<apiTaskReadResponseTaskType>,
    },
    data: () => ({
        loading: false as boolean,
        noError: false as boolean,
        form: {
            task_id: 0 as number,
            task_name: "" as string,
            task_default_minute: "" as string,
            task_is_everyday: 1 as number,
            task_room_id: 1 as number,
        },
    }),
    computed: {
        ...mapState({
            loginInfo: (state: any): apiUserBearerAuthenticationResponseType =>
                state.loginInfo,
        }),
    },
    methods: {
        async submit() {
            const form = this.$refs.form as vformType;
            form.validate();
            if (!this.noError) {
                return;
            }
            this.loading = true;
            let apiParam: apiTaskCreateRequestType = {
                task: this.form,
            };
            const requestConfig: AxiosRequestConfig = {
                url: `/api/task/create`,
                method: "POST",
                data: apiParam,
            };
            await this.$axios(requestConfig)
                .then((res: AxiosResponse) => {
                    this.$store.dispatch("task/setTodayTasks");
                })
                .finally(() => {
                    this.loading = false;
                    this.$emit("onCloseTaskDialog");
                });
        },
    },
    mounted() {
        this.$set(this.form, "task_room_id", this.loginInfo.room_id);
        if (this.focusTask) {
            this.$set(this.form, "task_id", this.focusTask.task_id);
            this.$set(this.form, "task_name", this.focusTask.name);
            this.$set(
                this.form,
                "task_default_minute",
                this.focusTask.task_default_minute
            );
            this.$set(
                this.form,
                "task_is_everyday",
                this.focusTask.task_is_everyday
            );
        }
    },
});
</script>