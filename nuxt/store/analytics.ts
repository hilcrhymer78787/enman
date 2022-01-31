import { ActionTree, MutationTree } from 'vuex'
import { apiWorkReadAnalyticsRequestType } from '@/types/api/work/read/analytics/request'
import { apiWorkReadAnalyticsResponseType } from '@/types/api/work/read/analytics/response'
import { AxiosRequestConfig, AxiosResponse, AxiosError } from "axios";
import moment from 'moment'
import { RootState } from '~/store'
let setCalendarWorksCancel: any = null;
export type AnotherModuleState = ReturnType<typeof state>

export const state = () => ({
    works: null,
    calendarWorks: null,
})

export const mutations: MutationTree<AnotherModuleState> = {
    setWorks(state, works) {
        state.works = works
    },
    setCalendarWorks(state, calendarWorks) {
        state.calendarWorks = calendarWorks
    },
}

export const actions: ActionTree<AnotherModuleState, RootState> = {
    async setWorks({ commit }, apiParam: apiWorkReadAnalyticsRequestType) {
        const requestConfig: AxiosRequestConfig = {
            url: `/api/work/read/analytics`,
            method: "POST",
            data: apiParam,
        };
        await this.$axios(requestConfig)
            .then((res: AxiosResponse<apiWorkReadAnalyticsResponseType>) => {
                commit("setWorks", res.data);
            })
            .catch((err: AxiosError) => {
            })
    },
    async setCalendarWorks({ commit }) {
        commit("setCalendarWorks", null);
        const year = this.$router.currentRoute.query.year
        const month = this.$router.currentRoute.query.month
        const apiParam: apiWorkReadAnalyticsRequestType = {
            start_date: moment(`${year}/${month}/1`).startOf('month').format("YYYY-MM-DD"),
            last_date: moment(`${year}/${month}/1`).endOf('month').format("YYYY-MM-DD"),
        }
        if (setCalendarWorksCancel) {
            setCalendarWorksCancel()
        }
        const requestConfig: AxiosRequestConfig = {
            url: `/api/work/read/analytics`,
            method: "POST",
            data: apiParam,
        };
        await this.$axios(requestConfig)
            .then((res: AxiosResponse<apiWorkReadAnalyticsResponseType>) => {
                commit("setCalendarWorks", res.data);
            })
            .catch((err: AxiosError) => {
            })
    },
}