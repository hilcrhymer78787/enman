import { GetterTree, ActionTree, MutationTree } from 'vuex'
import { apiWorkReadAnalyticsRequestType } from '@/types/api/work/read/analytics/request'
import { apiWorkReadAnalyticsResponseType } from '@/types/api/work/read/analytics/response'
import { AxiosRequestConfig, AxiosResponse, AxiosError } from "axios";
export type RootState = ReturnType<typeof state>

export const state = () => ({
    works: null,
})

export const mutations: MutationTree<RootState> = {
    setWorks(state, works) {
        state.works = works
    },
}

export const actions: ActionTree<RootState, RootState> = {
    async setWorks({ commit }, param: apiWorkReadAnalyticsRequestType) {
        await this.$axios
            .post(`/api/work/read/analytics`, param)
            .then((res: AxiosResponse<apiWorkReadAnalyticsResponseType>) => {
                commit("setWorks", res.data);
            })
            .catch((err: AxiosError) => {})
    },
}