import { GetterTree, ActionTree, MutationTree } from 'vuex'
import { apiTaskReadResponseType } from '@/types/api/task/read/response'
import { AxiosRequestConfig, AxiosResponse, AxiosError } from "axios";
import { RootState } from '~/store'

export type AnotherModuleState = ReturnType<typeof state>

export const state = () => ({
    todayTasks: [],
    focusTasks: [],
})

export const mutations: MutationTree<AnotherModuleState> = {
    setTodayTasks(state, todayTasks) {
        state.todayTasks = todayTasks
    },
    setFocusTasks(state, focusTasks) {
        state.focusTasks = focusTasks
    },
}

export const actions: ActionTree<AnotherModuleState, RootState> = {
    async setTodayTasks({ commit }) {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 1;
        const day = today.getDate();
        await this.$axios
            .get(
                `/api/task/read?year=${year}&month=${month}&day=${day}`
            )
            .then((res: AxiosResponse<apiTaskReadResponseType>) => {
                commit('setTodayTasks', res.data.tasks)
            })
            .catch((err: AxiosError) => {
                console.error(err.response)
            })
    },
    async setFocusTasks({ commit }) {
        const year = this.$router.currentRoute.query.year
        const month = this.$router.currentRoute.query.month
        const day = this.$router.currentRoute.query.day
        await this.$axios
            .get(
                `/api/task/read?year=${year}&month=${month}&day=${day}`
            )
            .then((res: AxiosResponse<apiTaskReadResponseType>) => {
                commit('setFocusTasks', res.data.tasks)
            })
            .catch((err: AxiosError) => {
                console.error(err.response)
            })
    },
}